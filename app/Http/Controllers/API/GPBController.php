<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pension;

/// TODO: AJOUTER LE MODULE

class GPBController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Pension::all());
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'CP' => 'nullable|string|max:10',
            'tel' => 'nullable|string|max:20'
        ]);

        $pension = Pension::create($validated);
        return response()->json($pension, 201);
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $pension = Pension::findOrFail($id);
        return response()->json($pension);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $pension = Pension::findOrFail($id);
        $validated = $request->validate([
            'adresse' => 'sometimes|required|string|max:255',
            'ville' => 'sometimes|required|string|max:100',
            'CP' => 'sometimes|nullable|string|max:10',
            'tel' => 'sometimes|nullable|string|max:20'
        ]);

        $pension->update($validated);
        return response()->json($pension);
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $pension = Pension::findOrFail($id);
        $pension->delete();
        return response()->json(['message' => 'Pension supprimée']);
    }
}
