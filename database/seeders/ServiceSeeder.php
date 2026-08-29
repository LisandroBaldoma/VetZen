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
            ],
            [
                'name' => 'Fitoterapia',
                'description' => 'Terapia veterinaria complementaria basada en fitoterapia.',
            ],
            [
                'name' => 'Flores de Bach / Terapia Floral',
                'description' => 'Terapia floral complementaria adaptada a cada paciente.',
            ],
            [
                'name' => 'Fisioterapia Veterinaria',
                'description' => 'Terapia orientada a la rehabilitación física, movilidad y recuperación funcional del paciente.',
            ],
        ];

        foreach ($services as $service) {
            Service::query()->firstOrCreate(
                ['name' => $service['name']],
                [...$service, 'is_active' => true],
            );
        }
    }
}
