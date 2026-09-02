import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { Badge } from '@/components/ui/badge';
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

type TreatmentSummary = {
    id: number;
    treatment_name: string;
    planned_sessions: number;
    completed_sessions_count: number;
    status: string;
    starts_on: string;
};

const statusLabels: Record<string, string> = {
    pending: 'Pendiente',
    in_progress: 'En curso',
    completed: 'Completado',
    suspended: 'Suspendido',
    cancelled: 'Cancelado',
};

export default function PetTreatments({
    pet,
    petTreatments,
}: {
    pet: Pet;
    petTreatments: TreatmentSummary[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`Tratamientos de ${pet.name}`} />
            <div className="mx-auto max-w-4xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <Heading
                        title={`Tratamientos de ${pet.name}`}
                        description="Seguimiento de condiciones, sesiones y progreso del paciente."
                    />
                    <Button asChild>
                        <Link href={create.url(pet.id)}>
                            Iniciar tratamiento
                        </Link>
                    </Button>
                </div>
                <div className="grid gap-3">
                    {petTreatments.length === 0 && (
                        <p className="rounded-xl border p-6 text-muted-foreground">
                            Todavía no hay tratamientos asignados.
                        </p>
                    )}
                    {petTreatments.map((item) => (
                        <Link
                            key={item.id}
                            href={show.url([pet.id, item.id])}
                            className="rounded-xl border p-5 transition-colors hover:bg-muted/40"
                        >
                            <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div className="min-w-0">
                                    <div className="font-semibold break-words">
                                        {item.treatment_name}
                                    </div>
                                    <div className="text-sm text-muted-foreground">
                                        {item.completed_sessions_count} de{' '}
                                        {item.planned_sessions} sesiones
                                        completadas
                                    </div>
                                </div>
                                <Badge variant="outline">
                                    {statusLabels[item.status] ?? item.status}
                                </Badge>
                            </div>
                            <div className="mt-4 h-2 overflow-hidden rounded-full bg-muted">
                                <div
                                    className="h-full rounded-full bg-primary"
                                    style={{
                                        width: `${Math.min(100, (item.completed_sessions_count / item.planned_sessions) * 100)}%`,
                                    }}
                                    aria-label={`Progreso: ${item.completed_sessions_count} de ${item.planned_sessions}`}
                                />
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </>
    );
}
