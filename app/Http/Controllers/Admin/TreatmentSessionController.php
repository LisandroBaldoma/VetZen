<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TreatmentSession\UpdateTreatmentSessionRequest;
use App\Models\TreatmentSession;
use App\Services\TreatmentAssignmentService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class TreatmentSessionController extends Controller
{
    public function update(UpdateTreatmentSessionRequest $request, TreatmentSession $session, TreatmentAssignmentService $assignments): RedirectResponse
    {
        $assignments->updateSession($session, $request->validated());
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Sesión actualizada.')]);

        return back();
    }
}
