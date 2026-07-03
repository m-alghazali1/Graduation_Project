<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\NeighborhoodController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/governorates', [GovernorateController::class, 'index']);
Route::post('/governorates', [GovernorateController::class, 'store']);

Route::get('/cities', [CityController::class, 'index']);
Route::post('/cities', [CityController::class, 'store']);


Route::get('/medicines', [MedicineController::class, 'index']);
Route::post('/medicines', [MedicineController::class, 'store']);
Route::delete('/medicines/{id}', [MedicineController::class, 'destroy']);

Route::get('/neighborhoods', [NeighborhoodController::class, 'index']);
Route::post('/neighborhoods', [NeighborhoodController::class, 'store']);

Route::get('/analyses', [AnalysisController::class, 'index']);
Route::post('/analyses', [AnalysisController::class, 'store']);

Route::get('/dashboard/cities', function () {
    return view('cities.index');
});
Route::get('/dashboard/governorates', function () {
    return view('governorates.index');
});
Route::get('/dashboard/medicines', function () {
    return view('medicine-types');
});
Route::get('/dashboard/neighborhoods', function () {
    return view('districts');
});
Route::get('/dashboard/analyses', function () {
    return view('analysis-types');
});


