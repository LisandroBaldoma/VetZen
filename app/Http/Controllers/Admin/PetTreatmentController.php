<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PetTreatment\StorePetTreatmentRequest;
use App\Http\Requests\PetTreatment\UpdatePetTreatmentRequest;
use App\Http\Requests\PetTreatment\UpdatePetTreatmentStatusRequest;
use App\Models\Pet;
use App\Models\PetTreatment;
use App\Models\Treatment;
use App\Services\TreatmentAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PetTreatmentController extends Controller
{
    public function index(Pet $pet): Response
    {
        Gate::authorize('viewAny', PetTreatment::class);
        $pet->load('client.user:id,name');

        return Inertia::render('admin/pets/treatments/index', [
            'pet' => $this->petData($pet),
            'petTreatments' => $pet->treatments()
                ->withCount(['sessions as completed_sessions_count' => fn ($query) => $query->where('status', 'completed')])
                ->latest()
                ->get(['id', 'pet_id', 'treatment_name', 'planned_sessions', 'status', 'starts_on'])
                ->makeHidden('pet_id'),
        ]);
    }

    public function create(Pet $pet): Response
    {
        Gate::authorize('create', PetTreatment::class);
        $pet->load('client.user:id,name');

        return Inertia::render('admin/pets/treatments/create', [
            'pet' => $this->petData($pet),
            'treatments' => Treatment::query()
                ->where('is_active', true)
                ->whereHas('service', fn ($query) => $query->where('is_active', true))
                ->whereHas('procedures', fn ($query) => $query->where('is_active', true))
                ->whereDoesntHave('procedures', fn ($query) => $query->where('is_active', false))
                ->with('service:id,name')
                ->orderBy('name')
                ->get(['id', 'service_id', 'name', 'estimated_sessions']),
        ]);
    }

    public function store(StorePetTreatmentRequest $request, Pet $pet, TreatmentAssignmentService $service): RedirectResponse
    {
        $treatment = Treatment::query()->where('is_active', true)->findOrFail($request->integer('treatment_id'));
        $petTreatment = $service->assign($pet, $treatment, $request->safe()->except('treatment_id'));
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Tratamiento asignado.')]);

        return to_route('admin.pets.treatments.show', [$pet, $petTreatment]);
    }

    public function show(Pet $pet, PetTreatment $petTreatment): Response
    {
        Gate::authorize('view', $petTreatment);
        $pet->load('client.user:id,name');
        $petTreatment->load([
            'procedureSnapshots:id,pet_treatment_id,procedure_name,procedure_description',
            'sessions' => fn ($query) => $query->orderBy('session_number')->select(['id', 'pet_treatment_id', 'session_number', 'scheduled_at', 'price', 'currency', 'status', 'notes']),
        ]);

        return Inertia::render('admin/pets/treatments/show', [
            'pet' => $this->petData($pet),
            'petTreatment' => $this->treatmentData($petTreatment),
        ]);
    }

    public function update(UpdatePetTreatmentRequest $request, Pet $pet, PetTreatment $petTreatment, TreatmentAssignmentService $service): RedirectResponse
    {
        $service->updateConditions($petTreatment, $request->validated());
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Tratamiento actualizado.')]);

        return back();
    }

    public function updateStatus(UpdatePetTreatmentStatusRequest $request, Pet $pet, PetTreatment $petTreatment, TreatmentAssignmentService $service): RedirectResponse
    {
        $service->changeStatus($petTreatment, $request->string('status')->toString());
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Estado actualizado.')]);

        return back();
    }

    /** @return array<string, mixed> */
    private function petData(Pet $pet): array
    {
        return [
            'id' => $pet->id,
            'name' => $pet->name,
            'species' => $pet->species,
            'breed' => $pet->breed,
            'sex' => $pet->sex,
            'birth_date' => $pet->birth_date?->toDateString(),
            'weight' => $pet->weight,
            'color' => $pet->color,
            'notes' => $pet->notes,
            'has_photo' => $pet->photo !== null,
            'client' => ['id' => $pet->client->id, 'name' => $pet->client->user->name],
        ];
    }

    /** @return array<string, mixed> */
    private function treatmentData(PetTreatment $petTreatment): array
    {
        return [
            'id' => $petTreatment->id,
            'treatment_name' => $petTreatment->treatment_name,
            'treatment_description' => $petTreatment->treatment_description,
            'planned_sessions' => $petTreatment->planned_sessions,
            'default_session_price' => $petTreatment->default_session_price,
            'currency' => $petTreatment->currency,
            'starts_on' => $petTreatment->starts_on->toDateString(),
            'status' => $petTreatment->status,
            'notes' => $petTreatment->notes,
            'procedure_snapshots' => $petTreatment->procedureSnapshots->map->only(['id', 'procedure_name', 'procedure_description']),
            'sessions' => $petTreatment->sessions->map->only(['id', 'session_number', 'scheduled_at', 'price', 'currency', 'status', 'notes']),
        ];
    }
}
