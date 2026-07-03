<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    public function index()
    {
        $governorates = Governorate::all();
        return response()->json($governorates);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:governorates,name|max:255',
        ]);

        $governorate = Governorate::create([
            'name' => $request->name,
        ]);
        return response()->json(['message' => 'Governorate created successfully'], 201);
    }
}
