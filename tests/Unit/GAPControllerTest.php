<?php

namespace Tests\Unit;

use App\Models\Animaux;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Tests unitaires pour le contrôleur AnimalController (Module GAP)
 * Gestion des Animaux et des Propriétaires
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
        ]);

        $this->otherUser = User::factory()->create([
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
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
}

