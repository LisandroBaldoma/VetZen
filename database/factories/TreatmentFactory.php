<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Treatment> */
class TreatmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->paragraph(),
            'estimated_sessions' => fake()->numberBetween(1, 12),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
