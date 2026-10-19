<?php

namespace App\Http\Requests\Simon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSimonMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'client']) ?? false;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:1000'],
            'module' => ['nullable', 'string', Rule::in([
                'account',
                'appointments',
                'clients',
                'medical-records',
                'pets',
                'procedures',
                'service-requests',
                'services',
                'treatments',
            ])],
        ];
    }
}
