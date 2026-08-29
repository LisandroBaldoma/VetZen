<?php

namespace Database\Factories;

use App\Models\ClinicalRecord;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicalRecord>
 */
class ClinicalRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'created_by' => User::factory()->admin(),
            'updated_by' => fn (array $attributes): int => $attributes['created_by'],
            'type' => fake()->randomElement(ClinicalRecord::TYPES),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraph(),
            'occurred_at' => fake()->dateTimeBetween('-1 year'),
            'is_visible_to_client' => fake()->boolean(),
        ];
    }

    public function visibleToClient(): static
    {
        return $this->state(fn (): array => ['is_visible_to_client' => true]);
    }

    public function hiddenFromClient(): static
    {
        return $this->state(fn (): array => ['is_visible_to_client' => false]);
    }
}
