<?php

namespace App\Http\Controllers;

use App\Models\Animaux;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    // Liste tous les animaux (READ)
    public function index()
    {
        $animaux = Animaux::all();
        return response()->json($animaux);
    }

    // Affiche un animal spécifique (READ)
    public function show($id)
    {
        $animal = Animaux::find($id);

        if (!$animal) {
            return response()->json(['message' => 'Animal non trouvé'], 404);
        }

        return response()->json($animal);
    }

    // Crée un nouvel animal (CREATE)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'espece' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ]);

        $animal = Animaux::create($validated);

        return response()->json($animal, 201);
    }

    // Met à jour un animal (UPDATE)
    public function update(Request $request, $id)
    {
        $animal = Animaux::find($id);

        if (!$animal) {
            return response()->json(['message' => 'Animal non trouvé'], 404);
        }

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'espece' => 'sometimes|string|max:255',
            'age' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ]);

        $animal->update($validated);

        return response()->json($animal);
    }

    // Supprime un animal (DELETE)
    public function destroy($id)
    {
        $animal = Animaux::find($id);

        if (!$animal) {
            return response()->json(['message' => 'Animal non trouvé'], 404);
        }

        $animal->delete();

        return response()->json(['message' => 'Animal supprimé avec succès']);
    }
}
