<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        $request->session()->regenerate();
        
        $user = Auth::user();

        // 🛑 التعديل الجوهري هون: لا تستخدمي intended، استخدمي redirect المباشر
        if (trim(strtolower($user->role)) === 'admin') {
            return redirect('/admin/dashboard');
        }
        
        if (trim(strtolower($user->role)) === 'supervisor') {
            return redirect('/supervisor/super');
        }

        if (trim(strtolower($user->role)) === 'student') {
            return redirect('/student/project/create');
        }

        return redirect('/');
    }

    return back()->withErrors(['email' => __('auth.failed')]);
}


    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
