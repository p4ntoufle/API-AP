<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pension;
use App\Models\Box;
use App\Models\TypeGardiennage;
use OpenApi\Annotations as OA;

class GPBController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pensions",
     *     summary="Récupérer toutes les pensions",
     *     tags={"Pensions"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des pensions",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Pension"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Pension::all());
    }

    /**
     * @OA\Get(
     *     path="/api/pensions/{id}",
     *     summary="Afficher une pension",
     *     tags={"Pensions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Pension trouvée", @OA\JsonContent(ref="#/components/schemas/Pension")),
     *     @OA\Response(response=404, description="Pension non trouvée")
     * )
     */
    public function show($id)
    {
        $pension = Pension::findOrFail($id);
        return response()->json($pension);
    }

    /**
     * @OA\Post(
     *     path="/api/pensions",
     *     summary="Créer une pension",
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
     *             @OA\Property(property="actif", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Pension créée", @OA\JsonContent(ref="#/components/schemas/Pension")),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'code_postal' => 'required|string|max:20',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'capacite_chiens' => 'nullable|integer|min:0',
            'capacite_chats' => 'nullable|integer|min:0',
            'directeur_nom' => 'nullable|string|max:255',
            'directeur_email' => 'nullable|email|max:255',
            'services' => 'nullable|string',
            'horaires' => 'nullable|string',
            'prix_chien_jour' => 'nullable|numeric|min:0',
            'prix_chat_jour' => 'nullable|numeric|min:0',
            'actif' => 'required|boolean',
            'famille' => 'nullable|boolean',
        ]);

        $pension = Pension::create($validated);
        return response()->json($pension, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/pensions/{id}",
     *     summary="Mettre à jour une pension",
     *     tags={"Pensions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PensionUpdate")
     *     ),
     *     @OA\Response(response=200, description="Pension mise à jour", @OA\JsonContent(ref="#/components/schemas/Pension")),
     *     @OA\Response(response=404, description="Pension non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function update(Request $request, $id)
    {
        $pension = Pension::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'code_postal' => 'required|string|max:20',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'capacite_chiens' => 'nullable|integer|min:0',
            'capacite_chats' => 'nullable|integer|min:0',
            'directeur_nom' => 'nullable|string|max:255',
            'directeur_email' => 'nullable|email|max:255',
            'services' => 'nullable|string',
            'horaires' => 'nullable|string',
            'prix_chien_jour' => 'nullable|numeric|min:0',
            'prix_chat_jour' => 'nullable|numeric|min:0',
            'actif' => 'required|boolean',
            'famille' => 'nullable|boolean',
        ]);

        $pension->update($validated);
        return response()->json($pension);
    }

    /**
     * @OA\Delete(
     *     path="/api/pensions/{id}",
     *     summary="Supprimer une pension",
     *     tags={"Pensions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Pension supprimée"),
     *     @OA\Response(response=404, description="Pension non trouvée")
     * )
     */
    public function destroy($id)
    {
        $pension = Pension::findOrFail($id);
        $pension->delete();
        return response()->json(['message' => 'Pension supprimée']);
    }

    /**
     * @OA\Get(
     *     path="/api/pensions/{id}/boxes",
     *     summary="Récupérer les boxes d'une pension",
     *     tags={"Boxes"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Boxes récupérées", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Box"))),
     *     @OA\Response(response=404, description="Pension non trouvée")
     * )
     */
    public function getBoxes($id)
    {
        $pension = Pension::findOrFail($id);
        return response()->json($pension->boxes);
    }

    /**
     * @OA\Post(
     *     path="/api/pensions/{id}/boxes",
     *     summary="Créer une box",
     *     tags={"Boxes"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="superficie", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Box créée", @OA\JsonContent(ref="#/components/schemas/Box")),
     *     @OA\Response(response=404, description="Pension non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function storeBox(Request $request, $id)
    {
        $pension = Pension::findOrFail($id);
        $validated = $request->validate([
            'superficie' => 'nullable|numeric|min:0',
        ]);

        $validated['pension_id'] = $pension->id;
        $box = Box::create($validated);
        return response()->json($box, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/boxes/{id}",
     *     summary="Mettre à jour une box",
     *     tags={"Boxes"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="superficie", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Box mise à jour", @OA\JsonContent(ref="#/components/schemas/Box")),
     *     @OA\Response(response=404, description="Box non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updateBox(Request $request, $id)
    {
        $box = Box::findOrFail($id);
        $validated = $request->validate([
            'superficie' => 'nullable|numeric|min:0',
        ]);

        $box->update($validated);
        return response()->json($box);
    }

    /**
     * @OA\Delete(
     *     path="/api/boxes/{id}",
     *     summary="Supprimer une box",
     *     tags={"Boxes"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Box supprimée"),
     *     @OA\Response(response=404, description="Box non trouvée")
     * )
     */
    public function deleteBox($id)
    {
        $box = Box::findOrFail($id);
        $box->delete();
        return response()->json(['message' => 'Box supprimée']);
    }

    /**
     * @OA\Get(
     *     path="/api/pensions/{id}/types-gardiennage",
     *     summary="Récupérer les types de gardiennage d'une pension",
     *     tags={"Types de Gardiennage"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Types de gardiennage récupérés", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TypeGardiennage"))),
     *     @OA\Response(response=404, description="Pension non trouvée")
     * )
     */
    public function getTypesGardiennage($id)
    {
        $pension = Pension::findOrFail($id);
        return response()->json($pension->typesGardiennage);
    }

    /**
     * @OA\Post(
     *     path="/api/pensions/{id}/types-gardiennage",
     *     summary="Créer un type de gardiennage",
     *     tags={"Types de Gardiennage"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="libelle", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Type de gardiennage créé", @OA\JsonContent(ref="#/components/schemas/TypeGardiennage")),
     *     @OA\Response(response=404, description="Pension non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function storeTypeGardiennage(Request $request, $id)
    {
        $pension = Pension::findOrFail($id);
        $validated = $request->validate([
            'libelle' => 'nullable|string|max:255',
        ]);

        $validated['pension_id'] = $pension->id;
        $typeGardiennage = TypeGardiennage::create($validated);
        return response()->json($typeGardiennage, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/types-gardiennage/{id}",
     *     summary="Mettre à jour un type de gardiennage",
     *     tags={"Types de Gardiennage"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="libelle", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Type de gardiennage mis à jour", @OA\JsonContent(ref="#/components/schemas/TypeGardiennage")),
     *     @OA\Response(response=404, description="Type de gardiennage non trouvé"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updateTypeGardiennage(Request $request, $id)
    {
        $typeGardiennage = TypeGardiennage::findOrFail($id);
        $validated = $request->validate([
            'libelle' => 'nullable|string|max:255',
        ]);

        $typeGardiennage->update($validated);
        return response()->json($typeGardiennage);
    }

    /**
     * @OA\Delete(
     *     path="/api/types-gardiennage/{id}",
     *     summary="Supprimer un type de gardiennage",
     *     tags={"Types de Gardiennage"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Type de gardiennage supprimé"),
     *     @OA\Response(response=404, description="Type de gardiennage non trouvé")
     * )
     */
    public function deleteTypeGardiennage($id)
    {
        $typeGardiennage = TypeGardiennage::findOrFail($id);
        $typeGardiennage->delete();
        return response()->json(['message' => 'Type de gardiennage supprimé']);
    }
}

