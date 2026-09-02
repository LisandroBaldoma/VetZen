import { Head, setLayoutProps } from '@inertiajs/react';
import ClinicalRecordDetail from '@/components/clinical-record-detail';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index, show } from '@/routes/pets/medical-records';
import type {
    ClinicalRecordDetail as ClinicalRecordDetailData,
    PetContext,
} from '@/types';

export default function MedicalRecordShow({
    pet,
    record,
}: {
    pet: PetContext;
    record: ClinicalRecordDetailData;
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
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
                    variant="client"
                    active="medical-records"
                    editHref={edit.url(pet.id)}
                />
                <Heading
                    title="Registro clínico"
                    description={`Información clínica registrada para ${pet.name}.`}
                />
                <ClinicalRecordDetail record={record} />
            </div>
        </>
    );
}
