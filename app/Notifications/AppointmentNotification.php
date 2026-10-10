<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Appointment $appointment, private readonly string $event) {}

    public function via(object $notifiable): array
    {
        return config('appointments.email_notifications') ? ['database', 'mail'] : ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event' => $this->event,
            'appointment_id' => $this->appointment->id,
            'pet_name' => $this->appointment->pet->name,
            'service_name' => $this->appointment->service_name,
            'starts_at' => $this->appointment->starts_at->toIso8601String(),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Actualización de turno'))
            ->line(__('Tu turno de :service para :pet fue actualizado: :event.', [
                'service' => $this->appointment->service_name,
                'pet' => $this->appointment->pet->name,
                'event' => $this->event,
            ]));
    }
}
