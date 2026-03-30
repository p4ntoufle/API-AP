<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Animaux;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerCompleteSeeder extends Seeder
{
    public function run(): void
    {
        $owner1 = User::firstOrCreate(
            ['email' => 'owner.alice@example.com'],
            [
                'name' => 'Alice Dupont',
                'password' => Hash::make('password'),
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner1->id, 'nom' => 'Max'],
            [
                'espece' => 'Chien',
                'race' => 'Golden Retriever',
                'age' => 3,
                'poids' => 32.5,
                'description' => 'Chien très amical et joueur',
                'carnet_vaccination' => true,
                'vaccin_a_jour' => true,
                'vermifuge_a_jour' => true,
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner1->id, 'nom' => 'Luna'],
            [
                'espece' => 'Chat',
                'race' => 'Persan',
                'age' => 2,
                'poids' => 4.2,
                'description' => 'Chat calme et affectueux',
                'carnet_vaccination' => true,
                'vaccin_a_jour' => true,
                'vermifuge_a_jour' => true,
            ]
        );

        $owner2 = User::firstOrCreate(
            ['email' => 'owner.bob@example.com'],
            [
                'name' => 'Bob Martin',
                'password' => Hash::make('password'),
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner2->id, 'nom' => 'Rex'],
            [
                'espece' => 'Chien',
                'race' => 'Berger Allemand',
                'age' => 5,
                'poids' => 38.0,
                'description' => 'Chien protecteur et intelligent',
                'carnet_vaccination' => true,
                'vaccin_a_jour' => true,
                'vermifuge_a_jour' => true,
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner2->id, 'nom' => 'Minou'],
            [
                'espece' => 'Chat',
                'race' => 'Siamois',
                'age' => 4,
                'poids' => 3.8,
                'description' => 'Chat bavard et sociable',
                'carnet_vaccination' => true,
                'vaccin_a_jour' => true,
                'vermifuge_a_jour' => false,
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner2->id, 'nom' => 'Kiwi'],
            [
                'espece' => 'Oiseau',
                'race' => 'Perruche',
                'age' => 1,
                'poids' => 0.05,
                'description' => 'Perruche verte très colorée',
                'carnet_vaccination' => false,
                'vaccin_a_jour' => false,
                'vermifuge_a_jour' => false,
            ]
        );

        $owner3 = User::firstOrCreate(
            ['email' => 'owner.carol@example.com'],
            [
                'name' => 'Carol Bernard',
                'password' => Hash::make('password'),
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner3->id, 'nom' => 'Bella'],
            [
                'espece' => 'Chien',
                'race' => 'Labrador',
                'age' => 2,
                'poids' => 30.0,
                'description' => 'Chien doux et famille',
                'carnet_vaccination' => true,
                'vaccin_a_jour' => true,
                'vermifuge_a_jour' => true,
            ]
        );

        $owner4 = User::firstOrCreate(
            ['email' => 'owner.david@example.com'],
            [
                'name' => 'David Leclerc',
                'password' => Hash::make('password'),
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner4->id, 'nom' => 'Simba'],
            [
                'espece' => 'Chien',
                'race' => 'Husky',
                'age' => 4,
                'poids' => 28.5,
                'description' => 'Chien énergique qui aime courir',
                'carnet_vaccination' => true,
                'vaccin_a_jour' => true,
                'vermifuge_a_jour' => true,
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner4->id, 'nom' => 'Tigre'],
            [
                'espece' => 'Chat',
                'race' => 'Bengale',
                'age' => 1,
                'poids' => 5.0,
                'description' => 'Chat sauvage et indépendant',
                'carnet_vaccination' => true,
                'vaccin_a_jour' => false,
                'vermifuge_a_jour' => true,
            ]
        );

        $owner5 = User::firstOrCreate(
            ['email' => 'owner.emma@example.com'],
            [
                'name' => 'Emma Laurent',
                'password' => Hash::make('password'),
            ]
        );

        Animaux::firstOrCreate(
            ['user_id' => $owner5->id, 'nom' => 'Chloé'],
            [
                'espece' => 'Chat',
                'race' => 'Chartreux',
                'age' => 6,
                'poids' => 4.5,
                'description' => 'Chat âgé calme et zen',
                'carnet_vaccination' => true,
                'vaccin_a_jour' => true,
                'vermifuge_a_jour' => true,
            ]
        );
    }
}

