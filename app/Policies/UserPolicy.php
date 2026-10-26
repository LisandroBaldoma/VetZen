<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAnyAdministrators(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function updateRole(User $user, User $subject): bool
    {
        return $user->hasRole('admin') && ! $user->is($subject);
    }
}
