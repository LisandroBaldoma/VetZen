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
            'pet' => $pet,
            'petTreatments' => $pet->treatments()->withCount(['sessions', 'sessions as completed_sessions_count' => fn ($query) => $query->where('status', 'completed')])->latest()->get(),
        ]);
    }

    public function show(Pet $pet, PetTreatment $petTreatment): Response
    {
        Gate::authorize('view', $petTreatment);

        return Inertia::render('pets/treatments/show', [
            'pet' => $pet,
            'petTreatment' => $petTreatment->load(['procedureSnapshots', 'sessions' => fn ($query) => $query->orderBy('session_number')]),
        ]);
    }
}
