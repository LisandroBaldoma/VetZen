<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TreatmentSession\UpdateTreatmentSessionRequest;
use App\Models\TreatmentSession;
use App\Services\AppointmentClinicalTaskService;
use App\Services\TreatmentAssignmentService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class TreatmentSessionController extends Controller
{
    public function update(UpdateTreatmentSessionRequest $request, TreatmentSession $session, TreatmentAssignmentService $assignments, AppointmentClinicalTaskService $tasks): RedirectResponse
    {
        $session = $assignments->updateSession($session, $request->validated());
        $tasks->resolveForSession($session);
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Sesión actualizada.')]);

        return back();
    }
}
