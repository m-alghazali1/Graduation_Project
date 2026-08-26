<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VisitController extends Controller
{
    // جلب جميع الزيارات مع بيانات المريض والطبيب
    public function index()
    {
        $visits = Visit::with(['person', 'doctor'])->get();
        return response()->json($visits);
    }

    // إضافة زيارة جديدة (حجز موعد أو بدء كشفية)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'appointment_date' => 'required|date',
            'blood_pressure' => 'nullable|string|max:20',
            'weight'           => 'nullable|numeric|min:0',
            'temperature'      => 'nullable|numeric',
            'doctor_notes'     => 'nullable|string',
            'status'           => ['nullable', Rule::in(['waiting', 'in_progress', 'completed', 'cancelled'])],
            'person_id'        => 'required|exists:persons,id',
            'doctor_id'        => 'nullable|exists:users,id',
        ]);

        // إذا ما تم إرسال حالة، بنعطيها القيمة الافتراضية
        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'waiting';
        }

        $visit = Visit::create($validatedData);

        // إرجاع الزيارة مع تفاصيل المريض والدكتور
        return response()->json($visit->load(['person', 'doctor']), 201);
    }
}
