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
            'pets' => Pet::query()
                ->with('client.user:id,name')
                ->orderBy('name')
                ->get(['id', 'client_id', 'name', 'species', 'breed', 'photo'])
                ->map(fn (Pet $pet): array => [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'species' => $pet->species,
                    'breed' => $pet->breed,
                    'has_photo' => $pet->photo !== null,
                    'client' => [
                        'id' => $pet->client->id,
                        'name' => $pet->client->user->name,
                    ],
                ]),
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

        $pet->load('client.user:id,name');

        return Inertia::render('admin/pets/show', [
            'pet' => [
                ...$this->petData($pet),
                'client' => [
                    'id' => $pet->client->id,
                    'name' => $pet->client->user->name,
                ],
            ],
        ]);
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

    /**
     * @return array<string, bool|int|string|null>
     */
    private function petData(Pet $pet): array
    {
        return [
            'id' => $pet->id,
            'name' => $pet->name,
            'species' => $pet->species,
            'breed' => $pet->breed,
            'sex' => $pet->sex,
            'birth_date' => $pet->birth_date?->toDateString(),
            'weight' => $pet->weight,
            'color' => $pet->color,
            'notes' => $pet->notes,
            'has_photo' => $pet->photo !== null,
        ];
    }
}
