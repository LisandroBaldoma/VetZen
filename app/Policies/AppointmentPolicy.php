<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'client']);
    }

    public function view(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('admin') || $appointment->pet->client->user_id === $user->id;
    }

    public function create(User $user, Pet $pet): bool
    {
        return $user->hasRole('client') && $pet->client->user_id === $user->id;
    }

    public function updateStatus(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('admin');
    }
}
