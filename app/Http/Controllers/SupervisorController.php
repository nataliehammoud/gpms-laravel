<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectNote;
use App\Models\Notification;  // ✅ لازم يكون هنا مع الـ use الباقية
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SupervisorController extends Controller
{
public function super()
{
    if (trim(strtolower(auth()->user()->role)) !== 'supervisor') {
        abort(403, 'غير مسموح لك بدخول هذه الصفحة');
    }

    $projects = Project::where('supervisor_id', auth()->id())
        ->with(['student.user', 'stages', 'users', 'files'])
        ->get();

    foreach ($projects as $project) {
        $project->similarity_warning = false;
        $project->similar_with = null;
        $project->similarity_percentage = 0;

        foreach ($projects as $other) {
            if ($project->id == $other->id) continue;

            // ✅ استخدم الدالة الجديدة
            $percent = $this->levenshteinSimilarity($project->title, $other->title);

            // ✅ عتبة 60% تعني تشابه قوي (خفها للتجربة إذا أردت)
            if ($percent > 60) {
                $project->similarity_warning = true;
                $project->similar_with = $other;
                $project->similarity_percentage = round($percent);
                break;
            }
        }
    }

    return view('supervisor.super', compact('projects'));
}

    public function createNote($projectId) {
        return view('supervisor.notes_create', compact('projectId'));
    }

    public function storeNote(Request $request, $projectId)
{
    $request->validate([
        'content' => 'required'
    ]);

    // جلب بيانات المشروع والطالب
    $project = \App\Models\Project::with('student.user')->findOrFail($projectId);

    // حفظ الملاحظة
    $note = \App\Models\ProjectNote::create([
        'project_id' => $projectId,
        'content' => $request->content
    ]);

    // ✅ إرسال إشعار للطالب (إذا كان هناك طالب مرتبط بالمشروع)
    if ($project->student && $project->student->user) {
        $student = $project->student->user;

        \App\Models\Notification::create([
            'user_id' => $student->id,
            'project_id' => $project->id,
            'title' => '📝 ملاحظة جديدة من المشرف',
            'message' => "أضاف المشرف ملاحظة جديدة على مشروعك '{$project->title}'. يرجى مراجعتها.",
            'type' => 'info',
            'icon' => 'fa-comment-dots',
            'is_read' => false,
        ]);
    }

    return redirect()->back()->with('success', 'تم إرسال الملاحظة والإشعار للطالب!');
}

// دالة رصد الدرجة (لا تحتاج تعديل)
public function storeGrade(Request $request, $project_id)
{
    $request->validate([
        'grade' => 'required|numeric|min:0|max:100',
        'feedback' => 'nullable|string',
    ]);

    \DB::table('evaluations')->updateOrInsert(
        ['project_id' => $project_id],
        [
            'supervisor_id' => auth()->id(),
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );

    return back()->with('success', 'تم رصد التقييم بنجاح!');
}

    public function updateSize(Request $request)
    {
        $request->validate([
            'max_team_size' => 'nullable|numeric|min:1|max:15'
        ]);

        $user = auth()->user();
        $user->max_team_size = $request->max_team_size;
        $user->save();

        return back()->with('success', 'تم تحديث سعة الفريق بنجاح!');
    }

   public function dashboard()
{
    // ✅ جلب مشاريع المشرف المسجل فقط (وليس كل المشاريع)
    $projects = Project::where('supervisor_id', auth()->id())
        ->with(['student.user', 'users'])
        ->get();

    // تحديث نسب المشاريع غير المحللة
    foreach ($projects as $project) {
        if (is_null($project->analyzed_at) || $project->readiness == 0) {
            $this->updateProjectReadiness($project);
        }
    }

    // ✅ جلب المشاريع مرة أخرى بعد التحديث
    $projects = Project::where('supervisor_id', auth()->id())
        ->with(['student.user', 'users'])
        ->get();

    // ========== مؤشرات حقيقية ==========
    $totalProjects = $projects->count();
    $pendingProjects = $projects->where('status', 'pending')->count();
    $needReview = $projects->where('readiness', '<', 50)->where('readiness', '>', 0)->count();
    $readyForDiscussion = $projects->where('readiness', '>', 75)->count();

    // بيانات الرسوم البيانية (للمشاريع التابعة للمشرف فقط)
    $chartData = [
        'quality' => [
            'weak' => $projects->where('readiness', '<', 50)->where('readiness', '>', 0)->count(),
            'medium' => $projects->whereBetween('readiness', [50, 75])->count(),
            'excellent' => $projects->where('readiness', '>', 75)->count(),
            'unanalyzed' => $projects->where('readiness', 0)->count(),
        ],
        'monthly' => [
            'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            'approved' => collect(range(1, 12))->map(fn($m) => Project::where('supervisor_id', auth()->id())->whereMonth('created_at', $m)->whereYear('created_at', date('Y'))->where('status', 'approved')->count()),
            'rejected' => collect(range(1, 12))->map(fn($m) => Project::where('supervisor_id', auth()->id())->whereMonth('created_at', $m)->whereYear('created_at', date('Y'))->where('status', 'rejected')->count()),
        ],
        'kpis' => [
            'total' => $totalProjects,
            'pending' => $pendingProjects,
            'need_review' => $needReview,
            'ready_for_discussion' => $readyForDiscussion,
        ]
    ];

    return view('supervisor.dashboard', compact('projects', 'chartData'));
}

    private function updateProjectReadiness($project)
    {
        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:3000/api/analyze-report', [
                'title' => $project->title,
                'description' => $project->description ?? '',
            ]);

            $readiness = $response->json()['readiness_percentage'] ?? 0;

            $project->update([
                'readiness' => $readiness,
                'analyzed_at' => now(),
            ]);

            return $readiness;
        } catch (\Exception $e) {
            Log::error("Failed to analyze project {$project->id}: " . $e->getMessage());
            return 0;
        }
    }

    public function analyzeProject($id)
    {
        $project = Project::findOrFail($id);

        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:3000/api/analyze-report', [
                'title' => $project->title,
                'description' => $project->description,
            ]);

            $readiness = $response->json()['readiness_percentage'] ?? 0;

            $project->update([
                'readiness' => $readiness,
                'analyzed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'readiness' => $readiness,
                'message' => "تم تحليل المشروع بنجاح!"
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to analyze project {$project->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function analyzeAllProjects()
    {
        $projects = Project::all();
        $analyzed = 0;
        $results = [];

        foreach ($projects as $project) {
            $readiness = $this->updateProjectReadiness($project);
            if ($readiness > 0) {
                $analyzed++;
                $results[$project->id] = $readiness;
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "تم تحليل {$analyzed} مشروع بنجاح",
                'results' => $results,
                'stats' => [
                    'weak' => Project::where('readiness', '<', 50)->count(),
                    'medium' => Project::whereBetween('readiness', [50, 75])->count(),
                    'excellent' => Project::where('readiness', '>', 75)->count(),
                    'unanalyzed' => Project::where('readiness', 0)->count(),
                ]
            ]);
        }

        return redirect()->route('supervisor.dashboard')->with('success', "تم تحليل {$analyzed} مشروع");
    }
public function notifyStudent($projectId)
{
        // ✅ تأكد من أن المستخدم مشرف
    if (auth()->user()->role !== 'supervisor') {
        return response()->json(['success' => false, 'error' => 'غير مصرح لك'], 403);
    }
    try {
        $project = Project::with('student.user')->findOrFail($projectId);
        $student = $project->student->user;

        $notification = Notification::create([
            'user_id' => $student->id,
            'project_id' => $project->id,
            'title' => '📢 تنبيه من المشرف',
            'message' => "تم إرسال تنبيه بخصوص مشروعك '{$project->title}'. يرجى مراجعة الملاحظات وتحسين المشروع.",
            'type' => 'warning',
            'icon' => 'fa-bell',
            'is_read' => false,
        ]);

        // ✅ أضف هذا السطر هنا للتأكد من تخزين الإشعار
        \Log::info('Notification created:', ['id' => $notification->id, 'user_id' => $student->id, 'project_id' => $project->id]);

        return response()->json([
            'success' => true,
            'message' => '✅ تم إرسال التنبيه إلى الطالب بنجاح'
        ]);

    } catch (\Exception $e) {
        \Log::error('Notify error: ' . $e->getMessage()); // ✅ أيضا أضف هذا لمعرفة الخطأ
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

   public function showProjectAnalysis($id)
{
    $project = Project::with(['student.user', 'users'])->findOrFail($id);

    // 🔥 جلب الجاهزية والثقة من خادم Node.js
    try {
        $response = Http::timeout(10)->post('http://127.0.0.1:3000/api/analyze-project', [
            'text' => $project->title . ' ' . ($project->description ?? '')
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $confidence = $data['confidence'] ?? 0.5;  // الثقة من الشبكة العصبية
            $detectedCategory = $data['category'] ?? 'غير محدد';
        } else {
            $confidence = 0.5;
            $detectedCategory = 'غير محدد';
        }
    } catch (\Exception $e) {
        \Log::error("AI Analysis Error: " . $e->getMessage());
        $confidence = 0.5;
        $detectedCategory = 'غير محدد';
    }

    // ✅ حساب الجاهزية (Readiness) - كما هي موجودة
    try {
        $response2 = Http::timeout(5)->post('http://127.0.0.1:3000/api/analyze-report', [
            'title' => $project->title,
            'description' => $project->description ?? '',
        ]);
        $readinessPercentage = $response2->json()['readiness_percentage'] ?? 0;

        $project->update([
            'readiness' => $readinessPercentage,
            'analyzed_at' => now(),
        ]);
    } catch (\Exception $e) {
        $readinessPercentage = 0;
        \Log::error("Readiness Analysis Error: " . $e->getMessage());
    }

    // 🔥 تمرير الثقة والتصنيف إلى الواجهة
    return view('supervisor.project_analysis', compact(
        'project',
        'readinessPercentage',
        'confidence',
        'detectedCategory'
    ));
}
public function notifySimilarity(Request $request, $projectId)
{
    $project = Project::with('student.user')->findOrFail($projectId);
    $student = $project->student->user;
    $similarTitle = $request->similar_title;
    $percentage = $request->percentage;

    Notification::create([
        'user_id' => $student->id,
        'project_id' => $project->id,
        'title' => '⚠️ تنبيه: تشابه عالي في المشروع',
        'message' => "مشروعك '{$project->title}' يشابه مشروع '{$similarTitle}' بنسبة {$percentage}%. يرجى مراجعة الفكرة وتعديلها.",
        'type' => 'warning',
        'icon' => 'fa-exclamation-triangle',
        'is_read' => false,
    ]);

    return response()->json(['success' => true]);
}
/**
 * حساب نسبة التشابه بين نصين باستخدام خوارزمية Levenshtein
 */
private function levenshteinSimilarity($str1, $str2)
{
    if (empty($str1) || empty($str2)) {
        return 0;
    }

    $distance = levenshtein($str1, $str2);
    $maxLen = max(strlen($str1), strlen($str2));

    if ($maxLen == 0) {
        return 100;
    }

    return round((($maxLen - $distance) / $maxLen) * 100);
}
public function requestStopReceiving()
{
    $supervisor = auth()->user()->supervisor;
    $supervisor->stop_request_pending = true;
    $supervisor->save();

    return back()->with('success', 'تم إرسال طلب إيقاف الاستقبال إلى الأدمن');
}
public function mySchedule()
{
    $projects = Project::where('supervisor_id', auth()->id())
        ->whereNotNull('discussion_time')
        ->with('student.user')
        ->get();

    return view('supervisor.my-schedule', compact('projects'));
}
public function requestArchive($id)
{
    $project = Project::findOrFail($id);

    // التأكد أن المشرف هو المشرف المسؤول
    if ($project->supervisor_id != auth()->id()) {
        abort(403, 'غير مصرح لك');
    }

    // التأكد أن المشروع مقبول ولم يُطلب أرشفته مسبقاً
    if ($project->status != 'approved' || $project->archive_status != 'pending') {
        return back()->with('error', 'هذا المشروع غير مؤهل للأرشفة');
    }

    $project->archive_status = 'requested';
    $project->save();

    // إشعار للأدمن
    $adminUsers = User::where('role', 'admin')->get();
    foreach ($adminUsers as $admin) {
        Notification::create([
            'user_id' => $admin->id,
            'project_id' => $project->id,
            'title' => '📦 طلب أرشفة مشروع',
            'message' => "طلب المشرف {$project->supervisor->name} أرشفة مشروع: {$project->title}",
            'type' => 'info',
            'icon' => 'fa-archive',
            'is_read' => false,
        ]);
    }

    return back()->with('success', '✅ تم إرسال طلب الأرشفة للأدمن');
}
}
