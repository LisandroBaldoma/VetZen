<?php

namespace App\Policies;

use App\Models\PetTreatment;
use App\Models\User;

class PetTreatmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'client']);
    }

    public function view(User $user, PetTreatment $petTreatment): bool
    {
        return $user->hasRole('admin') || $petTreatment->pet->client->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, PetTreatment $petTreatment): bool
    {
        return $user->hasRole('admin');
    }
}
