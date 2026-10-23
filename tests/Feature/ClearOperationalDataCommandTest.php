<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Pet;
use App\Models\Procedure;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ClearOperationalDataCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_clears_operational_data_and_preserves_accounts_clients_and_pets(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();
        $pet = Pet::factory()->for($client)->create();
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();
        $treatment = Treatment::factory()->for($service)->create();
        $now = now();

        DB::table('procedure_treatment')->insert(['procedure_id' => $procedure->id, 'treatment_id' => $treatment->id]);
        $petTreatmentId = DB::table('pet_treatments')->insertGetId([
            'pet_id' => $pet->id,
            'treatment_id' => $treatment->id,
            'treatment_name' => $treatment->name,
            'treatment_description' => $treatment->description,
            'planned_sessions' => 1,
            'default_session_price' => 1000,
            'currency' => 'ARS',
            'starts_on' => $now->toDateString(),
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $sessionId = DB::table('treatment_sessions')->insertGetId([
            'pet_treatment_id' => $petTreatmentId,
            'session_number' => 1,
            'price' => 1000,
            'currency' => 'ARS',
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $requestId = DB::table('service_requests')->insertGetId([
            'pet_id' => $pet->id,
            'service_id' => $service->id,
            'pet_treatment_id' => $petTreatmentId,
            'status' => 'resolved',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $recordId = DB::table('clinical_records')->insertGetId([
            'pet_id' => $pet->id,
            'treatment_session_id' => $sessionId,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'type' => 'session',
            'title' => 'Test record',
            'content' => 'Test content',
            'occurred_at' => $now,
            'is_visible_to_client' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('clinical_record_audits')->insert([
            'clinical_record_id' => $recordId,
            'user_id' => $user->id,
            'action' => 'created',
            'new_values' => '{}',
            'created_at' => $now,
        ]);
        $scheduleDateId = DB::table('appointment_schedule_dates')->insertGetId([
            'date' => $now->toDateString(),
            'capacity' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('appointment_schedule_blocks')->insert([
            'appointment_schedule_date_id' => $scheduleDateId,
            'starts_at' => '10:00:00',
            'ends_at' => '11:00:00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('appointment_schedule_starts')->insert([
            'appointment_schedule_date_id' => $scheduleDateId,
            'starts_at' => '10:00:00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('appointment_schedule_date_service')->insert([
            'appointment_schedule_date_id' => $scheduleDateId,
            'service_id' => $service->id,
        ]);
        $appointmentId = DB::table('appointments')->insertGetId([
            'pet_id' => $pet->id,
            'service_id' => $service->id,
            'service_request_id' => $requestId,
            'treatment_session_id' => $sessionId,
            'appointment_schedule_date_id' => $scheduleDateId,
            'requested_by' => $user->id,
            'service_name' => $service->name,
            'duration_minutes' => 60,
            'starts_at' => $now,
            'ends_at' => $now->copy()->addHour(),
            'status' => 'approved',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('appointment_status_histories')->insert([
            'appointment_id' => $appointmentId,
            'changed_by' => $user->id,
            'from_status' => 'pending',
            'to_status' => 'approved',
            'created_at' => $now,
        ]);
        DB::table('appointment_clinical_tasks')->insert([
            'appointment_id' => $appointmentId,
            'clinical_record_id' => $recordId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('appointment_reminder_deliveries')->insert([
            'appointment_id' => $appointmentId,
            'appointment_starts_at' => $now,
            'sent_at' => $now,
        ]);
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'test',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => '{}',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->artisan('vetzen:clear-operational-data', ['--force' => true])
            ->expectsOutput('Operational data cleared. Users, clients, pets, authentication data, roles, and the Simón Knowledge Base were preserved.')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('clients', ['id' => $client->id]);
        $this->assertDatabaseHas('pets', ['id' => $pet->id]);
        $this->assertSame(0, DB::table('services')->count());
        $this->assertSame(0, DB::table('pet_treatments')->count());
        $this->assertSame(0, DB::table('treatment_sessions')->count());
        $this->assertSame(0, DB::table('service_requests')->count());
        $this->assertSame(0, DB::table('clinical_records')->count());
        $this->assertSame(0, DB::table('appointments')->count());
        $this->assertSame(0, DB::table('appointment_schedule_dates')->count());
        $this->assertSame(0, DB::table('notifications')->count());
    }

    public function test_it_requires_confirmation_without_force(): void
    {
        $service = Service::factory()->create();

        $this->artisan('vetzen:clear-operational-data')
            ->expectsConfirmation('This permanently deletes operational data. Continue?', 'no')
            ->expectsOutput('No data was deleted.')
            ->assertFailed();

        $this->assertModelExists($service);
    }
}
