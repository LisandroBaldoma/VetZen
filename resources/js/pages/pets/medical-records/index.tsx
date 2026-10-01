import { Head, setLayoutProps } from '@inertiajs/react';
import ClinicalRecordSummary from '@/components/clinical-record-summary';
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
                <div className="max-w-xl space-y-1 border-b border-border pb-6">
                    <p className="text-[0.6875rem] font-semibold tracking-[0.16em] text-clinical-foreground uppercase">
                        Continuidad clínica
                    </p>
                    <h2 className="text-2xl font-semibold tracking-tight">
                        Historia clínica
                    </h2>
                    <p className="text-sm leading-6 text-muted-foreground">
                        Todos los registros clínicos disponibles de {pet.name}.
                    </p>
                </div>
                {records.length === 0 ? (
                    <div className="border-y border-dashed py-10 text-sm text-muted-foreground">
                        Todavía no hay registros clínicos para esta mascota.
                    </div>
                ) : (
                    <ol className="relative border-l border-clinical-foreground/25 pl-5 sm:pl-7">
                        {records.map((record) => (
                            <li
                                key={record.id}
                                className="relative border-b border-border last:border-b-0"
                            >
                                <span className="absolute top-8 -left-[1.8rem] size-2.5 rounded-full border-2 border-background bg-clinical-foreground sm:-left-[2.3rem]" />
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
