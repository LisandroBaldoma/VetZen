<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ServiceRequest> */
class ServiceRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'service_id' => Service::factory(),
            'status' => 'pending',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
