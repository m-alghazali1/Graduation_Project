<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    // جلب جميع الأطباء
    public function index()
    {
        $doctors = Doctor::all();
        return response()->json($doctors);
    }

    // إضافة طبيب جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'status'    => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'active';
        }

        $doctor = Doctor::create($validatedData);

        return response()->json($doctor, 201);
    }

    // تعديل بيانات طبيب
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'status'    => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $doctor->update($validatedData);

        return response()->json($doctor);
    }

    // حذف طبيب
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return response()->json(['message' => 'تم حذف الطبيب بنجاح']);
    }
}
