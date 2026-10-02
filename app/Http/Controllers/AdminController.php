<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Announcement;
use App\Models\Supervisor;
use App\Models\Student;
use App\Models\Notification;
use App\Services\DistributionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\ArchivedProject;


class AdminController extends Controller
{
  // ========== لوحة التحكم الرئيسية (الموجودة) ==========
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'supervisors_count' => User::where('role', 'supervisor')->count(),
            'students_count' => User::where('role', 'student')->count(),
            'total_projects' => Project::count(),
        ];

        $projects = Project::with(['student.user', 'supervisor'])->latest()->get();

        $notifications = Notification::where('user_id', auth()->id())
                                     ->orderBy('created_at', 'desc')
                                     ->limit(20)
                                     ->get();

        $unreadCount = Notification::where('user_id', auth()->id())
                                   ->where('is_read', false)
                                   ->count();

        $pendingStopRequestsCount = Supervisor::where('stop_request_pending', true)->count();
        $archiveRequestsCount = Project::where('archive_status', 'requested')->count();

        return view('admin.dashboard', compact(
            'stats', 
            'projects', 
            'notifications', 
            'unreadCount', 
            'pendingStopRequestsCount', 
            'archiveRequestsCount'
        ));
    }

    // ========== 🔥 الدالة الجديدة لإحصائيات الأقسام ==========

public function statistics()
{
    // 1. إحصائيات الأقسام من الأرشيف (المصدر الأساسي)
    $projectsByDepartment = ArchivedProject::select('department', DB::raw('count(*) as total'))
                                            ->groupBy('department')
                                            ->get();

    // 2. تجهيز البيانات للرسوم البيانية
    $chartLabels = $projectsByDepartment->pluck('department')->toArray();
    $chartData = $projectsByDepartment->pluck('total')->toArray();

    // 3. إحصائيات الأرشيف فقط (للكروت)
    $totalArchived = array_sum($chartData); // إجمالي المشاريع المؤرشفة
    $departmentsCount = count($chartLabels); // عدد الأقسام الموجودة

    // 4. القسم الأكثر والأقل تكراراً
    $maxValue = $totalArchived > 0 ? max($chartData) : 0;
    $minValue = $totalArchived > 0 ? min($chartData) : 0;
    $maxIndex = array_search($maxValue, $chartData);
    $minIndex = array_search($minValue, $chartData);
    $topDepartment = $maxIndex !== false ? $chartLabels[$maxIndex] : 'لا يوجد';
    $bottomDepartment = $minIndex !== false ? $chartLabels[$minIndex] : 'لا يوجد';

    return view('admin.statistics', compact(
        'chartLabels',
        'chartData',
        'totalArchived',
        'departmentsCount',
        'topDepartment',
        'bottomDepartment'
    ));
}

    // تحديد إشعار كمقروء
    public function markNotificationAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
                                    ->where('id', $id)
                                    ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    // تحديد الكل كمقروء
    public function markAllNotificationsAsRead()
    {
        Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // تحديث إعدادات النظام
    public function updateSettings(Request $request)
    {
        $request->validate([
            'max_projects_per_supervisor' => 'required|integer|min:1|max:20',
        ]);

        Setting::updateOrCreate(
            ['key' => 'max_projects_per_supervisor'],
            ['value' => $request->max_projects_per_supervisor]
        );

        return back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }

// تشغيل التوزيع الذكي
public function runAIAllocation(DistributionService $service)
{
    try {
        $service->runGaleShapley();
        $results = Project::with(['student.user', 'supervisor'])->get();

        // ========== إضافة إشعارات للمشرفين بعد التوزيع ==========
        foreach ($results as $project) {
            if ($project->supervisor_id) {
                // ✅ أضف هذا السطر (يحدث المشروع ويعطيه علامة التوزيع)
                $project->update(['is_distributed' => true]);

                $studentName = $project->student->user->name ?? 'طالب';

                // جلب أسماء أعضاء الفريق
                $teamMembers = $project->users->pluck('name')->implode('، ');
                if (empty($teamMembers)) {
                    $teamMembers = $studentName;
                }

                $department = $project->student->department ?? 'غير محدد';

                \App\Models\Notification::create([
                    'user_id' => $project->supervisor_id,
                    'project_id' => $project->id,
                    'title' => '📋 تم تعيين مشروع لك',
                    'message' => "🎉 **تهانينا! تم توزيع مشروع جديد عليك**\n\n" .
                                 "👨‍🎓 الطالب: {$studentName}\n" .
                                 "👥 الفريق: {$teamMembers}\n" .
                                 "📋 المشروع: {$project->title}\n" .
                                 "🏛️ القسم: {$department}",
                    'type' => 'success',
                    'icon' => 'fa-regular fa-user-check',
                    'is_read' => false,
                ]);
            }
        }

        return redirect()->back()->with([
            'success' => 'تم التوزيع بنجاح!',
            'results' => $results
        ]);
    } catch (\Exception $e) {
        return back()->with('error', 'خطأ: ' . $e->getMessage());
    }
}
    // إدارة المستخدمين
    public function manageUsers()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    // إضافة مستخدم
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:student,supervisor',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'supervisor') {
            Supervisor::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization ?? 'عام',
                'office' => $request->office ?? 'غير محدد',
            ]);
        } elseif ($user->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'department' => $request->department ?? 'عام',
                'year' => now()->year,
            ]);
        }

        return back()->with('success', 'تم إضافة المستخدم وتجهيز بياناته بنجاح');
    }

    // حذف مستخدم
    public function destroyUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }

   // عرض الإعلانات
public function showAnnouncements()
{
    $user = auth()->user();
    $announcements = Announcement::orderBy('created_at', 'desc')->get();
    $max_students = Setting::where('key', 'max_projects_per_supervisor')->value('value') ?? 5;

    // جلب المشاريع (للإعلانات الخاصة)
    $projects = Project::with(['student.user'])->get();

    // تسجيل قراءة الإعلانات للمستخدم الحالي (اختياري)
    foreach ($announcements as $announcement) {
        DB::table('announcement_views')->updateOrInsert(
            [
                'announcement_id' => $announcement->id,
                'user_id' => $user->id
            ],
            ['viewed_at' => now()]
        );
    }

    return view('announcements.index', compact('announcements', 'max_students', 'projects'));
}

public function storeAnnouncement(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'body' => 'required|string',
        'type' => 'required|in:general,project',
        'project_id' => 'required_if:type,project|nullable|exists:projects,id',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120'
    ]);

    $attachmentPath = null;
    if ($request->hasFile('attachment')) {
        $attachmentPath = $request->file('attachment')->store('announcements', 'public');
    }

    Announcement::create([
        'title' => $request->title,
        'body' => $request->body,
        'type' => $request->type,
        'project_id' => $request->type == 'project' ? $request->project_id : null,
        'user_id' => auth()->id(),
        'attachment' => $attachmentPath,
    ]);

    return back()->with('success', 'تم إضافة الإعلان بنجاح');
}
    // حذف إعلان
    public function destroyAnnouncement(Announcement $announcement)
    {
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }
        $announcement->delete();
        return back()->with('success', 'تم حذف الإعلان بنجاح');
    }

// ===== عرض جدول المناقشات =====
public function showDiscussionSchedule()
{
    $projects = Project::has('student')
        ->whereNotNull('supervisor_id')
        ->where('status', 'approved') // ✅ فقط المشاريع المقبولة
        ->with(['student.user', 'supervisor', 'users'])
        ->get();

    return view('admin.projects.schedule', compact('projects'));
}

// ===== تحديث موعد المناقشة =====
public function updateDiscussion(Request $request, Project $project)
{
    $request->validate([
        'discussion_time' => 'required|date|after:now',
        'discussion_room' => 'required|string|max:255',
    ]);

    $discussionTime = Carbon::parse($request->discussion_time);
    $discussionRoom = $request->discussion_room;

    // نافذة زمنية 30 دقيقة قبل وبعد
    $startBuffer = $discussionTime->copy()->subMinutes(30);
    $endBuffer = $discussionTime->copy()->addMinutes(30);

    // 1. تعارض المشرف
    $supervisorConflict = Project::where('supervisor_id', $project->supervisor_id)
        ->where('id', '!=', $project->id)
        ->whereBetween('discussion_time', [$startBuffer, $endBuffer])
        ->exists();

    if ($supervisorConflict) {
        return back()->with('error', '⚠️ لا يمكن تحديد هذا الموعد لأن المشرف لديه مناقشة أخرى في نفس الوقت (أو خلال 30 دقيقة).');
    }

    // 2. تعارض أعضاء الفريق
    $studentIds = $project->users->pluck('id')->toArray();
    $studentConflict = Project::whereHas('users', function($q) use ($studentIds) {
            $q->whereIn('user_id', $studentIds);
        })
        ->where('id', '!=', $project->id)
        ->whereBetween('discussion_time', [$startBuffer, $endBuffer])
        ->exists();

    if ($studentConflict) {
        return back()->with('error', '⚠️ لا يمكن تحديد هذا الموعد لأن أحد أعضاء الفريق لديه مناقشة أخرى في نفس الوقت (أو خلال 30 دقيقة).');
    }

    // 3. تعارض القاعة
    $roomConflict = Project::where('discussion_room', $discussionRoom)
        ->where('id', '!=', $project->id)
        ->whereBetween('discussion_time', [$startBuffer, $endBuffer])
        ->exists();

    if ($roomConflict) {
        return back()->with('error', '⚠️ لا يمكن تحديد هذا الموعد لأن القاعة محجوزة لمشروع آخر في نفس الوقت (أو خلال 30 دقيقة).');
    }

    // حفظ الموعد
    $project->update([
        'discussion_time' => $discussionTime,
        'discussion_room' => $discussionRoom,
    ]);

    // إرسال إشعارات
    $this->sendScheduleNotification($project);

    return back()->with('success', 'تم تحديث موعد المناقشة بنجاح');
}
    /**
 * التحقق من تعارض المواعيد (API)
 */
public function checkScheduleConflict(Request $request)
{
    $projectId = $request->project_id;
    $discussionTime = Carbon::parse($request->discussion_time);
    $discussionRoom = $request->discussion_room;

    $project = Project::with(['supervisor', 'users'])->findOrFail($projectId);

    $conflicts = [];

    // 1. التحقق من تعارض المشرف
    $supervisorConflict = Project::where('supervisor_id', $project->supervisor_id)
        ->where('id', '!=', $projectId)
        ->where('discussion_time', $discussionTime)
        ->first();

    if ($supervisorConflict) {
        $conflicts[] = "⚠️ المشرف د. {$project->supervisor->name} لديه مناقشة مع مشروع '{$supervisorConflict->title}' في نفس الوقت.";
    }

    // 2. التحقق من تعارض أعضاء الفريق
    $studentIds = $project->users->pluck('id')->toArray();
    $studentConflict = Project::whereHas('users', function($q) use ($studentIds) {
            $q->whereIn('user_id', $studentIds);
        })
        ->where('id', '!=', $projectId)
        ->where('discussion_time', $discussionTime)
        ->first();

    if ($studentConflict) {
        $conflicts[] = "⚠️ أحد أعضاء الفريق لديه مناقشة مع مشروع '{$studentConflict->title}' في نفس الوقت.";
    }

    // 3. التحقق من تعارض القاعة
    $roomConflict = Project::where('discussion_room', $discussionRoom)
        ->where('id', '!=', $projectId)
        ->where('discussion_time', $discussionTime)
        ->first();

    if ($roomConflict) {
        $conflicts[] = "⚠️ القاعة '{$discussionRoom}' محجوزة لمشروع '{$roomConflict->title}' في نفس الوقت.";
    }

    return response()->json([
        'has_conflict' => count($conflicts) > 0,
        'conflicts' => $conflicts
    ]);
}

    // طلبات إيقاف الاستقبال
 public function showStopRequests()
{
    $pendingRequests = Supervisor::where('stop_request_pending', true)
        ->with('user')
        ->get();
    return view('admin.stop-requests', compact('pendingRequests'));
}

    public function getProjectJson($id) {
    $project = Project::with('student.user')->findOrFail($id);
    return response()->json([
        'id' => $project->id,
        'title' => $project->title,
        'student_name' => $project->student->user->name ?? 'غير محدد',
        'supervisor_name' => \App\Models\User::find($project->supervisor_id)->name ?? 'غير محدد',
        'department' => $project->student->department ?? 'عام',
        'discussion_time' => $project->discussion_time,
        'discussion_room' => $project->discussion_room,
        'supervisor_id' => $project->supervisor_id
    ]);
}
// ========== دوال الأرشيف ==========

// عرض صفحة الأرشيف مع الإحصائيات
public function showArchive()
{
    $archivedProjects = ArchivedProject::orderBy('year', 'desc')
        ->orderBy('department')
        ->paginate(20);

    $stats = [
        'total' => ArchivedProject::count(),
        'by_department' => ArchivedProject::select('department', \DB::raw('count(*) as total'))
            ->groupBy('department')
            ->get(),
        'by_year' => ArchivedProject::select('year', \DB::raw('count(*) as total'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get(),
        'most_used_supervisors' => ArchivedProject::select('supervisor', \DB::raw('count(*) as total'))
            ->groupBy('supervisor')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
    ];

    return view('admin.archive.index', compact('archivedProjects', 'stats'));
}

public function archiveIndex()
{
    $archivedProjects = ArchivedProject::orderBy('year', 'desc')->paginate(20);
    $departments = ArchivedProject::select('department')->distinct()->pluck('department');
    $userRole = auth()->user()->role;

    return view('archive.index', compact('archivedProjects', 'departments', 'userRole'));
}
// صفحة إضافة مشروع
public function createArchive()
{
    return view('admin.archive.create');
}

// حفظ مشروع
public function storeArchive(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'students_names' => 'required|string',
        'supervisor' => 'required|string',
        'year' => 'required|integer',
        'department' => 'required|in:Communications,Information Systems,Computer Systems',
    ]);

    ArchivedProject::create($request->all());

    return redirect()->route('archive.index')->with('success', 'تم إضافة المشروع بنجاح');
}

// فحص التشابه
public function checkArchiveSimilarity(Request $request)
{
    $title = $request->title;
    $description = $request->description ?? '';

    $archiveProjects = ArchivedProject::all(['id', 'title', 'description', 'year', 'department', 'supervisor', 'students_names']);

    $response = Http::post('http://127.0.0.1:3000/api/archive/check-duplicate', [
        'title' => $title,
        'description' => $description,
        'archiveProjects' => $archiveProjects
    ]);

    if($response->successful()) {
        return response()->json($response->json());
    }

    return response()->json(['success' => false, 'similar_projects' => []]);
}
// قبول طلب إيقاف الاستقبال
public function approveStopReceiving($id)
{
    $supervisor = Supervisor::findOrFail($id);
    $supervisor->stop_receiving = true;
    $supervisor->stop_request_pending = false;
    $supervisor->save();

    // ========== إضافة إشعار للمشرف ==========
    Notification::create([
        'user_id' => $supervisor->user_id,
        'project_id' => null,
        'title' => '✅ تم قبول طلب إيقاف الاستقبال',
        'message' => 'تم قبول طلبك لإيقاف استقبال مشاريع جديدة. لن يتم تعيين مشاريع جديدة لك.',
        'type' => 'success',
        'icon' => 'fa-regular fa-check-circle',
        'is_read' => false,
    ]);

    return back()->with('success', 'تم إيقاف استقبال المشاريع للمشرف');
}

// رفض طلب إيقاف الاستقبال
public function rejectStopReceiving($id)
{
    $supervisor = Supervisor::findOrFail($id);
    $supervisor->stop_request_pending = false;
    $supervisor->save();

    // ========== إضافة إشعار للمشرف ==========
    Notification::create([
        'user_id' => $supervisor->user_id,
        'project_id' => null,
        'title' => '❌ تم رفض طلب إيقاف الاستقبال',
        'message' => 'عذراً، تم رفض طلبك لإيقاف استقبال المشاريع. يمكنك تقديم طلب جديد لاحقاً.',
        'type' => 'error',
        'icon' => 'fa-regular fa-times-circle',
        'is_read' => false,
    ]);

    return back()->with('success', 'تم رفض طلب إيقاف الاستقبال');
}
/**
 * إرسال إشعارات عند تحديد موعد المناقشة
 */
private function sendScheduleNotification($project)
{
    $formattedDate = Carbon::parse($project->discussion_time)->format('Y-m-d');
    $formattedTime = Carbon::parse($project->discussion_time)->format('h:i A');

    $message = "📅 تم تحديد موعد مناقشة مشروعك\n\n";
    $message .= "📋 المشروع: {$project->title}\n";
    $message .= "🕒 التاريخ: {$formattedDate}\n";
    $message .= "⏰ الوقت: {$formattedTime}\n";
    $message .= "📍 المكان: {$project->discussion_room}";

    // إشعار للمشرف
    Notification::create([
        'user_id' => $project->supervisor_id,
        'project_id' => $project->id,
        'title' => '📅 تم تحديد موعد المناقشة',
        'message' => $message,
        'type' => 'info',
        'icon' => 'fa-calendar-alt',
        'is_read' => false,
    ]);

    // إشعار لأعضاء الفريق
    foreach ($project->users as $member) {
        Notification::create([
            'user_id' => $member->id,
            'project_id' => $project->id,
            'title' => '📅 تم تحديد موعد المناقشة',
            'message' => $message,
            'type' => 'info',
            'icon' => 'fa-calendar-alt',
            'is_read' => false,
        ]);
    }
}
public function archiveRequests()
{
    $requests = Project::where('archive_status', 'requested')
        ->with(['supervisor', 'student.user', 'users'])
        ->get();

return view('archive.requests', compact('requests'));}
public function approveArchive($id)
{
    $project = Project::findOrFail($id);

    // نقل المشروع إلى الأرشيف
    ArchivedProject::create([
        'title' => $project->title,
        'description' => $project->description,
        'students_names' => $project->users->pluck('name')->implode('، '),
        'supervisor' => $project->supervisor->name,
        'year' => $project->created_at->year,
        'department' => $project->student->department ?? 'غير محدد',
    ]);

    $project->archive_status = 'archived';
    $project->archived_at = now();
    $project->save();

    // إشعار للمشرف
    Notification::create([
        'user_id' => $project->supervisor_id,
        'project_id' => $project->id,
        'title' => '✅ تم أرشفة مشروعك',
        'message' => "تمت أرشفة مشروع: {$project->title}",
        'type' => 'success',
        'icon' => 'fa-check-circle',
        'is_read' => false,
    ]);

    return redirect()->route('admin.archive.requests')->with('success', 'تمت الأرشفة بنجاح');
}

public function rejectArchive($id)
{
    $project = Project::findOrFail($id);
    $project->archive_status = 'pending';
    $project->save();

    return redirect()->route('admin.archive.requests')->with('success', 'تم رفض طلب الأرشفة');
}
public function autoSchedule()
{
    // 1. جلب المشاريع غير المجدولة (نأخذ أول 8 فقط لتجنب تعقيد A*، والباقي نتركه للمرة القادمة)
    $projects = Project::whereNull('discussion_time')
                       ->where('status', 'approved')
                       ->with(['student.user', 'supervisor'])
                       ->orderBy('created_at') // الأقدم أولاً
                       ->limit(8) // ✅ حد أقصى 8 مشاريع لضمان سرعة A*
                       ->get();

    if ($projects->isEmpty()) {
        return back()->with('info', 'لا توجد مشاريع غير مجدولة.');
    }

    if ($projects->count() > 8) {
        return back()->with('info', 'يوجد ' . $projects->count() . ' مشروع غير مجدول. سيتم جدولة أول 8 فقط (الأقدم)، والباقي لاحقاً.');
    }

    // 2. تعريف القاعات والأوقات المتاحة (نقتصر على 3 أيام قادمة لتقليل الخيارات)
    $rooms = ['قاعة A', 'قاعة B', 'قاعة C'];
    $timeSlots = [];
    $startDate = Carbon::now()->startOfDay()->addDays(1); // من الغد
    for ($day = 0; $day < 3; $day++) {
        $date = $startDate->copy()->addDays($day);
        $hour = 9; // 9 صباحاً
        while ($hour <= 16) { // حتى 4 مساءً
            $timeSlots[] = $date->copy()->setHour($hour)->setMinute(0);
            $hour++;
        }
    }

    // 3. تشغيل A* المحسّن
    $result = $this->aStarOptimized($projects, $rooms, $timeSlots);

    if ($result['success'] && !empty($result['schedule'])) {
        session(['auto_schedule_result' => $result['schedule']]);
        return redirect()->route('admin.projects.schedule')
                         ->with('success', "✅ تم العثور على جدول مقترح (تم البحث في {$result['visited']} حالة).");
    }

    return back()->with('error', 'تعذر إيجاد جدول مناسب. حاول تقليل عدد المشاريع أو زيادة القاعات.');
}

/**
 * خوارزمية A* المحسّنة باستخدام SplPriorityQueue
 */
private function aStarOptimized($projects, $rooms, $timeSlots)
{
    // تحويل المشاريع إلى مصفوفة للتسلسل
    $projectList = $projects->values()->toArray();
    $totalProjects = count($projectList);

    // هيكل الحل: [projectIndex => ['time' => Carbon, 'room' => string]]
    $bestSchedule = [];
    $bestCost = PHP_INT_MAX;
    $visitedCount = 0;
    $maxIterations = 50000; // حد أقصى 50 ألف حالة

    // Priority Queue (الأولوية للأقل تكلفة)
    $queue = new \SplPriorityQueue();
    $initialHeuristic = $this->calculateHeuristicAStar($projectList, 0);
    $queue->insert([
        'schedule' => [],
        'cost' => 0,
        'heuristic' => $initialHeuristic,
        'index' => 0
    ], -($initialHeuristic)); // الأولوية الأعلى للأقل تكلفة

    while (!$queue->isEmpty() && $visitedCount < $maxIterations) {
        $current = $queue->extract();
        $visitedCount++;

        $currentSchedule = $current['schedule'];
        $currentCost = $current['cost'];
        $projectIndex = $current['index'];

        // إذا وصلنا لنهاية المشاريع
        if ($projectIndex >= $totalProjects) {
            if ($currentCost < $bestCost) {
                $bestCost = $currentCost;
                $bestSchedule = $currentSchedule;
            }
            continue;
        }

        $project = $projectList[$projectIndex];

        // ⭐ تحسين حاسم: تقليل الخيارات إلى أفضل 3 أوقات فقط لكل مشروع
        $bestSlots = $this->getBestTimeSlots($project, $timeSlots, $rooms, $currentSchedule);

        foreach ($bestSlots as $slot) {
            $time = $slot['time'];
            $room = $slot['room'];

            // تحقق من التعارضات
            if ($this->hasConflictAStar($currentSchedule, $project, $time, $room, $projectList)) {
                continue;
            }

            // أنشئ الجدول الجديد
            $newSchedule = $currentSchedule;
            $newSchedule[$project['id']] = [
                'time' => $time->copy(),
                'room' => $room,
                'title' => $project['title'],
                'student' => $project['student']['user']['name'] ?? '',
                'supervisor' => $project['supervisor']['name'] ?? ''
            ];

            // احسب التكلفة الجديدة
            $newCost = $currentCost + $this->calculateCostAStar($newSchedule, $project, $time);
            $newHeuristic = $this->calculateHeuristicAStar($projectList, $projectIndex + 1);

            // أضف إلى قائمة الأولوية
            $priority = -($newCost + $newHeuristic);
            $queue->insert([
                'schedule' => $newSchedule,
                'cost' => $newCost,
                'heuristic' => $newHeuristic,
                'index' => $projectIndex + 1
            ], $priority);
        }
    }

    return [
        'success' => !empty($bestSchedule),
        'schedule' => $bestSchedule,
        'visited' => $visitedCount
    ];
}

/**
 * اختيار أفضل 3 أوقات لكل مشروع (لتقليل عامل التفرع)
 */
private function getBestTimeSlots($project, $timeSlots, $rooms, $currentSchedule)
{
    $candidates = [];

    foreach ($timeSlots as $time) {
        foreach ($rooms as $room) {
            // نعطي الأولوية للأوقات الأقرب (الصباح) والأيام الأقدم
            $score = 0;
            $hour = $time->hour;
            if ($hour >= 9 && $hour <= 11) $score += 10;  // الصباح أفضل
            elseif ($hour >= 12 && $hour <= 14) $score += 5;
            else $score += 1;

            // نضيف نقاط إضافية إذا كانت القاعة غير مستخدمة في هذا الوقت
            $isRoomFree = true;
            foreach ($currentSchedule as $existing) {
                if ($existing['room'] === $room && 
                    $existing['time']->format('Y-m-d H:i') === $time->format('Y-m-d H:i')) {
                    $isRoomFree = false;
                    break;
                }
            }
            if ($isRoomFree) $score += 5;

            $candidates[] = [
                'time' => $time,
                'room' => $room,
                'score' => $score
            ];
        }
    }

    // ترتيب حسب الأعلى نقاط وأخذ أفضل 3 فقط
    usort($candidates, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    return array_slice($candidates, 0, 3);
}

/**
 * حساب Heuristic (تقدير التكلفة المتبقية)
 */
private function calculateHeuristicAStar($projectList, $startIndex)
{
    $remaining = count($projectList) - $startIndex;
    // أقل تكلفة ممكنة لكل مشروع هي 1 (ساعة واحدة)، مع إضافة تعارضات متوقعة
    return $remaining * 2;
}

/**
 * حساب تكلفة إضافة مشروع معين
 */
private function calculateCostAStar($schedule, $project, $time)
{
    $cost = 0;
    $hour = $time->hour;

    // تكلفة الوقت: كلما كان الوقت متأخراً، زادت التكلفة
    if ($hour >= 15) $cost += 10;
    elseif ($hour >= 13) $cost += 5;
    elseif ($hour >= 11) $cost += 2;

    // تكلفة القاعة: إذا كانت القاعة شاغرة في نفس الوقت (تعارض)
    foreach ($schedule as $existing) {
        if ($existing['time']->format('Y-m-d H:i') === $time->format('Y-m-d H:i')) {
            $cost += 20; // تعارض كبير
        }
    }

    return $cost;
}

/**
 * التحقق من التعارضات (لـ A*)
 */
private function hasConflictAStar($schedule, $project, $time, $room, $allProjects)
{
    $timeKey = $time->format('Y-m-d H:i');

    foreach ($schedule as $scheduledProjectId => $existing) {
        // 1. تعارض القاعة
        if ($existing['room'] === $room && $existing['time']->format('Y-m-d H:i') === $timeKey) {
            return true;
        }

        // 2. تعارض المشرف (نبحث عن المشرف في المشروع الأصلي)
        $existingProject = collect($allProjects)->firstWhere('id', $scheduledProjectId);
        if ($existingProject && $existingProject['supervisor_id'] == $project['supervisor_id']) {
            if ($existing['time']->format('Y-m-d H:i') === $timeKey) {
                return true;
            }
        }

        // 3. تعارض الطالب
        if ($existingProject && $existingProject['student_id'] == $project['student_id']) {
            if ($existing['time']->format('Y-m-d H:i') === $timeKey) {
                return true;
            }
        }
    }

    return false;
}

public function applyAutoSchedule()
{
    $schedule = session('auto_schedule_result');
    if (!$schedule) {
        return back()->with('error', 'لا توجد نتائج جدولة لتطبيقها.');
    }

    foreach ($schedule as $projectId => $details) {
        Project::where('id', $projectId)->update([
            'discussion_time' => $details['time'],
            'discussion_room' => $details['room']
        ]);
    }

    session()->forget('auto_schedule_result');
    return redirect()->route('admin.projects.schedule')
                     ->with('success', 'تم تطبيق الجدولة بنجاح!');
}
public function showVisualization()
{
    $projects = ArchivedProject::select('id', 'title', 'department', 'description')->get();
    return view('admin.visualization', compact('projects'));
}

public function getVisualizationData()
{
    $projects = ArchivedProject::select('id', 'title', 'department', 'description')->get();
    $response = Http::post('http://127.0.0.1:3000/api/visualize-clusters', [
        'projects' => $projects->toArray()
    ]);
    return response()->json($response->json());
}
}
