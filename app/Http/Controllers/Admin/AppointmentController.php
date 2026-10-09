<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Models\AppointmentStatusHistory;
use App\Services\AppointmentStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Appointment::class);

        return Inertia::render('admin/appointments/index', [
            'appointments' => Appointment::query()
                ->where('status', 'pending')
                ->with(['pet.client.user:id,name', 'service:id,name'])
                ->orderBy('starts_at')
                ->paginate(15)
                ->through(fn (Appointment $appointment): array => $this->appointmentData($appointment)),
        ]);
    }

    public function show(Appointment $appointment): Response
    {
        Gate::authorize('view', $appointment);
        $appointment->load([
            'pet.client.user:id,name',
            'statusHistory.changedBy:id,name',
        ]);

        return Inertia::render('admin/appointments/show', [
            'appointment' => $this->appointmentData($appointment),
            'history' => $appointment->statusHistory
                ->sortByDesc('created_at')
                ->values()
                ->map(fn (AppointmentStatusHistory $history): array => [
                    'from_status' => $history->from_status,
                    'to_status' => $history->to_status,
                    'reason' => $history->reason,
                    'created_at' => $history->created_at->toISOString(),
                    'changed_by' => $history->changedBy->name,
                ]),
        ]);
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment, AppointmentStatusService $statuses): RedirectResponse
    {
        $statuses->transition(
            $appointment,
            $request->user(),
            $request->validated('status'),
            $request->validated('reason'),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Estado del turno actualizado.')]);

        return back();
    }

    /** @return array<string, int|string> */
    private function appointmentData(Appointment $appointment): array
    {
        return [
            'id' => $appointment->id,
            'status' => $appointment->status,
            'service_name' => $appointment->service_name,
            'pet_name' => $appointment->pet->name,
            'responsible_name' => $appointment->pet->client->user->name,
            'starts_at' => $appointment->starts_at->setTimezone(config('appointments.timezone'))->toIso8601String(),
            'ends_at' => $appointment->ends_at->setTimezone(config('appointments.timezone'))->toIso8601String(),
            'duration_minutes' => $appointment->duration_minutes,
        ];
    }
}
