<?php

namespace App\Http\Controllers;

use App\Models\PrescriptionItem;
use App\Models\Medicine;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    // جلب جميع الوصفات الطبية أو تصفيتها حسب الزيارة
    public function index(Request $request)
    {
        $query = PrescriptionItem::with(['medicine', 'visit.person', 'visit.doctor.person']);

        if ($request->has('visit_id')) {
            $query->where('visit_id', $request->visit_id);
        }

        return response()->json($query->latest()->get());
    }

    // جلب الوصفات الطبية المعلقة بانتظار صرفها من الصيدلية
    public function pending(Request $request)
    {
        $pendingItems = PrescriptionItem::with(['medicine', 'visit.person', 'visit.doctor.person'])
            ->where('is_dispensed', false)
            ->latest()
            ->get();

        return response()->json($pendingItems);
    }

    // إضافة دواء جديد لوصفة الزيارة (بواسطة الطبيب)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'visit_id'            => 'required|exists:visits,id',
            'medicine_id'         => 'required|exists:medicines,id',
            'dosage'              => 'nullable|string|max:100',
            'prescribed_quantity' => 'required|integer|min:1',
            'instructions'        => 'nullable|string|max:500',
        ]);

        $item = PrescriptionItem::create([
            'visit_id'            => $validatedData['visit_id'],
            'medicine_id'         => $validatedData['medicine_id'],
            'dosage'              => $validatedData['dosage'] ?? null,
            'prescribed_quantity' => $validatedData['prescribed_quantity'],
            'instructions'        => $validatedData['instructions'] ?? null,
            'is_dispensed'        => false,
            'dispensed_at'        => null,
        ]);

        return response()->json($item->load(['medicine', 'visit.person']), 201);
    }

    // صرف الدواء من قبل الصيدلي وتحديث المخزون آلياً
    public function dispense(Request $request, $id)
    {
        $prescriptionItem = PrescriptionItem::with('medicine')->findOrFail($id);

        if ($prescriptionItem->is_dispensed) {
            return response()->json([
                'message' => 'تم صرف هذا الدواء مسبقاً.'
            ], 422);
        }

        $medicine = $prescriptionItem->medicine;

        if (!$medicine) {
            return response()->json([
                'message' => 'بيانات الدواء غير موجودة.'
            ], 404);
        }

        // التحقق من كفاية المخزون في الصيدلية
        if ($medicine->stock_quantity < $prescriptionItem->prescribed_quantity) {
            return response()->json([
                'message' => "الكمية المتوفرة في المخزون ({$medicine->stock_quantity}) غير كافية لصرف الكمية المطلوبة ({$prescriptionItem->prescribed_quantity})."
            ], 422);
        }

        // استخدام Transaction لضمان سلامة العملية المزدوجة
        DB::transaction(function () use ($prescriptionItem, $medicine) {
            // خصم الكمية من المخزون
            $medicine->decrement('stock_quantity', $prescriptionItem->prescribed_quantity);

            // تحديث حالة توفر الدواء إذا وصل الصفر
            if ($medicine->stock_quantity <= 0) {
                $medicine->update(['is_available' => false]);
            }

            // تحديث حالة الوصفة إلى مصروفة
            $prescriptionItem->update([
                'is_dispensed' => true,
                'dispensed_at' => now(),
            ]);
        });

        return response()->json([
            'message' => 'تم صرف الدواء بنجاح وخصم الكمية من المخزون.',
            'prescription_item' => $prescriptionItem->fresh()->load(['medicine', 'visit.person'])
        ]);
    }

    // حذف دواء من الوصفة (قبل الصرف)
    public function destroy($id)
    {
        $prescriptionItem = PrescriptionItem::findOrFail($id);

        if ($prescriptionItem->is_dispensed) {
            return response()->json([
                'message' => 'لا يمكن حذف دواء تم صرفه بالفعل من الصيدلية.'
            ], 422);
        }

        $prescriptionItem->delete();

        return response()->json([
            'message' => 'تم حذف الدواء من الوصفة بنجاح.'
        ]);
    }
}
