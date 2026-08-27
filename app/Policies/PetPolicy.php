<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['client', 'admin']);
    }

    public function view(User $user, Pet $pet): bool
    {
        return $user->hasRole('admin') || $user->id === $pet->client->user_id;
    }

    public function create(User $user, Client $client): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('client') && $user->id === $client->user_id);
    }

    public function update(User $user, Pet $pet): bool
    {
        return $this->view($user, $pet);
    }
}
