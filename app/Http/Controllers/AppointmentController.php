<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Service;
use App\Services\AppointmentBookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    public function create(Request $request, AppointmentBookingService $booking): Response
    {
        abort_unless($request->user()?->hasRole('client'), 403);

        $pets = $request->user()->client->pets()->orderBy('name')->get(['id', 'name']);
        $services = Service::query()
            ->where('is_active', true)
            ->whereNotNull('appointment_duration_minutes')
            ->orderBy('name')
            ->get(['id', 'name', 'appointment_duration_minutes']);
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
}
