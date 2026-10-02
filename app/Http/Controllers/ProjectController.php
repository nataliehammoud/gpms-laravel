<?php


namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Setting;
use App\Models\ProjectFile;
use App\Models\Announcement;   // ✅ مرة واحدة فقط في الأعلى
use Illuminate\Http\Request;
use App\Models\ProjectNote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\ProjectPreference;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;  // <-- أضف هذا
use App\Models\ArchivedProject;  // ✅ أضف هذا

class ProjectController extends Controller
{
public function index()
{
    $user = auth()->user();
    $studentProfile = $user->student;

    if (!$studentProfile) {
        return redirect()->route('home')->with('error', 'الملف الشخصي للطالب غير مكتمل.');
    }

    // ✅ طريقة آمنة 100%
    $myProjects = Project::where('student_id', $studentProfile->id)->pluck('id')->toArray();

    $memberProjects = DB::table('project_user')
        ->where('user_id', $user->id)
        ->pluck('project_id')
        ->toArray();

    $allProjectIds = array_unique(array_merge($myProjects, $memberProjects));

    $projects = Project::whereIn('id', $allProjectIds)
        ->with(['student.user', 'supervisor', 'users'])
        ->get();

    $readAnnouncementIds = DB::table('announcement_views')
        ->where('user_id', $user->id)
        ->pluck('announcement_id')
        ->toArray();

    foreach ($projects as $project) {
        // ✅ الملاحظات الجديدة
        $lastNotesView = session()->get('last_notes_viewed_' . $project->id);

        $query = ProjectNote::where('project_id', $project->id);

        if ($lastNotesView && $lastNotesView instanceof \Carbon\Carbon) {
            $query->where('created_at', '>', $lastNotesView->toDateTimeString());
        } elseif ($lastNotesView && is_string($lastNotesView)) {
            $query->where('created_at', '>', $lastNotesView);
        }
        // إذا ما في session، جيب كل الملاحظات
        $project->has_notes = $query->exists();

        // ✅ الإعلانات غير المقروءة
        $project->has_unread_announcements = Announcement::where(function($q) use ($project) {
                $q->where('project_id', $project->id)
                  ->orWhere('type', 'general');
            })
            ->whereNotIn('id', $readAnnouncementIds)
            ->exists();

        // ✅ درجة جديدة
        $project->has_new_grade = DB::table('evaluations')
            ->where('project_id', $project->id)
            ->exists();
    }

    // ✅ المشروع الأول (للمتغيرات الإضافية في الـ view)
    $firstProject = $projects->isNotEmpty() ? $projects->first() : null;

    return view('student.project.index', compact('studentProfile', 'projects', 'firstProject'));
}

    // صفحة إضافة مشروع (Create)
public function create()
{
    $deadline = Setting::where('key', 'registration_deadline')->value('value');
    if ($deadline && Carbon::now()->gt(Carbon::parse($deadline))) {
        return redirect()->route('student.project.index')->with('error', 'نعتذر، انتهت المهلة المحددة لتسجيل المشاريع.');
    }

    $studentProfile = Auth::user()->student;
    if (!$studentProfile) {
        return redirect()->route('profile.complete');
    }

    if (Project::where('student_id', $studentProfile->id)->exists()) {
        return redirect()->route('student.project.index')->with('error', 'لديك مشروع مسجل بالفعل!');
    }

    // ✅ جلب الحد الأقصى للمشاريع من إعدادات النظام
    $maxProjectsPerSupervisor = Setting::where('key', 'max_projects_per_supervisor')->value('value') ?? 8;

    // ✅ جلب المشرفين المتاحين: لم يطلبوا إيقاف الاستقبال + عدد مشاريعهم أقل من الحد الأقصى
    $supervisors = User::where('role', 'supervisor')
        ->whereHas('supervisor', function($q) {
            $q->where('stop_receiving', false);
        })
        ->with('supervisor')
        ->get()
->filter(function($user) {
    // ✅ فقط تحقق من حالة الإيقاف، لا علاقة بعدد المشاريع
    return $user->supervisor->stop_receiving == false;
});

    // الطلاب المتاحين للفريق
    $students = User::where('role', 'student')
        ->whereDoesntHave('student.project')
        ->whereDoesntHave('projects')
        ->where('id', '!=', Auth::id())
        ->get();

    return view('student.project.create', compact('supervisors', 'students'));
}

    public function store(Request $request)
    {
        // تخزين البيانات في الجلسة
        session([
            'temp_title' => $request->title,
            'temp_description' => $request->description,
            'temp_supervisor_id' => $request->supervisor_id,
            'temp_supervisors' => $request->supervisors,
            'temp_team_members' => $request->team_members,
        ]);

        // تخزين الملف مؤقتاً
        if ($request->hasFile('project_file')) {
            $tempPath = $request->file('project_file')->store('temp', 'public');
            session(['temp_project_file' => $tempPath]);
            session(['temp_project_file_name' => $request->file('project_file')->getClientOriginalName()]);
        }

        // التحقق من صحة البيانات
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'supervisor_id' => 'required|exists:users,id',
            'supervisors' => 'nullable|array',
            'team_members' => 'required|array|min:1',
            'project_file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10000',
        ]);

        // ========== فحص التشابه مع المشاريع الحالية ==========
        $existingProjects = Project::select('title', 'student_id')->with('student.user')->get();
        $highestSimilarity = 0;
        $similarProject = null;

        foreach ($existingProjects as $existing) {
            similar_text($request->title, $existing->title, $similarityPercent);
            if ($similarityPercent > $highestSimilarity) {
                $highestSimilarity = $similarityPercent;
                $similarProject = $existing;
            }
        }

        if ($highestSimilarity > 70 && $similarProject) {
            $studentName = $similarProject->student->user->name ?? 'طالب آخر';
            return back()->withInput()->with('error',
                "❌ **عذراً، هذا المشروع مشابه لمشروع موجود بالفعل!**\n\n" .
                "📌 **المشروع المشابه:** {$similarProject->title}\n" .
                "👨‍🎓 **مقدم من الطالب:** {$studentName}\n\n" .
                "🔴 لا يمكنك إضافة مشروع مكرر. يرجى تعديل فكرة مشروعك."
            );
        }

        // فحص بيانات الطالب
        $studentProfile = Auth::user()->student;

        if (!$studentProfile) {
            return back()->with('error', 'لم يتم العثور على ملف تعريف طالب لهذا المستخدم.');
        }

        if (Project::where('student_id', $studentProfile->id)->exists()) {
            return redirect()->route('student.project.index')
                             ->with('error', 'لديك مشروع مسجل بالفعل، لا يمكنك إضافة مشروع جديد.');
        }

        // الذكاء الاصطناعي
        $predictedCategory = 'مشروع تقني / برمجى';

        try {
            $fullTextForAI = $request->title . " " . $request->description;
            $aiResponse = Http::timeout(5)->post('http://127.0.0.1:3000/api/analyze-project', [
                'text' => $fullTextForAI
            ]);

            if ($aiResponse->successful()) {
                $aiResult = $aiResponse->json();
                if (isset($aiResult['category'])) {
                    $predictedCategory = $aiResult['category'];
                }
            }

        } catch (\Exception $e) {
            \Log::error('AI/Similarity Engine Connection Failed: ' . $e->getMessage());
        }

   // إنشاء المشروع
$project = Project::create([
    'title' => $request->title,
    'description' => $request->description,
    'student_id' => $studentProfile->id,
    'supervisor_id' => $request->supervisor_id,  // ✅ مشرف الطالب
    'status' => 'pending',
    'category' => $predictedCategory,
]);

// ✅ خزن المشرف المطلوب في Session للعرض فقط
session(['requested_supervisor_name_' . $project->id => User::find($request->supervisor_id)->name]);
        // حفظ الرغبات
        ProjectPreference::updateOrCreate(
            ['student_id' => $studentProfile->id, 'rank' => 1],
            [
                'project_id' => $project->id,
                'supervisor_id' => $request->supervisor_id
            ]
        );

      // بدل SupervisorPreference استخدمي ProjectPreference
if ($request->has('supervisors') && is_array($request->supervisors)) {
    foreach ($request->supervisors as $index => $svId) {
        if ($svId && $svId != $request->supervisor_id) {
            ProjectPreference::updateOrCreate(
                ['student_id' => $studentProfile->id, 'rank' => $index + 2],
                [
                    'project_id' => $project->id,
                    'supervisor_id' => $svId
                ]
            );
        }
    }
}

        // حفظ ملف المشروع
        if ($request->hasFile('project_file')) {
            $path = $request->file('project_file')->store('projects/proposals', 'public');
            $project->files()->create(['file_path' => $path, 'stage_id' => null]);
        } elseif (session('temp_project_file')) {
            $tempFile = session('temp_project_file');
            $newPath = str_replace('temp/', 'projects/proposals/', $tempFile);
            if (Storage::disk('public')->exists($tempFile)) {
                Storage::disk('public')->move($tempFile, $newPath);
                $project->files()->create(['file_path' => $newPath, 'stage_id' => null]);
            }
            session()->forget('temp_project_file');
            session()->forget('temp_project_file_name');
        }

        // ربط أعضاء الفريق
        $teamMembers = $request->team_members;
        if (!in_array(Auth::id(), $teamMembers)) {
            $teamMembers[] = Auth::id();
        }
        $project->users()->attach($teamMembers);

        // إضافة إشعارات
        $studentName = Auth::user()->name;
        $supervisorName = User::find($request->supervisor_id)->name;
        $department = $studentProfile->department ?? 'غير محدد';

        $teamMembersNames = [];
        foreach ($teamMembers as $memberId) {
            $member = User::find($memberId);
            if ($member) {
                $teamMembersNames[] = $member->name;
            }
        }
        $teamMembersList = implode('، ', $teamMembersNames);

        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'project_id' => $project->id,
                'title' => '📁 مشروع جديد',
                'message' => " **مشروع جديد**\n\n" .
                             "الطالب: {$studentName}\n" .
                             " الفريق: {$teamMembersList}\n" .
                             " عنوان المشروع: {$project->title}\n" .
                             " المشرف: {$supervisorName}\n" .
                             " القسم: {$department}",
                'type' => 'success',
                'icon' => 'fa-regular fa-folder-open',
                'is_read' => false,
            ]);
        }

        if ($request->has('supervisors') && is_array($request->supervisors)) {
            foreach ($request->supervisors as $index => $svId) {
                if ($svId && $svId != $request->supervisor_id) {
                    Notification::create([
                        'user_id' => $svId,
                        'project_id' => $project->id,
                        'title' => '⭐ طالب أبدى رغبة في الإشراف',
                        'message' => "الطالب {$studentName} أبدى رغبة رقم " . ($index + 2) . " في الإشراف على مشروعه: {$project->title}",
                        'type' => 'info',
                        'icon' => 'fa-regular fa-star',
                        'is_read' => false,
                    ]);
                }
            }
        }

        return redirect()->route('student.project.index')
                         ->with('success', "✅ تم إضافة المشروع بنجاح!");
    }

    // 4. صفحة التعديل
public function edit($id)
{
    $project = Project::findOrFail($id);
    $studentProfile = Auth::user()->student;
    if ($project->student_id !== $studentProfile->id || $project->status === 'approved') {
        abort(403, 'غير مسموح بتعديل مشروع مقبول نهائياً');
    }

    // ✅ جلب الحد الأقصى للمشاريع من إعدادات النظام
    $maxProjectsPerSupervisor = Setting::where('key', 'max_projects_per_supervisor')->value('value') ?? 8;

    // ✅ نفس الفلترة المستخدمة في create()
    $supervisors = User::where('role', 'supervisor')
        ->whereHas('supervisor', function($q) {
            $q->where('stop_receiving', false);
        })
        ->with('supervisor')
        ->get()
     ->filter(function($user) {
    // ✅ فقط تحقق من حالة الإيقاف، لا علاقة بعدد المشاريع
    return $user->supervisor->stop_receiving == false;
});

    $students = User::where('role', 'student')->get();
    return view('student.project.edit', compact('project', 'supervisors', 'students'));
}
  public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $studentProfile = Auth::user()->student;

        if ($project->student_id !== $studentProfile->id || $project->status === 'approved') {
            abort(403, 'غير مسموح بتعديل مشروع مقبول نهائياً');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'supervisor_id' => 'required|exists:users,id',
            'team_members' => 'required|array|min:1',
            'project_file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10000',  // ✅ أضف هذا
        ]);

        // حفظ البيانات القديمة
        $oldTitle = $project->title;
        $oldSupervisorId = $project->supervisor_id;

        // تحديث المشروع
        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'supervisor_id' => $request->supervisor_id,
        ]);

        // تحديث الملف إذا وجد
        if ($request->hasFile('project_file')) {
            foreach($project->files()->whereNull('stage_id')->get() as $oldFile) {
                Storage::disk('public')->delete($oldFile->file_path);
                $oldFile->delete();
            }
            $path = $request->file('project_file')->store('projects/proposals', 'public');
            $project->files()->create([
                'file_path' => $path,
                'stage_id'  => null
            ]);
        }

        // تحديث أعضاء الفريق
        $teamMembers = $request->team_members;
        if (!in_array(Auth::id(), $teamMembers)) {
            $teamMembers[] = Auth::id();
        }
        $project->users()->sync($teamMembers);

        // إشعارات
        $studentName = Auth::user()->name;
        $adminUsers = User::where('role', 'admin')->get();

        foreach ($adminUsers as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'project_id' => $project->id,
                'title' => '✏️ تعديل مشروع',
                'message' => "الطالب {$studentName} قام بتعديل مشروعه: '{$oldTitle}' → '{$request->title}'",
                'type' => 'info',
                'icon' => 'fa-regular fa-pen-to-square',
                'is_read' => false,
            ]);
        }

        if ($oldSupervisorId != $request->supervisor_id) {
            Notification::create([
                'user_id' => $oldSupervisorId,
                'project_id' => $project->id,
                'title' => '🔄 تغيير المشرف',
                'message' => "الطالب {$studentName} قام بتغيير المشرف على مشروعه '{$project->title}'",
                'type' => 'warning',
                'icon' => 'fa-regular fa-user-minus',
                'is_read' => false,
            ]);
        }

        Notification::create([
            'user_id' => $request->supervisor_id,
            'project_id' => $project->id,
            'title' => '📋 تم تعيينك مشرفاً (بعد التعديل)',
            'message' => "تم تعيينك مشرفاً على مشروع: {$project->title} للطالب {$studentName}",
            'type' => 'success',
            'icon' => 'fa-regular fa-user-check',
            'is_read' => false,
        ]);

        return redirect()->route('student.project.index')->with('success', 'تم تحديث المشروع بنجاح!');
    }


    // 6. للمشرف: تحديث الحالة
 public function updateStatus(Request $request, $id)
{
    $request->validate(['status' => 'required|in:approved,rejected']);
    $project = Project::findOrFail($id);

    if (Auth::id() != $project->supervisor_id) {
        abort(403);
    }

    // تحديث حالة المشروع
    $project->update(['status' => $request->status]);

    // ✅ التعديل هنا: أزلنا سطر الـ delete() لكي نحتفظ بكل الحالات
    // ننشئ سطر جديد في كل مرة يتغير فيها القرار
    \DB::table('project_stages')->insert([
        'project_id' => $project->id,
        'stage_name' => ($request->status == 'approved') ? 'اعتماد المقترح النهائي' : 'رفض المقترح - بحاجة لتعديل',
        'due_date'   => now(),
        'status'     => ($request->status == 'approved') ? 'approved' : 'rejected',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $msg = ($request->status == 'approved') ? 'تم قبول المشروع' : 'تم رفض المشروع';
    return back()->with('success', $msg);
}



    // 7. الحذف
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        if (Auth::user()->student->id !== $project->student_id) {
            abort(403);
        }
        foreach($project->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }
        $project->delete();
        return redirect()->route('student.project.index')->with('success', 'تم حذف المشروع');
    }
public function showNotes($project_id)
{
    $project = Project::with('notes')->findOrFail($project_id);

    // التأكد أن الطالب هو صاحب المشروع أو عضو فيه
    if (Auth::user()->student->id !== $project->student_id) {
        abort(403);
    }

    // ✅ تحديث آخر وقت قراءة للملاحظات
    session()->put('last_notes_viewed_' . $project_id, now());

    return view('student.project.notes', compact('project'));
}
public function showStages($projectId) {
    $project = Project::with('stages')->findOrFail($projectId);
    // تأكدي من إنشاء هذا الملف في مجلد الـ views
    return view('student.stages_index', compact('project'));
}
/**
 * حساب التشابه باستخدام خوارزمية Jaro-Winkler
 */
private function jaroWinkler($str1, $str2)
{
    $str1 = mb_strtolower($str1);
    $str2 = mb_strtolower($str2);

    $len1 = mb_strlen($str1);
    $len2 = mb_strlen($str2);

    if ($len1 == 0 || $len2 == 0) return 0;

    $matchDistance = floor(max($len1, $len2) / 2) - 1;
    if ($matchDistance < 0) $matchDistance = 0;

    $matches1 = array_fill(0, $len1, false);
    $matches2 = array_fill(0, $len2, false);

    $matches = 0;
    for ($i = 0; $i < $len1; $i++) {
        $start = max(0, $i - $matchDistance);
        $end = min($i + $matchDistance + 1, $len2);

        for ($j = $start; $j < $end; $j++) {
            if (!$matches2[$j] && mb_substr($str1, $i, 1) == mb_substr($str2, $j, 1)) {
                $matches1[$i] = true;
                $matches2[$j] = true;
                $matches++;
                break;
            }
        }
    }

    if ($matches == 0) return 0;

    $t = 0;
    $k = 0;
    for ($i = 0; $i < $len1; $i++) {
        if ($matches1[$i]) {
            while (!$matches2[$k]) $k++;
            if (mb_substr($str1, $i, 1) != mb_substr($str2, $k, 1)) $t++;
            $k++;
        }
    }

    $jaro = ($matches / $len1 + $matches / $len2 + ($matches - $t / 2) / $matches) / 3;

    // Winkler adjustment
    $prefixLength = 0;
    $maxPrefix = min(4, $len1, $len2);
    for ($i = 0; $i < $maxPrefix; $i++) {
        if (mb_substr($str1, $i, 1) == mb_substr($str2, $i, 1)) $prefixLength++;
        else break;
    }

    $jaroWinkler = $jaro + ($prefixLength * 0.1 * (1 - $jaro));

    return $jaroWinkler * 100;
}

}
