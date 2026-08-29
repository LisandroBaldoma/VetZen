<?php

namespace App\Policies;

use App\Models\Procedure;
use App\Models\User;

class ProcedurePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'client']);
    }

    public function view(User $user, Procedure $procedure): bool
    {
        return $user->hasRole('admin')
            || ($user->hasRole('client') && $procedure->is_active && $procedure->service->is_active);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Procedure $procedure): bool
    {
        return $user->hasRole('admin');
    }
}
