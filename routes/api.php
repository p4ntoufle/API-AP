<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\GPBController;
use App\Http\Controllers\Api\FaSeController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EspeceController;

/// INSCRIPTION ///
Route::post('/auth/register', [AuthController::class, 'register']);
/// VÉRIFICATION EMAIL ///
Route::get('/auth/verify-email/{token}', [AuthController::class, 'verifyEmail']);
Route::post('/auth/resend-verification-email', [AuthController::class, 'resendVerificationEmail']);
/// CONNEXION ///
Route::post('/auth/login', [AuthController::class, 'login']);

/// ROUTES PROTÉGÉES PAR SANCTUM ///
Route::middleware('auth:sanctum')->group(function () {

    /// Auth ///
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/me', [AuthController::class, 'update']);

    /// Espèces ///
    Route::get('/especes', [EspeceController::class, 'index']);

    /// Animaux ///
    Route::get('/animaux', [AnimalController::class, 'index']);
    Route::get('/animaux/{id}', [AnimalController::class, 'show']);
    Route::post('/animaux', [AnimalController::class, 'store']);
    Route::put('/animaux/{id}', [AnimalController::class, 'update']);
    Route::delete('/animaux/{id}', [AnimalController::class, 'destroy']);

    /// Factures ///

Route::get('/factures', [FaSeController::class, 'index']);
Route::get('/factures/{facture}/download', [FaSeController::class, 'download'])
    ->name('api.factures.download');
});
/// PENSIONS
Route::get('/pensions', [GPBController::class, 'index']);
Route::get('/pensions/{id}', [GPBController::class, 'show']);
Route::post('/pensions', [GPBController::class, 'store']);
Route::put('/pensions/{id}', [GPBController::class, 'update']);
Route::delete('/pensions/{id}', [GPBController::class, 'destroy']);

/// BOXES
Route::get('/pensions/{id}/boxes', [GPBController::class, 'getBoxes']);
Route::post('/pensions/{id}/boxes', [GPBController::class, 'storeBox']);
Route::put('/boxes/{id}', [GPBController::class, 'updateBox']);
Route::delete('/boxes/{id}', [GPBController::class, 'deleteBox']);

/// TYPES DE GARDIENNAGE
Route::get('/pensions/{id}/types-gardiennage', [GPBController::class, 'getTypesGardiennage']);
Route::post('/pensions/{id}/types-gardiennage', [GPBController::class, 'storeTypeGardiennage']);
Route::put('/types-gardiennage/{id}', [GPBController::class, 'updateTypeGardiennage']);
Route::delete('/types-gardiennage/{id}', [GPBController::class, 'deleteTypeGardiennage']);

/// TARIFS
Route::get('/pensions/{id}/tarifs', [GPBController::class, 'getTarifs']);
Route::post('/pensions/{id}/tarifs', [GPBController::class, 'storeTarif']);
Route::put('/tarifs/{id}', [GPBController::class, 'updateTarif']);
Route::delete('/tarifs/{id}', [GPBController::class, 'deleteTarif']);

/// Propriétaires (pour l'appli lourde pension) ///
Route::get('/proprietaires', [App\Http\Controllers\Api\GAPController::class, 'proprietaires']);
Route::get('/proprietaires/{id}/animaux', [App\Http\Controllers\Api\GAPController::class, 'animauxProprietaire']);
