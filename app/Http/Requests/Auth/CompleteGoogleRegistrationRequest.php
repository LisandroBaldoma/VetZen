<?php

namespace App\Http\Requests\Auth;

use App\Concerns\ClientValidationRules;
use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CompleteGoogleRegistrationRequest extends FormRequest
{
    use ClientValidationRules, PasswordValidationRules, ProfileValidationRules;

    public function authorize(): bool
    {
        $issuedAt = $this->session()->get('google.registration.issued_at');

        return is_int($issuedAt) && $issuedAt >= now()->subMinutes(10)->timestamp;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->profileRules(),
            ...$this->clientRules(),
            'password' => $this->passwordRules(),
            'google_id' => ['required', 'string', 'max:255', Rule::unique('users', 'google_id')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $googleUser = $this->session()->get('google.registration', []);

        $this->merge([
            'name' => $googleUser['name'] ?? null,
            'email' => $googleUser['email'] ?? null,
            'google_id' => $googleUser['id'] ?? null,
        ]);
    }

    protected function failedAuthorization(): never
    {
        $this->session()->forget('google.registration');

        throw new HttpResponseException(
            to_route('login')->with('googleError', __('La solicitud de registro con Google venció. Intentá nuevamente.')),
        );
    }
}
