<?php

namespace App\Http\Requests\Client;

use App\Concerns\ClientValidationRules;
use App\Models\Client;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    use ClientValidationRules;

    public function authorize(): bool
    {
        /** @var Client $client */
        $client = $this->route('client');

        return $this->user()->can('update', $client);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->clientRules();
    }
}
