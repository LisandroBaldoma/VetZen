<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentLinksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('updateStatus', $this->route('appointment')) ?? false;
    }

    public function rules(): array
    {
        return [
            'service_request_id' => ['nullable', 'integer', 'exists:service_requests,id'],
            'treatment_session_id' => ['nullable', 'integer', 'exists:treatment_sessions,id'],
        ];
    }
}
