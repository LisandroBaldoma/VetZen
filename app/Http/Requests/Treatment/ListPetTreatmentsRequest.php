<?php

namespace App\Http\Requests\Treatment;

use App\Models\PetTreatment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListPetTreatmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', PetTreatment::class) ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'service' => ['nullable', 'integer', Rule::exists('services', 'id')],
            'pet' => ['nullable', 'integer', Rule::exists('pets', 'id')],
            'status' => ['nullable', Rule::in(['pending', 'in_progress', 'completed', 'suspended', 'cancelled'])],
        ];
    }
}
