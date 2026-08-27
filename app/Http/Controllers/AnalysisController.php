<?php

namespace App\Http\Controllers;

use App\Models\TestType;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index()
    {
        return response()->json(TestType::latest()->get());
    }

    public function store(Request $request)
    {
        return app(TestTypeController::class)->store($request);
    }

    public function update(Request $request, $id)
    {
        return app(TestTypeController::class)->update($request, $id);
    }

    public function destroy($id)
    {
        return app(TestTypeController::class)->destroy($id);
    }
}
