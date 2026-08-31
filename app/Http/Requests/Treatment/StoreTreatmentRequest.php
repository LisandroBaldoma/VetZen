<?php

namespace App\Http\Requests\Treatment;

use App\Models\Treatment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTreatmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Treatment::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('treatments')->where('service_id', $this->route('service')->id)],
            'description' => ['required', 'string', 'max:5000'],
            'estimated_sessions' => ['required', 'integer', 'min:1', 'max:1000'],
            'procedure_ids' => ['required', 'array', 'min:1'],
            'procedure_ids.*' => ['integer', 'distinct', Rule::exists('procedures', 'id')->where(fn ($query) => $query->where('service_id', $this->route('service')->id)->where('is_active', true))],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
