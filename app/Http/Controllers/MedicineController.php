<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // جلب جميع الأدوية
    public function index()
    {
        return response()->json(Medicine::latest()->get());
    }

    // إضافة دواء جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'strength'       => 'nullable|string|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'is_available'   => 'required|boolean',
        ]);

        $medicine = Medicine::create($validated);

        return response()->json($medicine, 201);
    }

    // تعديل بيانات دواء ومخزونه
    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'strength'       => 'nullable|string|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'is_available'   => 'required|boolean',
        ]);

        $medicine->update($validated);

        return response()->json($medicine);
    }

    // حذف دواء
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return response()->json(['message' => 'تم حذف الدواء بنجاح']);
    }
}
