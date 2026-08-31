<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ProcedureStatusController extends Controller
{
    public function update(Request $request, Service $service, Procedure $procedure): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);
        Gate::authorize('update', $procedure);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $procedure->update(['is_active' => $validated['is_active']]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => $procedure->is_active
                ? __('Procedimiento activado.')
                : __('Procedimiento desactivado.'),
        ]);

        return back();
    }
}
