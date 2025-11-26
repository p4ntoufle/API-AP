<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Pension;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SiteController extends Controller
{
    public function home() {
        return view('home');
    }

    public function pensions() {
        $pensions = Pension::all();
        return view('pensions', compact('pensions'));
    }

    public function fiches() {
        $users = User::with('pensions')->get();
        return view('fiches', compact('users'));
    }

    public function factures() {
        $user = Auth::user();
        $factures = $user
            ? $user->factures()->with('pension')->orderByDesc('issued_at')->orderByDesc('created_at')->get()
            : collect();

        return view('factures', compact('factures', 'user'));
    }

    public function contact() {
        return view('contact');
    }

    // Auth web (indépendant de Sanctum)
    public function showLogin() {
        return view('login');
    }

    public function login(Request $request) {
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home')->with('success', 'Connexion réussie');
        }
        return back()->with('error', 'Identifiants invalides');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('home');
    }
}
