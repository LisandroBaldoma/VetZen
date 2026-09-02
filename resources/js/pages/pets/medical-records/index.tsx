import { Head, setLayoutProps } from '@inertiajs/react';
import ClinicalRecordSummary from '@/components/clinical-record-summary';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index, show } from '@/routes/pets/medical-records';
import type { ClinicalRecord, Pet } from '@/types';

export default function MedicalRecordsIndex({
    pet,
    records,
}: {
    pet: Pet;
    records: ClinicalRecord[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Historia clínica', href: index(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`${pet.name} · Medical records`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="medical-records"
                    editHref={edit.url(pet.id)}
                />
                <Heading
                    title="Medical records"
                    description={`Visible clinical history for ${pet.name}.`}
                />
                {records.length === 0 ? (
                    <div className="rounded-xl border border-dashed p-8 text-center text-sm text-muted-foreground">
                        No clinical records are currently available.
                    </div>
                ) : (
                    <div className="grid gap-4">
                        {records.map((record) => (
                            <ClinicalRecordSummary
                                key={record.id}
                                record={record}
                                href={show.url([pet.id, record.id])}
                            />
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
