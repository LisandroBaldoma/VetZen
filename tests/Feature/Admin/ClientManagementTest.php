<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_cannot_access_the_administrative_client_list(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->get(route('admin.clients.index'))
            ->assertForbidden();
    }

    public function test_admin_can_list_and_view_clients(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.clients.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('admin/clients/index')
                ->has('clients', 1));

        $this->actingAs($admin)
            ->get(route('admin.clients.edit', $client))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('admin/clients/edit')
                ->where('client.id', $client->id));
    }

    public function test_admin_can_update_client_information(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create(['phone' => 'original-phone']);

        $this->actingAs($admin)
            ->patch(route('admin.clients.update', $client), [
                'phone' => 'updated-phone',
                'user_id' => $admin->id,
                'role' => 'admin',
                'permissions' => ['clients.viewAny'],
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.clients.edit', $client));

        $this->assertSame('updated-phone', $client->fresh()->phone);
        $this->assertSame($client->user_id, $client->fresh()->user_id);
        $this->assertTrue($admin->fresh()->hasRole('admin'));
    }
}
