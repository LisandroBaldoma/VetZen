<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_only_administrators(): void
    {
        $actor = User::factory()->admin()->create(['name' => 'Ana Admin']);
        $promotedClient = Client::factory()->create();
        $promotedClient->user->update(['name' => 'Bruno Admin']);
        $promotedClient->user->syncRoles(['admin']);
        $client = Client::factory()->create();

        $this->actingAs($actor)
            ->get(route('admin.administrators.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/administrators/index')
                ->has('administrators', 2)
                ->where('administrators.0.id', $actor->id)
                ->where('administrators.1.id', $promotedClient->user_id)
                ->where('administrators.1.hasClientProfile', true)
                ->where('administrators.1.canDemote', true)
                ->where('administrators.1.isCurrentUser', false)
                ->where('administrators', fn ($administrators) => collect($administrators)
                    ->doesntContain('id', $client->user_id)),
            );
    }

    public function test_client_cannot_access_or_modify_administrators(): void
    {
        $client = Client::factory()->create();
        $target = User::factory()->admin()->create();

        $this->actingAs($client->user)
            ->get(route('admin.administrators.index'))
            ->assertForbidden();

        $this->actingAs($client->user)
            ->patch(route('admin.users.role.update', $target), ['role' => 'client'])
            ->assertForbidden();
    }

    public function test_admin_can_promote_client_without_losing_profile_or_ownership(): void
    {
        $actor = User::factory()->admin()->create();
        $client = Client::factory()->create();
        $client->user->forceFill(['google_id' => 'google-client'])->save();
        $pet = Pet::factory()->for($client)->create();

        $this->actingAs($actor)
            ->patch(route('admin.users.role.update', $client->user), [
                'role' => 'admin',
                'actor_user_id' => $client->user_id,
            ])
            ->assertSessionHasNoErrors()
            ->assertInertiaFlash('toast', [
                'type' => 'success',
                'message' => 'Acceso administrativo concedido.',
            ])
            ->assertRedirect(route('admin.administrators.index'));

        $user = $client->user->fresh();

        $this->assertTrue($user->hasExactRoles(['admin']));
        $this->assertSame('google-client', $user->google_id);
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'user_id' => $user->id]);
        $this->assertDatabaseHas('pets', ['id' => $pet->id, 'client_id' => $client->id]);
        $this->assertDatabaseHas('user_role_changes', [
            'subject_user_id' => $user->id,
            'actor_user_id' => $actor->id,
            'subject_email' => $user->email,
            'actor_email' => $actor->email,
            'from_role' => 'client',
            'to_role' => 'admin',
        ]);
    }

    public function test_promoted_admin_disappears_from_clients_and_client_routes(): void
    {
        $actor = User::factory()->admin()->create();
        $client = Client::factory()->create();
        $client->user->syncRoles(['admin']);

        $this->actingAs($actor)
            ->get(route('admin.clients.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/clients/index')
                ->has('clients', 0));

        $this->actingAs($actor)
            ->get(route('admin.clients.edit', $client))
            ->assertNotFound();

        $this->actingAs($actor)
            ->patch(route('admin.clients.update', $client), ['phone' => 'changed'])
            ->assertNotFound();
    }

    public function test_admin_can_demote_another_admin_with_client_profile(): void
    {
        $actor = User::factory()->admin()->create();
        $client = Client::factory()->create();
        $client->user->syncRoles(['admin']);

        $this->actingAs($actor)
            ->patch(route('admin.users.role.update', $client->user), ['role' => 'client'])
            ->assertSessionHasNoErrors()
            ->assertInertiaFlash('toast', [
                'type' => 'success',
                'message' => 'Acceso administrativo retirado.',
            ])
            ->assertRedirect(route('admin.clients.edit', $client));

        $this->assertTrue($client->user->fresh()->hasExactRoles(['client']));
        $this->assertDatabaseHas('user_role_changes', [
            'subject_user_id' => $client->user_id,
            'actor_user_id' => $actor->id,
            'from_role' => 'admin',
            'to_role' => 'client',
        ]);

        $this->actingAs($actor)
            ->get(route('admin.clients.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('clients.0.id', $client->id));
    }

    public function test_admin_cannot_change_own_role(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.role.update', $admin), ['role' => 'client'])
            ->assertForbidden();

        $this->assertTrue($admin->fresh()->hasExactRoles(['admin']));
        $this->assertDatabaseCount('user_role_changes', 0);
    }

    public function test_internal_admin_without_client_profile_cannot_be_demoted(): void
    {
        $actor = User::factory()->admin()->create();
        $target = User::factory()->admin()->create();

        $this->actingAs($actor)
            ->from(route('admin.administrators.index'))
            ->patch(route('admin.users.role.update', $target), ['role' => 'client'])
            ->assertSessionHasErrors('role')
            ->assertRedirect(route('admin.administrators.index'));

        $this->assertTrue($target->fresh()->hasExactRoles(['admin']));
        $this->assertDatabaseCount('user_role_changes', 0);
    }

    public function test_role_update_rejects_unknown_or_unchanged_roles(): void
    {
        $actor = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $this->actingAs($actor)
            ->patch(route('admin.users.role.update', $client->user), ['role' => 'superadmin'])
            ->assertSessionHasErrors('role');

        $this->actingAs($actor)
            ->patch(route('admin.users.role.update', $client->user), ['role' => 'client'])
            ->assertSessionHasErrors('role');

        $this->assertTrue($client->user->fresh()->hasExactRoles(['client']));
        $this->assertDatabaseCount('user_role_changes', 0);
    }
}
