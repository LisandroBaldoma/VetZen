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

        return Inertia::render('admin/pets/treatments/index', [
            'pet' => $pet,
            'petTreatments' => $pet->treatments()->withCount(['sessions', 'sessions as completed_sessions_count' => fn ($query) => $query->where('status', 'completed')])->latest()->get(),
        ]);
    }

    public function create(Pet $pet): Response
    {
        Gate::authorize('create', PetTreatment::class);

        return Inertia::render('admin/pets/treatments/create', [
            'pet' => $pet,
            'treatments' => Treatment::query()->where('is_active', true)->with('service:id,name')->orderBy('name')->get(),
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

        return Inertia::render('admin/pets/treatments/show', [
            'pet' => $pet,
            'petTreatment' => $petTreatment->load(['procedureSnapshots', 'sessions' => fn ($query) => $query->orderBy('session_number')]),
        ]);
    }

    public function update(UpdatePetTreatmentRequest $request, Pet $pet, PetTreatment $petTreatment, TreatmentAssignmentService $service): RedirectResponse
    {
        $updated = $service->resize($petTreatment, $request->integer('planned_sessions'));
        $updated->update($request->safe()->only(['default_session_price', 'currency', 'notes']));
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Tratamiento actualizado.')]);

        return back();
    }

    public function updateStatus(UpdatePetTreatmentStatusRequest $request, Pet $pet, PetTreatment $petTreatment, TreatmentAssignmentService $service): RedirectResponse
    {
        $service->changeStatus($petTreatment, $request->string('status')->toString());
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Estado actualizado.')]);

        return back();
    }
}
