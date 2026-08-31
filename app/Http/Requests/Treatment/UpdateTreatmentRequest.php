<?php

namespace App\Http\Requests\Treatment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTreatmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('treatment')) ?? false;
    }

    public function rules(): array
    {
        $service = $this->route('service');
        $treatment = $this->route('treatment');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('treatments')->where('service_id', $service->id)->ignore($treatment)],
            'description' => ['nullable', 'string', 'max:2000'],
            'estimated_sessions' => ['required', 'integer', 'min:1', 'max:1000'],
            'procedure_ids' => ['required', 'array', 'min:1'],
            'procedure_ids.*' => ['integer', 'distinct', Rule::exists('procedures', 'id')->where(fn ($query) => $query->where('service_id', $service->id)->where('is_active', true))],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
