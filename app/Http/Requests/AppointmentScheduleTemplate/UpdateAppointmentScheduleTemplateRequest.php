<?php

namespace App\Http\Requests\AppointmentScheduleTemplate;

use App\Concerns\AppointmentScheduleDateValidationRules;
use App\Models\AppointmentScheduleTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAppointmentScheduleTemplateRequest extends FormRequest
{
    use AppointmentScheduleDateValidationRules;

    public function authorize(): bool
    {
        $template = $this->route('scheduleTemplate');

        return $template instanceof AppointmentScheduleTemplate
            && ($this->user()?->can('update', $template) ?? false);
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
