<?php

namespace App\Http\Requests\AppointmentScheduleDate;

use App\Concerns\AppointmentScheduleDateValidationRules;
use App\Models\AppointmentScheduleDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAppointmentScheduleDateRequest extends FormRequest
{
    use AppointmentScheduleDateValidationRules;

    public function authorize(): bool
    {
        $scheduleDate = $this->route('scheduleDate');

        return $scheduleDate instanceof AppointmentScheduleDate
            && ($this->user()?->can('update', $scheduleDate) ?? false);
    }

    public function rules(): array
    {
        return $this->scheduleDateRules($this->route('scheduleDate'));
    }

    public function withValidator(Validator $validator): void
    {
        $this->validateScheduleDate($validator);
    }
}
