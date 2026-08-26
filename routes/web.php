<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('analysis-types');
});

Route::get('/dashboard/analyses', function () {
    return view('analysis-types');
});

Route::get('/dashboard/persons', function () {
    return view('patients');
});

Route::get('/dashboard/doctors', function () {
    return view('doctors');
});

Route::get('/dashboard/users', function () {
    return view('users');
});

Route::get('/dashboard/visits', function () {
    return view('appointments');
});

Route::get('/dashboard/cities', function () {
    return view('cities.index');
});

Route::get('/dashboard/governorates', function () {
    return view('governorates.index');
});

Route::get('/dashboard/neighborhoods', function () {
    return view('districts');
});

Route::get('/dashboard/medicine-types', function () {
    return view('medicine-types');
});

Route::get('/dashboard/lab-results', function () {
    return view('lab-results');
});

Route::get('/login', function () {
    return view('login');
});
