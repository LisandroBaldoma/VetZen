<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pet>
 */
class PetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'name' => fake()->firstName(),
            'species' => fake()->randomElement(['Dog', 'Cat']),
            'breed' => fake()->optional()->word(),
            'sex' => fake()->randomElement(['Female', 'Male']),
            'birth_date' => fake()->optional()->date(),
            'weight' => fake()->optional()->randomFloat(2, 0.1, 99.99),
            'color' => fake()->optional()->colorName(),
            'notes' => fake()->optional()->sentence(),
            'photo' => null,
        ];
    }
}
