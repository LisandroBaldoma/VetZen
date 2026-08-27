<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('clients.viewAny');
    }

    public function view(User $user, Client $client): bool
    {
        return $user->id === $client->user_id || $user->can('clients.view');
    }

    public function update(User $user, Client $client): bool
    {
        return $user->id === $client->user_id || $user->can('clients.update');
    }
}
