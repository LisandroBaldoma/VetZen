<?php

namespace App\Policies;

use App\Models\ClinicalRecord;
use App\Models\Pet;
use App\Models\User;

class ClinicalRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['client', 'admin']);
    }

    public function view(User $user, ClinicalRecord $clinicalRecord): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('client')
            && $user->id === $clinicalRecord->pet->client->user_id;
    }

    public function create(User $user, Pet $pet): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, ClinicalRecord $clinicalRecord): bool
    {
        return $user->hasRole('admin');
    }
}
