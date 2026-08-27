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
        $users = User::with('person')->latest()->get();
        return response()->json($users);
    }

    // إضافة مستخدم جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'person_id' => 'nullable|exists:persons,id',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8',
            'role'      => ['required', Rule::in(['admin', 'doctor', 'lab_employee', 'pharmacist'])],
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return response()->json($user->load('person'), 201);
    }

    // تعديل بيانات مستخدم
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'person_id' => 'nullable|exists:persons,id',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'  => 'nullable|string|min:8',
            'role'      => ['required', Rule::in(['admin', 'doctor', 'lab_employee', 'pharmacist'])],
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json($user->fresh()->load('person'));
    }

    // حذف مستخدم
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // منع المستخدم من حذف نفسه
        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'لا يمكنك حذف حسابك الحالي أثناء تسجيل الدخول به.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'تم حذف المستخدم بنجاح']);
    }
}
