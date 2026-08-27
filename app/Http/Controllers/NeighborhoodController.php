<?php

namespace App\Http\Controllers;

use App\Models\Neighborhood;
use App\Models\Person;
use Illuminate\Http\Request;

class NeighborhoodController extends Controller
{
    public function index()
    {
        $neighborhoods = Neighborhood::with('city.governorate')->get();
        return response()->json($neighborhoods);
    }

    public function show($id)
    {
        $neighborhood = Neighborhood::with('city.governorate')->findOrFail($id);
        return response()->json($neighborhood);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ]);

        $neighborhood = Neighborhood::create($validated);

        return response()->json([
            'message' => 'تمت إضافة الحي بنجاح',
            'data' => $neighborhood->load('city.governorate')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $neighborhood = Neighborhood::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'city_id' => 'sometimes|required|exists:cities,id',
        ]);

        $neighborhood->update($validated);

        return response()->json([
            'message' => 'تم تعديل بيانات الحي بنجاح',
            'data' => $neighborhood->load('city.governorate')
        ]);
    }

    public function destroy($id)
    {
        $neighborhood = Neighborhood::findOrFail($id);

        // فك ارتباط المرضى المسجلين بهذا الحي لتجنب كسر قيود المفاتيح الأجنبية
        Person::where('neighborhood_id', $id)->update(['neighborhood_id' => null]);

        $neighborhood->delete();

        return response()->json([
            'message' => 'تم حذف الحي بنجاح'
        ]);
    }
}
