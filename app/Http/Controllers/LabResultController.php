<?php

namespace App\Http\Controllers;

use App\Models\LabResult;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabResultController extends Controller
{
    // جلب جميع نتائج المختبر مع تفاصيل التحليل والزيارة (والمريض التابع للزيارة)
    public function index()
    {
        $labResults = LabResult::with(['testType', 'visit.person'])->get();
        return response()->json($labResults);
    }

    // إضافة نتيجة مختبر جديدة لزيارة معينة
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'result_value' => 'nullable|numeric',
            'lab_notes'    => 'nullable|string',
            'status'       => ['nullable', Rule::in(['pending', 'completed', 'reviewed'])],
            'visit_id'     => 'required|exists:visits,id',
            'test_type_id' => 'required|exists:test_types,id',
        ]);

        // القيمة الافتراضية للحالة لو ما تم إرسالها
        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'pending';
        }

        $labResult = LabResult::create($validatedData);

        // إرجاع النتيجة مع تحميل كل العلاقات اللي بتلزم الواجهة
        return response()->json($labResult->load(['testType', 'visit.person']), 201);
    }
}
