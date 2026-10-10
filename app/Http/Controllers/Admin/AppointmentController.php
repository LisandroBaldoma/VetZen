<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\RescheduleAppointmentRequest;
use App\Http\Requests\Appointment\StoreAdminAppointmentRequest;
use App\Http\Requests\Appointment\UpdateAppointmentLinksRequest;
use App\Http\Requests\Appointment\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Models\AppointmentScheduleDate;
use App\Models\AppointmentStatusHistory;
use App\Models\Pet;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\TreatmentSession;
use App\Services\AppointmentBookingService;
use App\Services\AppointmentLinkService;
use App\Services\AppointmentStatusService;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    public function create(Request $request, AppointmentBookingService $booking): Response
    {
        Gate::authorize('createAdmin', Appointment::class);

        $pets = Pet::query()->with('client.user:id,name')->orderBy('name')->get();
        $services = Service::query()->where('is_active', true)->whereNotNull('appointment_duration_minutes')->orderBy('name')->get(['id', 'name', 'appointment_duration_minutes', 'is_active']);
        $pet = $request->integer('pet') ? $pets->firstWhere('id', $request->integer('pet')) : null;
        $service = $request->integer('service') ? $services->firstWhere('id', $request->integer('service')) : null;

        return Inertia::render('admin/appointments/create', [
            'pets' => $pets->map(fn (Pet $pet): array => ['id' => $pet->id, 'name' => $pet->name, 'responsible_name' => $pet->client->user->name]),
            'services' => $services,
            'selectedPetId' => $pet?->id,
            'selectedServiceId' => $service?->id,
            'availability' => $pet && $service ? $booking->availability($pet, $service, false) : [],
        ]);
    }

    public function schedule(Request $request): Response
    {
        Gate::authorize('viewAny', Appointment::class);

        $date = $request->string('date')->toString();
        abort_unless($date === '' || preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1, 422);
        $weekStart = ($date === ''
            ? now(config('appointments.timezone'))->toImmutable()
            : CarbonImmutable::createFromFormat('Y-m-d', $date, config('appointments.timezone')))
            ->startOfWeek();
        $weekEnd = $weekStart->addDays(6);

        $scheduleDates = AppointmentScheduleDate::query()
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->with(['appointments' => fn ($query) => $query->with('pet:id,name')->orderBy('starts_at')])
            ->get()
            ->keyBy(fn ($scheduleDate) => $scheduleDate->date->toDateString());

        return Inertia::render('admin/appointments/schedule', [
            'weekStart' => $weekStart->toDateString(),
            'week' => collect(range(0, 6))->map(function (int $offset) use ($weekStart, $scheduleDates): array {
                $date = $weekStart->addDays($offset);
                $scheduleDate = $scheduleDates->get($date->toDateString());

                return [
                    'date' => $date->toDateString(),
                    'capacity' => $scheduleDate?->capacity,
                    'appointments' => $scheduleDate?->appointments->map(fn (Appointment $appointment): array => [
                        'id' => $appointment->id,
                        'pet_name' => $appointment->pet->name,
                        'service_name' => $appointment->service_name,
                        'status' => $appointment->status,
                        'starts_at' => $appointment->starts_at->setTimezone(config('appointments.timezone'))->toIso8601String(),
                        'ends_at' => $appointment->ends_at->setTimezone(config('appointments.timezone'))->toIso8601String(),
                    ])->values() ?? [],
                ];
            }),
        ]);
    }

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
            'clinicalTask.clinicalRecord',
            'linkHistory.changedBy:id,name',
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
            'links' => [
                'service_request_id' => $appointment->service_request_id,
                'treatment_session_id' => $appointment->treatment_session_id,
                'locked' => $appointment->clinicalTask?->clinical_record_id !== null,
                'history' => $appointment->linkHistory->map(fn ($history): array => ['created_at' => $history->created_at->toISOString(), 'changed_by' => $history->changedBy->name, 'old_service_request_id' => $history->old_service_request_id, 'new_service_request_id' => $history->new_service_request_id, 'old_treatment_session_id' => $history->old_treatment_session_id, 'new_treatment_session_id' => $history->new_treatment_session_id]),
            ],
            'linkOptions' => [
                'serviceRequests' => ServiceRequest::query()
                    ->with('service:id,name')
                    ->where('pet_id', $appointment->pet_id)
                    ->where('service_id', $appointment->service_id)
                    ->get(['id', 'service_id', 'status'])
                    ->map(fn (ServiceRequest $request): array => [
                        'id' => $request->id,
                        'status' => $request->status,
                        'service_name' => $request->service->name,
                    ]),
                'treatmentSessions' => TreatmentSession::query()
                    ->with('petTreatment:id,treatment_name')
                    ->whereHas('petTreatment', fn ($query) => $query->where('pet_id', $appointment->pet_id)->whereHas('treatment', fn ($treatment) => $treatment->where('service_id', $appointment->service_id)))
                    ->get(['id', 'pet_treatment_id', 'session_number', 'status'])
                    ->map(fn (TreatmentSession $session): array => [
                        'id' => $session->id,
                        'session_number' => $session->session_number,
                        'status' => $session->status,
                        'treatment_name' => $session->petTreatment->treatment_name,
                    ]),
            ],
        ]);
    }

    public function updateLinks(UpdateAppointmentLinksRequest $request, Appointment $appointment, AppointmentLinkService $links): RedirectResponse
    {
        $links->update($appointment, $request->user(), $request->integer('service_request_id') ?: null, $request->integer('treatment_session_id') ?: null);
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Vínculos del turno actualizados.')]);

        return back();
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

    public function store(StoreAdminAppointmentRequest $request, AppointmentBookingService $booking): RedirectResponse
    {
        Gate::authorize('createAdmin', Appointment::class);
        $appointment = $booking->book(
            $request->user(),
            Pet::query()->findOrFail($request->integer('pet_id')),
            Service::query()->findOrFail($request->integer('service_id')),
            $request->integer('schedule_date_id'),
            $request->string('starts_at')->toString(),
            'approved',
            false,
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Turno creado y aprobado.')]);

        return to_route('admin.appointments.show', $appointment);
    }

    public function reschedule(RescheduleAppointmentRequest $request, Appointment $appointment, AppointmentBookingService $booking): RedirectResponse
    {
        $booking->reschedule($appointment, $request->user(), $request->integer('schedule_date_id'), $request->string('starts_at')->toString());
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Turno reprogramado.')]);

        return back();
    }

    public function editReschedule(Appointment $appointment, AppointmentBookingService $booking): Response
    {
        Gate::authorize('reschedule', $appointment);
        $appointment->loadMissing(['pet', 'service']);

        return Inertia::render('admin/appointments/reschedule', [
            'appointment' => $this->appointmentData($appointment->loadMissing('pet.client.user')),
            'availability' => $booking->availability($appointment->pet, $appointment->service, false, $appointment),
        ]);
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
