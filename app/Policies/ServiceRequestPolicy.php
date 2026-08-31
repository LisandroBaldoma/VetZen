<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'client']);
    }

    public function view(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->hasRole('admin') || $serviceRequest->pet->client->user_id === $user->id;
    }

    public function create(User $user, Pet $pet): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('client') && $pet->client->user_id === $user->id);
    }

    public function resolve(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->hasRole('admin');
    }

    public function cancel(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->hasRole('admin');
    }
}
