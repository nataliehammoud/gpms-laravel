<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    // App\Http\Controllers\SubmissionController.php

public function store(Request $request)
{
    $request->validate([
        'stage_id' => 'required|exists:project_stages,id',
        'file'     => 'required|mimes:pdf,zip,docx|max:10240', // حد أقصى 10MB
    ]);

    // رفع الملف وتخزينه في مجلد submissions
    $filePath = $request->file('file')->store('submissions', 'public');

    \App\Models\Submission::create([
        'stage_id'   => $request->stage_id,
        'student_id' => auth()->id(), // الطالب المسجل حالياً
        'file_path'  => $filePath,
        'notes'      => $request->notes,
    ]);

    return back()->with('success', 'تم رفع الملف بنجاح، بانتظار تقييم المشرف.');
}

}
