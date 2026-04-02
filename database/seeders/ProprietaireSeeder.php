<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProprietaireSeeder extends Seeder
{
    /**
     * Seed the proprietaires (users) table.
     */
    public function run(): void
    {
        // Créer des propriétaires de test avec email vérifié
        User::factory()->create([
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        User::factory()->create([
            'name' => 'Marie Martin',
            'email' => 'marie.martin@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        User::factory()->create([
            'name' => 'Pierre Lefevre',
            'email' => 'pierre.lefevre@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        User::factory()->create([
            'name' => 'Sophie Bertrand',
            'email' => 'sophie.bertrand@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        User::factory()->create([
            'name' => 'Luc Moreau',
            'email' => 'luc.moreau@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        // Créer aussi un utilisateur non vérifié à titre d'exemple
        User::factory()->create([
            'name' => 'Paul Unverified',
            'email' => 'paul.unverified@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => null,
            'email_verification_token' => 'unverified-token-' . str_random(40),
        ]);

        // Créer 10 propriétaires supplémentaires de manière aléatoire
        User::factory(10)->create([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);
    }
}
