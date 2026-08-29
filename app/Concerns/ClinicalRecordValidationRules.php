<?php

namespace App\Concerns;

use App\Models\ClinicalRecord;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ClinicalRecordValidationRules
{
    /**
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function clinicalRecordRules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(ClinicalRecord::TYPES)],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'occurred_at' => ['required', 'date', 'before_or_equal:now'],
            'is_visible_to_client' => ['required', 'boolean'],
        ];
    }
}
