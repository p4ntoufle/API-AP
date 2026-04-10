<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pension;
use App\Models\TypeGardiennage;
use App\Models\Box;
use App\Models\Tarif;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PensionCompleteSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::firstOrCreate(
            ['email' => 'pension.paris@example.com'],
            [
                'name' => 'Pension Paris',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $pension1 = Pension::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'ville' => 'Paris',
                'adresse' => '123 Rue de la Paix',
                'telephone' => '01 23 45 67 89',
                'responsable' => 'Marie Dupont',
            ]
        );

        $type1_1 = TypeGardiennage::firstOrCreate(
            ['pension_id' => $pension1->id, 'libelle' => 'Gardiennage à domicile'],
            ['libelle' => 'Gardiennage à domicile']
        );
        $type1_2 = TypeGardiennage::firstOrCreate(
            ['pension_id' => $pension1->id, 'libelle' => 'Garde en chenil'],
            ['libelle' => 'Garde en chenil']
        );
        $type1_3 = TypeGardiennage::firstOrCreate(
            ['pension_id' => $pension1->id, 'libelle' => 'Promenade avec garde'],
            ['libelle' => 'Promenade avec garde']
        );

        Tarif::firstOrCreate(
            ['pension_id' => $pension1->id, 'type_gardiennage_id' => $type1_1->id],
            ['prix' => 25.00]
        );
        Tarif::firstOrCreate(
            ['pension_id' => $pension1->id, 'type_gardiennage_id' => $type1_2->id],
            ['prix' => 20.00]
        );
        Tarif::firstOrCreate(
            ['pension_id' => $pension1->id, 'type_gardiennage_id' => $type1_3->id],
            ['prix' => 30.00]
        );

        for ($i = 1; $i <= 3; $i++) {
            Box::firstOrCreate(
                ['pension_id' => $pension1->id, 'id' => $pension1->id * 1000 + $i],
                ['superficie' => rand(20, 50) + 0.5]
            );
        }

        $user2 = User::firstOrCreate(
            ['email' => 'pension.lyon@example.com'],
            [
                'name' => 'Pension Lyon',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $pension2 = Pension::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'ville' => 'Lyon',
                'adresse' => '456 Avenue du Rhône',
                'telephone' => '04 56 78 90 12',
                'responsable' => 'Jean Martin',
            ]
        );

        $type2_1 = TypeGardiennage::firstOrCreate(
            ['pension_id' => $pension2->id, 'libelle' => 'Gardiennage à domicile'],
            ['libelle' => 'Gardiennage à domicile']
        );
        $type2_2 = TypeGardiennage::firstOrCreate(
            ['pension_id' => $pension2->id, 'libelle' => 'Garde en pension'],
            ['libelle' => 'Garde en pension']
        );

        Tarif::firstOrCreate(
            ['pension_id' => $pension2->id, 'type_gardiennage_id' => $type2_1->id],
            ['prix' => 22.50]
        );
        Tarif::firstOrCreate(
            ['pension_id' => $pension2->id, 'type_gardiennage_id' => $type2_2->id],
            ['prix' => 18.50]
        );

        for ($i = 1; $i <= 4; $i++) {
            Box::firstOrCreate(
                ['pension_id' => $pension2->id, 'id' => $pension2->id * 1000 + $i],
                ['superficie' => rand(25, 60) + 0.5]
            );
        }

        $user3 = User::firstOrCreate(
            ['email' => 'pension.marseille@example.com'],
            [
                'name' => 'Pension Marseille',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $pension3 = Pension::firstOrCreate(
            ['user_id' => $user3->id],
            [
                'ville' => 'Marseille',
                'adresse' => '789 Boulevard de la Mer',
                'telephone' => '04 91 23 45 67',
                'responsable' => 'Sophie Bernard',
            ]
        );

        $type3_1 = TypeGardiennage::firstOrCreate(
            ['pension_id' => $pension3->id, 'libelle' => 'Gardiennage premium'],
            ['libelle' => 'Gardiennage premium']
        );
        $type3_2 = TypeGardiennage::firstOrCreate(
            ['pension_id' => $pension3->id, 'libelle' => 'Garde basique'],
            ['libelle' => 'Garde basique']
        );
        $type3_3 = TypeGardiennage::firstOrCreate(
            ['pension_id' => $pension3->id, 'libelle' => 'Promenade'],
            ['libelle' => 'Promenade']
        );

        Tarif::firstOrCreate(
            ['pension_id' => $pension3->id, 'type_gardiennage_id' => $type3_1->id],
            ['prix' => 35.00]
        );
        Tarif::firstOrCreate(
            ['pension_id' => $pension3->id, 'type_gardiennage_id' => $type3_2->id],
            ['prix' => 15.00]
        );
        Tarif::firstOrCreate(
            ['pension_id' => $pension3->id, 'type_gardiennage_id' => $type3_3->id],
            ['prix' => 28.00]
        );

        for ($i = 1; $i <= 5; $i++) {
            Box::firstOrCreate(
                ['pension_id' => $pension3->id, 'id' => $pension3->id * 1000 + $i],
                ['superficie' => rand(15, 45) + 0.5]
            );
        }
    }
}

