<?php

use App\Http\Controllers\API\GPBController as GPBController;
use App\Http\Controllers\API\FaSeController as FactureController;
use App\Http\Controllers\Web\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/pensions', [SiteController::class, 'pensions'])->name('pensions');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::get('/horaires', [SiteController::class, 'horaires'])->name('horaires');
Route::get('/services', [SiteController::class, 'services'])->name('services');
Route::get('/factures', [SiteController::class, 'factures'])->name('factures');

Route::get('/login', [SiteController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [SiteController::class, 'login']);
Route::get('/register', [SiteController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/logout', [SiteController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    /// GPB — Pension Dashboard ///
    Route::get('/pension/dashboard', [SiteController::class, 'pensionDashboard'])->name('pension.dashboard');
    Route::get('/pension/edit', [SiteController::class, 'pensionEdit'])->name('pension.edit');
    Route::put('/pension', [SiteController::class, 'pensionUpdate'])->name('pension.update');

    /// GPB — Types de Gardiennage ///
    Route::get('/pension/types-gardiennage', [SiteController::class, 'pensionTypesGardiennage'])->name('pension.types-gardiennage');
    Route::get('/pension/types-gardiennage/create', [SiteController::class, 'pensionTypeGardiennageCreate'])->name('pension.types-gardiennage.create');
    Route::post('/pension/types-gardiennage', [SiteController::class, 'pensionTypeGardiennageStore'])->name('pension.types-gardiennage.store');
    Route::get('/pension/types-gardiennage/{typeGardiennage}/edit', [SiteController::class, 'pensionTypeGardiennageEdit'])->name('pension.types-gardiennage.edit');
    Route::put('/pension/types-gardiennage/{typeGardiennage}', [SiteController::class, 'pensionTypeGardiennageUpdate'])->name('pension.types-gardiennage.update');
    Route::delete('/pension/types-gardiennage/{typeGardiennage}', [SiteController::class, 'pensionTypeGardiennageDestroy'])->name('pension.types-gardiennage.destroy');

    /// GPB — Tarifs (Pension <-> TypeGardiennage) ///
    Route::get('/pension/tarifs', [SiteController::class, 'pensionTarifs'])->name('pension.tarifs');
    Route::post('/pension/tarifs', [SiteController::class, 'pensionTarifStore'])->name('pension.tarifs.store');
    Route::put('/pension/tarifs/{tarif}', [SiteController::class, 'pensionTarifUpdate'])->name('pension.tarifs.update');
    Route::delete('/pension/tarifs/{tarif}', [SiteController::class, 'pensionTarifDestroy'])->name('pension.tarifs.destroy');

    /// GPB — Boxes ///
    Route::get('/pension/boxes', [SiteController::class, 'pensionBoxes'])->name('pension.boxes');
    Route::get('/pension/boxes/create', [SiteController::class, 'pensionBoxCreate'])->name('pension.boxes.create');
    Route::post('/pension/boxes', [SiteController::class, 'pensionBoxStore'])->name('pension.boxes.store');
    Route::get('/pension/boxes/{box}/edit', [SiteController::class, 'pensionBoxEdit'])->name('pension.boxes.edit');
    Route::put('/pension/boxes/{box}', [SiteController::class, 'pensionBoxUpdate'])->name('pension.boxes.update');
    Route::delete('/pension/boxes/{box}', [SiteController::class, 'pensionBoxDestroy'])->name('pension.boxes.destroy');

    /// FaSE ///
    Route::get('/factures/{facture}/telecharger', [FactureController::class, 'download'])->name('factures.download');

    /// GAP — Animaux ///
    Route::get('/animaux', [SiteController::class, 'animaux'])->name('animaux');
    Route::get('/animaux/ajouter', [SiteController::class, 'animalCreate'])->name('animaux.create');
    Route::post('/animaux', [SiteController::class, 'animalStore'])->name('animaux.store');
    Route::get('/animaux/{id}/modifier', [SiteController::class, 'animalEdit'])->name('animaux.edit');
    Route::put('/animaux/{id}', [SiteController::class, 'animalUpdate'])->name('animaux.update');
    Route::delete('/animaux/{id}', [SiteController::class, 'animalDestroy'])->name('animaux.destroy');

    /// GAP — Profil ///
    Route::get('/profil', [SiteController::class, 'profil'])->name('profil');
    Route::put('/profil', [SiteController::class, 'profilUpdate'])->name('profil.update');

    /// GPB — Fiches (pour compatibilité) ///
    Route::get('/fiches', [SiteController::class, 'fiches'])->name('fiches');
    Route::put('/fiches/{fiche}', [GPBController::class, 'update'])->name('fiches.update');
});
