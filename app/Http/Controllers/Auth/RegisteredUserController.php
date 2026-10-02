<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification; // ✅ أضف هذا السطر
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        // ========== إضافة إشعار للأدمن ==========
        $adminUsers = User::where('role', 'admin')->get();

        foreach ($adminUsers as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'project_id' => null,
                'title' => '👤 طالب جديد',
                'message' => "تم تسجيل طالب جديد: {$user->name}",
                'type' => 'info',
                'icon' => 'fa-regular fa-user-plus',
                'is_read' => false,
            ]);
        }
        // =====================================

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
