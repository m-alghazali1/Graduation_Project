<?php

namespace App\Http\Controllers;

use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TestTypeController extends Controller
{
    // جلب جميع أنواع التحاليل
    public function index()
    {
        return response()->json(TestType::latest()->get());
    }

    // إضافة نوع تحليل جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50',
            'unit'        => 'nullable|string|max:50',
            'min_range'   => 'nullable|numeric',
            'max_range'   => 'nullable|numeric',
            'price'       => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status'      => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'active';
        }

        $testType = TestType::create($validatedData);

        return response()->json($testType, 201);
    }

    // تعديل بيانات نوع تحليل
    public function update(Request $request, $id)
    {
        $testType = TestType::findOrFail($id);

        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50',
            'unit'        => 'nullable|string|max:50',
            'min_range'   => 'nullable|numeric',
            'max_range'   => 'nullable|numeric',
            'price'       => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status'      => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $testType->update($validatedData);

        return response()->json($testType);
    }

    // حذف نوع تحليل
    public function destroy($id)
    {
        $testType = TestType::findOrFail($id);
        $testType->delete();

        return response()->json(['message' => 'تم حذف نوع التحليل بنجاح']);
    }
}
