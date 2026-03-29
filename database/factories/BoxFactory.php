<?php

namespace Database\Factories;

use App\Models\Box;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Box>
 */
class BoxFactory extends Factory
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
            'superficie' => $this->faker->randomFloat(2, 10, 50),
        ];
    }
}
