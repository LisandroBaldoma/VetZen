<?php

namespace App\Http\Requests\ClinicalRecord;

use App\Concerns\ClinicalRecordValidationRules;
use App\Models\ClinicalRecord;
use App\Models\Pet;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClinicalRecordRequest extends FormRequest
{
    use ClinicalRecordValidationRules;

    public function authorize(): bool
    {
        $pet = $this->route('pet');

        return $pet instanceof Pet
            && $this->user()->can('create', [ClinicalRecord::class, $pet]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->clinicalRecordRules();
    }
}
