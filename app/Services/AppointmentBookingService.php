<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentScheduleDate;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentBookingService
{
    /** @return array<int, array{id: int, date: string, starts: array<int, array{time: string, ends_at: string}>}> */
    public function availability(Pet $pet, Service $service): array
    {
        if (! $service->is_active || $service->appointment_duration_minutes === null) {
            return [];
        }

        return AppointmentScheduleDate::query()
            ->whereDate('date', '>=', $this->nowInVeterinaryTimezone()->toDateString())
            ->whereHas('services', fn ($query) => $query->whereKey($service->id))
            ->with([
                'blocks' => fn ($query) => $query->orderBy('starts_at'),
                'starts' => fn ($query) => $query->orderBy('starts_at'),
                'appointments' => fn ($query) => $query
                    ->whereIn('status', ['pending', 'approved'])
                    ->orderBy('starts_at'),
            ])
            ->orderBy('date')
            ->get()
            ->map(function (AppointmentScheduleDate $scheduleDate) use ($pet, $service): array {
                $starts = $this->availableStarts($scheduleDate, $pet, $service);

                return [
                    'id' => $scheduleDate->id,
                    'date' => $scheduleDate->date->toDateString(),
                    'starts' => $starts,
                ];
            })
            ->filter(fn (array $date): bool => $date['starts'] !== [])
            ->values()
            ->all();
    }

    public function book(User $user, Pet $pet, Service $service, int $scheduleDateId, string $start): Appointment
    {
        return DB::transaction(function () use ($user, $pet, $service, $scheduleDateId, $start): Appointment {
            $scheduleDate = AppointmentScheduleDate::query()
                ->with(['blocks', 'starts', 'services'])
                ->lockForUpdate()
                ->findOrFail($scheduleDateId);

            $service = Service::query()->lockForUpdate()->findOrFail($service->id);
            $appointment = $this->appointmentForStart($scheduleDate, $pet, $service, $start);

            $overlappingAppointments = Appointment::query()
                ->where('appointment_schedule_date_id', $scheduleDate->id)
                ->whereIn('status', ['pending', 'approved'])
                ->where('starts_at', '<', $appointment['ends_at'])
                ->where('ends_at', '>', $appointment['starts_at'])
                ->lockForUpdate()
                ->get();

            if ($this->maximumOccupancy($overlappingAppointments, $appointment['starts_at'], $appointment['ends_at']) > $scheduleDate->capacity) {
                throw ValidationException::withMessages([
                    'starts_at' => __('El horario seleccionado ya no tiene capacidad disponible.'),
                ]);
            }

            $petHasConflict = Appointment::query()
                ->where('pet_id', $pet->id)
                ->whereIn('status', ['pending', 'approved'])
                ->where('starts_at', '<', $appointment['ends_at'])
                ->where('ends_at', '>', $appointment['starts_at'])
                ->lockForUpdate()
                ->exists();

            if ($petHasConflict) {
                throw ValidationException::withMessages([
                    'starts_at' => __('Esta mascota ya tiene un turno en ese horario.'),
                ]);
            }

            $booking = new Appointment([
                'service_name' => $service->name,
                'duration_minutes' => $service->appointment_duration_minutes,
                'starts_at' => $appointment['starts_at'],
                'ends_at' => $appointment['ends_at'],
                'status' => 'pending',
            ]);
            $booking->pet()->associate($pet);
            $booking->service()->associate($service);
            $booking->scheduleDate()->associate($scheduleDate);
            $booking->requestedBy()->associate($user);
            $booking->save();

            return $booking;
        });
    }

    /** @return array<int, array{time: string, ends_at: string}> */
    private function availableStarts(AppointmentScheduleDate $scheduleDate, Pet $pet, Service $service): array
    {
        return $scheduleDate->starts
            ->map(fn ($start): string => $this->normalizeTime($start->starts_at))
            ->filter(function (string $start) use ($scheduleDate, $pet, $service): bool {
                try {
                    $appointment = $this->appointmentForStart($scheduleDate, $pet, $service, $start);
                } catch (ValidationException) {
                    return false;
                }

                $overlaps = $scheduleDate->appointments->filter(
                    fn (Appointment $existing): bool => $existing->starts_at < $appointment['ends_at']
                        && $existing->ends_at > $appointment['starts_at'],
                );

                return $this->maximumOccupancy($overlaps, $appointment['starts_at'], $appointment['ends_at']) <= $scheduleDate->capacity
                    && ! $scheduleDate->appointments->contains(
                        fn (Appointment $existing): bool => $existing->pet_id === $pet->id
                            && $existing->starts_at < $appointment['ends_at']
                            && $existing->ends_at > $appointment['starts_at'],
                    );
            })
            ->map(function (string $start) use ($scheduleDate, $service): array {
                $startsAt = $this->localStart($scheduleDate, $start);

                return [
                    'time' => $start,
                    'ends_at' => $startsAt->addMinutes($service->appointment_duration_minutes)->format('H:i'),
                ];
            })
            ->values()
            ->all();
    }

    /** @return array{starts_at: CarbonImmutable, ends_at: CarbonImmutable} */
    private function appointmentForStart(AppointmentScheduleDate $scheduleDate, Pet $pet, Service $service, string $start): array
    {
        if (! $service->is_active || $service->appointment_duration_minutes === null) {
            throw ValidationException::withMessages(['service_id' => __('El servicio seleccionado no está disponible.')]);
        }

        $time = $this->normalizeTime($start);
        if (! $scheduleDate->services->contains('id', $service->id)) {
            throw ValidationException::withMessages(['service_id' => __('El servicio no está habilitado para esta fecha.')]);
        }

        if (! $scheduleDate->starts->contains(fn ($publishedStart): bool => $this->normalizeTime($publishedStart->starts_at) === $time)) {
            throw ValidationException::withMessages(['starts_at' => __('El horario seleccionado no está publicado.')]);
        }

        $startsAt = $this->localStart($scheduleDate, $time);
        if ($startsAt->lessThan($this->nowInVeterinaryTimezone()->addMinutes(config('appointments.client_booking_lead_minutes')))) {
            throw ValidationException::withMessages(['starts_at' => __('El turno debe solicitarse con la anticipación mínima requerida.')]);
        }

        $endsAt = $startsAt->addMinutes($service->appointment_duration_minutes);
        $insideBlock = $scheduleDate->blocks->contains(function ($block) use ($startsAt, $endsAt): bool {
            $blockStart = $this->localStart($startsAt->toDateString(), $this->normalizeTime($block->starts_at));
            $blockEnd = $this->localStart($startsAt->toDateString(), $this->normalizeTime($block->ends_at));

            return $startsAt >= $blockStart && $endsAt <= $blockEnd;
        });

        if (! $insideBlock) {
            throw ValidationException::withMessages(['starts_at' => __('La duración del servicio no entra dentro del bloque de trabajo.')]);
        }

        return [
            'starts_at' => $startsAt->setTimezone('UTC'),
            'ends_at' => $endsAt->setTimezone('UTC'),
        ];
    }

    /** @param Collection<int, Appointment> $appointments */
    private function maximumOccupancy(Collection $appointments, CarbonImmutable $startsAt, CarbonImmutable $endsAt): int
    {
        $events = collect([[$startsAt->getTimestamp(), 1], [$endsAt->getTimestamp(), -1]]);

        foreach ($appointments as $appointment) {
            $events->push([$appointment->starts_at->getTimestamp(), 1]);
            $events->push([$appointment->ends_at->getTimestamp(), -1]);
        }

        $occupancy = 0;
        $maximum = 0;
        foreach ($events->sort(fn (array $left, array $right): int => $left[0] === $right[0]
            ? $left[1] <=> $right[1]
            : $left[0] <=> $right[0]) as [, $change]) {
            $occupancy += $change;
            $maximum = max($maximum, $occupancy);
        }

        return $maximum;
    }

    private function localStart(AppointmentScheduleDate|string $scheduleDate, string $time): CarbonImmutable
    {
        $date = $scheduleDate instanceof AppointmentScheduleDate
            ? $scheduleDate->date->toDateString()
            : $scheduleDate;

        return CarbonImmutable::createFromFormat('Y-m-d H:i', "$date $time", config('appointments.timezone'));
    }

    private function nowInVeterinaryTimezone(): CarbonImmutable
    {
        return now(config('appointments.timezone'))->toImmutable();
    }

    private function normalizeTime(string $time): string
    {
        return CarbonImmutable::createFromFormat('H:i:s', strlen($time) === 5 ? "$time:00" : $time)
            ->format('H:i');
    }
}
