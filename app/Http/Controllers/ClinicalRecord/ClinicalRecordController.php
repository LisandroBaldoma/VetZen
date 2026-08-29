<?php

namespace App\Http\Controllers\ClinicalRecord;

use App\Http\Controllers\Controller;
use App\Models\ClinicalRecord;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ClinicalRecordController extends Controller
{
    public function index(Request $request, Pet $pet): Response
    {
        $this->authorizeClient($request);
        Gate::authorize('view', $pet);
        Gate::authorize('viewAny', ClinicalRecord::class);

        $records = $pet->clinicalRecords()
            ->where('is_visible_to_client', true)
            ->orderByDesc('occurred_at')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('pets/medical-records/index', [
            'pet' => $pet,
            'records' => $records,
        ]);
    }

    public function show(Request $request, Pet $pet, ClinicalRecord $clinicalRecord): Response
    {
        $this->authorizeClient($request);
        $this->ensureRecordBelongsToPet($clinicalRecord, $pet);
        Gate::authorize('view', $pet);
        Gate::authorize('view', $clinicalRecord);

        return Inertia::render('pets/medical-records/show', [
            'pet' => $pet,
            'record' => $clinicalRecord,
        ]);
    }

    private function authorizeClient(Request $request): void
    {
        abort_unless($request->user()?->hasRole('client'), 403);
    }

    private function ensureRecordBelongsToPet(ClinicalRecord $clinicalRecord, Pet $pet): void
    {
        abort_unless($clinicalRecord->pet_id === $pet->id, 403);
    }
}
