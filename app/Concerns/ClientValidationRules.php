<?php

namespace App\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait ClientValidationRules
{
    /**
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function clientRules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'document' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
        ];
    }
}
