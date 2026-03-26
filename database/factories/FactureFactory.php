<?php

namespace Database\Factories;

use App\Models\Facture;
use App\Models\Pension;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FactureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Facture::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issued = $this->faker->dateTimeBetween('-1 years', 'now');
        $start = (clone $issued)->modify('-'.rand(0,7).' days');
        $end = (clone $issued)->modify('+'.rand(0,14).' days');

        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $pension = Pension::inRandomOrder()->first() ?? Pension::create([]);

        $total_ht = $this->faker->randomFloat(2, 20, 1000);
        $total_ttc = round($total_ht * 1.2, 2);

        $numero = strtoupper(Str::random(8));

        return [
            'user_id' => $user->id,
            'pension_id' => $pension->id,
            'numero' => $numero,
            'issued_at' => $issued->format('Y-m-d'),
            'stay_start_at' => $start->format('Y-m-d'),
            'stay_end_at' => $end->format('Y-m-d'),
            'animals_count' => $this->faker->numberBetween(1, 4),
            'total_ht' => $total_ht,
            'total_ttc' => $total_ttc,
            'pdf_path' => sprintf('factures/%d/%s.pdf', $user->id, $numero),
        ];
    }
}

