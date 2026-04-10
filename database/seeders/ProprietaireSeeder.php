<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProprietaireSeeder extends Seeder
{
    /**
     * Seed the proprietaires (users) table.
     */
    public function run(): void
    {
        // Créer des propriétaires de test avec email vérifié
        User::firstOrCreate(
            ['email' => 'jean.dupont@example.com'],
            [
                'name' => 'Jean Dupont',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'marie.martin@example.com'],
            [
                'name' => 'Marie Martin',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'pierre.lefevre@example.com'],
            [
                'name' => 'Pierre Lefevre',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'sophie.bertrand@example.com'],
            [
                'name' => 'Sophie Bertrand',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'luc.moreau@example.com'],
            [
                'name' => 'Luc Moreau',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]
        );

        // Créer aussi un utilisateur non vérifié à titre d'exemple
        User::firstOrCreate(
            ['email' => 'paul.unverified@example.com'],
            [
                'name' => 'Paul Unverified',
                'password' => bcrypt('password123'),
                'email_verified_at' => null,
                'email_verification_token' => 'unverified-token-' . Str::random(40),
            ]
        );
    }
}
