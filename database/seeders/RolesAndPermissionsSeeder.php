<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect([
            'clients.viewAny',
            'clients.view',
            'clients.update',
        ])->map(fn (string $name): Permission => Permission::findOrCreate($name, 'web'));

        Role::findOrCreate('client', 'web');
        Role::findOrCreate('admin', 'web')->syncPermissions($permissions);

        User::query()
            ->doesntHave('roles')
            ->each(fn (User $user) => $user->assignRole('client'));
    }
}
