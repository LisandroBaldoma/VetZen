<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('client') ?? false;
    }

    public function rules(): array
    {
        return [
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'schedule_date_id' => ['required', 'integer', 'exists:appointment_schedule_dates,id'],
            'starts_at' => ['required', 'date_format:H:i'],
        ];
    }
}
