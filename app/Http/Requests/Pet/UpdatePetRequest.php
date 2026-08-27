<?php

namespace App\Http\Requests\Pet;

use App\Concerns\PetValidationRules;
use App\Models\Pet;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    use PetValidationRules;

    public function authorize(): bool
    {
        $pet = $this->route('pet');

        return $pet instanceof Pet && $this->user()->can('update', $pet);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->petRules();
    }
}
