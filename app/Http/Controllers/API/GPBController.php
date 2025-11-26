<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pension;

/// TODO: AJOUTER LE MODULE

class GPBController extends Controller
{
    /**
     * Afficher la liste des pensions.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Pension::all());
    }

    /**
     * Ajouter une pension.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'               => 'required|string|max:255',
            'adresse'           => 'required|string|max:255',
            'ville'             => 'required|string|max:100',
            'region'            => 'nullable|string|max:100',
            'code_postal'       => 'required|string|max:20',
            'telephone'         => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'description'       => 'nullable|string',
            'capacite_chiens'   => 'nullable|integer|min:0',
            'capacite_chats'    => 'nullable|integer|min:0',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // limite 2MB
            'directeur_nom'     => 'nullable|string|max:255',
            'directeur_email'   => 'nullable|email|max:255',
            'services'          => 'nullable|string', // JSON possible, change en 'array'
            'horaires'          => 'nullable|string', // JSON possible
            'prix_chien_jour'   => 'nullable|numeric|min:0',
            'prix_chat_jour'    => 'nullable|numeric|min:0',
            'actif'             => 'required|boolean',
            'famille'           => 'nullable|boolean',
        ]);

        $pension = Pension::create($validated);
        return response()->json($pension, 201);
    }

    /**
     * Afficher la pension.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $pension = Pension::findOrFail($id);
        return response()->json($pension);
    }

    /**
     * Mettre à jour la pension.
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $pension = Pension::findOrFail($id);
        $validated = $request->validate([
            'nom'               => 'required|string|max:255',
            'adresse'           => 'required|string|max:255',
            'ville'             => 'required|string|max:100',
            'region'            => 'nullable|string|max:100',
            'code_postal'       => 'required|string|max:20',
            'telephone'         => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'description'       => 'nullable|string',
            'capacite_chiens'   => 'nullable|integer|min:0',
            'capacite_chats'    => 'nullable|integer|min:0',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // limite 2MB
            'directeur_nom'     => 'nullable|string|max:255',
            'directeur_email'   => 'nullable|email|max:255',
            'services'          => 'nullable|string', // JSON possible, change en 'array'
            'horaires'          => 'nullable|string', // JSON possible
            'prix_chien_jour'   => 'nullable|numeric|min:0',
            'prix_chat_jour'    => 'nullable|numeric|min:0',
            'actif'             => 'required|boolean',
            'famille'           => 'nullable|boolean',
        ]);

        $pension->update($validated);
        return response()->json($pension);
    }

    /**
     * Supprimer la pension.
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
