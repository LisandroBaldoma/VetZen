<?php

namespace App\Concerns;

use App\Models\Service;
use Illuminate\Validation\Rule;

trait ServiceValidationRules
{
    /** @return array<string, array<mixed>> */
    protected function serviceRules(?Service $service = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(Service::class, 'name')->ignore($service)],
            'description' => ['required', 'string', 'max:10000'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'decimal:0,2', 'min:0'],
            'currency' => ['required', Rule::in(['ARS'])],
            'modalities' => ['present', 'array'],
            'modalities.*' => ['string', 'distinct', Rule::in(['clinic', 'online', 'home_visit'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
