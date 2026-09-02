<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\StorePetRequest;
use App\Http\Requests\Pet\UpdatePetRequest;
use App\Models\Pet;
use App\Services\PetPhotoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PetController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorizeClient($request);
        Gate::authorize('viewAny', Pet::class);

        return Inertia::render('pets/index', [
            'pets' => $request->user()->client->pets()
                ->orderBy('name')
                ->get(['id', 'name', 'species', 'breed', 'photo'])
                ->map(fn (Pet $pet): array => [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'species' => $pet->species,
                    'breed' => $pet->breed,
                    'has_photo' => $pet->photo !== null,
                ]),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->authorizeClient($request);
        Gate::authorize('create', [Pet::class, $request->user()->client]);

        return Inertia::render('pets/create');
    }

    public function store(StorePetRequest $request, PetPhotoService $photos): RedirectResponse
    {
        $this->authorizeClient($request);
        $pet = $request->user()->client->pets()->create(Arr::except($request->validated(), ['client_id', 'photo']));

        if ($request->hasFile('photo')) {
            $photos->replace($pet, $request->file('photo'));
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Pet created.')]);

        return to_route('pets.show', $pet);
    }

    public function show(Pet $pet): Response
    {
        Gate::authorize('view', $pet);

        return Inertia::render('pets/show', [
            'pet' => $this->petData($pet),
        ]);
    }

    public function edit(Pet $pet): Response
    {
        Gate::authorize('update', $pet);

        return Inertia::render('pets/edit', ['pet' => $pet]);
    }

    public function update(UpdatePetRequest $request, Pet $pet, PetPhotoService $photos): RedirectResponse
    {
        $pet->update(Arr::except($request->validated(), ['photo']));

        if ($request->hasFile('photo')) {
            $photos->replace($pet, $request->file('photo'));
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Pet updated.')]);

        return to_route('pets.show', $pet);
    }

    public function photo(Pet $pet): StreamedResponse
    {
        Gate::authorize('view', $pet);
        abort_if($pet->photo === null, 404);

        return Storage::disk(config('filesystems.default'))->response($pet->photo);
    }

    public function destroyPhoto(Pet $pet, PetPhotoService $photos): RedirectResponse
    {
        Gate::authorize('update', $pet);
        $photos->delete($pet);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Pet photo removed.')]);

        return back();
    }

    private function authorizeClient(Request $request): void
    {
        abort_unless($request->user()?->hasRole('client'), 403);
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
