import { Head, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { Badge } from '@/components/ui/badge';
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

type Session = {
    id: number;
    session_number: number;
    scheduled_at: string | null;
    price: string;
    currency: string;
    status: 'pending' | 'completed' | 'cancelled';
    notes: string | null;
};

type PetTreatment = {
    id: number;
    treatment_name: string;
    treatment_description: string;
    planned_sessions: number;
    default_session_price: string;
    currency: string;
    starts_on: string;
    status: string;
    notes: string | null;
    procedure_snapshots: {
        id: number;
        procedure_name: string;
        procedure_description: string | null;
    }[];
    sessions: Session[];
};

const statusLabels: Record<string, string> = {
    pending: 'Pendiente',
    in_progress: 'En curso',
    completed: 'Completado',
    suspended: 'Suspendido',
    cancelled: 'Cancelado',
};

const sessionStatusLabels: Record<Session['status'], string> = {
    pending: 'Pendiente',
    completed: 'Completada',
    cancelled: 'Cancelada',
};

const dateFormatter = new Intl.DateTimeFormat('es-AR', { dateStyle: 'long' });
const dateTimeFormatter = new Intl.DateTimeFormat('es-AR', {
    dateStyle: 'medium',
    timeStyle: 'short',
});

function money(value: string, currency: string): string {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency,
    }).format(Number(value));
}

export default function Treatment({
    pet,
    petTreatment,
}: {
    pet: Pet;
    petTreatment: PetTreatment;
}) {
    const completed = petTreatment.sessions.filter(
        (session) => session.status === 'completed',
    ).length;

    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
            {
                title: petTreatment.treatment_name,
                href: show([pet.id, petTreatment.id]),
            },
        ],
    });

    return (
        <>
            <Head title={petTreatment.treatment_name} />
            <div className="mx-auto max-w-4xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <Heading
                        title={petTreatment.treatment_name}
                        description={`Tratamiento de ${pet.name}`}
                    />
                    <Badge variant="outline">
                        {statusLabels[petTreatment.status] ??
                            petTreatment.status}
                    </Badge>
                </div>

                <section className="space-y-4 rounded-xl border p-5">
                    <div className="flex items-end justify-between gap-4">
                        <div>
                            <p className="text-sm text-muted-foreground">
                                Progreso
                            </p>
                            <p className="text-2xl font-semibold">
                                {completed} de {petTreatment.planned_sessions}
                            </p>
                        </div>
                        <p className="text-sm text-muted-foreground">
                            {Math.round(
                                (completed / petTreatment.planned_sessions) *
                                    100,
                            )}
                            %
                        </p>
                    </div>
                    <div className="h-2 overflow-hidden rounded-full bg-muted">
                        <div
                            className="h-full rounded-full bg-primary"
                            style={{
                                width: `${Math.min(100, (completed / petTreatment.planned_sessions) * 100)}%`,
                            }}
                        />
                    </div>
                </section>

                <section className="space-y-4 rounded-xl border p-5">
                    <div>
                        <h2 className="font-semibold">Condiciones acordadas</h2>
                        <p className="mt-1 text-sm whitespace-pre-wrap text-muted-foreground">
                            {petTreatment.treatment_description}
                        </p>
                    </div>
                    <dl className="grid gap-4 sm:grid-cols-3">
                        <div>
                            <dt className="text-sm text-muted-foreground">
                                Fecha de inicio
                            </dt>
                            <dd className="font-medium">
                                {dateFormatter.format(
                                    new Date(
                                        `${petTreatment.starts_on.slice(0, 10)}T00:00:00`,
                                    ),
                                )}
                            </dd>
                        </div>
                        <div>
                            <dt className="text-sm text-muted-foreground">
                                Sesiones requeridas
                            </dt>
                            <dd className="font-medium">
                                {petTreatment.planned_sessions}
                            </dd>
                        </div>
                        <div>
                            <dt className="text-sm text-muted-foreground">
                                Precio de referencia
                            </dt>
                            <dd className="font-medium">
                                {money(
                                    petTreatment.default_session_price,
                                    petTreatment.currency,
                                )}
                            </dd>
                        </div>
                    </dl>
                    <div>
                        <h3 className="text-sm font-medium">Procedimientos</h3>
                        <ul className="mt-2 grid gap-2 sm:grid-cols-2">
                            {petTreatment.procedure_snapshots.map(
                                (procedure) => (
                                    <li
                                        key={procedure.id}
                                        className="rounded-lg bg-muted/50 p-3"
                                    >
                                        <p className="font-medium">
                                            {procedure.procedure_name}
                                        </p>
                                        {procedure.procedure_description && (
                                            <p className="mt-1 text-sm text-muted-foreground">
                                                {
                                                    procedure.procedure_description
                                                }
                                            </p>
                                        )}
                                    </li>
                                ),
                            )}
                        </ul>
                    </div>
                    <div>
                        <h3 className="text-sm font-medium">Notas generales</h3>
                        <p className="mt-1 text-sm whitespace-pre-wrap text-muted-foreground">
                            {petTreatment.notes || 'Sin notas.'}
                        </p>
                    </div>
                </section>

                <section className="space-y-3">
                    <h2 className="text-lg font-semibold">Sesiones</h2>
                    {petTreatment.sessions.map((session) => (
                        <article
                            key={session.id}
                            className="rounded-xl border p-4 sm:p-5"
                        >
                            <div className="flex items-center justify-between gap-3">
                                <h3 className="font-semibold">
                                    Sesión #{session.session_number}
                                </h3>
                                <Badge variant="outline">
                                    {sessionStatusLabels[session.status]}
                                </Badge>
                            </div>
                            <dl className="mt-4 grid gap-3 text-sm sm:grid-cols-3">
                                <div>
                                    <dt className="text-muted-foreground">
                                        Fecha y hora
                                    </dt>
                                    <dd className="font-medium">
                                        {session.scheduled_at
                                            ? dateTimeFormatter.format(
                                                  new Date(
                                                      session.scheduled_at,
                                                  ),
                                              )
                                            : 'Sin programar'}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-muted-foreground">
                                        Precio
                                    </dt>
                                    <dd className="font-medium">
                                        {money(session.price, session.currency)}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-muted-foreground">
                                        Notas
                                    </dt>
                                    <dd className="whitespace-pre-wrap">
                                        {session.notes || 'Sin notas.'}
                                    </dd>
                                </div>
                            </dl>
                        </article>
                    ))}
                </section>
            </div>
        </>
    );
}
