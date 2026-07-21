<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // جلب جميع المستخدمين (الطاقم) مع بياناتهم الشخصية
    public function index()
    {
        $users = User::with('person')->get();
        return response()->json($users);
    }

    // إضافة مستخدم جديد (طبيب، صيدلي، موظف مختبر، أو أدمن)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'person_id' => 'nullable|exists:persons,id',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8',
            'role'      => ['required', Rule::in(['admin', 'doctor', 'lab_employee', 'pharmacist'])],
        ]);

        // تشفير كلمة المرور قبل الحفظ في الداتا بيز
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        // إرجاع النتيجة مع تحميل بياناته الشخصية
        return response()->json($user->load('person'), 201);
    }
}
