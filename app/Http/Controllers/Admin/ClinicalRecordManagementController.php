<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicalRecord\StoreClinicalRecordRequest;
use App\Http\Requests\ClinicalRecord\UpdateClinicalRecordRequest;
use App\Models\ClinicalRecord;
use App\Models\ClinicalRecordAudit;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ClinicalRecordManagementController extends Controller
{
    public function index(Request $request, Pet $pet): Response
    {
        Gate::authorize('viewAny', [ClinicalRecord::class, $pet]);
        $pet->load('client.user:id,name');

        $validated = $request->validate([
            'type' => ['nullable', 'string', 'in:'.implode(',', ClinicalRecord::TYPES)],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $records = $pet->clinicalRecords()
            ->with('creator:id,name')
            ->when($validated['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->when($validated['date_from'] ?? null, fn ($query, $date) => $query->whereDate('occurred_at', '>=', $date))
            ->when($validated['date_to'] ?? null, fn ($query, $date) => $query->whereDate('occurred_at', '<=', $date))
            ->orderByDesc('occurred_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (ClinicalRecord $record) => $this->recordSummaryData($record));

        return Inertia::render('admin/pets/medical-records/index', [
            'pet' => $this->petData($pet),
            'records' => $records,
            'filters' => [
                'type' => $validated['type'] ?? null,
                'date_from' => $validated['date_from'] ?? null,
                'date_to' => $validated['date_to'] ?? null,
            ],
            'types' => ClinicalRecord::TYPES,
        ]);
    }

    public function create(Request $request, Pet $pet): Response
    {
        Gate::authorize('create', [ClinicalRecord::class, $pet]);
        $pet->load('client.user:id,name');
        $type = $request->string('type')->toString();

        return Inertia::render('admin/pets/medical-records/create', [
            'pet' => $this->petData($pet),
            'types' => ClinicalRecord::TYPES,
            'prefill' => ['type' => in_array($type, ClinicalRecord::TYPES, true) ? $type : null],
        ]);
    }

    public function store(StoreClinicalRecordRequest $request, Pet $pet): RedirectResponse
    {
        $record = new ClinicalRecord($request->validated());
        $record->pet()->associate($pet);
        $record->creator()->associate($request->user());
        $record->updater()->associate($request->user());
        $record->save();
        $this->recordAudit($record, $request, 'created', null, $this->auditValues($record));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Registro clínico creado.')]);

        return to_route('admin.pets.medical-records.show', [$pet, $record]);
    }

    public function show(Pet $pet, ClinicalRecord $clinicalRecord): Response
    {
        $this->ensureRecordBelongsToPet($pet, $clinicalRecord);
        Gate::authorize('view', $clinicalRecord);
        $pet->load('client.user:id,name');
        $clinicalRecord->load(['creator:id,name', 'updater:id,name']);

        return Inertia::render('admin/pets/medical-records/show', [
            'pet' => $this->petData($pet),
            'record' => $this->recordDetailData($clinicalRecord),
        ]);
    }

    public function edit(Pet $pet, ClinicalRecord $clinicalRecord): Response
    {
        $this->ensureRecordBelongsToPet($pet, $clinicalRecord);
        Gate::authorize('update', $clinicalRecord);
        $pet->load('client.user:id,name');

        return Inertia::render('admin/pets/medical-records/edit', [
            'pet' => $this->petData($pet),
            'record' => $this->recordDetailData($clinicalRecord),
            'types' => ClinicalRecord::TYPES,
        ]);
    }

    public function update(UpdateClinicalRecordRequest $request, Pet $pet, ClinicalRecord $clinicalRecord): RedirectResponse
    {
        $this->ensureRecordBelongsToPet($pet, $clinicalRecord);
        $oldValues = $this->auditValues($clinicalRecord);
        $clinicalRecord->fill($request->validated());
        $clinicalRecord->updater()->associate($request->user());
        $clinicalRecord->save();
        $this->recordAudit($clinicalRecord, $request, 'updated', $oldValues, $this->auditValues($clinicalRecord));
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Registro clínico actualizado.')]);

        return to_route('admin.pets.medical-records.show', [$pet, $clinicalRecord]);
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
    private function recordSummaryData(ClinicalRecord $record): array
    {
        return [
            'id' => $record->id,
            'type' => $record->type,
            'title' => $record->title,
            'occurred_at' => $record->occurred_at->toISOString(),
            'is_visible_to_client' => $record->is_visible_to_client,
            'creator' => $record->creator ? ['name' => $record->creator->name] : null,
        ];
    }

    /** @return array<string, mixed> */
    private function recordDetailData(ClinicalRecord $record): array
    {
        return [
            ...$this->recordSummaryData($record),
            'content' => $record->content,
            'updater' => $record->updater ? ['name' => $record->updater->name] : null,
        ];
    }

    private function ensureRecordBelongsToPet(Pet $pet, ClinicalRecord $clinicalRecord): void
    {
        abort_unless($clinicalRecord->pet_id === $pet->id, 403);
    }

    /** @return array<string, mixed> */
    private function auditValues(ClinicalRecord $record): array
    {
        return collect($record->toArray())->only([
            'type',
            'title',
            'content',
            'occurred_at',
            'is_visible_to_client',
            'created_by',
            'updated_by',
        ])->all();
    }

    /**
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>  $newValues
     */
    private function recordAudit(ClinicalRecord $record, Request $request, string $action, ?array $oldValues, array $newValues): void
    {
        $audit = new ClinicalRecordAudit([
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
        $audit->user()->associate($request->user());
        $record->audits()->save($audit);
    }
}
