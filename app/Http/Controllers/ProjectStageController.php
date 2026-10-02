<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectFile;
use App\Models\Notification; // أضف هذا
use App\Models\User; // أضف هذا

class ProjectStageController extends Controller
{
    // عرض صفحة المشروع (للطالب والمشرف)
    public function index($project_id)
    {
        $project = Project::with(['stages.files', 'users', 'student'])->findOrFail($project_id);
        $user = Auth::user();

        $isOwner = $user->student && ($user->student->id == $project->student_id);
        $isSupervisor = $user->id == $project->supervisor_id;
        $isTeamMember = $project->users->contains($user->id);

        if (!$isOwner && !$isSupervisor && !$isTeamMember) {
            abort(403, 'غير مصرح لك بالدخول');
        }

        return view('project.stages.index', compact('project'));
    }

    // ========== 1. حفظ مرحلة جديدة (للمشرف فقط) ==========
    public function store(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);

        if (Auth::id() != $project->supervisor_id) {
            abort(403, 'لا تملك صلاحية إضافة مراحل لهذا المشروع.');
        }

        $request->validate([
            'stage_name' => 'required|string|max:255',
            'due_date' => 'required|date|after:today',
        ]);

        $stage = ProjectStage::create([
            'project_id' => $project_id,
            'stage_name' => $request->stage_name,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        // ========== إشعار للطالب صاحب المشروع ==========
        $studentName = $project->student->user->name ?? 'الطالب';

        Notification::create([
            'user_id' => $project->student->user_id,
            'project_id' => $project_id,
            'title' => '📌 مرحلة جديدة مضافة',
            'message' => "تم إضافة مرحلة جديدة '{$request->stage_name}' لمشروعك, موعد التسليم: {$request->due_date}",
            'type' => 'info',
            'icon' => 'fa-regular fa-plus-circle',
            'is_read' => false,
        ]);

        return back()->with('success', 'تمت إضافة المرحلة بنجاح');
    }

    // ========== 2. مراجعة المرحلة والملفات (للمشرف) ==========
    public function updateStatus(Request $request, $stage_id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'grade' => 'nullable|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $stage = ProjectStage::findOrFail($stage_id);
        $project = Project::findOrFail($stage->project_id);
        $oldStatus = $stage->status;

        $stage->update(['status' => $request->status]);

        if ($request->status == 'approved') {
            $submission = \DB::table('project_files')
                ->where('stage_id', $stage_id)
                ->latest()
                ->first();

            \DB::table('evaluations')->updateOrInsert(
                ['project_id' => $stage->project_id],
                [
                    'supervisor_id' => Auth::id(),
                    'grade' => $request->grade ?? 0,
                    'feedback' => $request->feedback,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // ========== إشعار للطالب بنتيجة المراجعة ==========
        $supervisorName = Auth::user()->name;
        $statusText = $request->status == 'approved' ? '✅ مقبولة' : '❌ مرفوضة';
        $statusIcon = $request->status == 'approved' ? 'fa-regular fa-check-circle' : 'fa-regular fa-times-circle';

        Notification::create([
            'user_id' => $project->student->user_id,
            'project_id' => $stage->project_id,
            'title' => "📋 نتيجة مراجعة المرحلة: {$stage->stage_name}",
            'message' => "قام المشرف {$supervisorName} بمراجعة مرحلتك وأصبحت {$statusText}" . ($request->feedback ? "\nملاحظات: {$request->feedback}" : ''),
            'type' => $request->status == 'approved' ? 'success' : 'warning',
            'icon' => $statusIcon,
            'is_read' => false,
        ]);

        return back()->with('success', 'تم تحديث حالة المرحلة بنجاح.');
    }

    // ========== 3. رفع الملفات للمراحل (للطالب) ==========
    public function uploadFile(Request $request, $stage_id)
    {
        $request->validate([
            'project_file' => 'required|file|mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg,txt|max:20480',
        ]);

        $stage = ProjectStage::findOrFail($stage_id);
        $project = Project::findOrFail($stage->project_id);

        if ($request->hasFile('project_file')) {
            $path = $request->file('project_file')->store('projects/stages', 'public');

            \DB::table('project_files')->insert([
                'project_id'    => $stage->project_id,
                'stage_id'      => $stage_id,
                'file_path'     => $path,
                'student_notes' => $request->student_notes ?? null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $stage->update(['status' => 'submitted']);

            // ========== إشعار للمشرف ==========
            $studentName = Auth::user()->name;

            Notification::create([
                'user_id' => $project->supervisor_id,
                'project_id' => $stage->project_id,
                'title' => '📎 ملف مرفوع للمراجعة',
                'message' => "الطالب {$studentName} قام برفع ملف للمرحلة '{$stage->stage_name}' ويحتاج إلى مراجعتك",
                'type' => 'info',
                'icon' => 'fa-regular fa-file-arrow-up',
                'is_read' => false,
            ]);

            return back()->with('success', 'تم رفع الملف بنجاح، بانتظار مراجعة المشرف.');
        }

        return back()->with('error', 'فشل في رفع الملف');
    }
}
