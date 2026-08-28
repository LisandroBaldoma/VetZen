<?php

namespace App\Http\Requests\ClinicalRecord;

use App\Concerns\ClinicalRecordValidationRules;
use App\Models\ClinicalRecord;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicalRecordRequest extends FormRequest
{
    use ClinicalRecordValidationRules;

    public function authorize(): bool
    {
        $clinicalRecord = $this->route('clinicalRecord');

        return $clinicalRecord instanceof ClinicalRecord
            && $this->user()->can('update', $clinicalRecord);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->clinicalRecordRules();
    }
}
