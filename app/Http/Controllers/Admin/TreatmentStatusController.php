<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TreatmentStatusController extends Controller
{
    public function update(Request $request, Service $service, Treatment $treatment): RedirectResponse
    {
        Gate::authorize('update', $treatment);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $treatment->update(['is_active' => $validated['is_active']]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => $treatment->is_active
                ? __('Plantilla activada.')
                : __('Plantilla desactivada.'),
        ]);

        return back();
    }
}
