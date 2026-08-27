<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        if (app()->environment('local')) {
            $admin = User::query()->firstOrCreate([
                'email' => 'admin@example.test',
            ], [
                'name' => 'Development Admin',
                'password' => Hash::make(env('VETZEN_ADMIN_PASSWORD', 'password')),
            ]);

            $admin->syncRoles('admin');
        }
    }
}
