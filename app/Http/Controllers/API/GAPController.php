<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Animaux;
use Illuminate\Http\Request;

class GAPController extends Controller
{
    public function index(Request $request)
    {
        $animaux = Animaux::where('user_id', $request->user()->id)->get();
        return response()->json($animaux);
    }

    public function show(Request $request, string $id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', $request->user()->id)->first();
        if (!$animal) return response()->json(['message' => 'Animal non trouvé'], 404);
        return response()->json($animal);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'                => 'required|string|max:255',
            'espece'             => 'nullable|string|max:255',
            'race'               => 'nullable|string|max:255',
            'age'                => 'nullable|integer|min:0',
            'poids'              => 'nullable|numeric|min:0',
            'description'        => 'nullable|string',
            'carnet_vaccination' => 'nullable|boolean',
            'vaccin_a_jour'      => 'nullable|boolean',
            'vermifuge_a_jour'   => 'nullable|boolean',
        ]);
        $validated['user_id'] = $request->user()->id;
        $animal = Animaux::create($validated);
        return response()->json($animal, 201);
    }

    public function update(Request $request, string $id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', $request->user()->id)->first();
        if (!$animal) return response()->json(['message' => 'Animal non trouvé'], 404);

        $validated = $request->validate([
            'nom'                => 'sometimes|string|max:255',
            'espece'             => 'nullable|string|max:255',
            'race'               => 'nullable|string|max:255',
            'age'                => 'nullable|integer|min:0',
            'poids'              => 'nullable|numeric|min:0',
            'description'        => 'nullable|string',
            'carnet_vaccination' => 'nullable|boolean',
            'vaccin_a_jour'      => 'nullable|boolean',
            'vermifuge_a_jour'   => 'nullable|boolean',
        ]);
        $animal->update($validated);
        return response()->json($animal);
    }

    public function destroy(Request $request, string $id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', $request->user()->id)->first();
        if (!$animal) return response()->json(['message' => 'Animal non trouvé'], 404);
        $animal->delete();
        return response()->json(['message' => 'Animal supprimé avec succès']);
    }
    public function proprietaires()
    {
        $users = \App\Models\User::with('animaux')->get();
        return response()->json($users);
    }

    public function animauxProprietaire(Request $request, string $id)
    {
        $animaux = \App\Models\Animaux::where('user_id', $id)->get();
        return response()->json($animaux);
    }
}


