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
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/governorates', [GovernorateController::class, 'index']);
Route::post('/governorates', [GovernorateController::class, 'store']);
Route::delete('/governorates/{id}', [GovernorateController::class, 'destroy']);

Route::get('/cities', [CityController::class, 'index']);
Route::post('/cities', [CityController::class, 'store']);
Route::delete('/cities/{id}', [CityController::class, 'destroy']);

Route::get('/medicines', [MedicineController::class, 'index']);
Route::post('/medicines', [MedicineController::class, 'store']);
Route::delete('/medicines/{id}', [MedicineController::class, 'destroy']);

Route::get('/neighborhoods', [NeighborhoodController::class, 'index']);
Route::post('/neighborhoods', [NeighborhoodController::class, 'store']);
Route::delete('/neighborhoods/{id}', [NeighborhoodController::class, 'destroy']);

Route::get('/analyses', [AnalysisController::class, 'index']);
Route::post('/analyses', [AnalysisController::class, 'store']);

Route::get('/persons', [PersonController::class, 'index']);
Route::post('/persons', [PersonController::class, 'store']);
Route::delete('/persons/{id}', [PersonController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

Route::get('/visits', [VisitController::class, 'index']);
Route::post('/visits', [VisitController::class, 'store']);
Route::delete('/visits/{id}', [VisitController::class, 'destroy']);

Route::get('/lab-results', [LabResultController::class, 'index']);
Route::post('/lab-results', [LabResultController::class, 'store']);
Route::delete('/lab-results/{id}', [LabResultController::class, 'destroy']);

Route::get('/test-types', [TestTypeController::class, 'index']);
Route::post('/test-types', [TestTypeController::class, 'store']);

Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors', [DoctorController::class, 'store']);
Route::put('/doctors/{id}', [DoctorController::class, 'update']);
Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);
