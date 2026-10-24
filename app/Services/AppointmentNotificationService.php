<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AppointmentNotificationService
{
    public function send(Appointment $appointment, string $event, bool $notifyAdmins = false): void
    {
        $appointment->loadMissing('pet.client.user');
        $recipients = collect([$appointment->pet->client->user]);
        if ($notifyAdmins) {
            $recipients = $recipients->merge(User::query()->whereHas('roles', fn ($query) => $query->where('name', 'admin'))->get());
        }

        try {
            Notification::send($recipients->unique('id'), new AppointmentNotification($appointment, $event));
        } catch (\Throwable $exception) {
            Log::warning('Unable to deliver appointment notification.', ['appointment_id' => $appointment->id, 'event' => $event, 'exception' => $exception]);
        }
    }
}
