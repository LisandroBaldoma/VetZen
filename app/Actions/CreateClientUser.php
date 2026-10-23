<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CreateClientUser
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes, ?string $googleId = null): User
    {
        return DB::transaction(function () use ($attributes, $googleId): User {
            $user = new User(Arr::only($attributes, ['name', 'email', 'password']));
            $user->google_id = $googleId;
            $user->save();

            $user->client()->create(Arr::only($attributes, [
                'phone',
                'address',
                'city',
                'province',
                'postal_code',
                'document',
                'birth_date',
            ]));

            $user->assignRole(Role::findOrCreate('client', 'web'));

            return $user;
        });
    }
}
