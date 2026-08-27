<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PetPhotoService
{
    public function replace(Pet $pet, UploadedFile $photo): void
    {
        $disk = Storage::disk(config('filesystems.default'));

        if ($pet->photo !== null) {
            $disk->delete($pet->photo);
        }

        $pet->update([
            'photo' => $disk->putFile("pets/{$pet->client_id}", $photo),
        ]);
    }

    public function delete(Pet $pet): void
    {
        if ($pet->photo !== null) {
            Storage::disk(config('filesystems.default'))->delete($pet->photo);
            $pet->update(['photo' => null]);
        }
    }
}
