<?php

namespace App\Concerns;

use App\Models\AppointmentScheduleDate;
use App\Models\Service;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait AppointmentScheduleDateValidationRules
{
    /** @return array<string, array<mixed>> */
    protected function scheduleConfigurationRules(bool $required = true, bool $requireInterval = false): array
    {
        $presence = $required ? ['required'] : ['nullable'];

        return [
            'capacity' => [...$presence, 'integer', 'between:1,5'],
            'interval_minutes' => $requireInterval
                ? ['required', 'integer', Rule::in([15, 30, 45, 60])]
                : ['nullable', 'integer', Rule::in([15, 30, 45, 60])],
            'blocks' => [...$presence, 'array', 'min:1', 'max:12'],
            'blocks.*.starts_at' => ['required', 'date_format:H:i'],
            'blocks.*.ends_at' => ['required', 'date_format:H:i'],
            'starts' => ['nullable', 'array', 'min:1', 'max:48'],
            'starts.*' => ['required', 'date_format:H:i', 'distinct'],
            'service_ids' => [...$presence, 'array', 'min:1'],
            'service_ids.*' => ['required', 'integer', 'distinct', Rule::exists(Service::class, 'id')->where('is_active', true)],
        ];
    }

    /** @return array<string, array<mixed>> */
    protected function scheduleDateRules(?AppointmentScheduleDate $scheduleDate = null): array
    {
        return [
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                Rule::unique(AppointmentScheduleDate::class, 'date')->ignore($scheduleDate),
            ],
            ...$this->scheduleConfigurationRules(),
        ];
    }

    protected function validateScheduleDate(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $blocks = collect($this->input('blocks', []))
                ->filter(fn ($block): bool => is_array($block)
                    && isset($block['starts_at'], $block['ends_at'])
                    && $block['starts_at'] < $block['ends_at'])
                ->sortBy('starts_at')
                ->values();

            foreach ($this->input('blocks', []) as $index => $block) {
                if (is_array($block) && ($block['starts_at'] ?? null) >= ($block['ends_at'] ?? null)) {
                    $validator->errors()->add("blocks.$index.ends_at", 'El fin del bloque debe ser posterior al inicio.');
                }
            }

            foreach ($blocks->values() as $index => $block) {
                $previous = $blocks->get($index - 1);
                if ($previous !== null && $previous['ends_at'] > $block['starts_at']) {
                    $validator->errors()->add('blocks', 'Los bloques de trabajo no pueden superponerse.');
                    break;
                }
            }

            foreach ($this->input('starts', []) as $index => $start) {
                $insideBlock = $blocks->contains(
                    fn (array $block): bool => $start >= $block['starts_at'] && $start < $block['ends_at'],
                );

                if (! $insideBlock) {
                    $validator->errors()->add("starts.$index", 'El inicio habilitado debe pertenecer a un bloque de trabajo.');
                }
            }

            $servicesWithoutDuration = Service::query()
                ->whereIn('id', $this->input('service_ids', []))
                ->whereNull('appointment_duration_minutes')
                ->pluck('name');

            if ($servicesWithoutDuration->isNotEmpty()) {
                $validator->errors()->add('service_ids', 'Todos los servicios habilitados deben tener una duración de turno configurada.');
            }
        });
    }
}
