import { Head, Link, setLayoutProps } from '@inertiajs/react';
import ClinicalRecordSummary from '@/components/clinical-record-summary';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/admin/pets';
import {
    create,
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
            <div className="mx-auto max-w-5xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="medical-records"
                    editHref={edit.url(pet.id)}
                />
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <Heading
                        title="Historia clínica"
                        description={`Cronología clínica completa de ${pet.name}.`}
                    />
                    <Button asChild>
                        <Link href={create.url(pet.id)}>Nuevo registro</Link>
                    </Button>
                </div>
                {records.length === 0 ? (
                    <div className="rounded-xl border border-dashed p-8 text-center text-sm text-muted-foreground">
                        No hay registros clínicos para este paciente.
                    </div>
                ) : (
                    <ol className="grid gap-4 md:ml-3 md:gap-0 md:border-l md:border-border">
                        {records.map((record) => (
                            <li
                                key={record.id}
                                className="relative md:pb-8 md:pl-8 last:md:pb-0"
                            >
                                <span className="absolute top-8 -left-[5px] hidden size-2.5 rounded-full bg-primary ring-4 ring-background md:block" />
                                <ClinicalRecordSummary
                                    record={record}
                                    href={show.url([pet.id, record.id])}
                                />
                            </li>
                        ))}
                    </ol>
                )}
            </div>
        </>
    );
}
