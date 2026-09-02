<?php

namespace Tests\Feature\ClinicalRecord;

use App\Models\Client;
use App\Models\ClinicalRecord;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ClinicalRecordManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_client_can_view_all_records_for_an_owned_pet(): void
    {
        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $visibleRecord = ClinicalRecord::factory()->for($pet)->visibleToClient()->create([
            'occurred_at' => '2026-08-22 10:00:00',
        ]);
        $hiddenRecord = ClinicalRecord::factory()->for($pet)->hiddenFromClient()->create([
            'occurred_at' => '2026-08-21 10:00:00',
        ]);

        $this->actingAs($client->user)
            ->get(route('pets.medical-records.index', $pet))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('pets/medical-records/index')
                ->has('records', 2)
                ->where('records.0.id', $visibleRecord->id)
                ->where('records.1.id', $hiddenRecord->id)
                ->missing('records.0.content')
                ->missing('records.0.is_visible_to_client')
                ->missing('records.0.created_by')
                ->missing('pet.client_id')
                ->missing('pet.photo'));

        $this->actingAs($client->user)
            ->get(route('pets.medical-records.show', [$pet, $visibleRecord]))
            ->assertOk();

        $this->actingAs($client->user)
            ->get(route('pets.medical-records.show', [$pet, $hiddenRecord]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('pets/medical-records/show')
                ->where('record.id', $hiddenRecord->id)
                ->where('record.content', $hiddenRecord->content)
                ->missing('record.is_visible_to_client')
                ->missing('record.created_by'));
    }

    public function test_client_cannot_create_or_update_clinical_records(): void
    {
        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $record = ClinicalRecord::factory()->for($pet)->visibleToClient()->create();

        $this->actingAs($client->user)
            ->post(route('admin.pets.medical-records.store', $pet), $this->validPayload())
            ->assertForbidden();

        $this->actingAs($client->user)
            ->get(route('admin.pets.medical-records.create', $pet))
            ->assertForbidden();

        $this->actingAs($client->user)
            ->get(route('admin.pets.medical-records.edit', [$pet, $record]))
            ->assertForbidden();

        $this->actingAs($client->user)
            ->patch(route('admin.pets.medical-records.update', [$pet, $record]), $this->validPayload([
                'title' => 'Forbidden update',
            ]))
            ->assertForbidden();

        $this->assertSame($record->title, $record->fresh()->title);
    }

    public function test_client_cannot_access_another_clients_clinical_records_or_manipulate_routes(): void
    {
        $clientA = Client::factory()->create();
        $petA = Pet::factory()->for($clientA)->create();
        $clientB = Client::factory()->create();
        $petB = Pet::factory()->for($clientB)->create();
        $recordB = ClinicalRecord::factory()->for($petB)->hiddenFromClient()->create();

        $this->actingAs($clientA->user)
            ->get(route('pets.medical-records.index', $petB))
            ->assertForbidden();

        $this->actingAs($clientA->user)
            ->get(route('pets.medical-records.show', [$petB, $recordB]))
            ->assertForbidden();

        $this->actingAs($clientA->user)
            ->get(route('pets.medical-records.show', [$petA, $recordB]))
            ->assertForbidden();

        $this->actingAs($clientA->user)
            ->post(route('admin.pets.medical-records.store', $petB), $this->validPayload([
                'pet_id' => $petA->id,
            ]))
            ->assertForbidden();

        $this->actingAs($clientA->user)
            ->patch(route('admin.pets.medical-records.update', [$petB, $recordB]), $this->validPayload())
            ->assertForbidden();
    }

    public function test_admin_can_view_create_and_update_records_with_audit_and_server_owned_authorship(): void
    {
        $creator = User::factory()->admin()->create();
        $updater = User::factory()->admin()->create();
        $pet = Pet::factory()->create();
        $forgedUser = User::factory()->create();

        $this->actingAs($creator)
            ->get(route('admin.pets.medical-records.index', $pet))
            ->assertOk();

        $this->actingAs($creator)
            ->get(route('admin.pets.medical-records.create', $pet))
            ->assertOk();

        $this->actingAs($creator)
            ->post(route('admin.pets.medical-records.store', $pet), $this->validPayload([
                'pet_id' => Pet::factory()->create()->id,
                'client_id' => Client::factory()->create()->id,
                'created_by' => $forgedUser->id,
                'updated_by' => $forgedUser->id,
                'user_id' => $forgedUser->id,
                'role' => 'admin',
                'permissions' => ['clinical-records.update'],
            ]))
            ->assertSessionHasNoErrors();

        $record = ClinicalRecord::query()->where('title', 'Initial consultation')->firstOrFail();

        $this->assertSame($pet->id, $record->pet_id);
        $this->assertSame($creator->id, $record->created_by);
        $this->assertSame($creator->id, $record->updated_by);
        $this->assertDatabaseHas('clinical_record_audits', [
            'clinical_record_id' => $record->id,
            'user_id' => $creator->id,
            'action' => 'created',
        ]);

        $createdAudit = $record->audits()->firstOrFail();
        $this->assertNull($createdAudit->old_values);
        $this->assertSame('Initial consultation', $createdAudit->new_values['title']);
        $this->assertSame($creator->id, $createdAudit->new_values['created_by']);

        $this->actingAs($updater)
            ->get(route('admin.pets.medical-records.show', [$pet, $record]))
            ->assertOk();

        $this->actingAs($updater)
            ->patch(route('admin.pets.medical-records.update', [$pet, $record]), $this->validPayload([
                'title' => 'Updated consultation',
                'content' => 'The patient responded well.',
                'is_visible_to_client' => false,
                'created_by' => $forgedUser->id,
                'updated_by' => $forgedUser->id,
                'pet_id' => Pet::factory()->create()->id,
            ]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.pets.medical-records.show', [$pet, $record]));

        $record->refresh();
        $this->assertSame($pet->id, $record->pet_id);
        $this->assertSame($creator->id, $record->created_by);
        $this->assertSame($updater->id, $record->updated_by);
        $this->assertSame('Updated consultation', $record->title);
        $this->assertFalse($record->is_visible_to_client);

        $updatedAudit = $record->audits()->where('action', 'updated')->firstOrFail();
        $this->assertSame($updater->id, $updatedAudit->user_id);
        $this->assertSame('Initial consultation', $updatedAudit->old_values['title']);
        $this->assertSame($creator->id, $updatedAudit->old_values['updated_by']);
        $this->assertSame('Updated consultation', $updatedAudit->new_values['title']);
        $this->assertSame($updater->id, $updatedAudit->new_values['updated_by']);
        $this->assertTrue($updatedAudit->old_values['is_visible_to_client']);
        $this->assertFalse($updatedAudit->new_values['is_visible_to_client']);

        $this->actingAs($pet->client->user)
            ->get(route('pets.medical-records.show', [$pet, $record]))
            ->assertOk();
    }

    public function test_admin_clinical_pages_receive_minimal_context_and_metadata(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Dra. Laura']);
        $pet = Pet::factory()->create(['name' => 'Mora']);
        $record = ClinicalRecord::factory()->for($pet)->hiddenFromClient()->create([
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
            'title' => 'Evaluación inicial',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pets.medical-records.index', $pet))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/pets/medical-records/index')
                ->where('pet.name', 'Mora')
                ->where('pet.client.name', $pet->client->user->name)
                ->where('records.0.id', $record->id)
                ->where('records.0.creator.name', 'Dra. Laura')
                ->where('records.0.is_visible_to_client', false)
                ->missing('pet.client_id')
                ->missing('pet.client.user')
                ->missing('records.0.content')
                ->missing('records.0.created_by')
                ->missing('records.0.creator.email'));

        $this->actingAs($admin)
            ->get(route('admin.pets.medical-records.create', $pet))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/pets/medical-records/create')
                ->where('pet.name', 'Mora')
                ->where('types', ClinicalRecord::TYPES)
                ->missing('pet.client_id')
                ->missing('pet.client.user'));

        $this->actingAs($admin)
            ->get(route('admin.pets.medical-records.edit', [$pet, $record]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/pets/medical-records/edit')
                ->where('record.id', $record->id)
                ->where('record.content', $record->content)
                ->missing('record.pet_id')
                ->missing('record.created_by')
                ->missing('record.created_at'));

        $this->actingAs($admin)
            ->get(route('admin.pets.medical-records.show', [$pet, $record]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/pets/medical-records/show')
                ->where('record.id', $record->id)
                ->where('record.creator.name', 'Dra. Laura')
                ->where('record.updater.name', 'Dra. Laura')
                ->missing('record.pet_id')
                ->missing('record.creator.email'));
    }

    public function test_admin_clinical_record_create_prefills_an_allowed_type_without_persisting(): void
    {
        $admin = User::factory()->admin()->create();
        $pet = Pet::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.pets.medical-records.create', [
                'pet' => $pet,
                'type' => 'evolution',
            ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/pets/medical-records/create')
                ->where('pet.id', $pet->id)
                ->where('prefill.type', 'evolution'));

        $this->assertDatabaseCount('clinical_records', 0);

        $this->actingAs($admin)
            ->get(route('admin.pets.medical-records.create', [
                'pet' => $pet,
                'type' => 'diagnosis',
            ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('prefill.type', null));
    }

    public function test_admin_cannot_use_a_record_under_a_different_pet_route(): void
    {
        $admin = User::factory()->admin()->create();
        $pet = Pet::factory()->create();
        $otherPet = Pet::factory()->create();
        $record = ClinicalRecord::factory()->for($otherPet)->create();

        $this->actingAs($admin)
            ->get(route('admin.pets.medical-records.show', [$pet, $record]))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.pets.medical-records.edit', [$pet, $record]))
            ->assertForbidden();

        $this->actingAs($admin)
            ->patch(route('admin.pets.medical-records.update', [$pet, $record]), $this->validPayload())
            ->assertForbidden();
    }

    public function test_clinical_record_fields_are_validated(): void
    {
        $admin = User::factory()->admin()->create();
        $pet = Pet::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.pets.medical-records.store', $pet), [])
            ->assertSessionHasErrors(['type', 'title', 'content', 'occurred_at', 'is_visible_to_client']);

        $this->actingAs($admin)
            ->post(route('admin.pets.medical-records.store', $pet), $this->validPayload([
                'type' => 'diagnosis',
                'title' => str_repeat('a', 256),
                'occurred_at' => now()->addMinute()->toDateTimeString(),
                'is_visible_to_client' => 'not-a-boolean',
            ]))
            ->assertSessionHasErrors(['type', 'title', 'occurred_at', 'is_visible_to_client']);

        $this->assertDatabaseCount('clinical_records', 0);
        $this->assertDatabaseCount('clinical_record_audits', 0);
    }

    public function test_history_is_ordered_by_clinical_date_then_creation_date(): void
    {
        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $olderClinicalEvent = ClinicalRecord::factory()->for($pet)->visibleToClient()->create([
            'occurred_at' => '2026-08-20 10:00:00',
            'created_at' => '2026-08-23 10:00:00',
        ]);
        $laterCreatedTie = ClinicalRecord::factory()->for($pet)->visibleToClient()->create([
            'occurred_at' => '2026-08-22 10:00:00',
            'created_at' => '2026-08-22 12:00:00',
        ]);
        $earlierCreatedTie = ClinicalRecord::factory()->for($pet)->hiddenFromClient()->create([
            'occurred_at' => '2026-08-22 10:00:00',
            'created_at' => '2026-08-22 11:00:00',
        ]);

        $this->actingAs($client->user)
            ->get(route('pets.medical-records.index', $pet))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('records.0.id', $laterCreatedTie->id)
                ->where('records.1.id', $earlierCreatedTie->id)
                ->where('records.2.id', $olderClinicalEvent->id));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return [
            'type' => 'consultation',
            'title' => 'Initial consultation',
            'content' => 'The patient was evaluated.',
            'occurred_at' => '2026-08-20 10:00:00',
            'is_visible_to_client' => true,
            ...$overrides,
        ];
    }
}
