<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicalRecord\StoreClinicalRecordRequest;
use App\Http\Requests\ClinicalRecord\UpdateClinicalRecordRequest;
use App\Models\ClinicalRecord;
use App\Models\ClinicalRecordAudit;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ClinicalRecordController extends Controller
{
    public function index(Pet $pet): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('view', $pet);
        Gate::authorize('viewAny', ClinicalRecord::class);

        return Inertia::render('admin/pets/medical-records/index', [
            'pet' => $pet->load('client.user'),
            'records' => $pet->clinicalRecords()
                ->with(['creator:id,name', 'updater:id,name'])
                ->orderByDesc('occurred_at')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function create(Pet $pet): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('create', [ClinicalRecord::class, $pet]);

        return Inertia::render('admin/pets/medical-records/create', [
            'pet' => $pet->load('client.user'),
            'types' => ClinicalRecord::TYPES,
        ]);
    }

    public function store(StoreClinicalRecordRequest $request, Pet $pet): RedirectResponse
    {
        $user = $request->user();

        $clinicalRecord = DB::transaction(function () use ($request, $pet, $user): ClinicalRecord {
            $clinicalRecord = $pet->clinicalRecords()->make($request->validated());
            $clinicalRecord->creator()->associate($user);
            $clinicalRecord->updater()->associate($user);
            $clinicalRecord->save();

            $this->recordAudit($clinicalRecord, $user, 'created', null, $this->snapshot($clinicalRecord));

            return $clinicalRecord;
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Clinical record created.')]);

        return to_route('admin.pets.medical-records.show', [$pet, $clinicalRecord]);
    }

    public function show(Pet $pet, ClinicalRecord $clinicalRecord): Response
    {
        $this->authorizeAdmin();
        $this->ensureRecordBelongsToPet($clinicalRecord, $pet);
        Gate::authorize('view', $clinicalRecord);

        return Inertia::render('admin/pets/medical-records/show', [
            'pet' => $pet->load('client.user'),
            'record' => $clinicalRecord->load(['creator:id,name', 'updater:id,name']),
        ]);
    }

    public function edit(Pet $pet, ClinicalRecord $clinicalRecord): Response
    {
        $this->authorizeAdmin();
        $this->ensureRecordBelongsToPet($clinicalRecord, $pet);
        Gate::authorize('update', $clinicalRecord);

        return Inertia::render('admin/pets/medical-records/edit', [
            'pet' => $pet->load('client.user'),
            'record' => $clinicalRecord,
            'types' => ClinicalRecord::TYPES,
        ]);
    }

    public function update(
        UpdateClinicalRecordRequest $request,
        Pet $pet,
        ClinicalRecord $clinicalRecord,
    ): RedirectResponse {
        $this->ensureRecordBelongsToPet($clinicalRecord, $pet);
        $user = $request->user();

        DB::transaction(function () use ($request, $clinicalRecord, $user): void {
            $oldValues = $this->snapshot($clinicalRecord);
            $clinicalRecord->fill($request->validated());
            $clinicalRecord->updater()->associate($user);
            $clinicalRecord->save();

            $this->recordAudit($clinicalRecord, $user, 'updated', $oldValues, $this->snapshot($clinicalRecord));
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Clinical record updated.')]);

        return to_route('admin.pets.medical-records.show', [$pet, $clinicalRecord]);
    }

    /**
     * @return array<string, bool|int|string|null>
     */
    private function snapshot(ClinicalRecord $clinicalRecord): array
    {
        return [
            'pet_id' => $clinicalRecord->pet_id,
            'created_by' => $clinicalRecord->created_by,
            'updated_by' => $clinicalRecord->updated_by,
            'type' => $clinicalRecord->type,
            'title' => $clinicalRecord->title,
            'content' => $clinicalRecord->content,
            'occurred_at' => $clinicalRecord->occurred_at->toISOString(),
            'is_visible_to_client' => $clinicalRecord->is_visible_to_client,
        ];
    }

    /**
     * @param  array<string, bool|int|string|null>|null  $oldValues
     * @param  array<string, bool|int|string|null>  $newValues
     */
    private function recordAudit(
        ClinicalRecord $clinicalRecord,
        User $user,
        string $action,
        ?array $oldValues,
        array $newValues,
    ): void {
        $audit = new ClinicalRecordAudit([
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
        $audit->user()->associate($user);
        $clinicalRecord->audits()->save($audit);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
    }

    private function ensureRecordBelongsToPet(ClinicalRecord $clinicalRecord, Pet $pet): void
    {
        abort_unless($clinicalRecord->pet_id === $pet->id, 403);
    }
}
