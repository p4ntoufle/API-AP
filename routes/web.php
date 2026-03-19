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
Route::post('/logout', [SiteController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    /// GPB ///
    Route::get('/fiches', [SiteController::class, 'fiches'])->name('fiches');
    Route::put('/fiches/{fiche}', [GPBController::class, 'update'])->name('fiches.update');

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
});
