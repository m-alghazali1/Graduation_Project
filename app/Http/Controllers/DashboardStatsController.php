<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Visit;
use App\Models\LabResult;
use App\Models\PrescriptionItem;
use App\Models\Medicine;
use App\Models\User;
use App\Models\TestType;
use Illuminate\Http\Request;

class DashboardStatsController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $totalPatients = Person::count();
        $totalDoctors = User::where('role', 'doctor')->count();
        $todayVisits = Visit::whereDate('appointment_date', $today)->count();
        $waitingVisits = Visit::whereIn('status', ['waiting', 'in_progress'])->count();
        $pendingLabResults = LabResult::where('status', 'pending')->count();
        $pendingPrescriptions = PrescriptionItem::where('is_dispensed', false)->count();
        $lowStockMedicines = Medicine::where('stock_quantity', '<=', 10)->count();
        $totalTestTypes = TestType::where('status', 'active')->count();

        $recentVisits = Visit::with(['person', 'doctor.person'])
            ->latest()
            ->take(5)
            ->get();

        $recentLabOrders = LabResult::with(['testType', 'visit.person'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentPrescriptions = PrescriptionItem::with(['medicine', 'visit.person'])
            ->where('is_dispensed', false)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'stats' => [
                'total_patients'        => $totalPatients,
                'total_doctors'         => $totalDoctors,
                'today_visits'          => $todayVisits,
                'waiting_visits'        => $waitingVisits,
                'pending_lab_results'   => $pendingLabResults,
                'pending_prescriptions' => $pendingPrescriptions,
                'low_stock_medicines'   => $lowStockMedicines,
                'total_test_types'      => $totalTestTypes,
            ],
            'recent_visits'         => $recentVisits,
            'recent_lab_orders'     => $recentLabOrders,
            'recent_prescriptions'  => $recentPrescriptions,
        ]);
    }
}
