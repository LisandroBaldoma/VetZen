<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\StorePetRequest;
use App\Http\Requests\Pet\UpdatePetRequest;
use App\Models\Client;
use App\Models\Pet;
use App\Services\PetPhotoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
    public function index(): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('viewAny', Pet::class);

        return Inertia::render('admin/pets/index', [
            'pets' => Pet::query()->with('client.user')->orderBy('name')->get(),
        ]);
    }

    public function create(): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('viewAny', Pet::class);

        return Inertia::render('admin/pets/create', [
            'clients' => Client::query()->with('user')->orderBy('id')->get(),
        ]);
    }

    public function store(StorePetRequest $request, PetPhotoService $photos): RedirectResponse
    {
        $this->authorizeAdmin();
        $client = Client::findOrFail($request->validated('client_id'));
        $pet = $client->pets()->create(Arr::except($request->validated(), ['client_id', 'photo']));

        if ($request->hasFile('photo')) {
            $photos->replace($pet, $request->file('photo'));
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Pet created.')]);

        return to_route('admin.pets.show', $pet);
    }

    public function show(Pet $pet): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('view', $pet);

        return Inertia::render('admin/pets/show', ['pet' => $pet->load('client.user')]);
    }

    public function edit(Pet $pet): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('update', $pet);

        return Inertia::render('admin/pets/edit', ['pet' => $pet->load('client.user')]);
    }

    public function update(UpdatePetRequest $request, Pet $pet, PetPhotoService $photos): RedirectResponse
    {
        $this->authorizeAdmin();
        $pet->update(Arr::except($request->validated(), ['photo']));

        if ($request->hasFile('photo')) {
            $photos->replace($pet, $request->file('photo'));
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Pet updated.')]);

        return to_route('admin.pets.show', $pet);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
    }
}
