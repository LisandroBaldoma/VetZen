<?php

namespace App\Services;

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
                throw ValidationException::withMessages(['status' => __('Only pending requests can be resolved.')]);
            }
            if (! $treatment->is_active || $treatment->service_id !== $locked->service_id) {
                throw ValidationException::withMessages(['treatment_id' => __('The treatment must belong to the requested service.')]);
            }

            $petTreatment = $this->assignments->assign($locked->pet, $treatment, $attributes);
            $locked->petTreatment()->associate($petTreatment);
            $locked->status = 'resolved';
            $locked->save();

            return $locked->load(['pet.client.user', 'service', 'petTreatment.sessions']);
        });
    }
}
