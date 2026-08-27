<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\LabResultController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\NeighborhoodController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\DashboardStatsController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Medical Point Management System
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. المسارات العامة (Public Routes)
// ==========================================
Route::post('/login', [AuthController::class, 'login']);

// ==========================================
// 2. المسارات المحمية بمصادقة Sanctum
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // إدارة الجلسة والمستخدم الحالي
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/dashboard/stats', [DashboardStatsController::class, 'index']);

    // ==========================================
    // المرضى والسجلات الطبية
    // ==========================================
    Route::get('/persons', [PersonController::class, 'index']);
    Route::get('/persons/{id}', [PersonController::class, 'show']);
    Route::post('/persons', [PersonController::class, 'store']);
    Route::put('/persons/{id}', [PersonController::class, 'update']);
    Route::get('/persons/{id}/history', [VisitController::class, 'patientHistory']);
    Route::delete('/persons/{id}', [PersonController::class, 'destroy'])->middleware('role:admin');

    // ==========================================
    // الزيارات والكشف السريري للطبيب
    // ==========================================
    Route::get('/visits', [VisitController::class, 'index']);
    Route::get('/visits/{id}', [VisitController::class, 'show']);
    Route::post('/visits', [VisitController::class, 'store']);
    Route::put('/visits/{id}', [VisitController::class, 'update']);
    Route::delete('/visits/{id}', [VisitController::class, 'destroy'])->middleware('role:admin');

    // ==========================================
    // المختبر والتحاليل الطبية
    // ==========================================
    Route::get('/lab-results', [LabResultController::class, 'index']);
    Route::post('/lab-results', [LabResultController::class, 'store'])->middleware('role:admin,doctor');
    Route::put('/lab-results/{id}', [LabResultController::class, 'update'])->middleware('role:admin,lab_employee');
    Route::delete('/lab-results/{id}', [LabResultController::class, 'destroy'])->middleware('role:admin,doctor');

    Route::get('/test-types', [TestTypeController::class, 'index']);
    Route::post('/test-types', [TestTypeController::class, 'store'])->middleware('role:admin,lab_employee');
    Route::put('/test-types/{id}', [TestTypeController::class, 'update'])->middleware('role:admin,lab_employee');
    Route::delete('/test-types/{id}', [TestTypeController::class, 'destroy'])->middleware('role:admin');

    // مسارات التحاليل التوافقية
    Route::get('/analyses', [AnalysisController::class, 'index']);
    Route::post('/analyses', [AnalysisController::class, 'store'])->middleware('role:admin,lab_employee');
    Route::put('/analyses/{id}', [AnalysisController::class, 'update'])->middleware('role:admin,lab_employee');
    Route::delete('/analyses/{id}', [AnalysisController::class, 'destroy'])->middleware('role:admin');

    // ==========================================
    // الصيدلية والوصفات الطبية والمخزون
    // ==========================================
    Route::get('/prescriptions', [PrescriptionController::class, 'index']);
    Route::get('/prescriptions/pending', [PrescriptionController::class, 'pending'])->middleware('role:admin,pharmacist');
    Route::post('/prescriptions', [PrescriptionController::class, 'store'])->middleware('role:admin,doctor');
    Route::post('/prescriptions/{id}/dispense', [PrescriptionController::class, 'dispense'])->middleware('role:admin,pharmacist');
    Route::delete('/prescriptions/{id}', [PrescriptionController::class, 'destroy'])->middleware('role:admin,doctor');

    Route::get('/medicines', [MedicineController::class, 'index']);
    Route::post('/medicines', [MedicineController::class, 'store'])->middleware('role:admin,pharmacist');
    Route::put('/medicines/{id}', [MedicineController::class, 'update'])->middleware('role:admin,pharmacist');
    Route::delete('/medicines/{id}', [MedicineController::class, 'destroy'])->middleware('role:admin,pharmacist');

    // ==========================================
    // إدارة الطاقم والمستخدمين والأطباء (Admin فقط)
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::get('/doctors', [DoctorController::class, 'index']);
        Route::post('/doctors', [DoctorController::class, 'store']);
        Route::put('/doctors/{id}', [DoctorController::class, 'update']);
        Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']);
    });

    // ==========================================
    // الثوابت الجغرافية (المحافظات، المدن، الأحياء)
    // ==========================================
    Route::get('/governorates', [GovernorateController::class, 'index']);
    Route::get('/governorates/{id}', [GovernorateController::class, 'show']);
    Route::post('/governorates', [GovernorateController::class, 'store'])->middleware('role:admin');
    Route::put('/governorates/{id}', [GovernorateController::class, 'update'])->middleware('role:admin');
    Route::delete('/governorates/{id}', [GovernorateController::class, 'destroy'])->middleware('role:admin');

    Route::get('/cities', [CityController::class, 'index']);
    Route::get('/cities/{id}', [CityController::class, 'show']);
    Route::post('/cities', [CityController::class, 'store'])->middleware('role:admin');
    Route::put('/cities/{id}', [CityController::class, 'update'])->middleware('role:admin');
    Route::delete('/cities/{id}', [CityController::class, 'destroy'])->middleware('role:admin');

    Route::get('/neighborhoods', [NeighborhoodController::class, 'index']);
    Route::get('/neighborhoods/{id}', [NeighborhoodController::class, 'show']);
    Route::post('/neighborhoods', [NeighborhoodController::class, 'store'])->middleware('role:admin');
    Route::put('/neighborhoods/{id}', [NeighborhoodController::class, 'update'])->middleware('role:admin');
    Route::delete('/neighborhoods/{id}', [NeighborhoodController::class, 'destroy'])->middleware('role:admin');
});
