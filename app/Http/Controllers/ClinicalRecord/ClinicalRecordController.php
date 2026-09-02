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
            ->orderByDesc('occurred_at')
            ->orderByDesc('created_at')
            ->get(['id', 'type', 'title', 'occurred_at', 'created_at'])
            ->map(fn (ClinicalRecord $clinicalRecord): array => $this->recordSummaryData($clinicalRecord));

        return Inertia::render('pets/medical-records/index', [
            'pet' => $this->petData($pet),
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
            'pet' => $this->petData($pet),
            'record' => [
                ...$this->recordSummaryData($clinicalRecord),
                'content' => $clinicalRecord->content,
            ],
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

    /**
     * @return array<string, bool|int|string|null>
     */
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

    /**
     * @return array<string, int|string>
     */
    private function recordSummaryData(ClinicalRecord $clinicalRecord): array
    {
        return [
            'id' => $clinicalRecord->id,
            'type' => $clinicalRecord->type,
            'title' => $clinicalRecord->title,
            'occurred_at' => $clinicalRecord->occurred_at->toISOString(),
        ];
    }
}
