<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'client']);
    }

    public function view(User $user, Service $service): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('client') && $service->is_active);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Service $service): bool
    {
        return $user->hasRole('admin');
    }
}
