<?php

namespace Tests\Feature\Treatment;

use App\Models\Procedure;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
                ->has('treatments', 0));
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
}
