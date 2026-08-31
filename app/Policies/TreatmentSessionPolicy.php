<?php

namespace App\Policies;

use App\Models\TreatmentSession;
use App\Models\User;

class TreatmentSessionPolicy
{
    public function view(User $user, TreatmentSession $session): bool
    {
        return $user->hasRole('admin') || $session->petTreatment->pet->client->user_id === $user->id;
    }

    public function update(User $user, TreatmentSession $session): bool
    {
        return $user->hasRole('admin');
    }
}
