<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animaux;
use Illuminate\Http\Request;

class GAPController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/animaux",
     *     summary="Liste des animaux de l'utilisateur connecté",
     *     tags={"Animaux"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des animaux"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $animaux = Animaux::where('user_id', $request->user()->id)->get();
        return response()->json($animaux);
    }

    /**
     * @OA\Get(
     *     path="/api/animaux/{id}",
     *     summary="Afficher un animal",
     *     tags={"Animaux"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Animal trouvé"),
     *     @OA\Response(response=404, description="Animal non trouvé")
     * )
     */
    public function show(Request $request, string $id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', $request->user()->id)->first();
        if (!$animal) return response()->json(['message' => 'Animal non trouvé'], 404);
        return response()->json($animal);
    }

    /**
     * @OA\Post(
     *     path="/api/animaux",
     *     summary="Créer un animal",
     *     tags={"Animaux"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom"},
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="espece", type="string"),
     *             @OA\Property(property="race", type="string"),
     *             @OA\Property(property="age", type="integer"),
     *             @OA\Property(property="poids", type="number", format="float"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="carnet_vaccination", type="boolean"),
     *             @OA\Property(property="vaccin_a_jour", type="boolean"),
     *             @OA\Property(property="vermifuge_a_jour", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Animal créé")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/animaux/{id}",
     *     summary="Mettre à jour un animal",
     *     tags={"Animaux"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="espece", type="string"),
     *             @OA\Property(property="race", type="string"),
     *             @OA\Property(property="age", type="integer"),
     *             @OA\Property(property="poids", type="number", format="float"),
    *             @OA\Property(property="vaccin_a_jour", type="boolean"),
    *             @OA\Property(property="vermifuge_a_jour", type="boolean")
    *         )
    *     ),
    *     @OA\Response(response=200, description="Animal mis à jour"),
    *     @OA\Response(response=404, description="Animal non trouvé")
    * )
    */
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
    /**
     * @OA\Delete(
     *     path="/api/animaux/{id}",
     *     summary="Supprimer un animal",
     *     tags={"Animaux"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Animal supprimé"),
     *     @OA\Response(response=404, description="Animal non trouvé")
     * )
     */
    public function destroy(Request $request, string $id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', $request->user()->id)->first();
        if (!$animal) return response()->json(['message' => 'Animal non trouvé'], 404);
        $animal->delete();
        return response()->json(['message' => 'Animal supprimé avec succès']);
    }
    
    /**
     * @OA\Get(
     *     path="/api/proprietaires",
     *     summary="Liste des propriétaires avec leurs animaux",
     *     tags={"Propriétaires"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs avec animaux"
     *     )
     * )
     */
    public function proprietaires()
    {
        $users = \App\Models\User::with('animaux')->get();
        return response()->json($users);
    }
    
    /**
     * @OA\Get(
     *     path="/api/proprietaires/{id}/animaux",
     *     summary="Liste des animaux d'un propriétaire",
     *     tags={"Propriétaires"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des animaux"
     *     )
     * )
     */
    public function animauxProprietaire(Request $request, string $id)
    {
        $animaux = \App\Models\Animaux::where('user_id', $id)->get();
        return response()->json($animaux);
    }
}


