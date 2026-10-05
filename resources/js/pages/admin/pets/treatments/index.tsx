import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ClipboardPlusIcon } from 'lucide-react';
import { EmptyState } from '@/components/empty-state';
import PageHeader from '@/components/page-header';
import PetContextHeader from '@/components/pet-context-header';
import PetTreatmentCard from '@/components/pet-treatment-card';
import type { PetTreatmentSummary } from '@/components/pet-treatment-card';
import type { TreatmentStatus } from '@/components/treatment-status-badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/admin/pets';
import { create, index, show } from '@/routes/admin/pets/treatments';

type Pet = {
    id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birth_date: string | null;
    weight: string | null;
    color: string | null;
    notes: string | null;
    has_photo?: boolean;
    client?: { id: number; name?: string };
};

const currentStatuses: TreatmentStatus[] = [
    'pending',
    'in_progress',
    'suspended',
];

function treatmentCount(count: number): string {
    return `${count} ${count === 1 ? 'tratamiento' : 'tratamientos'}`;
}

function TreatmentGroup({
    title,
    treatments,
    petId,
    historical = false,
}: {
    title: string;
    treatments: PetTreatmentSummary[];
    petId: number;
    historical?: boolean;
}) {
    if (treatments.length === 0) {
        return null;
    }

    return (
        <section
            aria-labelledby={`${historical ? 'history' : 'current'}-treatments`}
        >
            <div className="mb-4 flex items-baseline justify-between gap-4">
                <h2
                    id={`${historical ? 'history' : 'current'}-treatments`}
                    className={
                        historical
                            ? 'text-section-title font-semibold text-muted-foreground'
                            : 'text-section-title font-semibold text-foreground'
                    }
                >
                    {title}
                </h2>
                <span className="shrink-0 text-sm text-muted-foreground tabular-nums">
                    {treatmentCount(treatments.length)}
                </span>
            </div>
            <div className="grid gap-4 xl:grid-cols-2">
                {treatments.map((treatment) => (
                    <PetTreatmentCard
                        key={treatment.id}
                        treatment={treatment}
                        href={show.url([petId, treatment.id])}
                    />
                ))}
            </div>
        </section>
    );
}

export default function PetTreatments({
    pet,
    petTreatments,
}: {
    pet: Pet;
    petTreatments: PetTreatmentSummary[];
}) {
    const currentTreatments = petTreatments.filter((treatment) =>
        currentStatuses.includes(treatment.status),
    );
    const historicalTreatments = petTreatments.filter(
        (treatment) => !currentStatuses.includes(treatment.status),
    );

    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
        ],
    });

    const assignmentAction = (
        <Button asChild className="w-full sm:w-auto">
            <Link href={create.url(pet.id)}>Asignar tratamiento</Link>
        </Button>
    );

    return (
        <>
            <Head title={`Tratamientos de ${pet.name}`} />
            <div className="workspace-clinical">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <PageHeader
                    title="Tratamientos"
                    description="Consultá los tratamientos asignados y su progreso."
                    actions={assignmentAction}
                    actionsClassName="w-full sm:w-auto"
                />

                {petTreatments.length === 0 ? (
                    <EmptyState
                        icon={<ClipboardPlusIcon aria-hidden="true" />}
                        title="Esta mascota todavía no tiene tratamientos asignados."
                        description="Cuando asignes un tratamiento podrás consultar desde aquí su estado y progreso."
                        action={assignmentAction}
                    />
                ) : (
                    <div className="space-y-8">
                        <TreatmentGroup
                            title="Tratamientos actuales"
                            treatments={currentTreatments}
                            petId={pet.id}
                        />
                        <TreatmentGroup
                            title="Historial de tratamientos"
                            treatments={historicalTreatments}
                            petId={pet.id}
                            historical
                        />
                    </div>
                )}
            </div>
        </>
    );
}
