<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetTreatment;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PetTreatmentController extends Controller
{
    public function index(Pet $pet): Response
    {
        Gate::authorize('view', $pet);

        return Inertia::render('pets/treatments/index', [
            'pet' => $this->petData($pet),
            'petTreatments' => $pet->treatments()
                ->withCount(['sessions as completed_sessions_count' => fn ($query) => $query->where('status', 'completed')])
                ->latest()
                ->get(['id', 'pet_id', 'treatment_name', 'planned_sessions', 'status', 'starts_on'])
                ->makeHidden('pet_id'),
        ]);
    }

    public function show(Pet $pet, PetTreatment $petTreatment): Response
    {
        Gate::authorize('view', $petTreatment);
        $petTreatment->load([
            'procedureSnapshots:id,pet_treatment_id,procedure_name,procedure_description',
            'sessions' => fn ($query) => $query->orderBy('session_number')->select(['id', 'pet_treatment_id', 'session_number', 'scheduled_at', 'price', 'currency', 'status', 'notes']),
        ]);

        return Inertia::render('pets/treatments/show', [
            'pet' => $this->petData($pet),
            'petTreatment' => $this->treatmentData($petTreatment),
        ]);
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
