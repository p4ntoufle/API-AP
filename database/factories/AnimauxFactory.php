<?php

namespace Database\Factories;

use App\Models\Animaux;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimauxFactory extends Factory
{
    protected $model = Animaux::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nom' => $this->faker->firstName(),
            'espece' => $this->faker->randomElement(['Chien', 'Chat', 'Oiseau', 'Reptile']),
            'age' => $this->faker->numberBetween(0, 15),
            'poids' => $this->faker->randomFloat(2, 1, 80),
            'description' => $this->faker->optional()->sentence(),
            'carnet_vaccination' => $this->faker->boolean(70),
            'vaccin_a_jour' => $this->faker->boolean(60),
            'vermifuge_a_jour' => $this->faker->boolean(50),
        ];
    }
}

