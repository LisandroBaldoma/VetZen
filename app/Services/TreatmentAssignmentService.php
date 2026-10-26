<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\PetTreatment;
use App\Models\Treatment;
use App\Models\TreatmentSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TreatmentAssignmentService
{
    /** @param array{planned_sessions: int, default_session_price: string, currency: string, starts_on: string, status: string, notes?: string|null} $attributes */
    public function assign(Pet $pet, Treatment $treatment, array $attributes, ?User $actor = null): PetTreatment
    {
        return DB::transaction(function () use ($pet, $treatment, $attributes, $actor): PetTreatment {
            $treatment = Treatment::query()
                ->with(['service', 'procedures'])
                ->lockForUpdate()
                ->findOrFail($treatment->id);

            if (! $treatment->is_active || ! $treatment->service->is_active) {
                throw ValidationException::withMessages(['treatment_id' => __('El tratamiento seleccionado no está disponible.')]);
            }

            if ($treatment->procedures->isEmpty() || $treatment->procedures->contains(
                fn ($procedure): bool => ! $procedure->is_active || $procedure->service_id !== $treatment->service_id,
            )) {
                throw ValidationException::withMessages(['treatment_id' => __('El tratamiento seleccionado no tiene procedimientos válidos para su servicio.')]);
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
            if ($actor) {
                app(AuditLogger::class)->record($actor, 'pet_treatment.assigned', $petTreatment, null, ['status' => $petTreatment->status, 'planned_sessions' => $petTreatment->planned_sessions]);
            }

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
                throw ValidationException::withMessages(['planned_sessions' => __('Este tratamiento no puede cambiar sus condiciones en su estado actual.')]);
            }

            $completed = $locked->sessions()->where('status', 'completed')->count();
            if ($plannedSessions < max(1, $completed)) {
                throw ValidationException::withMessages(['planned_sessions' => __('Las sesiones planificadas no pueden ser menores que las sesiones completadas.')]);
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
                    throw ValidationException::withMessages(['planned_sessions' => __('Solo se pueden eliminar las últimas sesiones pendientes.')]);
                }

                $locked->sessions()->whereIn('id', $pendingIds)->delete();
            }

            return $locked->load('sessions');
        });
    }

    /** @param array{scheduled_at?: string|null, price: string, currency: string, status: string, notes?: string|null} $attributes */
    public function updateSession(TreatmentSession $session, array $attributes, ?User $actor = null): TreatmentSession
    {
        return DB::transaction(function () use ($session, $attributes, $actor): TreatmentSession {
            $locked = TreatmentSession::query()->lockForUpdate()->findOrFail($session->id);
            $petTreatment = PetTreatment::query()->lockForUpdate()->findOrFail($locked->pet_treatment_id);

            if (! in_array($petTreatment->status, ['pending', 'in_progress'], true)) {
                throw ValidationException::withMessages(['status' => __('Solo se pueden modificar las sesiones de tratamientos pendientes o en curso.')]);
            }

            if (in_array($locked->status, ['completed', 'cancelled'], true) && $attributes['status'] !== $locked->status) {
                throw ValidationException::withMessages(['status' => __('Las sesiones completadas o canceladas no pueden cambiar de estado.')]);
            }

            $previousStatus = $locked->status;
            $locked->update($attributes);
            if ($actor && $previousStatus !== $locked->status) {
                app(AuditLogger::class)->record($actor, 'treatment_session.status_changed', $locked, ['status' => $previousStatus], ['status' => $locked->status]);
            }

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

    public function changeStatus(PetTreatment $petTreatment, string $requestedStatus, ?User $actor = null): PetTreatment
    {
        return DB::transaction(function () use ($petTreatment, $requestedStatus, $actor): PetTreatment {
            $locked = PetTreatment::query()->lockForUpdate()->findOrFail($petTreatment->id);

            if (in_array($locked->status, ['completed', 'cancelled'], true)) {
                throw ValidationException::withMessages(['status' => __('Los tratamientos completados o cancelados no pueden cambiar de estado.')]);
            }

            if ($requestedStatus === 'resume') {
                if ($locked->status !== 'suspended') {
                    throw ValidationException::withMessages(['status' => __('Solo se pueden reanudar los tratamientos suspendidos.')]);
                }

                $completed = $locked->sessions()->where('status', 'completed')->count();
                $requestedStatus = $completed > 0 ? 'in_progress' : 'pending';
            }

            $previousStatus = $locked->status;
            $locked->update(['status' => $requestedStatus]);
            if ($actor && $previousStatus !== $locked->status) {
                app(AuditLogger::class)->record($actor, 'pet_treatment.status_changed', $locked, ['status' => $previousStatus], ['status' => $locked->status]);
            }

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
