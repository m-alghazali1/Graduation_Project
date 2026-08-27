<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonController extends Controller
{
    // جلب جميع المرضى مع تفاصيل العنوان كاملة
    public function index()
    {
        $persons = Person::with('neighborhood.city.governorate')->latest()->get();
        return response()->json($persons);
    }

    // جلب بيانات مريض محدد
    public function show($id)
    {
        $person = Person::with('neighborhood.city.governorate')->findOrFail($id);
        return response()->json($person);
    }

    // إضافة مريض جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'national_id'     => 'required|string|unique:persons,national_id',
            'full_name'       => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'birth_date'      => 'nullable|date',
            'gender'          => 'required|in:male,female',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
        ], [
            'national_id.unique' => 'رقم الهوية مسجل مسبقاً في النظام.',
            'gender.in'          => 'الجنس يجب أن يكون ذكراً أو أنثى.'
        ]);

        $person = Person::create($validatedData);

        return response()->json($person->load('neighborhood.city.governorate'), 201);
    }

    // تعديل بيانات مريض
    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);

        $validatedData = $request->validate([
            'national_id'     => ['required', 'string', Rule::unique('persons', 'national_id')->ignore($person->id)],
            'full_name'       => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'birth_date'      => 'nullable|date',
            'gender'          => 'required|in:male,female',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
        ]);

        $person->update($validatedData);

        return response()->json($person->fresh()->load('neighborhood.city.governorate'));
    }

    // حذف مريض
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return response()->json(['message' => 'تم حذف بيانات المريض بنجاح']);
    }
}
