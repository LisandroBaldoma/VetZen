<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'Fisioterapia Veterinaria' => [
                ['name' => 'Lámpara infrarroja', 'duration_minutes' => 15],
                ['name' => 'Magnetoterapia', 'duration_minutes' => 30],
                ['name' => 'Electroterapia', 'duration_minutes' => 15],
                ['name' => 'Masoterapia', 'duration_minutes' => 15],
                ['name' => 'Ejercicios terapéuticos', 'duration_minutes' => 20],
            ],
            'Acupuntura Veterinaria' => [
                ['name' => 'Acupuntura corporal'],
                ['name' => 'Electroacupuntura'],
                ['name' => 'Aplicación de moxa'],
            ],
            'Fitoterapia' => [
                ['name' => 'Evaluación fitoterapéutica'],
                ['name' => 'Formulación fitoterapéutica'],
                ['name' => 'Control y ajuste de fórmula'],
            ],
            'Flores de Bach / Terapia Floral' => [
                ['name' => 'Evaluación emocional'],
                ['name' => 'Preparación de fórmula floral'],
                ['name' => 'Control de evolución'],
            ],
        ];

        foreach ($catalog as $serviceName => $procedures) {
            $service = Service::query()->where('name', $serviceName)->firstOrFail();

            foreach ($procedures as $procedure) {
                $service->procedures()->firstOrCreate(
                    ['name' => $procedure['name']],
                    [
                        'description' => null,
                        'duration_minutes' => $procedure['duration_minutes'] ?? null,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
