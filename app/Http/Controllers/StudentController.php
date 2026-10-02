<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectPreference;
use App\Models\Notification;
use App\Models\User;

class StudentController extends Controller
{
    public function storePreferences(Request $request) {
        $request->validate([
            'projects' => 'required|array|min:3', // التأكد أنه اختار 3
            'projects.*' => 'distinct', // التأكد أنه لم يكرر نفس المشروع في الرغبات
        ]);

        $student = auth()->user()->student;
        $studentName = auth()->user()->name;

        // حفظ الرغبات
        foreach ($request->projects as $rank => $projectId) {
            ProjectPreference::updateOrCreate(
                ['student_id' => $student->id, 'rank' => $rank + 1], // +1 عشان الرتبة تبدأ من 1
                ['project_id' => $projectId]
            );
        }

        // ========== إضافة إشعار للأدمن ==========
        $adminUsers = User::where('role', 'admin')->get();

        foreach ($adminUsers as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'project_id' => null,
                'title' => '📋 طالب جديد سجل رغباته',
                'message' => "الطالب {$studentName} قام بتسجيل رغباته الثلاث بنجاح",
                'type' => 'info',
                'icon' => 'fa-regular fa-pen-to-square',
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'تم حفظ رغباتك الثلاث بنجاح!');
    }

}
