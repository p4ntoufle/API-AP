<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\VerifyEmailMail;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Inscrire un nouvel utilisateur",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inscription réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function register(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6']
        ]);

        // Générer un token de vérification unique
        $verificationToken = Str::random(60);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // 'email_verification_token' => $verificationToken
        ]);

        // Générer l'URL de vérification
        $verificationUrl = config('app.url') . '/api/auth/verify-email/' . $verificationToken;

        // Envoyer l'email de vérification --- SI SERVEUR SMTP
        // Mail::send(new VerifyEmailMail($user, $verificationUrl));

        /* return response()->json([
            'message' => 'Inscription réussie. Veuillez vérifier votre email pour activer votre compte.',
            'user' => $user,
            'email_verification_required' => true
        ], 201); 
        */
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Connecter un utilisateur",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Identifiants invalides"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    /// CONNEXION ///
    public function login(Request $request) {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'min:6']
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Mauvais Identifiants'
            ], 401);
        }

        // Vérifier si l'email a été confirmé -- SI SERVEUR SMTP
        /* if (!$user->email_verified_at) {
            return response()->json([
                'message' => 'Veuillez vérifier votre email avant de vous connecter',
                'email_verified' => false
            ], 403);
        } */

        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Déconnecter l'utilisateur actuel",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Déconnexion réussie", @OA\JsonContent(@OA\Property(property="message", type="string")))
     * )
     */
    /// DÉCONNEXION ///
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Déconnecté'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Obtenir les informations de l'utilisateur actuel",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informations de l'utilisateur",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    /// INFORMATIONS UTILISATEUR CONNECTÉ ///
    public function me(Request $request) {
        return response()->json($request->user());
    }

    /**
     * @OA\Put(
     *     path="/api/auth/me",
     *     summary="Mettre à jour le profil de l'utilisateur actuel",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil mis à jour",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    /// MODIFIER LE PROFIL ///
    public function update(Request $request) {
        $user = $request->user();

        $data = $request->validate([
            'name'  => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return response()->json($user);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/verify-email/{token}",
     *     summary="Vérifier l'adresse email avec un token",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         required=true,
     *         description="Token de vérification d'email",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email vérifié avec succès",
     *         @OA\JsonContent(@OA\Property(property="message", type="string"), @OA\Property(property="user", ref="#/components/schemas/User"), @OA\Property(property="token", type="string"))
     *     ),
     *     @OA\Response(response=400, description="Token invalide ou expiré")
     * )
     */

    // VÉRIFIER MAIL -- SI SERVEUR SMTP
    /* public function verifyEmail($token) {
        // Chercher l'utilisateur avec ce token
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token de vérification invalide ou expiré'
            ], 400);
        }

        // Vérifier que l'email n'a pas déjà été vérifié
        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Cet email a déjà été vérifié'
            ], 400);
        }

        // Marquer l'email comme vérifié
        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null
        ]);

        // Créer un token Sanctum pour la session
        $authToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Email vérifié avec succès',
            'user' => $user,
            'token' => $authToken
        ]);
    }
        */

    /**
     * @OA\Post(
     *     path="/api/auth/resend-verification-email",
     *     summary="Renvoyer l'email de vérification",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email de vérification renvoyé",
     *         @OA\JsonContent(@OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=404, description="Utilisateur non trouvé"),
     *     @OA\Response(response=400, description="Email déjà vérifié")
     * )
     */

    // RENVOYER MAIL -- SI SERVEUR SMTP
    /* public function resendVerificationEmail(Request $request) {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users']
        ]);

        $user = User::where('email', $data['email'])->first();

        // Vérifier si l'email est déjà vérifié
        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Cet email a déjà été vérifié'
            ], 400);
        }

        // Générer un nouveau token
        $verificationToken = Str::random(60);
        $user->update(['email_verification_token' => $verificationToken]);

        // Générer l'URL de vérification
        $verificationUrl = config('app.url') . '/api/auth/verify-email/' . $verificationToken;

        // Envoyer l'email de vérification
        Mail::send(new VerifyEmailMail($user, $verificationUrl));

        return response()->json([
            'message' => 'Email de vérification renvoyé avec succès'
        ]);
    }
        */
}

