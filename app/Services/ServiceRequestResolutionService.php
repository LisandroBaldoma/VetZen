<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Treatment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceRequestResolutionService
{
    public function __construct(private TreatmentAssignmentService $assignments) {}

    public function resolve(ServiceRequest $serviceRequest, Treatment $treatment, array $attributes): ServiceRequest
    {
        return DB::transaction(function () use ($serviceRequest, $treatment, $attributes): ServiceRequest {
            $locked = ServiceRequest::query()->lockForUpdate()->findOrFail($serviceRequest->id);
            if ($locked->status !== 'pending') {
                throw ValidationException::withMessages(['status' => __('Solo se pueden resolver solicitudes pendientes.')]);
            }

            $service = Service::query()->lockForUpdate()->findOrFail($locked->service_id);
            if (! $service->is_active) {
                throw ValidationException::withMessages(['service' => __('El servicio solicitado está inactivo. Reactivalo antes de resolver la solicitud.')]);
            }

            if (! $treatment->is_active || $treatment->service_id !== $locked->service_id) {
                throw ValidationException::withMessages(['treatment_id' => __('La plantilla debe estar activa y pertenecer al servicio solicitado.')]);
            }

            $petTreatment = $this->assignments->assign($locked->pet, $treatment, $attributes);
            $locked->petTreatment()->associate($petTreatment);
            $locked->status = 'resolved';
            $locked->save();

            return $locked->load(['pet.client.user', 'service', 'petTreatment.sessions']);
        });
    }
}
