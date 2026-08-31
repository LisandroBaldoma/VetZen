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
            $treatment->load('procedures');

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

    public function resize(PetTreatment $petTreatment, int $plannedSessions): PetTreatment
    {
        return DB::transaction(function () use ($petTreatment, $plannedSessions): PetTreatment {
            $locked = PetTreatment::query()->lockForUpdate()->findOrFail($petTreatment->id);

            if (! in_array($locked->status, ['pending', 'in_progress'], true)) {
                throw ValidationException::withMessages(['planned_sessions' => __('This treatment cannot change its planned sessions in its current status.')]);
            }

            $completed = $locked->sessions()->where('status', 'completed')->count();
            if ($plannedSessions < max(1, $completed)) {
                throw ValidationException::withMessages(['planned_sessions' => __('Planned sessions cannot be lower than completed sessions.')]);
            }

            $coverage = $locked->sessions()->whereIn('status', ['pending', 'completed'])->count();
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

            $locked->update(['planned_sessions' => $plannedSessions]);

            return $locked->load('sessions');
        });
    }

    /** @param array{scheduled_at?: string|null, price: string, currency: string, status: string, notes?: string|null} $attributes */
    public function updateSession(TreatmentSession $session, array $attributes): TreatmentSession
    {
        return DB::transaction(function () use ($session, $attributes): TreatmentSession {
            $locked = TreatmentSession::query()->lockForUpdate()->findOrFail($session->id);
            $petTreatment = PetTreatment::query()->lockForUpdate()->findOrFail($locked->pet_treatment_id);

            if (in_array($petTreatment->status, ['suspended', 'cancelled'], true)) {
                throw ValidationException::withMessages(['status' => __('Suspended or cancelled treatments cannot have their sessions modified.')]);
            }

            $locked->update($attributes);

            $completed = $petTreatment->sessions()->where('status', 'completed')->count();
            $pending = $petTreatment->sessions()->where('status', 'pending')->count();

            if ($locked->status === 'cancelled' && ! in_array($petTreatment->status, ['suspended', 'cancelled'], true) && $completed + $pending < $petTreatment->planned_sessions) {
                $next = ((int) $petTreatment->sessions()->max('session_number')) + 1;
                $this->createPendingSessions($petTreatment, 1, $next);
            }

            if (! in_array($petTreatment->status, ['suspended', 'cancelled'], true)) {
                $status = $completed >= $petTreatment->planned_sessions
                    ? 'completed'
                    : ($completed > 0 ? 'in_progress' : 'pending');
                $petTreatment->update(['status' => $status]);
            }

            return $locked->load('petTreatment');
        });
    }

    public function changeStatus(PetTreatment $petTreatment, string $requestedStatus): PetTreatment
    {
        return DB::transaction(function () use ($petTreatment, $requestedStatus): PetTreatment {
            $locked = PetTreatment::query()->lockForUpdate()->findOrFail($petTreatment->id);

            if ($locked->status === 'cancelled') {
                throw ValidationException::withMessages(['status' => __('Cancelled treatments cannot be reopened.')]);
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
