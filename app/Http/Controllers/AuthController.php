<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // دالة تسجيل الدخول
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // البحث عن المستخدم بواسطة البريد الإلكتروني مع جلب بياناته الشخصية لو وجدت
        $user = User::with('person')->where('email', $request->email)->first();

        // التحقق من وجود المستخدم وسلامة كلمة المرور المشفرة
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'
            ], 422);
        }

        // إنشاء توكن مصادقة خاص بـ Laravel Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // تحديد مسار التوجيه الصحيح بناءً على دور المستخدم (Role)
        $redirectUrl = '/dashboard/analyses'; // القيمة الافتراضية للمدير

        if ($user->role === 'doctor') {
            $redirectUrl = '/dashboard/visits';
        } elseif ($user->role === 'lab') {
            $redirectUrl = '/dashboard/lab-results';
        } elseif ($user->role === 'pharmacist') {
            $redirectUrl = '/dashboard/medicine-types';
        }

        // إرجاع استجابة نجاح مع رابط التوجيه السليم
        return response()->json([
            'message'      => 'تم تسجيل الدخول بنجاح',
            'token'        => $token,
            'role'         => $user->role,
            'redirect_url' => $redirectUrl,
            'user'         => $user
        ], 200);
    }
}
