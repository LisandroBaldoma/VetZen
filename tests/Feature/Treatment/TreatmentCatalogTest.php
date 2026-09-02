<?php

namespace Tests\Feature\Treatment;

use App\Models\Procedure;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class TreatmentCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_global_catalog_when_it_is_empty(): void
    {
        $this->withoutVite();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.treatments.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('admin/treatments/index')
                ->has('services', 0)
                ->has('creationServices', 0)
                ->has('treatments.data', 0)
                ->where('filters.search', '')
                ->where('filters.service', '')
                ->where('filters.status', ''));
    }

    public function test_admin_creates_treatment_with_active_procedures_from_same_service(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();

        $this->actingAs($admin)->post(route('admin.services.treatments.store', $service), [
            'name' => 'Plan inicial', 'description' => 'Descripción', 'estimated_sessions' => 6,
            'procedure_ids' => [$procedure->id], 'is_active' => true,
        ])->assertRedirect(route('admin.services.treatments.index', $service));

        $treatment = Treatment::query()->sole();
        $this->assertSame(6, $treatment->estimated_sessions);
        $this->assertTrue($treatment->procedures()->sole()->is($procedure));
    }

    public function test_client_cannot_manage_catalog_and_cross_service_procedure_is_rejected(): void
    {
        $client = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $otherProcedure = Procedure::factory()->create();
        $payload = ['name' => 'Plan', 'description' => 'Descripción', 'estimated_sessions' => 2, 'procedure_ids' => [$otherProcedure->id]];

        $this->actingAs($client)->post(route('admin.services.treatments.store', $service), $payload)->assertForbidden();
        $this->actingAs($admin)->post(route('admin.services.treatments.store', $service), $payload)->assertSessionHasErrors('procedure_ids.0');
        $this->assertSame(0, Treatment::query()->count());
    }

    public function test_global_catalog_filters_templates_and_paginates_with_query_string(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['name' => 'Fisioterapia']);
        $otherService = Service::factory()->create();
        Procedure::factory()->for($service)->create();

        foreach (range(1, 11) as $number) {
            Treatment::factory()->for($service)->create([
                'name' => 'Plan lumbar '.$number,
                'is_active' => true,
            ]);
        }
        Treatment::factory()->for($service)->create(['name' => 'Plan lumbar inactivo', 'is_active' => false]);
        Treatment::factory()->for($otherService)->create(['name' => 'Plan lumbar externo', 'is_active' => true]);

        $this->actingAs($admin)->get(route('admin.treatments.index', [
            'search' => 'Plan lumbar',
            'service' => $service->id,
            'status' => 'active',
        ]))->assertOk()->assertInertia(fn ($page) => $page
            ->has('treatments.data', 10)
            ->where('treatments.total', 11)
            ->where('treatments.last_page', 2)
            ->where('filters.search', 'Plan lumbar')
            ->where('filters.service', (string) $service->id)
            ->where('filters.status', 'active')
            ->where('treatments.links.2.url', fn ($url) => str_contains($url, 'search=Plan%20lumbar')
                && str_contains($url, 'service='.$service->id)
                && str_contains($url, 'status=active')));
    }

    public function test_global_catalog_rejects_invalid_filters(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.treatments.index', [
            'service' => 999999,
            'status' => 'archived',
        ]))->assertSessionHasErrors(['service', 'status']);
    }

    public function test_admin_can_change_only_template_status(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $treatment = Treatment::factory()->for($service)->create([
            'name' => 'Plan estable',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.treatments.index'))
            ->patch(route('admin.services.treatments.status.update', [$service, $treatment]), ['is_active' => false])
            ->assertRedirect(route('admin.treatments.index'))
            ->assertSessionHasNoErrors();

        $treatment->refresh();
        $this->assertFalse($treatment->is_active);
        $this->assertSame('Plan estable', $treatment->name);
    }

    public function test_template_update_requires_description_with_store_maximum(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();
        $treatment = Treatment::factory()->for($service)->create();
        $treatment->procedures()->attach($procedure);
        $payload = [
            'name' => $treatment->name,
            'estimated_sessions' => 4,
            'procedure_ids' => [$procedure->id],
            'is_active' => true,
        ];

        $this->actingAs($admin)->patch(route('admin.services.treatments.update', [$service, $treatment]), $payload)
            ->assertSessionHasErrors('description');
        $this->actingAs($admin)->patch(route('admin.services.treatments.update', [$service, $treatment]), [
            ...$payload,
            'description' => str_repeat('a', 5001),
        ])->assertSessionHasErrors('description');
    }

    public function test_edit_keeps_associated_inactive_procedure_but_rejects_another_inactive_procedure(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $associated = Procedure::factory()->for($service)->inactive()->create(['name' => 'Histórico']);
        $otherInactive = Procedure::factory()->for($service)->inactive()->create(['name' => 'No disponible']);
        $treatment = Treatment::factory()->for($service)->create();
        $treatment->procedures()->attach($associated);

        $this->actingAs($admin)->get(route('admin.services.treatments.edit', [$service, $treatment]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('procedures', 1)
                ->where('procedures.0.id', $associated->id)
                ->where('procedures.0.is_active', false));

        $payload = [
            'name' => $treatment->name,
            'description' => 'Descripción actualizada',
            'estimated_sessions' => 5,
            'is_active' => true,
        ];
        $this->actingAs($admin)->patch(route('admin.services.treatments.update', [$service, $treatment]), [
            ...$payload,
            'procedure_ids' => [$associated->id],
        ])->assertSessionHasNoErrors();
        $this->assertTrue($treatment->procedures()->sole()->is($associated));

        $this->actingAs($admin)->patch(route('admin.services.treatments.update', [$service, $treatment]), [
            ...$payload,
            'procedure_ids' => [$associated->id, $otherInactive->id],
        ])->assertSessionHasErrors('procedure_ids.1');
        $this->assertTrue($treatment->procedures()->sole()->is($associated));
    }

    public function test_new_template_is_blocked_for_inactive_service_and_without_active_procedures(): void
    {
        $admin = User::factory()->admin()->create();
        $inactiveService = Service::factory()->inactive()->create();
        $inactiveProcedure = Procedure::factory()->for($inactiveService)->create();
        $activeService = Service::factory()->create();

        $this->actingAs($admin)->get(route('admin.services.treatments.create', $inactiveService))->assertForbidden();
        $this->actingAs($admin)->post(route('admin.services.treatments.store', $inactiveService), [
            'name' => 'No permitido',
            'description' => 'Descripción',
            'estimated_sessions' => 2,
            'procedure_ids' => [$inactiveProcedure->id],
        ])->assertForbidden();

        $this->actingAs($admin)->get(route('admin.services.treatments.create', $activeService))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->where('procedures', []));
        $this->assertTrue(Route::has('admin.services.procedures.create'));
        $this->assertSame(0, Treatment::query()->count());
    }
}
