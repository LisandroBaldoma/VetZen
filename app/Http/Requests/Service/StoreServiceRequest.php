<?php

namespace App\Http\Requests\Service;

use App\Concerns\ServiceValidationRules;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    use ServiceValidationRules;

    public function authorize(): bool
    {
        return $this->user()->can('create', Service::class);
    }

    public function rules(): array
    {
        return $this->serviceRules();
    }
}
