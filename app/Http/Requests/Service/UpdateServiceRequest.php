<?php

namespace App\Http\Requests\Service;

use App\Concerns\ServiceValidationRules;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    use ServiceValidationRules;

    public function authorize(): bool
    {
        $service = $this->route('service');

        return $service instanceof Service && $this->user()->can('update', $service);
    }

    public function rules(): array
    {
        return $this->serviceRules($this->route('service'));
    }
}
