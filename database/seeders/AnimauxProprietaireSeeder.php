<?php

namespace Database\Seeders;

use App\Models\Animaux;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnimauxProprietaireSeeder extends Seeder
{
    /**
     * Seed les animaux pour les propriétaires
     */
    public function run(): void
    {
        // Propriétaire 1: Jean Dupont
        $jean = User::where('email', 'jean.dupont@example.com')->first();
        if ($jean) {
            Animaux::firstOrCreate(
                ['user_id' => $jean->id, 'nom' => 'Rex'],
                [
                    'nom' => 'Rex',
                    'espece' => 'Chien',
                    'race' => 'Labrador',
                    'age' => 5,
                    'poids' => 32.5,
                    'description' => 'Chien très affectueux',
                    'carnet_vaccination' => true,
                    'vaccin_a_jour' => true,
                    'vermifuge_a_jour' => true,
                ]
            );
            
            Animaux::firstOrCreate(
                ['user_id' => $jean->id, 'nom' => 'Minou'],
                [
                    'nom' => 'Minou',
                    'espece' => 'Chat',
                    'race' => 'Persan',
                    'age' => 3,
                    'poids' => 4.2,
                    'description' => 'Chat calme et tranquille',
                    'carnet_vaccination' => true,
                    'vaccin_a_jour' => true,
                    'vermifuge_a_jour' => true,
                ]
            );
        }

        // Propriétaire 2: Marie Martin
        $marie = User::where('email', 'marie.martin@example.com')->first();
        if ($marie) {
            Animaux::firstOrCreate(
                ['user_id' => $marie->id, 'nom' => 'Bella'],
                [
                    'nom' => 'Bella',
                    'espece' => 'Chien',
                    'race' => 'Golden Retriever',
                    'age' => 2,
                    'poids' => 28.0,
                    'description' => 'Jeune chienne énergique',
                    'carnet_vaccination' => true,
                    'vaccin_a_jour' => true,
                    'vermifuge_a_jour' => true,
                ]
            );
            
            Animaux::firstOrCreate(
                ['user_id' => $marie->id, 'nom' => 'Fluffy'],
                [
                    'nom' => 'Fluffy',
                    'espece' => 'Chat',
                    'race' => 'Maine Coon',
                    'age' => 4,
                    'poids' => 6.8,
                    'description' => 'Chat très social',
                    'carnet_vaccination' => true,
                    'vaccin_a_jour' => true,
                    'vermifuge_a_jour' => false,
                ]
            );
            
            Animaux::firstOrCreate(
                ['user_id' => $marie->id, 'nom' => 'Nemo'],
                [
                    'nom' => 'Nemo',
                    'espece' => 'Poisson',
                    'race' => 'Poisson clown',
                    'age' => 1,
                    'poids' => 0.05,
                    'description' => 'Poisson tropical',
                    'carnet_vaccination' => false,
                    'vaccin_a_jour' => false,
                    'vermifuge_a_jour' => false,
                ]
            );
        }

        // Propriétaire 3: Pierre Lefevre
        $pierre = User::where('email', 'pierre.lefevre@example.com')->first();
        if ($pierre) {
            Animaux::firstOrCreate(
                ['user_id' => $pierre->id, 'nom' => 'Max'],
                [
                    'nom' => 'Max',
                    'espece' => 'Chien',
                    'race' => 'Berger Allemand',
                    'age' => 7,
                    'poids' => 35.5,
                    'description' => 'Chien de garde bien dressé',
                    'carnet_vaccination' => true,
                    'vaccin_a_jour' => true,
                    'vermifuge_a_jour' => true,
                ]
            );
        }

        // Propriétaire 4: Sophie Bertrand
        $sophie = User::where('email', 'sophie.bertrand@example.com')->first();
        if ($sophie) {
            Animaux::firstOrCreate(
                ['user_id' => $sophie->id, 'nom' => 'Chouchou'],
                [
                    'nom' => 'Chouchou',
                    'espece' => 'Lapin',
                    'race' => 'Lapin bélier',
                    'age' => 2,
                    'poids' => 2.1,
                    'description' => 'Lapin très doux',
                    'carnet_vaccination' => false,
                    'vaccin_a_jour' => false,
                    'vermifuge_a_jour' => true,
                ]
            );
            
            Animaux::firstOrCreate(
                ['user_id' => $sophie->id, 'nom' => 'Kiwi'],
                [
                    'nom' => 'Kiwi',
                    'espece' => 'Oiseau',
                    'race' => 'Perruche ondulée',
                    'age' => 1,
                    'poids' => 0.035,
                    'description' => 'Petit oiseau très bavard',
                    'carnet_vaccination' => false,
                    'vaccin_a_jour' => false,
                    'vermifuge_a_jour' => false,
                ]
            );
        }

        // Propriétaire 5: Luc Moreau
        $luc = User::where('email', 'luc.moreau@example.com')->first();
        if ($luc) {
            Animaux::firstOrCreate(
                ['user_id' => $luc->id, 'nom' => 'Cacatoès'],
                [
                    'nom' => 'Cacatoès',
                    'espece' => 'Oiseau',
                    'race' => 'Cacatoès blanc',
                    'age' => 10,
                    'poids' => 1.2,
                    'description' => 'Grand oiseau blanc très intelligent',
                    'carnet_vaccination' => false,
                    'vaccin_a_jour' => false,
                    'vermifuge_a_jour' => false,
                ]
            );
        }
    }
}
