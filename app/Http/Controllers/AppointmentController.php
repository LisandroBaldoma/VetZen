<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointment\CancelAppointmentRequest;
use App\Http\Requests\Appointment\ClientRescheduleAppointmentRequest;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Service;
use App\Services\AppointmentBookingService;
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
        abort_unless($request->user()?->hasRole('client'), 403);
        Gate::authorize('viewAny', Appointment::class);

        $appointments = Appointment::query()
            ->whereIn('pet_id', $request->user()->client->pets()->select('id'))
            ->with('pet:id,name')
            ->orderByDesc('starts_at')
            ->get();

        $now = now('UTC');

        return Inertia::render('appointments/index', [
            'upcoming' => $appointments
                ->filter(fn (Appointment $appointment): bool => in_array($appointment->status, ['pending', 'approved'], true)
                    && $appointment->ends_at->greaterThan($now))
                ->sortBy('starts_at')
                ->values()
                ->map(fn (Appointment $appointment): array => $this->appointmentData($appointment)),
            'history' => $appointments
                ->filter(fn (Appointment $appointment): bool => ! in_array($appointment->status, ['pending', 'approved'], true)
                    || $appointment->ends_at->lessThanOrEqualTo($now))
                ->values()
                ->map(fn (Appointment $appointment): array => $this->appointmentData($appointment)),
        ]);
    }

    public function create(Request $request, AppointmentBookingService $booking): Response
    {
        abort_unless($request->user()?->hasRole('client'), 403);

        $pets = $request->user()->client->pets()->orderBy('name')->get(['id', 'name']);
        $services = Service::query()
            ->where('is_active', true)
            ->whereNotNull('appointment_duration_minutes')
            ->orderBy('name')
            ->get(['id', 'name', 'appointment_duration_minutes', 'is_active']);
        $pet = $request->integer('pet') ? $pets->firstWhere('id', $request->integer('pet')) : null;
        $service = $request->integer('service') ? $services->firstWhere('id', $request->integer('service')) : null;

        return Inertia::render('appointments/create', [
            'pets' => $pets,
            'services' => $services,
            'selectedPetId' => $pet?->id,
            'selectedServiceId' => $service?->id,
            'availability' => $pet && $service ? $booking->availability($pet, $service) : [],
        ]);
    }

    public function store(StoreAppointmentRequest $request, AppointmentBookingService $booking): RedirectResponse
    {
        $pet = $request->user()->client->pets()->findOrFail($request->integer('pet_id'));
        Gate::authorize('create', [Appointment::class, $pet]);

        $service = Service::query()->findOrFail($request->integer('service_id'));
        $booking->book(
            $request->user(),
            $pet,
            $service,
            $request->integer('schedule_date_id'),
            $request->string('starts_at')->toString(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Turno solicitado.')]);

        return to_route('appointments.create');
    }

    public function show(Appointment $appointment): Response
    {
        Gate::authorize('view', $appointment);
        $appointment->loadMissing('pet:id,name');

        return Inertia::render('appointments/show', [
            'appointment' => $this->appointmentData($appointment),
            'canCancel' => in_array($appointment->status, ['pending', 'approved'], true)
                && $appointment->starts_at->setTimezone(config('appointments.timezone'))->greaterThanOrEqualTo(now(config('appointments.timezone'))->addMinutes(config('appointments.client_cancellation_lead_minutes'))),
            'canReschedule' => $appointment->status === 'approved'
                && $appointment->starts_at->setTimezone(config('appointments.timezone'))->greaterThanOrEqualTo(now(config('appointments.timezone'))->addMinutes(config('appointments.client_cancellation_lead_minutes'))),
        ]);
    }

    public function cancel(CancelAppointmentRequest $request, Appointment $appointment, AppointmentStatusService $statuses): RedirectResponse
    {
        $statuses->transition($appointment, $request->user(), 'cancelled', null, true);
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Turno cancelado.')]);

        return to_route('appointments.show', $appointment);
    }

    public function reschedule(ClientRescheduleAppointmentRequest $request, Appointment $appointment, AppointmentBookingService $booking): RedirectResponse
    {
        $booking->reschedule($appointment, $request->user(), $request->integer('schedule_date_id'), $request->string('starts_at')->toString(), 'pending', true);
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Turno reprogramado y pendiente de revisión.')]);

        return to_route('appointments.show', $appointment);
    }

    /** @return array{id: int, pet_name: string, service_name: string, duration_minutes: int, starts_at: string, ends_at: string, status: string} */
    private function appointmentData(Appointment $appointment): array
    {
        $timezone = config('appointments.timezone');

        return [
            'id' => $appointment->id,
            'pet_name' => $appointment->pet->name,
            'service_name' => $appointment->service_name,
            'duration_minutes' => $appointment->duration_minutes,
            'starts_at' => $appointment->starts_at->setTimezone($timezone)->toIso8601String(),
            'ends_at' => $appointment->ends_at->setTimezone($timezone)->toIso8601String(),
            'status' => $appointment->status,
        ];
    }
}
