<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectFileController extends Controller
{
    // رفع ملف لمرحلة معيّنة
   public function store(Request $request, $stage_id)
{
    $request->validate([
        'project_file'  => 'required|file|mimes:pdf,doc,docx,zip|max:10000',
        'student_notes' => 'nullable|string|max:500',
    ]);

    // 1. جلب المرحلة والمشروع المرتبط بها
    $stage = ProjectStage::with('project.users')->findOrFail($stage_id);
    $project = $stage->project;

    // 2. الحصول على ID المستخدم الحالي و ID بروفايله
    $userId = auth()->id();
    $studentProfileId = auth()->user()->student ? auth()->user()->student->id : null;

    // 3. التحقق: هل هو صاحب المشروع OR عضو في الفريق؟
    $isOwner = ($studentProfileId && $project->student_id == $studentProfileId);
    $isTeamMember = $project->users->contains($userId);

    if (!$isOwner && !$isTeamMember) {
        abort(403, 'غير مصرح لك برفع ملفات لهذا المشروع.');
    }

    // 4. رفع الملف
    $path = $request->file('project_file')->store('projects/files', 'public');

    // 5. إنشاء سجل الملف
    ProjectFile::create([
        'project_id'    => $project->id,
        'stage_id'      => $stage->id,
        'file_path'     => $path,
        'student_notes' => $request->student_notes,
    ]);

    // 6. تحديث حالة المرحلة لتصبح "تم الرفع" لكي يراها المشرف للمراجعة
    $stage->update(['status' => 'submitted']);

    return back()->with('success', 'تم رفع ملف المرحلة بنجاح، بانتظار مراجعة المشرف.');
}

    // المشرف يغيّر حالة الملف أو المرحلة
    public function updateStatus(Request $request, $file_id)
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $file = ProjectFile::findOrFail($file_id);
        $stage = $file->stage;

        $stage->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث حالة المرحلة');
    }
}
