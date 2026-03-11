<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Crée un utilisateur de test.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@test.com'],
            [
                'name'     => 'Utilisateur Test',
                'password' => 'pension', // Hashé automatiquement grâce au cast 'hashed' du modèle
            ]
        );
    }
}

