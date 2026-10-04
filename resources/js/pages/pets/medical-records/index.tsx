import { Head, setLayoutProps } from '@inertiajs/react';
import { ClinicalRecordsEmptyState } from '@/components/clinical-records-empty-state';
import { ClinicalRecordsHeader } from '@/components/clinical-records-header';
import { ClinicalRecordsTimeline } from '@/components/clinical-records-timeline';
import PetContextHeader from '@/components/pet-context-header';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index, show } from '@/routes/pets/medical-records';
import type {
    ClinicalRecordSummary as ClinicalRecordSummaryData,
    PetContext,
} from '@/types';

export default function MedicalRecordsIndex({
    pet,
    records,
}: {
    pet: PetContext;
    records: ClinicalRecordSummaryData[];
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
            <Head title={`${pet.name} · Historia clínica`} />
            <div className="workspace-clinical">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="medical-records"
                    editHref={edit.url(pet.id)}
                />
                <ClinicalRecordsHeader
                    petName={pet.name}
                    count={records.length}
                />
                {records.length === 0 ? (
                    <ClinicalRecordsEmptyState petName={pet.name} />
                ) : (
                    <ClinicalRecordsTimeline
                        records={records}
                        recordHref={(record) => show.url([pet.id, record.id])}
                    />
                )}
            </div>
        </>
    );
}
