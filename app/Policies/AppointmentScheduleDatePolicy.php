<?php

namespace App\Policies;

use App\Models\AppointmentScheduleDate;
use App\Models\User;

class AppointmentScheduleDatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, AppointmentScheduleDate $scheduleDate): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, AppointmentScheduleDate $scheduleDate): bool
    {
        return $user->hasRole('admin');
    }
}
