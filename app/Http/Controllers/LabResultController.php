<?php

namespace App\Http\Controllers;

use App\Models\LabResult;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabResultController extends Controller
{
    // جلب نتائج المختبر مع إمكانية التصفية حسب الحالة (مثل: pending) أو الزيارة
    public function index(Request $request)
    {
        $query = LabResult::with(['testType', 'visit.person', 'visit.doctor.person']);

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('visit_id') && !empty($request->visit_id)) {
            $query->where('visit_id', $request->visit_id);
        }

        return response()->json($query->latest()->get());
    }

    // إضافة طلب فحص مخبري جديد لزيارة مريض (من قبل الطبيب أو الاستقبال)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'visit_id'     => 'required|exists:visits,id',
            'test_type_id' => 'required|exists:test_types,id',
            'result_value' => 'nullable|numeric',
            'lab_notes'    => 'nullable|string',
            'status'       => ['nullable', Rule::in(['pending', 'completed', 'reviewed'])],
        ]);

        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'pending';
        }

        $labResult = LabResult::create($validatedData);

        return response()->json($labResult->load(['testType', 'visit.person']), 201);
    }

    // إدخال وتحديث نتيجة الفحص المخبري (من قبل فني المختبر)
    public function update(Request $request, $id)
    {
        $labResult = LabResult::findOrFail($id);

        $validatedData = $request->validate([
            'result_value' => 'required|numeric',
            'lab_notes'    => 'nullable|string',
            'status'       => ['nullable', Rule::in(['pending', 'completed', 'reviewed'])],
        ]);

        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'completed';
        }

        $labResult->update($validatedData);

        return response()->json($labResult->fresh()->load(['testType', 'visit.person']));
    }

    // حذف نتيجة أو طلب تحليل
    public function destroy($id)
    {
        $labResult = LabResult::findOrFail($id);
        $labResult->delete();

        return response()->json(['message' => 'تم حذف طلب التحليل بنجاح']);
    }
}
