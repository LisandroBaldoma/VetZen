<?php

namespace App\Http\Requests\AppointmentScheduleDate;

use App\Concerns\AppointmentScheduleDateValidationRules;
use App\Models\AppointmentScheduleDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreAppointmentScheduleDateRequest extends FormRequest
{
    use AppointmentScheduleDateValidationRules;

    public function authorize(): bool
    {
        return $this->user()?->can('create', AppointmentScheduleDate::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'dates' => ['required', 'array', 'min:1', 'max:31'],
            'dates.*' => [
                'required',
                'date',
                'after_or_equal:today',
                'distinct',
                Rule::unique(AppointmentScheduleDate::class, 'date'),
            ],
            ...$this->scheduleConfigurationRules(),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $this->validateScheduleDate($validator);
    }
}
