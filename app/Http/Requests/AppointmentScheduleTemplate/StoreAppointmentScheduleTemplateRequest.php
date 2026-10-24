<?php

namespace App\Http\Requests\AppointmentScheduleTemplate;

use App\Concerns\AppointmentScheduleDateValidationRules;
use App\Models\AppointmentScheduleTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppointmentScheduleTemplateRequest extends FormRequest
{
    use AppointmentScheduleDateValidationRules;

    public function authorize(): bool
    {
        return $this->user()?->can('create', AppointmentScheduleTemplate::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            ...$this->scheduleConfigurationRules(requireInterval: true),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $this->validateScheduleDate($validator);
    }
}
