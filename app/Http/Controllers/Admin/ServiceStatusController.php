<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ServiceStatusController extends Controller
{
    public function update(Request $request, Service $service): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);
        Gate::authorize('update', $service);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $service->update(['is_active' => $validated['is_active']]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => $service->is_active
                ? __('Servicio activado.')
                : __('Servicio desactivado.'),
        ]);

        return back();
    }
}
