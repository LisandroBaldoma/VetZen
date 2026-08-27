<?php

namespace App\Http\Requests\Pet;

use App\Concerns\PetValidationRules;
use App\Models\Client;
use App\Models\Pet;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePetRequest extends FormRequest
{
    use PetValidationRules;

    public function authorize(): bool
    {
        if ($this->user()->hasRole('admin')) {
            return true;
        }

        $client = $this->user()->client;

        return $client instanceof Client && $this->user()->can('create', [Pet::class, $client]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->petRules(),
            'client_id' => [Rule::requiredIf($this->user()->hasRole('admin')), 'nullable', 'integer', Rule::exists(Client::class, 'id')],
        ];
    }
}
