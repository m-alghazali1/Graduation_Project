<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VisitController extends Controller
{
    // جلب جميع الزيارات مع بيانات المريض والطبيب والتحاليل والوصفات
    public function index(Request $request)
    {
        $query = Visit::with([
            'person.neighborhood.city.governorate',
            'doctor.person',
            'labResults.testType',
            'prescriptionItems.medicine'
        ]);

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('person_id') && !empty($request->person_id)) {
            $query->where('person_id', $request->person_id);
        }

        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('appointment_date', $request->date);
        }

        return response()->json($query->latest()->get());
    }

    // جلب تفاصيل زيارة محددة
    public function show($id)
    {
        $visit = Visit::with([
            'person.neighborhood.city.governorate',
            'doctor.person',
            'labResults.testType',
            'prescriptionItems.medicine'
        ])->findOrFail($id);

        return response()->json($visit);
    }

    // إضافة زيارة جديدة (حجز موعد أو استقبال)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'appointment_date' => 'required|date',
            'blood_pressure'   => 'nullable|string|max:20',
            'weight'           => 'nullable|numeric|min:0',
            'temperature'      => 'nullable|numeric',
            'diagnosis'        => 'nullable|string|max:255',
            'doctor_notes'     => 'nullable|string',
            'status'           => ['nullable', Rule::in(['waiting', 'in_progress', 'completed', 'cancelled'])],
            'person_id'        => 'required|exists:persons,id',
            'doctor_id'        => 'nullable|exists:users,id',
        ]);

        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'waiting';
        }

        $visit = Visit::create($validatedData);

        return response()->json($visit->load([
            'person',
            'doctor.person',
            'labResults.testType',
            'prescriptionItems.medicine'
        ]), 201);
    }

    // تعديل بيانات الزيارة / إجراء الكشف الطبي من قبل الطبيب
    public function update(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);

        $validatedData = $request->validate([
            'appointment_date' => 'sometimes|required|date',
            'blood_pressure'   => 'nullable|string|max:20',
            'weight'           => 'nullable|numeric|min:0',
            'temperature'      => 'nullable|numeric',
            'diagnosis'        => 'nullable|string|max:255',
            'doctor_notes'     => 'nullable|string',
            'status'           => ['sometimes', 'required', Rule::in(['waiting', 'in_progress', 'completed', 'cancelled'])],
            'person_id'        => 'sometimes|required|exists:persons,id',
            'doctor_id'        => 'nullable|exists:users,id',
        ]);

        $visit->update($validatedData);

        return response()->json($visit->fresh()->load([
            'person.neighborhood.city.governorate',
            'doctor.person',
            'labResults.testType',
            'prescriptionItems.medicine'
        ]));
    }

    // جلب السجل الطبي والتاريخ الكامل للمريض (Medical History)
    public function patientHistory($personId)
    {
        $person = Person::with('neighborhood.city.governorate')->findOrFail($personId);

        $visits = Visit::with([
            'doctor.person',
            'labResults.testType',
            'prescriptionItems.medicine'
        ])
        ->where('person_id', $personId)
        ->latest('appointment_date')
        ->get();

        return response()->json([
            'patient' => $person,
            'history' => $visits,
            'total_visits' => $visits->count(),
            'total_lab_tests' => $visits->pluck('labResults')->flatten()->count(),
            'total_prescriptions' => $visits->pluck('prescriptionItems')->flatten()->count(),
        ]);
    }

    // حذف زيارة
    public function destroy($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->delete();

        return response()->json(['message' => 'تم حذف الزيارة بنجاح']);
    }
}
