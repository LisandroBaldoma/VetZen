<?php

namespace App\Http\Requests\AppointmentScheduleDate;

use App\Concerns\AppointmentScheduleDateValidationRules;
use App\Models\AppointmentScheduleDate;
use App\Models\AppointmentScheduleTemplate;
use Carbon\CarbonImmutable;
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
            'dates' => ['nullable', 'array', 'min:1', 'max:31'],
            'dates.*' => [
                'required',
                'date',
                'after_or_equal:today',
                'distinct',
                Rule::unique(AppointmentScheduleDate::class, 'date'),
            ],
            'date_from' => ['nullable', 'date', 'after_or_equal:today'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'weekdays' => ['nullable', 'array', 'min:1', 'max:7'],
            'weekdays.*' => ['required', 'integer', 'between:0,6', 'distinct'],
            'template_id' => ['nullable', 'integer', Rule::exists(AppointmentScheduleTemplate::class, 'id')],
            ...$this->scheduleConfigurationRules(
                required: ! $this->filled('template_id'),
                requireInterval: ! $this->filled('template_id'),
            ),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $this->validateScheduleDate($validator);

        $validator->after(function (Validator $validator): void {
            $hasDates = is_array($this->input('dates')) && $this->input('dates') !== [];
            $hasRange = $this->filled('date_from') || $this->filled('date_to');

            if ($hasDates === $hasRange) {
                $validator->errors()->add('dates', 'Indique fechas explícitas o un rango de fechas.');
            }

            if ($hasRange && (! $this->filled('date_from') || ! $this->filled('date_to'))) {
                $validator->errors()->add('date_from', 'El rango requiere fecha de inicio y fin.');
            }

            if ($this->filled('template_id') && ($this->filled('interval_minutes') || $this->filled('blocks') || $this->filled('service_ids'))) {
                $validator->errors()->add('template_id', 'Seleccione una plantilla o una configuración personalizada, no ambas.');
            }

            if ($this->filled('template_id') && AppointmentScheduleTemplate::query()
                ->find($this->integer('template_id'))?->services()
                ->where(fn ($query) => $query->where('is_active', false)->orWhereNull('appointment_duration_minutes'))
                ->exists()) {
                $validator->errors()->add('template_id', 'La plantilla contiene servicios inactivos o sin duración de turno configurada.');
            }

            if ($validator->errors()->isNotEmpty() || (! $hasDates && ! $hasRange)) {
                return;
            }

            $dates = $this->publicationDates();

            if (count($dates) > 31) {
                $validator->errors()->add('date_to', 'La publicación no puede incluir más de 31 fechas.');
            }

            if (collect($dates)->contains(fn (string $date): bool => AppointmentScheduleDate::query()
                ->whereDate('date', $date)
                ->exists())) {
                $validator->errors()->add('dates', 'Una o más fechas ya están publicadas.');
            }
        });
    }

    /** @return array<int, string> */
    public function publicationDates(): array
    {
        $dates = $this->input('dates');

        if (is_array($dates) && $dates !== []) {
            return $dates;
        }

        if (! $this->filled('date_from') || ! $this->filled('date_to')) {
            return [];
        }

        $weekdays = $this->input('weekdays', range(0, 6));
        $date = CarbonImmutable::parse($this->input('date_from'))->startOfDay();
        $end = CarbonImmutable::parse($this->input('date_to'))->startOfDay();
        $result = [];

        while ($date->lte($end)) {
            if (in_array($date->dayOfWeek, $weekdays, true)) {
                $result[] = $date->toDateString();
            }

            $date = $date->addDay();
        }

        return $result;
    }
}
