<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Animaux;
use App\Models\User;
use App\Models\Pension;
use App\Models\Box;
use App\Models\TypeGardiennage;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

# Controller gérant la navigation vers les endpoints
class SiteController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function pensions()
    {
        // Si l'utilisateur est une pension connectée, rediriger vers le dashboard
        if (Auth::check() && Auth::user()->pension) {
            return redirect()->route('pension.dashboard');
        }

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
        // L'utilisateur n'accède qu'à sa propre fiche
        return view('fiches');
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
        \Log::info('Login page accessed - Current URL: ' . url()->current());
        \Log::info('APP_URL: ' . config('app.url'));
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('Login attempt via web', ['email' => $credentials['email']]);

        // Call the API internally
        $apiController = app(\App\Http\Controllers\AuthController::class);
        
        // Create a new request for the API
        $apiRequest = Request::create('/api/auth/login', 'POST', $credentials, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ]);
        
        try {
            $apiResponse = $apiController->login($apiRequest);
            $responseData = json_decode($apiResponse->getContent(), true);
            
            \Log::info('API response', ['status' => $apiResponse->status()]);
            
            if ($apiResponse->status() === 200) {
                $user = User::find($responseData['user']['id']);
                
                // Authenticate the user for web session
                Auth::login($user);
                $request->session()->regenerate();
                
                \Log::info('User authenticated', ['id' => $user->id]);
                
                return redirect()->intended(route('home'))->with('success', 'Connexion réussie');
            } else {
                \Log::warning('API auth failed', ['status' => $apiResponse->status()]);
                return back()->withErrors(['email' => $responseData['message'] ?? 'Identifiants invalides'])->onlyInput('email');
            }
        } catch (\Exception $e) {
            \Log::error('Login error', ['error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'Erreur de connexion'])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ─── GAP : Gestion des Animaux et des Propriétaires ───────────────────────

    public function animaux()
    {
        $animaux = Auth::user()->animaux()->orderBy('nom')->get();
        return view('gap.animaux', compact('animaux'));
    }

    public function animalCreate()
    {
        return view('gap.animal-form', ['animal' => null]);
    }

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

    public function animalEdit($id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('gap.animal-form', compact('animal'));
    }

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

    public function animalDestroy($id)
    {
        $animal = Animaux::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $animal->delete();

        return redirect()->route('animaux')->with('success', 'Animal supprimé avec succès !');
    }

    public function profil()
    {
        $user = Auth::user();
        return view('gap.profil', compact('user'));
    }

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

    // ─── GPB : Gestion des Pensions ───────────────────────────────────────────

    public function pensionDashboard()
    {
        $user = Auth::user();
        $pension = $user->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('info', 'Veuillez compléter votre fiche de pension');
        }

        return view('pension.dashboard', compact('pension'));
    }

    public function pensionEdit()
    {
        $user = Auth::user();
        $pension = $user->pension ?? new Pension();

        return view('pension.edit-fiche', compact('pension'));
    }

    public function pensionUpdate(Request $request)
    {
        $user = Auth::user();
        $pension = $user->pension ?? new Pension();

        $validated = $request->validate([
            'ville' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'responsable' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = $user->id;
        $pension->fill($validated)->save();

        return redirect()->route('pension.dashboard')->with('success', 'Fiche pension mise à jour avec succès !');
    }

    public function pensionTypesGardiennage()
    {
        $pension = Auth::user()->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('error', 'Veuillez d\'abord compléter votre fiche de pension');
        }

        $typesGardiennage = $pension->typesGardiennage;

        return view('pension.types-gardiennage', compact('pension', 'typesGardiennage'));
    }

    public function pensionTypeGardiennageCreate()
    {
        $pension = Auth::user()->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('error', 'Veuillez d\'abord compléter votre fiche de pension');
        }

        return view('pension.type-gardiennage-form', ['typeGardiennage' => null, 'pension' => $pension]);
    }

    public function pensionTypeGardiennageStore(Request $request)
    {
        $pension = Auth::user()->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('error', 'Veuillez d\'abord compléter votre fiche de pension');
        }

        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'prix' => 'nullable|numeric|min:0',
        ]);

        $typeGardiennage = TypeGardiennage::create([
            'pension_id' => $pension->id,
            'libelle' => $validated['libelle'],
        ]);

        // Créer le tarif si fourni
        if (!empty($validated['prix'])) {
            Tarif::create([
                'pension_id' => $pension->id,
                'type_gardiennage_id' => $typeGardiennage->id,
                'prix' => $validated['prix'],
            ]);
        }

        return redirect()->route('pension.types-gardiennage')->with('success', 'Type de gardiennage créé avec succès !');
    }

    public function pensionTypeGardiennageEdit(TypeGardiennage $typeGardiennage)
    {
        $pension = Auth::user()->pension;

        if (!$pension || $typeGardiennage->pension_id !== $pension->id) {
            abort(403);
        }

        return view('pension.type-gardiennage-form', compact('typeGardiennage', 'pension'));
    }

    public function pensionTypeGardiennageUpdate(Request $request, TypeGardiennage $typeGardiennage)
    {
        $pension = Auth::user()->pension;

        if (!$pension || $typeGardiennage->pension_id !== $pension->id) {
            abort(403);
        }

        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
        ]);

        $typeGardiennage->update($validated);

        return redirect()->route('pension.types-gardiennage')->with('success', 'Type de gardiennage mis à jour avec succès !');
    }

    public function pensionTypeGardiennageDestroy(TypeGardiennage $typeGardiennage)
    {
        $pension = Auth::user()->pension;

        if (!$pension || $typeGardiennage->pension_id !== $pension->id) {
            abort(403);
        }

        $typeGardiennage->delete();

        return redirect()->route('pension.types-gardiennage')->with('success', 'Type de gardiennage supprimé avec succès !');
    }

    // ─── Gestion des Tarifs (Relation Pension <-> TypeGardiennage) ───

    public function pensionTarifs()
    {
        $pension = Auth::user()->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('error', 'Veuillez d\'abord compléter votre fiche de pension');
        }

        $tarifs = $pension->tarifs()->with('typeGardiennage')->get();
        $typesGardiennage = $pension->typesGardiennage;

        return view('pension.tarifs', compact('pension', 'tarifs', 'typesGardiennage'));
    }

    public function pensionTarifStore(Request $request)
    {
        $pension = Auth::user()->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('error', 'Veuillez d\'abord compléter votre fiche de pension');
        }

        $validated = $request->validate([
            'type_gardiennage_id' => 'required|integer|exists:type_gardiennages,id',
            'prix' => 'required|numeric|min:0',
        ]);

        // Vérifier que le type de gardiennage appartient à cette pension
        $typeGardiennage = TypeGardiennage::where('id', $validated['type_gardiennage_id'])
            ->where('pension_id', $pension->id)
            ->firstOrFail();

        // Vérifier qu'un tarif n'existe pas déjà
        $existant = Tarif::where('pension_id', $pension->id)
            ->where('type_gardiennage_id', $validated['type_gardiennage_id'])
            ->first();

        if ($existant) {
            return redirect()->route('pension.tarifs')->with('error', 'Un tarif existe déjà pour ce type d\'hébergement');
        }

        Tarif::create([
            'pension_id' => $pension->id,
            'type_gardiennage_id' => $validated['type_gardiennage_id'],
            'prix' => $validated['prix'],
        ]);

        return redirect()->route('pension.tarifs')->with('success', 'Tarif créé avec succès !');
    }

    public function pensionTarifUpdate(Request $request, Tarif $tarif)
    {
        $pension = Auth::user()->pension;

        if (!$pension || $tarif->pension_id !== $pension->id) {
            abort(403);
        }

        $validated = $request->validate([
            'prix' => 'required|numeric|min:0',
        ]);

        $tarif->update($validated);

        return redirect()->route('pension.tarifs')->with('success', 'Tarif mis à jour avec succès !');
    }

    public function pensionTarifDestroy(Tarif $tarif)
    {
        $pension = Auth::user()->pension;

        if (!$pension || $tarif->pension_id !== $pension->id) {
            abort(403);
        }

        $tarif->delete();

        return redirect()->route('pension.tarifs')->with('success', 'Tarif supprimé avec succès !');
    }

    // ─── Gestion des Boxes ───

    public function pensionBoxes()
    {
        $pension = Auth::user()->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('error', 'Veuillez d\'abord compléter votre fiche de pension');
        }

        $boxes = $pension->boxes;

        return view('pension.boxes', compact('pension', 'boxes'));
    }

    public function pensionBoxCreate()
    {
        $pension = Auth::user()->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('error', 'Veuillez d\'abord compléter votre fiche de pension');
        }

        return view('pension.box-form', ['box' => null, 'pension' => $pension]);
    }

    public function pensionBoxStore(Request $request)
    {
        $pension = Auth::user()->pension;

        if (!$pension) {
            return redirect()->route('pension.edit')->with('error', 'Veuillez d\'abord compléter votre fiche de pension');
        }

        $validated = $request->validate([
            'superficie' => 'nullable|numeric|min:0',
        ]);

        $validated['pension_id'] = $pension->id;
        Box::create($validated);

        return redirect()->route('pension.boxes')->with('success', 'Box créée avec succès !');
    }

    public function pensionBoxEdit(Box $box)
    {
        $pension = Auth::user()->pension;

        if (!$pension || $box->pension_id !== $pension->id) {
            abort(403);
        }

        return view('pension.box-form', compact('box', 'pension'));
    }

    public function pensionBoxUpdate(Request $request, Box $box)
    {
        $pension = Auth::user()->pension;

        if (!$pension || $box->pension_id !== $pension->id) {
            abort(403);
        }

        $validated = $request->validate([
            'superficie' => 'nullable|numeric|min:0',
        ]);

        $box->update($validated);

        return redirect()->route('pension.boxes')->with('success', 'Box mise à jour avec succès !');
    }

    public function pensionBoxDestroy(Box $box)
    {
        $pension = Auth::user()->pension;

        if (!$pension || $box->pension_id !== $pension->id) {
            abort(403);
        }

        $box->delete();

        return redirect()->route('pension.boxes')->with('success', 'Box supprimée avec succès !');
    }
}

