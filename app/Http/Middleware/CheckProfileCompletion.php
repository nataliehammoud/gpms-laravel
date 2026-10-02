<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
public function handle(Request $request, Closure $next): Response
{
    $user = auth()->user();

    // 1. إذا كان المستخدم مشرفاً أو أدمن، لا تتدخل واتركه يمر فوراً
    if ($user && ($user->role === 'supervisor' || $user->role === 'admin')) {
        return $next($request);
    }

    // 2. فقط للطالب: إذا لم يكن لديه سجل بجدول students، حوله للإكمال
    if ($user && $user->role === 'student' && !$user->student()->exists()) {
        if (!$request->routeIs('profile.complete') && !$request->is('complete-profile*')) {
            return redirect()->route('profile.complete');
        }
    }

    return $next($request);
}

}
