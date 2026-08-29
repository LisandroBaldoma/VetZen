<?php

namespace Tests\Feature\Treatment;

use App\Models\Pet;
use App\Models\PetTreatment;
use App\Models\Service;
use App\Services\TreatmentAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_freezes_catalog_and_generates_priced_sessions(): void
    {
        $service = Service::factory()->create();
        $procedure = $service->procedures()->create([
            'name' => 'Masotherapy',
            'description' => 'Original description',
            'duration_minutes' => 25,
        ]);
        $treatment = $service->treatments()->create([
            'name' => 'Lumbar therapy',
            'description' => 'Original treatment',
            'estimated_sessions' => 6,
        ]);
        $treatment->procedures()->attach($procedure);
        $pet = Pet::factory()->create();

        $assigned = app(TreatmentAssignmentService::class)->assign($pet, $treatment, [
            'planned_sessions' => 6,
            'default_session_price' => '18000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-09-01',
            'status' => 'pending',
            'notes' => null,
        ]);

        $this->assertSame('Lumbar therapy', $assigned->treatment_name);
        $this->assertSame('Masotherapy', $assigned->procedureSnapshots->sole()->procedure_name);
        $this->assertCount(6, $assigned->sessions);
        $this->assertSame([1, 2, 3, 4, 5, 6], $assigned->sessions->pluck('session_number')->all());
        $this->assertSame(['18000.00'], $assigned->sessions->pluck('price')->unique()->values()->all());

        $treatment->update(['name' => 'Changed catalog name']);
        $procedure->update(['name' => 'Changed procedure']);

        $this->assertSame('Lumbar therapy', $assigned->fresh()->treatment_name);
        $this->assertSame('Masotherapy', $assigned->procedureSnapshots()->sole()->procedure_name);
    }

    public function test_cancelling_session_generates_historical_replacement_without_changing_plan(): void
    {
        $assigned = $this->assignedTreatment(6);
        $third = $assigned->sessions()->where('session_number', 3)->firstOrFail();

        app(TreatmentAssignmentService::class)->updateSession($third, [
            'scheduled_at' => null,
            'price' => '18000.00',
            'currency' => 'ARS',
            'status' => 'cancelled',
            'notes' => 'Client unavailable',
        ]);

        $assigned->refresh();
        $this->assertSame(6, $assigned->planned_sessions);
        $this->assertSame('cancelled', $third->fresh()->status);
        $this->assertSame('18000.00', $third->fresh()->price);
        $this->assertSame([1, 2, 3, 4, 5, 6, 7], $assigned->sessions()->orderBy('session_number')->pluck('session_number')->all());
        $this->assertSame('pending', $assigned->sessions()->where('session_number', 7)->value('status'));
    }

    public function test_completed_sessions_drive_status_and_cancelled_sessions_do_not_count(): void
    {
        $assigned = $this->assignedTreatment(2);
        $manager = app(TreatmentAssignmentService::class);

        foreach ($assigned->sessions()->orderBy('session_number')->get() as $session) {
            $manager->updateSession($session, [
                'scheduled_at' => null,
                'price' => $session->price,
                'currency' => 'ARS',
                'status' => 'completed',
                'notes' => null,
            ]);
        }

        $this->assertSame('completed', $assigned->fresh()->status);
        $this->assertSame(2, $assigned->sessions()->where('status', 'completed')->count());
    }

    private function assignedTreatment(int $plannedSessions): PetTreatment
    {
        $service = Service::factory()->create();
        $procedure = $service->procedures()->create(['name' => 'Procedure']);
        $treatment = $service->treatments()->create([
            'name' => 'Treatment',
            'description' => 'Description',
            'estimated_sessions' => $plannedSessions,
        ]);
        $treatment->procedures()->attach($procedure);

        return app(TreatmentAssignmentService::class)->assign(Pet::factory()->create(), $treatment, [
            'planned_sessions' => $plannedSessions,
            'default_session_price' => '18000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-09-01',
            'status' => 'pending',
            'notes' => null,
        ]);
    }
}
