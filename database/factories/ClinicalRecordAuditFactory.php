<?php

namespace Database\Factories;

use App\Models\ClinicalRecord;
use App\Models\ClinicalRecordAudit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicalRecordAudit>
 */
class ClinicalRecordAuditFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clinical_record_id' => ClinicalRecord::factory(),
            'user_id' => User::factory()->admin(),
            'action' => 'created',
            'old_values' => null,
            'new_values' => [],
        ];
    }
}
