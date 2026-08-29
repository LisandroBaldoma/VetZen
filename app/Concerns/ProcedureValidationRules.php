<?php

namespace App\Concerns;

use App\Models\Procedure;
use App\Models\Service;
use Illuminate\Validation\Rule;

trait ProcedureValidationRules
{
    /** @return array<string, array<mixed>> */
    protected function procedureRules(Service $service, ?Procedure $procedure = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Procedure::class, 'name')
                    ->where('service_id', $service->id)
                    ->ignore($procedure),
            ],
            'description' => ['nullable', 'string', 'max:10000'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
