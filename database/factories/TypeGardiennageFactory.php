<?php

namespace Database\Factories;

use App\Models\TypeGardiennage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TypeGardiennage>
 */
class TypeGardiennageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pension_id' => \App\Models\Pension::factory(),
            'libelle' => $this->faker->word(),
        ];
    }
}

