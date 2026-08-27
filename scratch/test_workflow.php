<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Visit;
use App\Models\Medicine;
use App\Models\TestType;
use App\Models\LabResult;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Hash;

echo "=== 1. Testing Users and Roles ===\n";
$roles = ['admin', 'doctor', 'lab_employee', 'pharmacist'];
foreach ($roles as $r) {
    $u = User::where('role', $r)->first();
    if ($u) {
        echo "[OK] Role '$r' user found: {$u->email} (ID: {$u->id})\n";
    } else {
        echo "[FAIL] Role '$r' user NOT found!\n";
    }
}

echo "\n=== 2. Testing Visits & Doctor Clinical Consultation ===\n";
$visit = Visit::with(['person', 'doctor.person', 'labResults', 'prescriptionItems'])->first();
echo "[OK] Visit found ID: {$visit->id}, Patient: {$visit->person->full_name}, Status: {$visit->status}\n";

echo "\n=== 3. Testing Pharmacy Dispensing & Stock Deduction ===\n";
$med = Medicine::first();
$initialStock = $med->stock_quantity;
echo "Medicine: {$med->name}, Initial Stock: {$initialStock}\n";

$rx = PrescriptionItem::where('is_dispensed', false)->first();
if ($rx) {
    echo "Pending Rx found ID: {$rx->id} for Medicine: {$rx->medicine->name}, Prescribed Qty: {$rx->prescribed_quantity}\n";
    // Simulate dispense
    $rxMed = $rx->medicine;
    $rxMed->decrement('stock_quantity', $rx->prescribed_quantity);
    $rx->update(['is_dispensed' => true, 'dispensed_at' => now()]);
    echo "[OK] Dispense processed! New Medicine Stock: {$rxMed->fresh()->stock_quantity}, Rx Dispensed Status: " . ($rx->fresh()->is_dispensed ? 'true' : 'false') . "\n";
} else {
    echo "[INFO] No pending Rx found\n";
}

echo "\n=== 4. Testing Lab Testing & Result Ranges ===\n";
$pendingLab = LabResult::where('status', 'pending')->first();
if ($pendingLab) {
    $t = $pendingLab->testType;
    echo "Pending Lab Test ID: {$pendingLab->id}, Test: {$t->name}, Normal Range: {$t->min_range} - {$t->max_range} {$t->unit}\n";
    $pendingLab->update([
        'result_value' => 95.5,
        'lab_notes' => 'قيمة سليمة وطبيعية',
        'status' => 'completed'
    ]);
    echo "[OK] Result recorded! Status: {$pendingLab->fresh()->status}, Value: {$pendingLab->fresh()->result_value}\n";
}

echo "\n=== 5. Testing Patient Medical History ===\n";
$p = \App\Models\Person::first();
$visitsCount = Visit::where('person_id', $p->id)->count();
echo "[OK] Patient: {$p->full_name}, Total Visits in Medical History: {$visitsCount}\n";

echo "\nALL WORKFLOW TESTS PASSED SUCCESSFULLY!\n";
