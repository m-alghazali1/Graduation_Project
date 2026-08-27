<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Medical Point Management System
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/persons', function () {
        return view('patients');
    });

    Route::get('/visits', function () {
        return view('visits');
    });

    Route::get('/lab-results', function () {
        return view('lab-results');
    });

    Route::get('/analyses', function () {
        return view('analysis-types');
    });

    Route::get('/pharmacy', function () {
        return view('pharmacy');
    });

    Route::get('/medicine-types', function () {
        return view('medicine-types');
    });

    Route::get('/users', function () {
        return view('users');
    });

    Route::get('/doctors', function () {
        return view('doctors');
    });

    Route::get('/governorates', function () {
        return view('governorates.index');
    });

    Route::get('/cities', function () {
        return view('cities.index');
    });

    Route::get('/neighborhoods', function () {
        return view('districts');
    });
});
