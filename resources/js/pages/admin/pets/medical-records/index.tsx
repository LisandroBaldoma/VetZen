import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ClinicalRecordsEmptyState } from '@/components/clinical-records-empty-state';
import { ClinicalRecordsHeader } from '@/components/clinical-records-header';
import { ClinicalRecordsTimeline } from '@/components/clinical-records-timeline';
import PetContextHeader from '@/components/pet-context-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/admin/pets';
import {
    create,
    edit as editRecord,
    index as medicalRecordsIndex,
    show,
} from '@/routes/admin/pets/medical-records';
import type {
    ClinicalRecordSummary as ClinicalRecordSummaryData,
    PetContext,
} from '@/types';

export default function AdminMedicalRecordsIndex({
    pet,
    records,
}: {
    pet: PetContext;
    records: ClinicalRecordSummaryData[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Historia clínica', href: medicalRecordsIndex(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`${pet.name} · Historia clínica`} />
            <div className="workspace-clinical">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="medical-records"
                    editHref={edit.url(pet.id)}
                />
                <ClinicalRecordsHeader
                    petName={pet.name}
                    count={records.length}
                    description={`Cronología clínica completa de ${pet.name}.`}
                    actions={
                        <Button asChild className="min-h-11">
                            <Link href={create.url(pet.id)}>
                                Nuevo registro
                            </Link>
                        </Button>
                    }
                />
                {records.length === 0 ? (
                    <ClinicalRecordsEmptyState
                        petName={pet.name}
                        title="No hay registros clínicos"
                        description={`Registrá el primer antecedente clínico de ${pet.name}.`}
                        action={
                            <Button asChild className="min-h-11">
                                <Link href={create.url(pet.id)}>
                                    Nuevo registro
                                </Link>
                            </Button>
                        }
                    />
                ) : (
                    <ClinicalRecordsTimeline
                        records={records}
                        recordHref={(record) => show.url([pet.id, record.id])}
                        editHref={(record) =>
                            editRecord.url([pet.id, record.id])
                        }
                    />
                )}
            </div>
        </>
    );
}
