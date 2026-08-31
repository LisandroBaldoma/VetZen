<?php

namespace App\Http\Requests\PetTreatment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePetTreatmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('petTreatment')) ?? false;
    }

    public function rules(): array
    {
        return ['status' => ['required', Rule::in(['suspended', 'cancelled', 'resume'])]];
    }
}
