import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetSummary from '@/components/pet-summary';
import { edit, photo } from '@/routes/pets';
import type { Pet } from '@/types';

export default function PetShow({ pet }: { pet: Pet }) {
    return (
        <>
            <Head title={pet.name} />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading title={pet.name} description="Pet details" />
                <PetSummary
                    pet={pet}
                    editHref={edit.url(pet.id)}
                    photoHref={photo.url(pet.id)}
                />
            </div>
        </>
    );
}
