<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
