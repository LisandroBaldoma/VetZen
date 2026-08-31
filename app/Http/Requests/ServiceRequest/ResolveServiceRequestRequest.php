<?php

namespace App\Http\Requests\ServiceRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResolveServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('resolve', $this->route('serviceRequest')) ?? false;
    }

    public function rules(): array
    {
        $request = $this->route('serviceRequest');

        return [
            'treatment_id' => ['required', 'integer', Rule::exists('treatments', 'id')->where(fn ($query) => $query->where('service_id', $request->service_id)->where('is_active', true))],
            'planned_sessions' => ['required', 'integer', 'min:1', 'max:1000'],
            'default_session_price' => ['required', 'decimal:0,2', 'min:0'],
            'currency' => ['required', Rule::in(['ARS'])],
            'starts_on' => ['required', 'date'],
            'status' => ['required', Rule::in(['pending', 'in_progress'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
