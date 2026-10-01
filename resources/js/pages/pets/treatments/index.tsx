import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index, show } from '@/routes/pets/treatments';

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
};

type TreatmentSummary = {
    id: number;
    treatment_name: string;
    planned_sessions: number;
    completed_sessions_count: number;
    status: string;
};

const statusLabels: Record<string, string> = {
    pending: 'Pendiente',
    in_progress: 'En curso',
    completed: 'Completado',
    suspended: 'Suspendido',
    cancelled: 'Cancelado',
};

const statusStyles: Record<string, string> = {
    pending: 'bg-operational text-operational-foreground',
    in_progress: 'bg-clinical text-clinical-foreground',
    completed: 'bg-clinical text-clinical-foreground',
    suspended: 'bg-muted text-muted-foreground',
    cancelled: 'bg-muted text-muted-foreground',
};

export default function Treatments({
    pet,
    petTreatments,
}: {
    pet: Pet;
    petTreatments: TreatmentSummary[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`Tratamientos de ${pet.name}`} />
            <div className="workspace-reading">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <div className="space-y-1 border-b border-border pb-6">
                    <p className="text-[0.6875rem] font-semibold tracking-[0.16em] text-clinical-foreground uppercase">
                        Continuidad de atención
                    </p>
                    <Heading
                        title={`Tratamientos de ${pet.name}`}
                        description="Consulta de progreso y sesiones."
                    />
                </div>
                <div className="border-y border-border">
                    {petTreatments.length === 0 && (
                        <p className="border-dashed py-8 text-muted-foreground">
                            No hay tratamientos asignados.
                        </p>
                    )}
                    {petTreatments.map((item) => (
                        <Link
                            key={item.id}
                            href={show.url([pet.id, item.id])}
                            className="block border-b border-border py-6 transition-colors last:border-b-0 hover:bg-clinical/25 sm:px-4"
                        >
                            <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <strong>{item.treatment_name}</strong>
                                    <p className="text-sm text-muted-foreground">
                                        {item.completed_sessions_count} de{' '}
                                        {item.planned_sessions} sesiones
                                        completadas
                                    </p>
                                </div>
                                <span
                                    className={`inline-flex items-center gap-2 rounded-sm px-2 py-1 text-xs font-semibold ${statusStyles[item.status] ?? 'bg-muted text-muted-foreground'}`}
                                >
                                    <span
                                        className="size-1.5 rounded-full bg-current"
                                        aria-hidden="true"
                                    />
                                    {statusLabels[item.status] ?? item.status}
                                </span>
                            </div>
                            <div className="mt-5 h-1.5 overflow-hidden rounded-full bg-muted">
                                <div
                                    className="h-full rounded-full bg-primary"
                                    style={{
                                        width: `${Math.min(100, (item.completed_sessions_count / item.planned_sessions) * 100)}%`,
                                    }}
                                />
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </>
    );
}
