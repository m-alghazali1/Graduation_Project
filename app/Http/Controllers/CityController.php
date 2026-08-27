<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('governorate')->get();
        return response()->json($cities);
    }

    public function show($id)
    {
        $city = City::with(['governorate', 'neighborhoods'])->findOrFail($id);
        return response()->json($city);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'governorate_id' => 'required|exists:governorates,id',
        ]);

        $city = City::create($validated);

        return response()->json([
            'message' => 'تمت إضافة المدينة بنجاح',
            'data' => $city->load('governorate')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'governorate_id' => 'sometimes|required|exists:governorates,id',
        ]);

        $city->update($validated);

        return response()->json([
            'message' => 'تم تعديل بيانات المدينة بنجاح',
            'data' => $city->load('governorate')
        ]);
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);

        // التحقق من وجود أحياء تابعة للمدينة
        if ($city->neighborhoods()->count() > 0) {
            return response()->json([
                'message' => 'لا يمكن حذف هذه المدينة لوجود أحياء تابعة لها. يرجى حذف الأحياء التابعة أولاً.'
            ], 422);
        }

        $city->delete();

        return response()->json([
            'message' => 'تم حذف المدينة بنجاح'
        ]);
    }
}
