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
                [
                    'name' => 'Lámpara infrarroja',
                    'description' => 'Aplicación de calor mediante luz infrarroja como parte de la preparación muscular para la sesión de fisioterapia.',
                    'duration_minutes' => 15,
                ],
                [
                    'name' => 'Magnetoterapia',
                    'description' => 'Aplicación de campos magnéticos con parámetros adaptados a la evaluación y al plan de rehabilitación de cada mascota.',
                    'duration_minutes' => 30,
                ],
                [
                    'name' => 'Electroterapia',
                    'description' => 'Aplicación de estímulos eléctricos controlados para trabajar el manejo del dolor o la activación muscular, según indicación profesional.',
                    'duration_minutes' => 15,
                ],
                [
                    'name' => 'Masoterapia',
                    'description' => 'Masajes terapéuticos y maniobras manuales adaptadas para trabajar la tensión muscular y favorecer la relajación.',
                    'duration_minutes' => 15,
                ],
                [
                    'name' => 'Ejercicios terapéuticos',
                    'description' => 'Ejercicios adaptados para trabajar la movilidad, la fuerza, el equilibrio y la coordinación durante la rehabilitación.',
                    'duration_minutes' => 20,
                ],
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
