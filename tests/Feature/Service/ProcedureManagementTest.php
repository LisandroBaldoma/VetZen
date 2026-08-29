<?php

namespace Tests\Feature\Service;

use App\Models\Procedure;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\ProcedureSeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProcedureManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_create_view_update_and_change_procedure_status(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $otherService = Service::factory()->create();

        $this->actingAs($admin)->get(route('admin.services.procedures.index', $service))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('admin/services/procedures/index')
                ->where('service.id', $service->id)
                ->has('procedures', 0));

        $response = $this->actingAs($admin)->post(route('admin.services.procedures.store', $service), [
            'name' => 'Magnetotherapy',
            'description' => null,
            'duration_minutes' => null,
            'service_id' => $otherService->id,
        ]);

        $procedure = Procedure::query()->where('name', 'Magnetotherapy')->firstOrFail();
        $response->assertRedirect(route('admin.services.procedures.show', [$service, $procedure]));
        $this->assertTrue($procedure->service->is($service));
        $this->assertNull($procedure->duration_minutes);
        $this->assertTrue($procedure->is_active);

        $this->actingAs($admin)->get(route('admin.services.procedures.show', [$service, $procedure]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('admin/services/procedures/show')
                ->where('procedure.name', 'Magnetotherapy'));

        $this->actingAs($admin)->patch(route('admin.services.procedures.update', [$service, $procedure]), [
            'name' => 'Updated magnetotherapy',
            'description' => 'Updated description.',
            'duration_minutes' => 30,
            'is_active' => false,
            'service_id' => $otherService->id,
        ])->assertRedirect(route('admin.services.procedures.show', [$service, $procedure]));

        $procedure->refresh();
        $this->assertSame('Updated magnetotherapy', $procedure->name);
        $this->assertSame(30, $procedure->duration_minutes);
        $this->assertFalse($procedure->is_active);
        $this->assertTrue($procedure->service->is($service));
    }

    public function test_client_cannot_access_or_mutate_procedure_administration(): void
    {
        $client = User::factory()->create();
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();
        $payload = ['name' => 'Forbidden', 'duration_minutes' => 15];

        $this->actingAs($client)->get(route('admin.services.procedures.index', $service))->assertForbidden();
        $this->actingAs($client)->get(route('admin.services.procedures.create', $service))->assertForbidden();
        $this->actingAs($client)->get(route('admin.services.procedures.show', [$service, $procedure]))->assertForbidden();
        $this->actingAs($client)->post(route('admin.services.procedures.store', $service), $payload)->assertForbidden();
        $this->actingAs($client)->patch(route('admin.services.procedures.update', [$service, $procedure]), $payload)->assertForbidden();

        $this->assertSame($procedure->name, $procedure->fresh()->name);
    }

    public function test_guests_are_redirected_from_procedure_administration(): void
    {
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();

        $this->get(route('admin.services.procedures.index', $service))->assertRedirect(route('login'));
        $this->get(route('admin.services.procedures.show', [$service, $procedure]))->assertRedirect(route('login'));
    }

    public function test_procedure_validation_enforces_required_unique_and_positive_values(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        Procedure::factory()->for($service)->create(['name' => 'Existing procedure']);

        $this->actingAs($admin)->post(route('admin.services.procedures.store', $service), [
            'name' => '',
            'duration_minutes' => 0,
            'is_active' => 'invalid',
        ])->assertSessionHasErrors(['name', 'duration_minutes', 'is_active']);

        $this->actingAs($admin)->post(route('admin.services.procedures.store', $service), [
            'name' => 'Existing procedure',
            'duration_minutes' => 15,
        ])->assertSessionHasErrors('name');
    }

    public function test_same_procedure_name_is_allowed_in_different_services(): void
    {
        $admin = User::factory()->admin()->create();
        $firstService = Service::factory()->create();
        $secondService = Service::factory()->create();
        Procedure::factory()->for($firstService)->create(['name' => 'Shared name']);

        $this->actingAs($admin)->post(route('admin.services.procedures.store', $secondService), [
            'name' => 'Shared name',
        ])->assertSessionHasNoErrors();

        $this->assertSame(2, Procedure::query()->where('name', 'Shared name')->count());
    }

    public function test_nested_binding_rejects_a_procedure_from_another_service(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $otherService = Service::factory()->create();
        $procedure = Procedure::factory()->for($otherService)->create();

        $this->actingAs($admin)
            ->get(route('admin.services.procedures.show', [$service, $procedure]))
            ->assertNotFound();

        $this->actingAs($admin)
            ->patch(route('admin.services.procedures.update', [$service, $procedure]), ['name' => 'Moved'])
            ->assertNotFound();
    }

    public function test_client_catalog_returns_only_active_procedures_of_active_services(): void
    {
        $client = User::factory()->create();
        $service = Service::factory()->create();
        $active = Procedure::factory()->for($service)->create(['name' => 'Active procedure']);
        Procedure::factory()->for($service)->inactive()->create(['name' => 'Hidden procedure']);

        $this->actingAs($client)->get(route('services.show', $service))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('service.procedures', 1)
                ->where('service.procedures.0.id', $active->id)
                ->missing('service.procedures.0.is_active'));

        $emptyService = Service::factory()->create();
        $this->actingAs($client)->get(route('services.show', $emptyService))
            ->assertInertia(fn ($page) => $page->where('service.procedures', []));

        $inactiveService = Service::factory()->inactive()->create();
        Procedure::factory()->for($inactiveService)->create();
        $this->actingAs($client)->get(route('services.show', $inactiveService))->assertForbidden();
    }

    public function test_procedure_policy_matches_the_role_matrix(): void
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->create();
        $service = Service::factory()->create();
        $active = Procedure::factory()->for($service)->create();
        $inactive = Procedure::factory()->for($service)->inactive()->create();

        $this->assertTrue($admin->can('viewAny', Procedure::class));
        $this->assertTrue($admin->can('view', $inactive));
        $this->assertTrue($admin->can('create', Procedure::class));
        $this->assertTrue($admin->can('update', $active));
        $this->assertTrue($client->can('view', $active));
        $this->assertFalse($client->can('view', $inactive));
        $this->assertFalse($client->can('create', Procedure::class));
        $this->assertFalse($client->can('update', $active));
    }

    public function test_procedure_seeder_is_idempotent(): void
    {
        $this->seed(ServiceSeeder::class);
        $this->seed(ProcedureSeeder::class);
        $this->seed(ProcedureSeeder::class);

        $this->assertSame(14, Procedure::query()->count());
        $this->assertDatabaseHas('procedures', [
            'name' => 'Lámpara infrarroja',
            'duration_minutes' => 15,
        ]);
    }

    public function test_procedure_routes_do_not_include_deletion(): void
    {
        $this->assertFalse(Route::has('admin.services.procedures.destroy'));
    }
}
