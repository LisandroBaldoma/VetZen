<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentClinicalTask;
use App\Models\AppointmentLinkHistory;
use App\Models\ServiceRequest;
use App\Models\TreatmentSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentLinkService
{
    public function update(Appointment $appointment, User $user, ?int $serviceRequestId, ?int $treatmentSessionId): Appointment
    {
        return DB::transaction(function () use ($appointment, $user, $serviceRequestId, $treatmentSessionId): Appointment {
            $locked = Appointment::query()->lockForUpdate()->findOrFail($appointment->id);
            $task = AppointmentClinicalTask::query()->where('appointment_id', $locked->id)->lockForUpdate()->first();
            if ($task?->clinical_record_id !== null || $task?->resolved_at !== null) {
                throw ValidationException::withMessages(['links' => __('No se pueden cambiar los vínculos de una atención con documentación clínica resuelta.')]);
            }

            $serviceRequest = $serviceRequestId === null ? null : ServiceRequest::query()->lockForUpdate()->findOrFail($serviceRequestId);
            $session = $treatmentSessionId === null ? null : TreatmentSession::query()->with('petTreatment.treatment')->lockForUpdate()->findOrFail($treatmentSessionId);
            if ($serviceRequest && ($serviceRequest->pet_id !== $locked->pet_id || $serviceRequest->service_id !== $locked->service_id)) {
                throw ValidationException::withMessages(['service_request_id' => __('La solicitud debe pertenecer a la misma mascota y servicio.')]);
            }
            if ($session && ($session->petTreatment->pet_id !== $locked->pet_id || $session->petTreatment->treatment->service_id !== $locked->service_id)) {
                throw ValidationException::withMessages(['treatment_session_id' => __('La sesión debe pertenecer a la misma mascota y servicio.')]);
            }
            if ($session && $session->status === 'completed') {
                throw ValidationException::withMessages(['treatment_session_id' => __('Una sesión completada no puede volver a reservarse.')]);
            }
            if ($serviceRequest && $this->hasActiveAppointment($locked, 'service_request_id', $serviceRequest->id)) {
                throw ValidationException::withMessages(['service_request_id' => __('La solicitud ya tiene un turno pendiente o aprobado.')]);
            }
            if ($session && $this->hasActiveAppointment($locked, 'treatment_session_id', $session->id)) {
                throw ValidationException::withMessages(['treatment_session_id' => __('La sesión ya tiene un turno pendiente o aprobado.')]);
            }
            if ($session && Appointment::query()->where('treatment_session_id', $session->id)->where('status', 'attended')->whereHas('clinicalTask', fn ($query) => $query->whereNull('resolved_at'))->exists()) {
                throw ValidationException::withMessages(['treatment_session_id' => __('La sesión tiene una atención pendiente de documentación clínica.')]);
            }

            if ($locked->service_request_id !== $serviceRequest?->id || $locked->treatment_session_id !== $session?->id) {
                AppointmentLinkHistory::query()->create([
                    'appointment_id' => $locked->id, 'changed_by' => $user->id,
                    'old_service_request_id' => $locked->service_request_id, 'new_service_request_id' => $serviceRequest?->id,
                    'old_treatment_session_id' => $locked->treatment_session_id, 'new_treatment_session_id' => $session?->id,
                    'created_at' => now(),
                ]);
                $locked->forceFill(['service_request_id' => $serviceRequest?->id, 'treatment_session_id' => $session?->id])->save();
            }

            return $locked;
        });
    }

    private function hasActiveAppointment(Appointment $appointment, string $column, int $id): bool
    {
        return Appointment::query()
            ->where($column, $id)
            ->whereKeyNot($appointment->id)
            ->whereIn('status', ['pending', 'approved'])
            ->lockForUpdate()
            ->exists();
    }
}
