<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\PetTreatment;
use App\Models\Treatment;
use App\Models\TreatmentSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TreatmentAssignmentService
{
    /** @param array{planned_sessions: int, default_session_price: string, currency: string, starts_on: string, status: string, notes?: string|null} $attributes */
    public function assign(Pet $pet, Treatment $treatment, array $attributes): PetTreatment
    {
        return DB::transaction(function () use ($pet, $treatment, $attributes): PetTreatment {
            $treatment = Treatment::query()
                ->with(['service', 'procedures'])
                ->lockForUpdate()
                ->findOrFail($treatment->id);

            if (! $treatment->is_active || ! $treatment->service->is_active) {
                throw ValidationException::withMessages(['treatment_id' => __('The selected treatment is not available.')]);
            }

            if ($treatment->procedures->isEmpty() || $treatment->procedures->contains(
                fn ($procedure): bool => ! $procedure->is_active || $procedure->service_id !== $treatment->service_id,
            )) {
                throw ValidationException::withMessages(['treatment_id' => __('The selected treatment has no valid procedures for its service.')]);
            }

            $petTreatment = new PetTreatment([
                'treatment_name' => $treatment->name,
                'treatment_description' => $treatment->description,
                ...$attributes,
            ]);
            $petTreatment->pet()->associate($pet);
            $petTreatment->treatment()->associate($treatment);
            $petTreatment->save();

            $petTreatment->procedureSnapshots()->createMany(
                $treatment->procedures->map(fn ($procedure): array => [
                    'procedure_id' => $procedure->id,
                    'procedure_name' => $procedure->name,
                    'procedure_description' => $procedure->description,
                ])->all(),
            );

            $this->createPendingSessions($petTreatment, $petTreatment->planned_sessions, 1);

            return $petTreatment->load(['procedureSnapshots', 'sessions']);
        });
    }

    /** @param array{planned_sessions: int, default_session_price: string, currency: string, notes?: string|null} $attributes */
    public function updateConditions(PetTreatment $petTreatment, array $attributes): PetTreatment
    {
        return DB::transaction(function () use ($petTreatment, $attributes): PetTreatment {
            $locked = PetTreatment::query()->lockForUpdate()->findOrFail($petTreatment->id);
            $plannedSessions = $attributes['planned_sessions'];

            if (! in_array($locked->status, ['pending', 'in_progress'], true)) {
                throw ValidationException::withMessages(['planned_sessions' => __('This treatment cannot change its conditions in its current status.')]);
            }

            $completed = $locked->sessions()->where('status', 'completed')->count();
            if ($plannedSessions < max(1, $completed)) {
                throw ValidationException::withMessages(['planned_sessions' => __('Planned sessions cannot be lower than completed sessions.')]);
            }

            $coverage = $locked->sessions()->whereIn('status', ['pending', 'completed'])->count();
            $locked->update($attributes);

            if ($plannedSessions > $coverage) {
                $next = ((int) $locked->sessions()->max('session_number')) + 1;
                $this->createPendingSessions($locked, $plannedSessions - $coverage, $next);
            } elseif ($plannedSessions < $coverage) {
                $remove = $coverage - $plannedSessions;
                $pendingIds = $locked->sessions()->where('status', 'pending')->orderByDesc('session_number')->limit($remove)->pluck('id');

                if ($pendingIds->count() !== $remove) {
                    throw ValidationException::withMessages(['planned_sessions' => __('Only the latest pending sessions can be removed.')]);
                }

                $locked->sessions()->whereIn('id', $pendingIds)->delete();
            }

            return $locked->load('sessions');
        });
    }

    /** @param array{scheduled_at?: string|null, price: string, currency: string, status: string, notes?: string|null} $attributes */
    public function updateSession(TreatmentSession $session, array $attributes): TreatmentSession
    {
        return DB::transaction(function () use ($session, $attributes): TreatmentSession {
            $locked = TreatmentSession::query()->lockForUpdate()->findOrFail($session->id);
            $petTreatment = PetTreatment::query()->lockForUpdate()->findOrFail($locked->pet_treatment_id);

            if (! in_array($petTreatment->status, ['pending', 'in_progress'], true)) {
                throw ValidationException::withMessages(['status' => __('Only pending or in-progress treatments can have their sessions modified.')]);
            }

            if (in_array($locked->status, ['completed', 'cancelled'], true) && $attributes['status'] !== $locked->status) {
                throw ValidationException::withMessages(['status' => __('Completed or cancelled sessions cannot change status.')]);
            }

            $locked->update($attributes);

            $completed = $petTreatment->sessions()->where('status', 'completed')->count();
            $pending = $petTreatment->sessions()->where('status', 'pending')->count();

            if ($locked->status === 'cancelled' && $completed + $pending < $petTreatment->planned_sessions) {
                $next = ((int) $petTreatment->sessions()->max('session_number')) + 1;
                $this->createPendingSessions($petTreatment, 1, $next);
            }

            $status = $completed >= $petTreatment->planned_sessions
                ? 'completed'
                : ($completed > 0 ? 'in_progress' : 'pending');
            $petTreatment->update(['status' => $status]);

            return $locked->load('petTreatment');
        });
    }

    public function changeStatus(PetTreatment $petTreatment, string $requestedStatus): PetTreatment
    {
        return DB::transaction(function () use ($petTreatment, $requestedStatus): PetTreatment {
            $locked = PetTreatment::query()->lockForUpdate()->findOrFail($petTreatment->id);

            if (in_array($locked->status, ['completed', 'cancelled'], true)) {
                throw ValidationException::withMessages(['status' => __('Completed or cancelled treatments cannot change status.')]);
            }

            if ($requestedStatus === 'resume') {
                if ($locked->status !== 'suspended') {
                    throw ValidationException::withMessages(['status' => __('Only suspended treatments can be resumed.')]);
                }

                $completed = $locked->sessions()->where('status', 'completed')->count();
                $requestedStatus = $completed > 0 ? 'in_progress' : 'pending';
            }

            $locked->update(['status' => $requestedStatus]);

            return $locked;
        });
    }

    private function createPendingSessions(PetTreatment $petTreatment, int $count, int $firstNumber): void
    {
        $petTreatment->sessions()->createMany(
            collect(range($firstNumber, $firstNumber + $count - 1))->map(fn (int $number): array => [
                'session_number' => $number,
                'price' => $petTreatment->default_session_price,
                'currency' => $petTreatment->currency,
                'status' => 'pending',
            ])->all(),
        );
    }
}
