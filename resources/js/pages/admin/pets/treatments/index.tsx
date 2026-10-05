import { Head, Link, setLayoutProps } from '@inertiajs/react';
import {
    ArrowRightIcon,
    CalendarDaysIcon,
    ClipboardPlusIcon,
} from 'lucide-react';
import { EmptyState } from '@/components/empty-state';
import PageHeader from '@/components/page-header';
import PetContextHeader from '@/components/pet-context-header';
import TreatmentProgress from '@/components/treatment-progress';
import TreatmentStatusBadge from '@/components/treatment-status-badge';
import type { TreatmentStatus } from '@/components/treatment-status-badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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

type TreatmentSummary = {
    id: number;
    treatment_name: string;
    planned_sessions: number;
    completed_sessions_count: number;
    status: TreatmentStatus;
    starts_on: string | null;
};

const currentStatuses: TreatmentStatus[] = [
    'pending',
    'in_progress',
    'suspended',
];

const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    dateStyle: 'long',
});

function treatmentCount(count: number): string {
    return `${count} ${count === 1 ? 'tratamiento' : 'tratamientos'}`;
}

function TreatmentCard({
    treatment,
    petId,
}: {
    treatment: TreatmentSummary;
    petId: number;
}) {
    return (
        <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
            <CardHeader className="gap-3 p-4 pb-3 sm:p-5 sm:pb-3">
                <div className="flex items-start justify-between gap-3">
                    <CardTitle className="min-w-0 text-section-title leading-snug break-words">
                        {treatment.treatment_name}
                    </CardTitle>
                    <TreatmentStatusBadge
                        status={treatment.status}
                        className="shrink-0"
                    />
                </div>
            </CardHeader>
            <CardContent className="px-4 pb-3 sm:px-5">
                <TreatmentProgress
                    completedSessions={treatment.completed_sessions_count}
                    plannedSessions={treatment.planned_sessions}
                    compact
                />
            </CardContent>
            <CardFooter className="justify-between gap-3 px-4 pt-0 pb-3 sm:px-5 sm:pb-4">
                {treatment.starts_on ? (
                    <p className="flex min-w-0 items-center gap-1.5 text-xs font-medium text-muted-foreground tabular-nums">
                        <CalendarDaysIcon
                            aria-hidden
                            className="size-3.5 shrink-0"
                        />
                        <span className="truncate">
                            Inicio ·{' '}
                            {dateFormatter.format(
                                new Date(
                                    `${treatment.starts_on.slice(0, 10)}T00:00:00`,
                                ),
                            )}
                        </span>
                    </p>
                ) : (
                    <span />
                )}
                <Button
                    asChild
                    variant="ghost"
                    className="shrink-0 px-2 text-primary hover:text-primary"
                >
                    <Link href={show.url([petId, treatment.id])}>
                        Ver tratamiento
                        <ArrowRightIcon aria-hidden="true" />
                    </Link>
                </Button>
            </CardFooter>
        </Card>
    );
}

function TreatmentGroup({
    title,
    treatments,
    petId,
    historical = false,
}: {
    title: string;
    treatments: TreatmentSummary[];
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
                    <TreatmentCard
                        key={treatment.id}
                        treatment={treatment}
                        petId={petId}
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
    petTreatments: TreatmentSummary[];
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
