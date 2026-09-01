<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_sees_their_dashboard_with_their_client_data(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/dashboard')
                ->where('client.id', $client->id));
    }

    public function test_admin_sees_the_administration_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('admin/dashboard'));
    }

    public function test_admin_role_selects_the_administration_dashboard_without_client_permissions(): void
    {
        $admin = User::factory()->create();
        $admin->syncRoles([Role::findOrCreate('admin', 'web')]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('admin/dashboard'));
    }

    public function test_client_permission_does_not_select_the_administration_dashboard(): void
    {
        $client = Client::factory()->create();
        $client->user->givePermissionTo(
            Permission::findOrCreate('clients.viewAny', 'web'),
        );

        $this->actingAs($client->user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/dashboard')
                ->where('client.id', $client->id));
    }

    public function test_user_without_an_approved_role_cannot_access_a_dashboard(): void
    {
        $user = User::factory()->create();
        $user->syncRoles([]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
    }
}
