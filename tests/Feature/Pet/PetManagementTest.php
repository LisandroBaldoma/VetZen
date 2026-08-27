<?php

namespace Tests\Feature\Pet;

use App\Models\Client;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PetManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_manage_only_own_pets(): void
    {
        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $otherPet = Pet::factory()->create();

        $this->actingAs($client->user)->get(route('pets.index'))
            ->assertOk()->assertInertia(fn ($page) => $page->has('pets', 1));
        $this->actingAs($client->user)->get(route('pets.show', $pet))->assertOk();
        $this->actingAs($client->user)->patch(route('pets.update', $pet), [
            'name' => 'Updated name', 'species' => $pet->species, 'sex' => $pet->sex,
        ])->assertSessionHasNoErrors();

        $this->actingAs($client->user)->get(route('pets.show', $otherPet))->assertForbidden();
        $this->actingAs($client->user)->patch(route('pets.update', $otherPet), [
            'name' => 'Forbidden', 'species' => 'Dog', 'sex' => 'Male',
        ])->assertForbidden();
    }

    public function test_client_cannot_assign_a_pet_to_another_client(): void
    {
        $client = Client::factory()->create();
        $otherClient = Client::factory()->create();

        $this->actingAs($client->user)->post(route('pets.store'), [
            'name' => 'Mora', 'species' => 'Dog', 'sex' => 'Female', 'client_id' => $otherClient->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('pets', ['name' => 'Mora', 'client_id' => $client->id]);
    }

    public function test_client_cannot_access_the_administrative_pet_area(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)->get(route('admin.pets.index'))->assertForbidden();
    }

    public function test_admin_can_manage_pets_for_any_client(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();

        $this->actingAs($admin)->get(route('admin.pets.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.pets.show', $pet))->assertOk();
        $this->actingAs($admin)->post(route('admin.pets.store'), [
            'name' => 'Nina', 'species' => 'Cat', 'sex' => 'Female', 'client_id' => $client->id,
        ])->assertRedirect(route('admin.pets.show', Pet::query()->where('name', 'Nina')->firstOrFail()));
    }

    public function test_pet_fields_and_photos_are_validated_and_replaced_securely(): void
    {
        Storage::fake('local');
        $client = Client::factory()->create();

        $this->actingAs($client->user)->post(route('pets.store'), [])->assertSessionHasErrors(['name', 'species', 'sex']);

        $pet = Pet::factory()->for($client)->create();
        $firstPhoto = UploadedFile::fake()->image('first.jpg');
        $this->actingAs($client->user)->patch(route('pets.update', $pet), [
            'name' => $pet->name, 'species' => $pet->species, 'sex' => $pet->sex, 'photo' => $firstPhoto,
        ])->assertSessionHasNoErrors();
        $firstPath = $pet->fresh()->photo;
        Storage::disk('local')->assertExists($firstPath);

        $this->actingAs($client->user)->patch(route('pets.update', $pet), [
            'name' => $pet->name, 'species' => $pet->species, 'sex' => $pet->sex, 'photo' => UploadedFile::fake()->image('second.png'),
        ])->assertSessionHasNoErrors();
        Storage::disk('local')->assertMissing($firstPath);

        $this->actingAs($client->user)->delete(route('pets.photo.destroy', $pet))->assertRedirect();
        $this->assertNull($pet->fresh()->photo);
    }

    public function test_pet_photos_follow_pet_ownership_rules(): void
    {
        Storage::fake('local');
        $client = Client::factory()->create();
        $otherClient = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create(['photo' => 'pets/'.$client->id.'/private.jpg']);
        Storage::disk('local')->put($pet->photo, 'private image');

        $this->actingAs($otherClient->user)->get(route('pets.photo', $pet))->assertForbidden();
    }
}
