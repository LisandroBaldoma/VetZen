<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PetPhotoService
{
    public function replace(Pet $pet, UploadedFile $photo): void
    {
        $temporaryPath = $photo->getPathname();

        if (! $photo->isValid() || ! is_file($temporaryPath) || ! is_readable($temporaryPath)) {
            throw ValidationException::withMessages([
                'photo' => 'No se pudo leer la foto subida. Elegí el archivo nuevamente.',
            ]);
        }

        $disk = Storage::disk(config('filesystems.default'));
        $previousPhoto = $pet->photo;
        $photoPath = "pets/{$pet->client_id}/{$photo->hashName()}";
        $stream = fopen($temporaryPath, 'r');

        if ($stream === false) {
            throw ValidationException::withMessages([
                'photo' => 'No se pudo leer la foto subida. Elegí el archivo nuevamente.',
            ]);
        }

        try {
            $stored = $disk->put($photoPath, $stream);
        } finally {
            fclose($stream);
        }

        if (! $stored) {
            throw ValidationException::withMessages([
                'photo' => 'No se pudo guardar la foto subida. Intentá nuevamente.',
            ]);
        }

        try {
            $pet->update(['photo' => $photoPath]);
        } catch (\Throwable $exception) {
            $disk->delete($photoPath);

            throw $exception;
        }

        if ($previousPhoto !== null) {
            $disk->delete($previousPhoto);
        }
    }

    public function delete(Pet $pet): void
    {
        if ($pet->photo !== null) {
            Storage::disk(config('filesystems.default'))->delete($pet->photo);
            $pet->update(['photo' => null]);
        }
    }
}
