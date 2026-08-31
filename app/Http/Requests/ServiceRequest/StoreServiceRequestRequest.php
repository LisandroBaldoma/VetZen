<?php

namespace App\Http\Requests\ServiceRequest;

use App\Models\Pet;
use App\Models\ServiceRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('pet') instanceof Pet && ($this->user()?->can('create', [ServiceRequest::class, $this->route('pet')]) ?? false);
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'integer', Rule::exists('services', 'id')->where('is_active', true)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
