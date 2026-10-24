<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class RescheduleAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('reschedule', $this->route('appointment')) ?? false;
    }

    public function rules(): array
    {
        return ['schedule_date_id' => ['required', 'integer', 'exists:appointment_schedule_dates,id'], 'starts_at' => ['required', 'date_format:H:i']];
    }
}
