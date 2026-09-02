import { Head, setLayoutProps } from '@inertiajs/react';
import ClinicalRecordDetail from '@/components/clinical-record-detail';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { dashboard } from '@/routes';
import {
    edit as editPet,
    index as petsIndex,
    show as petShow,
} from '@/routes/admin/pets';
import { edit, index, show } from '@/routes/admin/pets/medical-records';
import type { ClinicalRecord, Pet } from '@/types';

export default function AdminMedicalRecordShow({
    pet,
    record,
}: {
    pet: Pet;
    record: ClinicalRecord;
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Historia clínica', href: index(pet.id) },
            { title: record.title, href: show([pet.id, record.id]) },
        ],
    });

    return (
        <>
            <Head title={record.title} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="medical-records"
                    editHref={editPet.url(pet.id)}
                />
                <Heading
                    title="Medical record"
                    description={`Clinical history for ${pet.name}.`}
                />
                <ClinicalRecordDetail
                    record={record}
                    editHref={edit.url([pet.id, record.id])}
                />
            </div>
        </>
    );
}
