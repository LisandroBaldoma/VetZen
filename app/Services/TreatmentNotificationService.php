<?php

namespace App\Services;

use App\Models\PetTreatment;
use App\Notifications\TreatmentNotification;
use Illuminate\Support\Facades\Log;

class TreatmentNotificationService
{
    public function send(PetTreatment $treatment, string $event): void
    {
        $treatment->loadMissing('pet.client.user');

        try {
            $treatment->pet->client->user->notify(new TreatmentNotification($treatment, $event));
        } catch (\Throwable $exception) {
            Log::warning('Unable to deliver treatment notification.', ['pet_treatment_id' => $treatment->id, 'event' => $event, 'exception' => $exception]);
        }
    }
}
