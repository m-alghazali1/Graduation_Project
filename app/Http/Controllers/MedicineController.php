<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        return response()->json(Medicine::all());
    }

    // لحفظ دواء جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'strength' => 'nullable|string|max:100',
            'stock_quantity' => 'required|integer',
            'is_available' => 'required|boolean',
        ]);

        $medicine = Medicine::create($validated);

        return response()->json($medicine, 201);
    }

    // (اختياري) لحذف دواء
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
