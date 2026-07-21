<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestTypeController extends Controller
{
    // جلب جميع أنواع التحاليل
    public function index()
    {
        $testTypes = TestType::all();
        return response()->json($testTypes);
    }

    // إضافة نوع تحليل جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'test_name' => 'required|string|max:255',
            'min_range' => 'nullable|numeric',
            'max_range' => 'nullable|numeric|gte:min_range', // الـ gte بتضمن إن الحد الأقصى يكون أكبر من أو يساوي الحد الأدنى
        ], [
            'max_range.gte' => 'الحد الأقصى للتحليل يجب أن يكون أكبر من أو يساوي الحد الأدنى.'
        ]);

        $testType = TestType::create($validatedData);

        return response()->json($testType, 201);
    }
}
