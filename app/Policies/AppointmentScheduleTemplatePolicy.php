<?php

namespace App\Policies;

use App\Models\AppointmentScheduleTemplate;
use App\Models\User;

class AppointmentScheduleTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, AppointmentScheduleTemplate $template): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, AppointmentScheduleTemplate $template): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, AppointmentScheduleTemplate $template): bool
    {
        return $user->hasRole('admin');
    }
}
