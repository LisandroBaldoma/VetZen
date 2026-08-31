<?php

namespace App\Http\Requests\TreatmentSession;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTreatmentSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('session')) ?? false;
    }

    public function rules(): array
    {
        return [
            'scheduled_at' => ['nullable', 'date'],
            'price' => ['required', 'decimal:0,2', 'min:0'],
            'currency' => ['required', Rule::in(['ARS'])],
            'status' => ['required', Rule::in(['pending', 'completed', 'cancelled'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
