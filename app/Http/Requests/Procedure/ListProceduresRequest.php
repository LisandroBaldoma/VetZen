<?php

namespace App\Http\Requests\Procedure;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListProceduresRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'integer', Rule::exists('services', 'id')],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ];
    }
}
