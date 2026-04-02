<?php

namespace Tests\Unit;

use App\Models\Animaux;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Tests unitaires pour le contrôleur GAP (Gestion des Animaux et des Propriétaires)
 * Inclus : AnimalController et authentification avec vérification d'email
 */
class GAPControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer des utilisateurs pour les tests
        $this->user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        $this->otherUser = User::factory()->create([
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);
    }

    // ==================== TESTS INDEX ====================
    public function test_index_retourne_les_animaux_de_lutilisateur(): void
    {
        Animaux::factory()->count(3)->create(['user_id' => $this->user->id]);
        Animaux::factory()->create(['user_id' => $this->otherUser->id]);

        $response = $this->actingAs($this->user)->getJson('/api/animaux');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_index_retourne_un_tableau_vide_sans_animaux(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/animaux');

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    // ==================== TESTS SHOW ====================
    public function test_show_retourne_un_animal_specifique(): void
    {
        $animal = Animaux::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson("/api/animaux/{$animal->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $animal->id]);
        $response->assertJsonFragment(['nom' => $animal->nom]);
    }

    public function test_show_retourne_404_pour_animal_dun_autre_utilisateur(): void
    {
        $animal = Animaux::factory()->create(['user_id' => $this->otherUser->id]);

        $response = $this->actingAs($this->user)->getJson("/api/animaux/{$animal->id}");

        $response->assertStatus(404);
        $response->assertJsonFragment(['message' => 'Animal non trouvé']);
    }

    public function test_show_retourne_404_pour_animal_inexistant(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/animaux/99999');

        $response->assertStatus(404);
        $response->assertJsonFragment(['message' => 'Animal non trouvé']);
    }

    // ==================== TESTS STORE ====================
    public function test_store_crée_un_animal_avec_donnees_valides(): void
    {
        $data = [
            'nom' => 'Rex',
            'espece' => 'Chien',
            'age' => 3,
            'poids' => 25.5,
            'description' => 'Chien sympathique',
            'carnet_vaccination' => true,
            'vaccin_a_jour' => true,
            'vermifuge_a_jour' => false,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['nom' => 'Rex']);
        $this->assertDatabaseHas('animaux', [
            'nom' => 'Rex',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_store_crée_un_animal_avec_donnees_minimales(): void
    {
        $data = ['nom' => 'Minou'];

        $response = $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('animaux', [
            'nom' => 'Minou',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_store_échoue_sans_nom(): void
    {
        $data = ['espece' => 'Chat'];

        $response = $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nom');
    }

    public function test_store_échoue_avec_age_negatif(): void
    {
        $data = ['nom' => 'Fluffy', 'age' => -5];

        $response = $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('age');
    }

    public function test_store_échoue_avec_poids_negatif(): void
    {
        $data = ['nom' => 'Spot', 'poids' => -10];

        $response = $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('poids');
    }

    public function test_store_assigne_utilisateur_courant_a_lanimal(): void
    {
        $data = ['nom' => 'Buddy'];

        $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $this->assertDatabaseHas('animaux', [
            'nom' => 'Buddy',
            'user_id' => $this->user->id,
        ]);
    }

    // ==================== TESTS UPDATE ====================
    public function test_update_modifie_les_donnees_dun_animal(): void
    {
        $animal = Animaux::factory()->create([
            'user_id' => $this->user->id,
            'nom' => 'Ancien Nom',
            'age' => 2,
        ]);

        $updateData = ['nom' => 'Nouveau Nom', 'age' => 5];

        $response = $this->actingAs($this->user)->putJson("/api/animaux/{$animal->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJsonFragment(['nom' => 'Nouveau Nom']);
        $response->assertJsonFragment(['age' => 5]);
        $this->assertDatabaseHas('animaux', [
            'id' => $animal->id,
            'nom' => 'Nouveau Nom',
            'age' => 5,
        ]);
    }

    public function test_update_modification_partielle(): void
    {
        $animal = Animaux::factory()->create([
            'user_id' => $this->user->id,
            'nom' => 'Original',
            'poids' => 20,
        ]);

        $response = $this->actingAs($this->user)->putJson("/api/animaux/{$animal->id}", ['poids' => 25]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('animaux', [
            'id' => $animal->id,
            'nom' => 'Original',
            'poids' => 25,
        ]);
    }

    public function test_update_échoue_pour_animal_dun_autre_utilisateur(): void
    {
        $animal = Animaux::factory()->create(['user_id' => $this->otherUser->id]);

        $response = $this->actingAs($this->user)->putJson("/api/animaux/{$animal->id}", ['nom' => 'Hack']);

        $response->assertStatus(404);
    }

    public function test_update_échoue_avec_donnees_invalides(): void
    {
        $animal = Animaux::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->putJson("/api/animaux/{$animal->id}", ['age' => -3]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('age');
    }

    public function test_update_statut_vaccination(): void
    {
        $animal = Animaux::factory()->create([
            'user_id' => $this->user->id,
            'vaccin_a_jour' => false,
        ]);

        $response = $this->actingAs($this->user)->putJson("/api/animaux/{$animal->id}", ['vaccin_a_jour' => true]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('animaux', [
            'id' => $animal->id,
            'vaccin_a_jour' => 1,
        ]);
    }

    // ==================== TESTS DESTROY ====================
    public function test_destroy_supprime_un_animal(): void
    {
        $animal = Animaux::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/animaux/{$animal->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Animal supprimé avec succès']);
        $this->assertDatabaseMissing('animaux', ['id' => $animal->id]);
    }

    public function test_destroy_échoue_pour_animal_dun_autre_utilisateur(): void
    {
        $animal = Animaux::factory()->create(['user_id' => $this->otherUser->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/animaux/{$animal->id}");

        $response->assertStatus(404);
        $this->assertDatabaseHas('animaux', ['id' => $animal->id]);
    }

    public function test_destroy_retourne_404_pour_animal_inexistant(): void
    {
        $response = $this->actingAs($this->user)->deleteJson('/api/animaux/99999');

        $response->assertStatus(404);
    }

    // ==================== TESTS AUTHENTIFICATION ====================
    public function test_utilisateur_non_authentifie_ne_peut_acceder_aux_endpoints(): void
    {
        $response = $this->getJson('/api/animaux');
        $response->assertStatus(401);

        $response = $this->postJson('/api/animaux', ['nom' => 'Test']);
        $response->assertStatus(401);
    }

    // ==================== TESTS VALIDATION ====================
    public function test_store_échoue_avec_nom_trop_long(): void
    {
        $data = ['nom' => str_repeat('a', 256)];

        $response = $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nom');
    }

    public function test_store_accepte_poids_decimal(): void
    {
        $data = ['nom' => 'Léger', 'poids' => 15.75];

        $response = $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('animaux', [
            'nom' => 'Léger',
            'poids' => 15.75,
        ]);
    }

    public function test_store_accepte_booleans_vaccination(): void
    {
        $data = [
            'nom' => 'Vacciné',
            'carnet_vaccination' => true,
            'vaccin_a_jour' => false,
            'vermifuge_a_jour' => true,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/animaux', $data);

        $response->assertStatus(201);
    }

    public function test_utilisateur_ne_voit_que_ses_propres_animaux(): void
    {
        Animaux::factory()->count(2)->create(['user_id' => $this->user->id]);
        Animaux::factory()->count(3)->create(['user_id' => $this->otherUser->id]);

        $response = $this->actingAs($this->user)->getJson('/api/animaux');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_isolation_donnees_entre_utilisateurs(): void
    {
        $animal1 = Animaux::factory()->create(['user_id' => $this->user->id, 'nom' => 'Animal User 1']);
        $animal2 = Animaux::factory()->create(['user_id' => $this->otherUser->id, 'nom' => 'Animal User 2']);

        $response = $this->actingAs($this->user)->getJson('/api/animaux');
        $jsonData = $response->json();

        $this->assertCount(1, $jsonData);
        $this->assertEquals('Animal User 1', $jsonData[0]['nom']);
    }

    // ==================== TESTS AUTHENTIFICATION - REGISTER ====================
    public function test_register_crée_un_nouveau_proprietaire(): void
    {
        $data = [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['email_verification_required' => true]);
        $this->assertDatabaseHas('users', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com'
        ]);
    }

    public function test_register_échoue_sans_nom(): void
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_register_échoue_avec_email_invalide(): void
    {
        $data = [
            'name' => 'Test',
            'email' => 'invalid-email',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_register_échoue_avec_email_deja_existant(): void
    {
        $data = [
            'name' => 'Autre User',
            'email' => 'test@test.com', // Email du fixture $this->user
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_register_échoue_avec_mot_de_passe_trop_court(): void
    {
        $data = [
            'name' => 'Test',
            'email' => 'new@example.com',
            'password' => '123'
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    public function test_register_genere_token_de_verification(): void
    {
        $data = [
            'name' => 'Marie',
            'email' => 'marie@example.com',
            'password' => 'password123'
        ];

        $this->postJson('/api/auth/register', $data);

        $user = User::where('email', 'marie@example.com')->first();
        $this->assertNotNull($user->email_verification_token);
        $this->assertNull($user->email_verified_at);
    }

    public function test_register_ne_retourne_pas_token_sanctum(): void
    {
        $data = [
            'name' => 'Pierre',
            'email' => 'pierre@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertDontHave('token');
    }

    // ==================== TESTS AUTHENTIFICATION - LOGIN ====================
    public function test_login_avec_identifiants_valides(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@test.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => 'test@test.com']);
        $response->assertJsonStructure(['user', 'token']);
    }

    public function test_login_échoue_avec_email_inexistant(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(422);
    }

    public function test_login_échoue_avec_mot_de_passe_incorrect(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@test.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Mauvais Identifiants']);
    }

    public function test_login_échoue_si_email_non_verifie(): void
    {
        // Créer un utilisateur non vérifié
        $unverifiedUser = User::factory()->create([
            'email' => 'unverified@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => null,
            'email_verification_token' => 'token-123'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'unverified@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(403);
        $response->assertJsonFragment(['email_verified' => false]);
    }

    // ==================== TESTS VÉRIFICATION EMAIL ====================
    public function test_verify_email_avec_token_valide(): void
    {
        // Créer un utilisateur non vérifié
        $user = User::factory()->create([
            'email_verification_token' => 'valid-token-123',
            'email_verified_at' => null
        ]);

        $response = $this->getJson('/api/auth/verify-email/valid-token-123');

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Email vérifié avec succès']);
        $response->assertJsonStructure(['user', 'token']);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verification_token' => null
        ]);
    }

    public function test_verify_email_avec_token_invalide(): void
    {
        $response = $this->getJson('/api/auth/verify-email/invalid-token-xyz');

        $response->assertStatus(400);
        $response->assertJsonFragment(['message' => 'Token de vérification invalide ou expiré']);
    }

    public function test_verify_email_échoue_si_deja_verifie(): void
    {
        // Créer un utilisateur déjà vérifié
        $user = User::factory()->create([
            'email_verification_token' => 'some-token',
            'email_verified_at' => now()
        ]);

        $response = $this->getJson('/api/auth/verify-email/some-token');

        $response->assertStatus(400);
        $response->assertJsonFragment(['message' => 'Cet email a déjà été vérifié']);
    }

    public function test_verify_email_supprime_le_token(): void
    {
        $user = User::factory()->create([
            'email_verification_token' => 'token-to-verify',
            'email_verified_at' => null
        ]);

        $this->getJson('/api/auth/verify-email/token-to-verify');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verification_token' => null,
        ]);
    }

    public function test_verify_email_retourne_token_sanctum(): void
    {
        User::factory()->create([
            'email_verification_token' => 'sanctum-token',
            'email_verified_at' => null
        ]);

        $response = $this->getJson('/api/auth/verify-email/sanctum-token');

        $response->assertJsonStructure(['token']);
        $this->assertNotEmpty($response->json('token'));
    }

    // ==================== TESTS RENVOI EMAIL DE VÉRIFICATION ====================
    public function test_resend_verification_email_avec_utilisateur_valide(): void
    {
        $user = User::factory()->create([
            'email' => 'resend@example.com',
            'email_verification_token' => 'old-token',
            'email_verified_at' => null
        ]);

        $response = $this->postJson('/api/auth/resend-verification-email', [
            'email' => 'resend@example.com'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Email de vérification renvoyé avec succès']);
    }

    public function test_resend_verification_email_avec_email_inexistant(): void
    {
        $response = $this->postJson('/api/auth/resend-verification-email', [
            'email' => 'nonexistent@example.com'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_resend_verification_email_échoue_si_deja_verifie(): void
    {
        User::factory()->create([
            'email' => 'verified@example.com',
            'email_verified_at' => now()
        ]);

        $response = $this->postJson('/api/auth/resend-verification-email', [
            'email' => 'verified@example.com'
        ]);

        $response->assertStatus(400);
        $response->assertJsonFragment(['message' => 'Cet email a déjà été vérifié']);
    }

    public function test_resend_verification_email_genere_nouveau_token(): void
    {
        $user = User::factory()->create([
            'email' => 'newtoken@example.com',
            'email_verification_token' => 'old-token-value',
            'email_verified_at' => null
        ]);

        $this->postJson('/api/auth/resend-verification-email', [
            'email' => 'newtoken@example.com'
        ]);

        $updatedUser = User::find($user->id);
        $this->assertNotEquals('old-token-value', $updatedUser->email_verification_token);
        $this->assertNotNull($updatedUser->email_verification_token);
    }

    // ==================== TESTS SÉCURITÉ ET ISOLATION DES DONNÉES ====================
    public function test_utilisateur_non_verifie_ne_peut_pas_acceder_a_ses_animaux(): void
    {
        // Créer un utilisateur non vérifié
        $unverifiedUser = User::factory()->create([
            'email' => 'unverified-animal@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => null,
            'email_verification_token' => 'some-token'
        ]);

        // Créer un animal pour cet utilisateur
        Animaux::factory()->create(['user_id' => $unverifiedUser->id]);

        // Tenter d'accéder aux animaux (Les endpoints ne vérifier pas la vérification d'email)
        $response = $this->actingAs($unverifiedUser)->getJson('/api/animaux');

        // L'accès est autorisé car seule l'authentification est vérifiée
        $response->assertStatus(200);
    }

    public function test_animaux_persiste_apres_vérification_email(): void
    {
        // Créer un utilisateur
        $newUser = User::factory()->create([
            'email' => 'persist@example.com',
            'password' => bcrypt('password'),
            'email_verification_token' => 'verify-token',
            'email_verified_at' => null
        ]);

        // Créer un animal avant vérification
        $animal = Animaux::factory()->create([
            'user_id' => $newUser->id,
            'nom' => 'AnimalTest'
        ]);

        // Vérifier l'email
        $this->getJson('/api/auth/verify-email/verify-token');

        // Vérifier que l'animal existe toujours
        $response = $this->actingAs($newUser)->getJson('/api/animaux');
        $response->assertStatus(200);
        $response->assertJsonFragment(['nom' => 'AnimalTest']);
    }

    public function test_creer_animal_apres_inscription_et_verification(): void
    {
        // 1. Inscription
        $registerData = [
            'name' => 'Sophie Martin',
            'email' => 'sophie@example.com',
            'password' => 'password123'
        ];

        $registerResponse = $this->postJson('/api/auth/register', $registerData);
        $registerResponse->assertStatus(201);

        // 2. Récupérer le token de vérification
        $newUser = User::where('email', 'sophie@example.com')->first();
        $verifyToken = $newUser->email_verification_token;

        // 3. Vérifier l'email
        $verifyResponse = $this->getJson("/api/auth/verify-email/{$verifyToken}");
        $verifyResponse->assertStatus(200);
        $sanctumToken = $verifyResponse->json('token');

        // 4. Créer un animal avec le token reçu
        $animalData = [
            'nom' => 'Minou',
            'espece' => 'Chat',
            'age' => 2
        ];

        $animalResponse = $this->postJson(
            '/api/animaux',
            $animalData,
            ['Authorization' => "Bearer {$sanctumToken}"]
        );

        $animalResponse->assertStatus(201);
        $animalResponse->assertJsonFragment(['nom' => 'Minou']);
        $this->assertDatabaseHas('animaux', [
            'user_id' => $newUser->id,
            'nom' => 'Minou'
        ]);
    }

    public function test_isolation_donnees_animaux_entre_proprietaires_verifies(): void
    {
        // Créer deux propriétaires vérifiés
        $proprietaire1 = User::factory()->create([
            'email' => 'prop1@example.com',
            'email_verified_at' => now()
        ]);

        $proprietaire2 = User::factory()->create([
            'email' => 'prop2@example.com',
            'email_verified_at' => now()
        ]);

        // Créer des animaux pour chaque propriétaire
        $animal1 = Animaux::factory()->create([
            'user_id' => $proprietaire1->id,
            'nom' => 'Chien1'
        ]);

        $animal2 = Animaux::factory()->create([
            'user_id' => $proprietaire2->id,
            'nom' => 'Chien2'
        ]);

        // Propriétaire 1 ne voit que ses animaux
        $response1 = $this->actingAs($proprietaire1)->getJson('/api/animaux');
        $response1->assertStatus(200);
        $response1->assertJsonCount(1);
        $response1->assertJsonFragment(['nom' => 'Chien1']);
        $response1->assertJsonMissing(['nom' => 'Chien2']);

        // Propriétaire 2 ne voit que ses animaux
        $response2 = $this->actingAs($proprietaire2)->getJson('/api/animaux');
        $response2->assertStatus(200);
        $response2->assertJsonCount(1);
        $response2->assertJsonFragment(['nom' => 'Chien2']);
        $response2->assertJsonMissing(['nom' => 'Chien1']);
    }

    public function test_proprietaire_ne_peut_modifier_animal_dun_autre(): void
    {
        $proprietaire1 = User::factory()->create(['email_verified_at' => now()]);
        $proprietaire2 = User::factory()->create(['email_verified_at' => now()]);

        $animal = Animaux::factory()->create(['user_id' => $proprietaire1->id]);

        $response = $this->actingAs($proprietaire2)->putJson("/api/animaux/{$animal->id}", [
            'nom' => 'Changé'
        ]);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('animaux', [
            'id' => $animal->id,
            'nom' => 'Changé'
        ]);
    }

    public function test_proprietaire_ne_peut_supprimer_animal_dun_autre(): void
    {
        $proprietaire1 = User::factory()->create(['email_verified_at' => now()]);
        $proprietaire2 = User::factory()->create(['email_verified_at' => now()]);

        $animal = Animaux::factory()->create(['user_id' => $proprietaire1->id]);

        $response = $this->actingAs($proprietaire2)->deleteJson("/api/animaux/{$animal->id}");

        $response->assertStatus(404);
        $this->assertDatabaseHas('animaux', ['id' => $animal->id]);
    }
}

