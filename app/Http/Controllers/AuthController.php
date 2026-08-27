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

        // تحديد مسار التوجيه السليم بحسب دور المستخدم (Role)
        $redirectUrl = '/dashboard';

        if ($user->role === 'doctor') {
            $redirectUrl = '/dashboard/visits';
        } elseif ($user->role === 'lab_employee') {
            $redirectUrl = '/dashboard/lab-results';
        } elseif ($user->role === 'pharmacist') {
            $redirectUrl = '/dashboard/pharmacy';
        }

        // إرجاع استجابة نجاح مع بيانات الجلسة
        return response()->json([
            'message'      => 'تم تسجيل الدخول بنجاح',
            'token'        => $token,
            'role'         => $user->role,
            'redirect_url' => $redirectUrl,
            'user'         => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'name' => $user->person ? $user->person->full_name : $user->email,
                'person' => $user->person
            ]
        ], 200);
    }

    // دالة جلب بيانات المستخدم الحالي
    public function me(Request $request)
    {
        $user = $request->user()->load('person');
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'name' => $user->person ? $user->person->full_name : $user->email,
            'person' => $user->person
        ]);
    }

    // دالة تسجيل الخروج وإبطال التوكن في السيرفر
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح وإلغاء صلاحية الجلسة.'
        ]);
    }
}
