<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileCompletionController extends Controller
{

public function show() {
    return view('profile.complete'); // أنشئ هاد الملف بـ resources/views/profile/complete.blade.php
}

public function store(Request $request) {
    $request->validate([
        'student_number' => 'required|unique:students,student_number',
        'department' => 'required|string',
        'year' => 'required|integer|min:1|max:5',
    ]);

    // التعديل هنا: نمرر الحقول يدوياً لضمان عدم ضياعها
    auth()->user()->student()->create([
        'student_number' => $request->student_number,
        'department'     => $request->department,
        'year'           => $request->year,
    ]);

    return redirect()->route('dashboard')->with('success', 'تم إكمال بياناتك بنجاح');
}

}
