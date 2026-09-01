<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Pet;
use App\Models\PetTreatment;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\TreatmentAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_client_without_resources_receives_empty_dashboard_collections(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/dashboard')
                ->where('pets', [])
                ->where('pendingRequests', [])
                ->where('activeTreatments', [])
                ->missing('client')
                ->missing('pendingRequestsCount')
                ->missing('requests'));
    }

    public function test_client_role_without_client_record_receives_empty_dashboard_collections(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/dashboard')
                ->where('pets', [])
                ->where('pendingRequests', [])
                ->where('activeTreatments', []));
    }

    public function test_admin_receives_pending_count_and_prioritized_recent_requests(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['name' => 'Fisioterapia']);
        $pet = Pet::factory()->create(['name' => 'Mora']);

        $olderPending = ServiceRequest::factory()->for($pet)->for($service)->create([
            'status' => 'pending',
            'notes' => 'Private note',
            'created_at' => now()->subDays(3),
        ]);
        $newerPending = ServiceRequest::factory()->for($pet)->for($service)->create([
            'status' => 'pending',
            'created_at' => now()->subDay(),
        ]);
        $recentResolved = ServiceRequest::factory()->for($pet)->for($service)->create([
            'status' => 'resolved',
            'created_at' => now(),
        ]);
        ServiceRequest::factory()->for($pet)->for($service)->create([
            'status' => 'cancelled',
            'created_at' => now()->subDays(4),
        ]);
        ServiceRequest::factory()->for($pet)->for($service)->create([
            'status' => 'resolved',
            'created_at' => now()->subDays(5),
        ]);
        ServiceRequest::factory()->for($pet)->for($service)->create([
            'status' => 'resolved',
            'created_at' => now()->subDays(6),
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('admin/dashboard')
                ->where('pendingRequestsCount', 2)
                ->has('requests', 5)
                ->where('requests.0.id', $newerPending->id)
                ->where('requests.1.id', $olderPending->id)
                ->where('requests.2.id', $recentResolved->id)
                ->where('requests.0.pet.name', 'Mora')
                ->where('requests.0.service.name', 'Fisioterapia')
                ->missing('requests.0.notes')
                ->missing('client')
                ->missing('pets')
                ->missing('pendingRequests')
                ->missing('activeTreatments'));
    }

    public function test_admin_without_requests_receives_empty_dashboard_state(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('admin/dashboard')
                ->where('pendingRequestsCount', 0)
                ->where('requests', []));
    }

    public function test_client_dashboard_only_contains_owned_pending_and_active_resources(): void
    {
        $clientA = Client::factory()->create();
        $clientB = Client::factory()->create();
        $petA = Pet::factory()->for($clientA)->create(['name' => 'Mora']);
        $petB = Pet::factory()->for($clientB)->create(['name' => 'Ajena']);
        $service = Service::factory()->create(['name' => 'Fisioterapia']);

        $ownedRequest = ServiceRequest::factory()->for($petA)->for($service)->create([
            'status' => 'pending',
            'notes' => 'Sensitive note',
        ]);
        ServiceRequest::factory()->for($petA)->for($service)->create(['status' => 'resolved']);
        ServiceRequest::factory()->for($petB)->for($service)->create([
            'status' => 'pending',
            'created_at' => now()->addMinute(),
        ]);

        $ownedTreatment = $this->assignTreatment($petA, 'in_progress', 2);
        $ownedTreatment->sessions()->firstOrFail()->update(['status' => 'completed']);
        $suspendedTreatment = $this->assignTreatment($petA, 'suspended');
        $this->assignTreatment($petA, 'completed');
        $this->assignTreatment($petA, 'cancelled');
        $this->assignTreatment($petB, 'in_progress');

        $this->actingAs($clientA->user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/dashboard')
                ->has('pets', 1)
                ->where('pets.0.id', $petA->id)
                ->where('pets.0.name', 'Mora')
                ->missing('pets.0.client_id')
                ->has('pendingRequests', 1)
                ->where('pendingRequests.0.id', $ownedRequest->id)
                ->missing('pendingRequests.0.notes')
                ->has('activeTreatments', 2)
                ->where('activeTreatments.0.id', $suspendedTreatment->id)
                ->where('activeTreatments.1.id', $ownedTreatment->id)
                ->where('activeTreatments.1.completedSessions', 1)
                ->where('activeTreatments.1.plannedSessions', 2)
                ->missing('activeTreatments.0.default_session_price')
                ->missing('activeTreatments.0.sessions')
                ->missing('pendingRequestsCount')
                ->missing('requests'));
    }

    public function test_client_dashboard_applies_collection_limits_after_ownership(): void
    {
        $client = Client::factory()->create();
        $otherClient = Client::factory()->create();
        $service = Service::factory()->create();
        $ownedPets = Pet::factory()->for($client)->count(7)->create();
        $otherPet = Pet::factory()->for($otherClient)->create();

        ServiceRequest::factory()->for($otherPet)->for($service)->count(6)->create([
            'status' => 'pending',
            'created_at' => now()->addDay(),
        ]);
        ServiceRequest::factory()->for($ownedPets->first())->for($service)->count(6)->create([
            'status' => 'pending',
        ]);

        foreach (range(1, 6) as $position) {
            $this->assignTreatment(
                $ownedPets->first(),
                'pending',
                1,
                now()->subDays($position)->toDateString(),
            );
        }
        $this->assignTreatment($otherPet, 'pending', 1, now()->addDay()->toDateString());

        $this->actingAs($client->user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('pets', 6)
                ->has('pendingRequests', 5)
                ->has('activeTreatments', 5));
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
                ->where('pets', [])
                ->where('pendingRequests', [])
                ->where('activeTreatments', []));
    }

    public function test_user_without_an_approved_role_cannot_access_a_dashboard(): void
    {
        $user = User::factory()->create();
        $user->syncRoles([]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
    }

    private function assignTreatment(
        Pet $pet,
        string $status,
        int $plannedSessions = 1,
        ?string $startsOn = null,
    ): PetTreatment {
        $service = Service::factory()->create();
        $procedure = $service->procedures()->create(['name' => fake()->unique()->words(3, true)]);
        $treatment = $service->treatments()->create([
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->paragraph(),
            'estimated_sessions' => $plannedSessions,
        ]);
        $treatment->procedures()->attach($procedure);

        return app(TreatmentAssignmentService::class)->assign($pet, $treatment, [
            'planned_sessions' => $plannedSessions,
            'default_session_price' => '18000.00',
            'currency' => 'ARS',
            'starts_on' => $startsOn ?? now()->toDateString(),
            'status' => $status,
            'notes' => null,
        ]);
    }
}
