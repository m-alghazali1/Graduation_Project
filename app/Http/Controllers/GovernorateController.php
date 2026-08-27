<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    public function index()
    {
        $governorates = Governorate::withCount('cities')->get();
        return response()->json($governorates);
    }

    public function show($id)
    {
        $governorate = Governorate::with('cities')->findOrFail($id);
        return response()->json($governorate);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:governorates,name|max:255',
        ]);

        $governorate = Governorate::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'تمت إضافة المحافظة بنجاح',
            'data' => $governorate
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $governorate = Governorate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:governorates,name,' . $id,
        ]);

        $governorate->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'تم تعديل المحافظة بنجاح',
            'data' => $governorate
        ]);
    }

    public function destroy($id)
    {
        $governorate = Governorate::findOrFail($id);

        // التحقق من وجود مدن مرتبطة بالمحافظة
        if ($governorate->cities()->count() > 0) {
            return response()->json([
                'message' => 'لا يمكن حذف هذه المحافظة لوجود مدن تابعة لها. يرجى حذف المدن التابعة أولاً.'
            ], 422);
        }

        $governorate->delete();

        return response()->json([
            'message' => 'تم حذف المحافظة بنجاح'
        ]);
    }
}
