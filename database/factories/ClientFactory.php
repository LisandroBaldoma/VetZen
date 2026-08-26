<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->optional()->streetAddress(),
            'city' => fake()->optional()->city(),
            'province' => fake()->optional()->state(),
            'postal_code' => fake()->optional()->postcode(),
            'document' => fake()->optional()->numerify('########'),
            'birth_date' => fake()->optional()->date(),
        ];
    }
}
