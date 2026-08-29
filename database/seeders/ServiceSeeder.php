<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Acupuntura Veterinaria',
                'description' => 'Terapia complementaria veterinaria mediante acupuntura.',
                'duration_minutes' => 20,
                'modalities' => ['clinic'],
            ],
            [
                'name' => 'Fitoterapia',
                'description' => 'Terapia veterinaria complementaria basada en fitoterapia.',
                'duration_minutes' => 45,
                'modalities' => ['clinic', 'online'],
            ],
            [
                'name' => 'Flores de Bach / Terapia Floral',
                'description' => 'Terapia floral complementaria adaptada a cada paciente.',
                'duration_minutes' => 45,
                'modalities' => ['online'],
            ],
            [
                'name' => 'Fisioterapia Veterinaria',
                'description' => 'Terapia orientada a la rehabilitación física, movilidad y recuperación funcional del paciente.',
                'duration_minutes' => 45,
                'modalities' => ['clinic'],
            ],
        ];

        foreach ($services as $service) {
            Service::query()->firstOrCreate(
                ['name' => $service['name']],
                [...$service, 'price' => null, 'currency' => 'ARS', 'is_active' => true],
            );
        }
    }
}
