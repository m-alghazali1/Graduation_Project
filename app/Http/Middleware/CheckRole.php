<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول. يرجى تسجيل الدخول أولاً.'
            ], 401);
        }

        // إذا كان المستخدم يمتلك أحد الأدوار المسموح بها، اسمح بالمرور
        if (in_array($user->role, $roles) || $user->role === 'admin') {
            return $next($request);
        }

        return response()->json([
            'message' => 'عذراً، لا تمتلك الصلاحيات الكافية للوصول إلى هذه الخدمة.'
        ], 403);
    }
}
