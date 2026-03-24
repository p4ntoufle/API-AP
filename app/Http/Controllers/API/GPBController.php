<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pension;
use OpenApi\Annotations as OA;

class GPBController extends Controller
{
    /**
     * Afficher la liste des pensions.
     *
     * @OA\Get(
     *     path="/api/pensions",
     *     summary="Liste toutes les pensions",
     *     tags={"Pensions"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des pensions",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="adresse", type="string"),
     *             @OA\Property(property="ville", type="string"),
     *             @OA\Property(property="region", type="string"),
     *             @OA\Property(property="code_postal", type="string"),
     *             @OA\Property(property="telephone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="capacite_chiens", type="integer"),
     *             @OA\Property(property="capacite_chats", type="integer"),
     *             @OA\Property(property="prix_chien_jour", type="number"),
     *             @OA\Property(property="prix_chat_jour", type="number"),
     *             @OA\Property(property="actif", type="boolean"),
     *             @OA\Property(property="famille", type="boolean")
     *         ))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Pension::all());
    }

    /**
     * Ajouter une pension.
     *
     * @OA\Post(
     *     path="/api/pensions",
     *     summary="Créer une nouvelle pension",
     *     tags={"Pensions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom","adresse","ville","code_postal","actif"},
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="adresse", type="string"),
     *             @OA\Property(property="ville", type="string"),
     *             @OA\Property(property="region", type="string"),
     *             @OA\Property(property="code_postal", type="string"),
     *             @OA\Property(property="telephone", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="capacite_chiens", type="integer"),
     *             @OA\Property(property="capacite_chats", type="integer"),
     *             @OA\Property(property="directeur_nom", type="string"),
     *             @OA\Property(property="directeur_email", type="string", format="email"),
     *             @OA\Property(property="services", type="string"),
     *             @OA\Property(property="horaires", type="string"),
     *             @OA\Property(property="prix_chien_jour", type="number"),
     *             @OA\Property(property="prix_chat_jour", type="number"),
     *             @OA\Property(property="actif", type="boolean"),
     *             @OA\Property(property="famille", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Pension créée avec succès"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
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
     *
     * @OA\Get(
     *     path="/api/fiches",
     *     summary="Afficher une fiche pension",
     *     tags={"Pensions"},
     *     @OA\Parameter(name="id", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Détails de la pension"),
     *     @OA\Response(response=404, description="Pension non trouvée")
     * )
     */
    public function show(string $id)
    {
        $pension = Pension::findOrFail($id);
        return response()->json($pension);
    }

    /**
     * Mettre à jour la pension.
     *
     * @OA\Put(
     *     path="/api/pensions/{id}/update",
     *     summary="Mettre à jour une pension",
     *     tags={"Pensions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom","adresse","ville","code_postal","actif"},
     *             @OA\Property(property="nom", type="string"),
     *             @OA\Property(property="adresse", type="string"),
     *             @OA\Property(property="ville", type="string"),
     *             @OA\Property(property="region", type="string"),
     *             @OA\Property(property="code_postal", type="string"),
     *             @OA\Property(property="telephone", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="capacite_chiens", type="integer"),
     *             @OA\Property(property="capacite_chats", type="integer"),
     *             @OA\Property(property="directeur_nom", type="string"),
     *             @OA\Property(property="directeur_email", type="string", format="email"),
     *             @OA\Property(property="services", type="string"),
     *             @OA\Property(property="horaires", type="string"),
     *             @OA\Property(property="prix_chien_jour", type="number"),
     *             @OA\Property(property="prix_chat_jour", type="number"),
     *             @OA\Property(property="actif", type="boolean"),
     *             @OA\Property(property="famille", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Pension mise à jour"),
     *     @OA\Response(response=404, description="Pension non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
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
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // limite 2MB
            'directeur_nom'     => 'nullable|string|max:255',
            'directeur_email'   => 'nullable|email|max:255',
            'services'          => 'nullable|string', // JSON possible - change en 'array'
            'horaires'          => 'nullable|string', // JSON possible
            'prix_chien_jour'   => 'nullable|numeric|min:0',
            'prix_chat_jour'    => 'nullable|numeric|min:0',
            'actif'             => 'required|boolean',
            'famille'           => 'nullable|boolean',
        ]);

        $pension->update($validated);
        return redirect()->back()->with('success', 'Informations de pension à jour');
    }

    /**
     * Supprimer la pension.
     *
     * @OA\Delete(
     *     path="/api/pensions/{id}",
     *     summary="Supprimer une pension",
     *     tags={"Pensions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Pension supprimée"),
     *     @OA\Response(response=404, description="Pension non trouvée")
     * )
     */
    public function destroy(string $id)
    {
        $pension = Pension::findOrFail($id);
        $pension->delete();
        return response()->json(['message' => 'Pension supprimée']);
    }
}
