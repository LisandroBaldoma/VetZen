<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\AppointmentReminderDelivery;
use App\Services\AppointmentNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';

    protected $description = 'Send idempotent reminders for upcoming approved appointments.';

    public function handle(AppointmentNotificationService $notifications): int
    {
        $target = now('UTC')->addHours(config('appointments.reminder_hours'));
        Appointment::query()->where('status', 'approved')->whereBetween('starts_at', [$target->copy()->subMinutes(5), $target->copy()->addMinutes(5)])->each(function (Appointment $appointment) use ($notifications): void {
            $delivery = DB::transaction(function () use ($appointment) {
                $locked = Appointment::query()->lockForUpdate()->find($appointment->id);
                if (! $locked || $locked->status !== 'approved' || $locked->starts_at->notEqualTo($appointment->starts_at)) {
                    return null;
                }

                return AppointmentReminderDelivery::query()->firstOrCreate(['appointment_id' => $locked->id, 'appointment_starts_at' => $locked->starts_at], ['sent_at' => now()]);
            });
            if ($delivery && $delivery->wasRecentlyCreated) {
                $notifications->send($appointment, 'recordatorio');
            }
        });

        return self::SUCCESS;
    }
}
