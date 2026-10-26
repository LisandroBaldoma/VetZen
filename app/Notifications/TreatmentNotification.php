<?php

namespace App\Notifications;

use App\Models\PetTreatment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TreatmentNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly PetTreatment $treatment, private readonly string $event) {}

    public function via(object $notifiable): array
    {
        return config('notifications.email_enabled') ? ['database', 'mail'] : ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return ['event' => $this->event, 'pet_treatment_id' => $this->treatment->id, 'pet_id' => $this->treatment->pet_id, 'pet_name' => $this->treatment->pet->name, 'treatment_name' => $this->treatment->treatment_name];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject(__('Actualización de tratamiento'))->line(__('El tratamiento :treatment de :pet fue actualizado: :event.', ['treatment' => $this->treatment->treatment_name, 'pet' => $this->treatment->pet->name, 'event' => $this->event]));
    }
}
