import { Head, Link, setLayoutProps } from '@inertiajs/react';
import PetContextHeader from '@/components/pet-context-header';
import TreatmentProgress from '@/components/treatment-progress';
import TreatmentSessionCard from '@/components/treatment-session-card';
import type { TreatmentSession } from '@/components/treatment-session-card';
import TreatmentStatusBadge from '@/components/treatment-status-badge';
import type { TreatmentStatus } from '@/components/treatment-status-badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
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

type PetTreatment = {
    id: number;
    treatment_name: string;
    treatment_description: string;
    planned_sessions: number;
    default_session_price: string;
    currency: string;
    starts_on: string;
    status: TreatmentStatus;
    notes: string | null;
    procedure_snapshots: {
        id: number;
        procedure_name: string;
        procedure_description: string | null;
    }[];
    sessions: TreatmentSession[];
};

const dateFormatter = new Intl.DateTimeFormat('es-AR', { dateStyle: 'long' });

function money(value: string, currency: string): string {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency,
    }).format(Number(value));
}

function SessionGroup({
    title,
    sessions,
    highlighted = false,
    tone = 'default',
    compact = false,
    collapsible = false,
    showNotes = true,
}: {
    title: string;
    sessions: TreatmentSession[];
    highlighted?: boolean;
    tone?: 'default' | 'completed' | 'cancelled';
    compact?: boolean;
    collapsible?: boolean;
    showNotes?: boolean;
}) {
    if (sessions.length === 0) {
        return null;
    }

    const sessionCards = sessions.map((session, sessionIndex) => (
        <TreatmentSessionCard
            key={session.id}
            session={session}
            highlighted={highlighted && sessionIndex === 0}
            compact={compact}
            showNotes={showNotes}
        />
    ));

    if (collapsible) {
        return (
            <details className="rounded-xl bg-destructive/5 p-4 sm:p-5">
                <summary className="flex cursor-pointer list-none items-baseline justify-between gap-4">
                    <h3 className="text-section-title font-semibold text-destructive">
                        {title}
                    </h3>
                    <span className="text-sm text-muted-foreground tabular-nums">
                        {sessions.length}{' '}
                        {sessions.length === 1 ? 'sesión' : 'sesiones'}
                    </span>
                </summary>
                <div className="mt-3 space-y-3">{sessionCards}</div>
            </details>
        );
    }

    return (
        <section
            className={
                tone === 'cancelled'
                    ? 'rounded-xl bg-destructive/5 p-4 sm:p-5'
                    : 'rounded-xl bg-card p-4 shadow-sm sm:p-5'
            }
        >
            <div className="mb-3 flex items-baseline justify-between gap-4">
                <h3
                    className={`text-section-title font-semibold ${tone === 'cancelled' ? 'text-destructive' : tone === 'completed' ? 'text-muted-foreground' : ''}`}
                >
                    {title}
                </h3>
                <span className="text-sm text-muted-foreground tabular-nums">
                    {sessions.length}{' '}
                    {sessions.length === 1 ? 'sesión' : 'sesiones'}
                </span>
            </div>
            <div className="space-y-3">{sessionCards}</div>
        </section>
    );
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
    const scheduledSessions = petTreatment.sessions
        .filter(
            (session) =>
                session.status === 'pending' && session.scheduled_at !== null,
        )
        .sort(
            (first, second) =>
                new Date(first.scheduled_at as string).getTime() -
                    new Date(second.scheduled_at as string).getTime() ||
                first.session_number - second.session_number,
        );
    const pendingSessions = petTreatment.sessions.filter(
        (session) =>
            session.status === 'pending' && session.scheduled_at === null,
    );
    const completedSessions = petTreatment.sessions.filter(
        (session) => session.status === 'completed',
    );
    const cancelledSessions = petTreatment.sessions.filter(
        (session) => session.status === 'cancelled',
    );

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
            <div className="workspace-reading">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <div className="space-y-4">
                    <Button asChild variant="link" className="h-11 px-0">
                        <Link href={index.url(pet.id)}>
                            Volver a tratamientos
                        </Link>
                    </Button>
                    <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
                        <CardContent className="flex items-start justify-between gap-4 p-4 sm:p-5">
                            <div className="min-w-0">
                                <p className="text-meta font-semibold tracking-[0.14em] text-primary uppercase">
                                    Tratamiento asignado
                                </p>
                                <h1 className="mt-1 text-page-title font-semibold tracking-tight text-balance">
                                    {petTreatment.treatment_name}
                                </h1>
                                <div className="mt-3">
                                    <TreatmentStatusBadge
                                        status={petTreatment.status}
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <TreatmentProgress
                    completedSessions={completed}
                    plannedSessions={petTreatment.planned_sessions}
                    showRemaining
                />

                <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
                    <CardHeader className="p-4 sm:p-5">
                        <CardTitle className="text-section-title">
                            Información del tratamiento
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="px-4 pb-4 sm:px-5">
                        <dl className="divide-y divide-border-subtle text-sm">
                            <div className="flex items-baseline justify-between gap-4 py-3 first:pt-0">
                                <dt className="text-muted-foreground">
                                    Inicio
                                </dt>
                                <dd className="text-right font-medium tabular-nums">
                                    {dateFormatter.format(
                                        new Date(
                                            `${petTreatment.starts_on.slice(0, 10)}T00:00:00`,
                                        ),
                                    )}
                                </dd>
                            </div>
                            <div className="flex items-baseline justify-between gap-4 py-3">
                                <dt className="text-muted-foreground">
                                    Sesiones requeridas
                                </dt>
                                <dd className="font-medium tabular-nums">
                                    {petTreatment.planned_sessions}
                                </dd>
                            </div>
                            <div className="flex items-baseline justify-between gap-4 py-3">
                                <dt className="text-muted-foreground">
                                    Precio predeterminado
                                </dt>
                                <dd className="font-medium tabular-nums">
                                    {money(
                                        petTreatment.default_session_price,
                                        petTreatment.currency,
                                    )}
                                </dd>
                            </div>
                            <div className="flex items-baseline justify-between gap-4 py-3 last:pb-0">
                                <dt className="text-muted-foreground">
                                    Moneda
                                </dt>
                                <dd className="font-medium">
                                    {petTreatment.currency}
                                </dd>
                            </div>
                        </dl>
                    </CardContent>
                </Card>

                <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
                    <CardHeader className="p-4 sm:p-5">
                        <CardTitle className="text-section-title">
                            Descripción del tratamiento
                        </CardTitle>
                        <p className="text-sm text-muted-foreground">
                            Estos datos y procedimientos son snapshots de la
                            asignación.
                        </p>
                    </CardHeader>
                    <CardContent className="space-y-5 px-4 pb-4 sm:px-5">
                        <p className="whitespace-pre-wrap">
                            {petTreatment.treatment_description}
                        </p>
                    </CardContent>
                </Card>

                <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
                    <CardHeader className="p-4 sm:p-5">
                        <CardTitle className="text-section-title">
                            Procedimientos incluidos
                        </CardTitle>
                        <p className="text-sm text-muted-foreground">
                            Definidos al momento de la asignación del
                            tratamiento.
                        </p>
                    </CardHeader>
                    <CardContent className="px-4 pb-4 sm:px-5">
                        <ul className="flex flex-wrap gap-2">
                            {petTreatment.procedure_snapshots.map(
                                (procedure) => (
                                    <li
                                        key={procedure.id}
                                        className="rounded-lg bg-surface-subtle px-3 py-2 text-sm"
                                    >
                                        <p className="font-medium">
                                            {procedure.procedure_name}
                                        </p>
                                        {procedure.procedure_description && (
                                            <p className="mt-1 text-muted-foreground">
                                                {
                                                    procedure.procedure_description
                                                }
                                            </p>
                                        )}
                                    </li>
                                ),
                            )}
                        </ul>
                    </CardContent>
                </Card>

                <section className="rounded-xl bg-surface-subtle p-4 shadow-sm sm:p-5">
                    <h2 className="text-meta font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Notas del tratamiento
                    </h2>
                    <p className="mt-2 text-sm leading-relaxed whitespace-pre-wrap text-foreground">
                        {petTreatment.notes || 'Sin notas.'}
                    </p>
                </section>

                <section className="space-y-6">
                    <div>
                        <h2 className="text-page-title font-semibold">
                            Sesiones
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Las sesiones canceladas se conservan en el
                            historial.
                        </p>
                    </div>
                    <SessionGroup
                        title="Próxima sesión programada"
                        sessions={scheduledSessions.slice(0, 1)}
                        highlighted
                    />
                    <SessionGroup
                        title="Sesiones completadas"
                        sessions={completedSessions}
                        tone="completed"
                        compact
                    />
                    <section className="space-y-4 rounded-xl bg-card p-4 shadow-sm sm:p-5">
                        <div>
                            <h3 className="text-section-title font-semibold">
                                Plan restante
                            </h3>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Sesiones pendientes de realizar.
                            </p>
                        </div>
                        <SessionGroup
                            title="Otras sesiones programadas"
                            sessions={scheduledSessions.slice(1)}
                        />
                        <SessionGroup
                            title="Sesiones pendientes sin programar"
                            sessions={pendingSessions}
                        />
                    </section>
                    <SessionGroup
                        title="Sesiones canceladas"
                        sessions={cancelledSessions}
                        collapsible
                    />
                </section>
            </div>
        </>
    );
}
