<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pension;
use App\Models\Box;
use App\Models\TypeGardiennage;
use App\Models\Tarif;
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
            'user_id' => 'nullable|integer|exists:users,id',
            'ville' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'responsable' => 'nullable|string|max:255',
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
     *         @OA\JsonContent(ref="#/components/schemas/Pension")
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
            'user_id' => 'nullable|integer|exists:users,id',
            'ville' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'responsable' => 'nullable|string|max:255',
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

    /**
     * @OA\Get(
     *     path="/api/pensions/{id}/tarifs",
     *     summary="Récupérer les tarifs d'une pension",
     *     tags={"Tarifs"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Tarifs récupérés", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Tarif"))),
     *     @OA\Response(response=404, description="Pension non trouvée")
     * )
     */
    public function getTarifs($id)
    {
        $pension = Pension::findOrFail($id);
        return response()->json($pension->tarifs()->with('typeGardiennage')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/pensions/{id}/tarifs",
     *     summary="Créer un tarif",
     *     tags={"Tarifs"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="type_gardiennage_id", type="integer"),
     *             @OA\Property(property="prix", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tarif créé", @OA\JsonContent(ref="#/components/schemas/Tarif")),
     *     @OA\Response(response=404, description="Pension non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function storeTarif(Request $request, $id)
    {
        $pension = Pension::findOrFail($id);
        $validated = $request->validate([
            'type_gardiennage_id' => 'required|integer|exists:type_gardiennages,id',
            'prix' => 'required|numeric|min:0',
        ]);

        $validated['pension_id'] = $pension->id;
        $tarif = Tarif::create($validated);
        return response()->json($tarif, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/tarifs/{id}",
     *     summary="Mettre à jour un tarif",
     *     tags={"Tarifs"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="prix", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Tarif mis à jour", @OA\JsonContent(ref="#/components/schemas/Tarif")),
     *     @OA\Response(response=404, description="Tarif non trouvé"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updateTarif(Request $request, $id)
    {
        $tarif = Tarif::findOrFail($id);
        $validated = $request->validate([
            'prix' => 'required|numeric|min:0',
        ]);

        $tarif->update($validated);
        return response()->json($tarif);
    }

    /**
     * @OA\Delete(
     *     path="/api/tarifs/{id}",
     *     summary="Supprimer un tarif",
     *     tags={"Tarifs"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Tarif supprimé"),
     *     @OA\Response(response=404, description="Tarif non trouvé")
     * )
     */
    public function deleteTarif($id)
    {
        $tarif = Tarif::findOrFail($id);
        $tarif->delete();
        return response()->json(['message' => 'Tarif supprimé']);
    }
}

