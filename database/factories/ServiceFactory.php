<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Service> */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->paragraph(),
            'duration_minutes' => fake()->optional()->numberBetween(15, 90),
            'price' => fake()->optional()->randomFloat(2, 0, 100000),
            'currency' => 'ARS',
            'modalities' => fake()->randomElements(['clinic', 'online', 'home_visit'], fake()->numberBetween(0, 3)),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
