<?php

namespace Tests\Feature\Service;

use App\Models\Service;
use App\Models\User;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ServiceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_lists_and_views_only_active_services_with_commercial_fields(): void
    {
        $client = User::factory()->create();
        $active = Service::factory()->create(['name' => 'Active therapy']);
        $inactive = Service::factory()->inactive()->create(['name' => 'Hidden therapy']);

        $this->actingAs($client)->get(route('services.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('services/index')
                ->has('services', 1)
                ->where('services.0.name', 'Active therapy')
                ->missing('services.0.is_active')
                ->missing('services.0.created_at'));

        $this->actingAs($client)->get(route('services.show', $active))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->where('service.name', 'Active therapy')->missing('service.is_active'));
        $this->actingAs($client)->get(route('services.show', $inactive))->assertForbidden();
    }

    public function test_admin_can_list_create_view_update_and_change_service_status(): void
    {
        $admin = User::factory()->admin()->create();
        $inactive = Service::factory()->inactive()->create(['name' => 'Inactive']);

        $this->actingAs($admin)->get(route('admin.services.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('services', 1)->where('services.0.is_active', false));

        $response = $this->actingAs($admin)->post(route('admin.services.store'), [
            'name' => 'Acupuncture',
            'description' => 'General commercial description.',
            'duration_minutes' => 30,
            'price' => '1500.50',
            'currency' => 'ARS',
            'modalities' => ['clinic', 'home_visit'],
        ]);

        $service = Service::query()->where('name', 'Acupuncture')->firstOrFail();
        $response->assertRedirect(route('admin.services.show', $service));
        $this->assertTrue($service->is_active);
        $this->assertSame('1500.50', $service->price);

        $this->actingAs($admin)->patch(route('admin.services.update', $service), [
            'name' => 'Updated acupuncture',
            'description' => 'Updated description.',
            'duration_minutes' => null,
            'price' => '0',
            'currency' => 'ARS',
            'modalities' => [],
            'is_active' => false,
            'client_id' => 999,
            'role' => 'client',
        ])->assertRedirect(route('admin.services.show', $service));

        $service->refresh();
        $this->assertSame('Updated acupuncture', $service->name);
        $this->assertSame('0.00', $service->price);
        $this->assertSame([], $service->modalities);
        $this->assertFalse($service->is_active);
        $this->assertNull($service->getAttribute('client_id'));
        $this->assertNull($service->getAttribute('role'));
        $this->actingAs($admin)->get(route('admin.services.show', $inactive))->assertOk();
    }

    public function test_client_cannot_access_or_mutate_administrative_services(): void
    {
        $client = User::factory()->create();
        $service = Service::factory()->create();
        $payload = [
            'name' => 'Forbidden update',
            'description' => $service->description,
            'currency' => 'ARS',
            'modalities' => [],
        ];

        $this->actingAs($client)->get(route('admin.services.index'))->assertForbidden();
        $this->actingAs($client)->get(route('admin.services.create'))->assertForbidden();
        $this->actingAs($client)->get(route('admin.services.show', $service))->assertForbidden();
        $this->actingAs($client)->post(route('admin.services.store'), $payload)->assertForbidden();
        $this->actingAs($client)->patch(route('admin.services.update', $service), $payload)->assertForbidden();

        $this->assertSame($service->name, $service->fresh()->name);
    }

    public function test_service_validation_rejects_invalid_catalog_data_and_duplicate_names(): void
    {
        $admin = User::factory()->admin()->create();
        Service::factory()->create(['name' => 'Existing service']);

        $this->actingAs($admin)->post(route('admin.services.store'), [
            'name' => 'Existing service',
            'description' => '',
            'duration_minutes' => 0,
            'price' => '-0.01',
            'currency' => 'USD',
            'modalities' => ['clinic', 'clinic', 'remote'],
            'is_active' => 'not-boolean',
        ])->assertSessionHasErrors([
            'name', 'description', 'duration_minutes', 'price', 'currency', 'modalities.1', 'modalities.2', 'is_active',
        ]);

        $this->assertSame(1, Service::query()->count());
    }

    public function test_service_name_can_remain_unchanged_during_update(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['name' => 'Same name']);

        $this->actingAs($admin)->patch(route('admin.services.update', $service), [
            'name' => 'Same name',
            'description' => 'Still valid.',
            'duration_minutes' => null,
            'price' => null,
            'currency' => 'ARS',
            'modalities' => [],
            'is_active' => true,
        ])->assertSessionHasNoErrors();
    }

    public function test_catalog_is_sorted_by_name_and_id_and_has_no_delete_route(): void
    {
        $admin = User::factory()->admin()->create();
        Service::factory()->create(['name' => 'Zulu']);
        $firstAlpha = Service::factory()->create(['name' => 'Alpha']);

        $this->actingAs($admin)->get(route('admin.services.index'))
            ->assertInertia(fn ($page) => $page
                ->where('services.0.id', $firstAlpha->id)
                ->where('services.1.name', 'Zulu'));

        $this->assertFalse(Route::has('admin.services.destroy'));
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $service = Service::factory()->create();

        $this->get(route('services.index'))->assertRedirect(route('login'));
        $this->get(route('services.show', $service))->assertRedirect(route('login'));
        $this->get(route('admin.services.index'))->assertRedirect(route('login'));
    }

    public function test_service_policy_matches_the_role_matrix(): void
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->create();
        $active = Service::factory()->create();
        $inactive = Service::factory()->inactive()->create();

        $this->assertTrue($admin->can('viewAny', Service::class));
        $this->assertTrue($admin->can('view', $inactive));
        $this->assertTrue($admin->can('create', Service::class));
        $this->assertTrue($admin->can('update', $active));
        $this->assertTrue($client->can('viewAny', Service::class));
        $this->assertTrue($client->can('view', $active));
        $this->assertFalse($client->can('view', $inactive));
        $this->assertFalse($client->can('create', Service::class));
        $this->assertFalse($client->can('update', $active));
    }

    public function test_service_seeder_is_idempotent(): void
    {
        $this->seed(ServiceSeeder::class);
        $this->seed(ServiceSeeder::class);

        $this->assertSame(4, Service::query()->count());
    }
}
