import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetSummary from '@/components/pet-summary';
import { Button } from '@/components/ui/button';
import { edit } from '@/routes/admin/pets';
import { index as medicalRecordsIndex } from '@/routes/admin/pets/medical-records';
import { photo } from '@/routes/pets';
import type { Pet } from '@/types';

export default function AdminPetShow({ pet }: { pet: Pet }) {
    return (
        <>
            <Head title={pet.name} />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading title={pet.name} description="Patient details" />
                <PetSummary
                    pet={pet}
                    editHref={edit.url(pet.id)}
                    photoHref={photo.url(pet.id)}
                />
                <Button asChild variant="outline">
                    <Link href={medicalRecordsIndex.url(pet.id)}>
                        Manage medical records
                    </Link>
                </Button>
            </div>
        </>
    );
}
