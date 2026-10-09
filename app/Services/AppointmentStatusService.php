<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentStatusService
{
    /** @var array<string, array<int, string>> */
    private const TRANSITIONS = [
        'pending' => ['approved', 'rejected', 'cancelled'],
        'approved' => ['cancelled', 'attended', 'absent'],
    ];

    public function transition(Appointment $appointment, User $user, string $status, ?string $reason, bool $enforceCancellationLeadTime = false): Appointment
    {
        return DB::transaction(function () use ($appointment, $user, $status, $reason, $enforceCancellationLeadTime): Appointment {
            $locked = Appointment::query()->lockForUpdate()->findOrFail($appointment->id);

            if (! in_array($status, self::TRANSITIONS[$locked->status] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => __('La transición de estado solicitada no está permitida.'),
                ]);
            }

            if ($enforceCancellationLeadTime && $status === 'cancelled' && $locked->starts_at->setTimezone(config('appointments.timezone'))->lessThan(now(config('appointments.timezone'))->addMinutes(config('appointments.client_cancellation_lead_minutes')))) {
                throw ValidationException::withMessages([
                    'status' => __('El turno solo se puede cancelar con la anticipación mínima requerida.'),
                ]);
            }

            $history = new AppointmentStatusHistory([
                'from_status' => $locked->status,
                'to_status' => $status,
                'reason' => $reason,
            ]);
            $history->appointment()->associate($locked);
            $history->changedBy()->associate($user);

            $locked->update(['status' => $status]);
            $history->save();

            return $locked;
        });
    }
}
