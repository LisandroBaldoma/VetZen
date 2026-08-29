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
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
