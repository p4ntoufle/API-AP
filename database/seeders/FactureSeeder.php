<?php

namespace Database\Seeders;

use App\Models\Pension;
use App\Models\User;
use App\Models\Facture;
use Illuminate\Database\Seeder;

class FactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have some users and pensions
        User::factory(5)->create();

        // Create some simple pensions if none exist
        if (Pension::count() === 0) {
            Pension::create([/* minimal */]);
            Pension::create([/* minimal */]);
            Pension::create([/* minimal */]);
        }

        // Generate 30 factures
        Facture::factory(30)->create();
    }
}

