<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('governorate')->get();
        return response()->json($cities);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'governorate_id' => 'required|exists:governorates,id',
        ]);

        $city = City::create([
            'name' => $request->name,
            'governorate_id' => $request->governorate_id,
        ]);

        return response()->json(['message' => 'City created successfully'], 201);
    }
}
