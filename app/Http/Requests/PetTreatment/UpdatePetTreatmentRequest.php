<?php

namespace App\Http\Requests\PetTreatment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePetTreatmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('petTreatment')) ?? false;
    }

    public function rules(): array
    {
        return [
            'planned_sessions' => ['required', 'integer', 'min:1', 'max:1000'],
            'default_session_price' => ['required', 'decimal:0,2', 'min:0'],
            'currency' => ['required', Rule::in(['ARS'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
