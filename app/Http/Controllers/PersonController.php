<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    // جلب جميع المرضى مع تفاصيل العنوان كاملة
    public function index()
    {
        // هادا السطر بيجيب الحي، ومن الحي بيجيب المدينة، ومن المدينة بيجيب المحافظة!
        $persons = Person::with('neighborhood.city.governorate')->get();

        return response()->json($persons);
    }

    // إضافة مريض جديد
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'national_id'     => 'required|string|unique:persons,national_id',
            'full_name'       => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'birth_date'      => 'nullable|date',
            'gender'          => 'required|in:male,female',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
        ], [
            // رسائل خطأ مخصصة لو حبيت
            'national_id.unique' => 'رقم الهوية مسجل مسبقاً في النظام.',
            'gender.in' => 'الجنس يجب أن يكون ذكراً أو أنثى.'
        ]);

        // إنشاء المريض
        $person = Person::create($validatedData);

        // إرجاع النتيجة مع تحميل بيانات الحي المربوط فيه
        return response()->json($person->load('neighborhood.city.governorate'), 201);
    }
}
