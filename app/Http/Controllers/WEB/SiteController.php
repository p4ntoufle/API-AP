<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Animaux;
use App\Models\User;
use App\Models\Pension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiteController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function pensions()
    {
        $pensions = Pension::all();
        return view('pensions', compact('pensions'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function horaires()
    {
        return view('horaires');
    }

    public function services()
    {
        return view('services');
    }

    public function fiches()
    {
        $users = User::with('pensions')->get();
        return view('fiches', compact('users'));
    }

    public function factures()
    {
        $user = Auth::user();
        $factures = $user
            ? $user->factures()->with('pension')->orderByDesc('issued_at')->orderByDesc('created_at')->get()
            : collect();

        return view('factures', compact('factures', 'user'));
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Connexion réussie');
        }

        return back()->withErrors(['email' => 'Identifiants invalides'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ─── GAP : Gestion des Animaux et des Propriétaires ───────────────────────

    /**
     * Liste des animaux du propriétaire connecté
     */
    public function animaux()
    {
        $animaux = Auth::user()->animaux()->orderBy('nom')->get();
        return view('gap.animaux', compact('animaux'));
    }

    /**
     * Formulaire de création d'un animal
     */
    public function animalCreate()
    {
        return view('gap.animal-form', ['animal' => null]);
    }

    /**
     * Enregistrement d'un nouvel animal
     */
    public function animalStore(Request $request)
    {
        $validated = $request->validate([
            'nom'                => 'required|string|max:255',
            'espece'             => 'required|string|max:255',
            'race'               => 'nullable|string|max:255',
            'age'                => 'nullable|integer|min:0',
            'poids'              => 'nullable|numeric|min:0',
            'description'        => 'nullable|string',
            'carnet_vaccination' => 'nullable|boolean',
            'vaccin_a_jour'      => 'nullable|boolean',
            'vermifuge_a_jour'   => 'nullable|boolean',
        ]);

        $validated['user_id']            = Auth::id();
        $validated['carnet_vaccination'] = $request->boolean('carnet_vaccination');
        $validated['vaccin_a_jour']      = $request->boolean('vaccin_a_jour');
        $validated['vermifuge_a_jour']   = $request->boolean('vermifuge_a_jour');

        Animaux::create($validated);

        return redirect()->route('animaux')->with('success', 'Animal ajouté avec succès !');
    }

    /**
     * Formulaire de modification d'un animal
     */
    public function animalEdit($id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('gap.animal-form', compact('animal'));
    }

    /**
     * Mise à jour d'un animal
     */
    public function animalUpdate(Request $request, $id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'nom'                => 'required|string|max:255',
            'espece'             => 'required|string|max:255',
            'race'               => 'nullable|string|max:255',
            'age'                => 'nullable|integer|min:0',
            'poids'              => 'nullable|numeric|min:0',
            'description'        => 'nullable|string',
            'carnet_vaccination' => 'nullable|boolean',
            'vaccin_a_jour'      => 'nullable|boolean',
            'vermifuge_a_jour'   => 'nullable|boolean',
        ]);

        $validated['carnet_vaccination'] = $request->boolean('carnet_vaccination');
        $validated['vaccin_a_jour']      = $request->boolean('vaccin_a_jour');
        $validated['vermifuge_a_jour']   = $request->boolean('vermifuge_a_jour');

        $animal->update($validated);

        return redirect()->route('animaux')->with('success', 'Animal mis à jour avec succès !');
    }

    /**
     * Suppression d'un animal
     */
    public function animalDestroy($id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $animal->delete();

        return redirect()->route('animaux')->with('success', 'Animal supprimé avec succès !');
    }

    /**
     * Afficher le profil du propriétaire connecté
     */
    public function profil()
    {
        $user = Auth::user();
        return view('gap.profil', compact('user'));
    }

    /**
     * Mettre à jour le profil
     */
    public function profilUpdate(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'password'              => 'nullable|min:6|confirmed',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profil')->with('success', 'Profil mis à jour avec succès !');
    }
}
