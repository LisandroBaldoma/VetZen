import { Head, Link } from '@inertiajs/react';
import {
    ArrowRight,
    ClipboardList,
    HeartPulse,
    PawPrint,
    Plus,
    Stethoscope,
} from 'lucide-react';
import PageHeader from '@/components/page-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard } from '@/routes';
import {
    create as createPet,
    index as petsIndex,
    show as showPet,
} from '@/routes/pets';
import { show as showRequest } from '@/routes/pets/service-requests';
import { show as showTreatment } from '@/routes/pets/treatments';
import { index as servicesIndex } from '@/routes/services';
import type {
    ClientDashboardProps,
    DashboardRequest,
    DashboardTreatment,
} from '@/types';

const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
});

const treatmentStatusLabels: Record<DashboardTreatment['status'], string> = {
    pending: 'Pendiente',
    in_progress: 'En curso',
    suspended: 'Suspendido',
};

export default function ClientDashboard({
    pets,
    pendingRequests,
    activeTreatments,
}: ClientDashboardProps) {
    return (
        <>
            <Head title="Inicio" />
            <div className="space-y-6 p-4 md:p-6">
                <PageHeader
                    title="Inicio"
                    description="Consultá el seguimiento de tus mascotas y su atención en VetZen."
                    actions={
                        <Button asChild>
                            <Link href={servicesIndex()}>
                                <Stethoscope aria-hidden="true" />
                                Explorar servicios
                            </Link>
                        </Button>
                    }
                />

                <section aria-labelledby="pets-title" className="space-y-3">
                    <div className="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2
                                id="pets-title"
                                className="text-lg font-semibold"
                            >
                                Mis mascotas
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Accedé a la información de cada una.
                            </p>
                        </div>
                        <div className="flex flex-wrap gap-2">
                            {pets.length > 0 && (
                                <Button asChild variant="outline" size="sm">
                                    <Link href={petsIndex()}>Ver todas</Link>
                                </Button>
                            )}
                            <Button asChild size="sm">
                                <Link href={createPet()}>
                                    <Plus aria-hidden="true" />
                                    Registrar mascota
                                </Link>
                            </Button>
                        </div>
                    </div>

                    {pets.length === 0 ? (
                        <Card className="border-dashed shadow-none">
                            <CardContent className="flex flex-col items-start gap-4">
                                <div className="rounded-full bg-muted p-3">
                                    <PawPrint
                                        className="size-6 text-muted-foreground"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div className="space-y-1">
                                    <p className="font-medium">
                                        Todavía no registraste mascotas.
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        Registrá una mascota para solicitar
                                        atención y consultar su seguimiento.
                                    </p>
                                </div>
                                <Button asChild>
                                    <Link href={createPet()}>
                                        Registrar mascota
                                    </Link>
                                </Button>
                            </CardContent>
                        </Card>
                    ) : (
                        <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                            {pets.map((pet) => (
                                <Link
                                    key={pet.id}
                                    href={showPet(pet.id)}
                                    className="group rounded-xl border bg-card p-4 transition-colors hover:bg-muted/50 focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                >
                                    <div className="flex items-center justify-between gap-3">
                                        <div className="min-w-0">
                                            <p className="truncate font-medium">
                                                {pet.name}
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                {pet.species}
                                            </p>
                                        </div>
                                        <ArrowRight
                                            className="size-4 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-0.5"
                                            aria-hidden="true"
                                        />
                                    </div>
                                </Link>
                            ))}
                        </div>
                    )}
                </section>

                <div className="grid gap-6 lg:grid-cols-2">
                    <RequestsSection
                        requests={pendingRequests}
                        hasPets={pets.length > 0}
                    />
                    <TreatmentsSection
                        treatments={activeTreatments}
                        hasPets={pets.length > 0}
                    />
                </div>
            </div>
        </>
    );
}

function RequestsSection({
    requests,
    hasPets,
}: {
    requests: DashboardRequest[];
    hasPets: boolean;
}) {
    return (
        <section aria-labelledby="pending-requests-title" className="space-y-3">
            <div className="flex items-center gap-2">
                <ClipboardList
                    className="size-5 text-muted-foreground"
                    aria-hidden="true"
                />
                <h2
                    id="pending-requests-title"
                    className="text-lg font-semibold"
                >
                    Solicitudes pendientes
                </h2>
            </div>

            {requests.length === 0 ? (
                <Card className="h-full border-dashed shadow-none">
                    <CardContent className="space-y-3 text-sm text-muted-foreground">
                        <p>
                            {hasPets
                                ? 'No tenés solicitudes de atención pendientes.'
                                : 'Tus solicitudes aparecerán después de registrar una mascota.'}
                        </p>
                        {hasPets && (
                            <Button asChild variant="outline" size="sm">
                                <Link href={servicesIndex()}>
                                    Explorar servicios
                                </Link>
                            </Button>
                        )}
                    </CardContent>
                </Card>
            ) : (
                <div className="grid gap-3">
                    {requests.map((request) => (
                        <Card
                            key={request.id}
                            className="gap-4 py-4 shadow-none"
                        >
                            <CardHeader className="flex-row items-start justify-between gap-3">
                                <div className="min-w-0 space-y-1">
                                    <CardTitle>
                                        <Link
                                            href={showPet(request.pet.id)}
                                            className="underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                        >
                                            {request.pet.name}
                                        </Link>
                                    </CardTitle>
                                    <p className="text-sm text-muted-foreground">
                                        {request.service.name}
                                    </p>
                                </div>
                                <Badge variant="outline">Pendiente</Badge>
                            </CardHeader>
                            <CardContent className="flex flex-wrap items-center justify-between gap-3">
                                <span className="text-sm text-muted-foreground">
                                    {dateFormatter.format(
                                        new Date(request.createdAt),
                                    )}
                                </span>
                                <Button asChild variant="ghost" size="sm">
                                    <Link
                                        href={showRequest([
                                            request.pet.id,
                                            request.id,
                                        ])}
                                    >
                                        Ver solicitud
                                        <ArrowRight aria-hidden="true" />
                                    </Link>
                                </Button>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            )}
        </section>
    );
}

function TreatmentsSection({
    treatments,
    hasPets,
}: {
    treatments: DashboardTreatment[];
    hasPets: boolean;
}) {
    return (
        <section
            aria-labelledby="active-treatments-title"
            className="space-y-3"
        >
            <div className="flex items-center gap-2">
                <HeartPulse
                    className="size-5 text-muted-foreground"
                    aria-hidden="true"
                />
                <h2
                    id="active-treatments-title"
                    className="text-lg font-semibold"
                >
                    Tratamientos activos
                </h2>
            </div>

            {treatments.length === 0 ? (
                <Card className="h-full border-dashed shadow-none">
                    <CardContent className="text-sm text-muted-foreground">
                        {hasPets
                            ? 'No tenés tratamientos activos.'
                            : 'Tus tratamientos aparecerán después de registrar una mascota.'}
                    </CardContent>
                </Card>
            ) : (
                <div className="grid gap-3">
                    {treatments.map((treatment) => {
                        const percentage = Math.min(
                            100,
                            Math.round(
                                (treatment.completedSessions /
                                    treatment.plannedSessions) *
                                    100,
                            ),
                        );

                        return (
                            <Card
                                key={treatment.id}
                                className="gap-4 py-4 shadow-none"
                            >
                                <CardHeader className="gap-2">
                                    <div className="flex flex-wrap items-start justify-between gap-2">
                                        <div className="min-w-0 space-y-1">
                                            <CardTitle>
                                                {treatment.treatmentName}
                                            </CardTitle>
                                            <Link
                                                href={showPet(treatment.pet.id)}
                                                className="text-sm text-muted-foreground underline-offset-4 hover:text-foreground hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                            >
                                                {treatment.pet.name}
                                            </Link>
                                        </div>
                                        <Badge variant="outline">
                                            {
                                                treatmentStatusLabels[
                                                    treatment.status
                                                ]
                                            }
                                        </Badge>
                                    </div>
                                </CardHeader>
                                <CardContent className="space-y-3">
                                    <div className="space-y-2">
                                        <div className="flex items-center justify-between gap-3 text-sm">
                                            <span>Progreso</span>
                                            <span className="text-muted-foreground">
                                                {treatment.completedSessions} de{' '}
                                                {treatment.plannedSessions}{' '}
                                                sesiones completadas
                                            </span>
                                        </div>
                                        <div
                                            role="progressbar"
                                            aria-label={`Progreso del tratamiento ${treatment.treatmentName}`}
                                            aria-valuemin={0}
                                            aria-valuemax={
                                                treatment.plannedSessions
                                            }
                                            aria-valuenow={
                                                treatment.completedSessions
                                            }
                                            className="h-2 overflow-hidden rounded-full bg-muted"
                                        >
                                            <div
                                                className="h-full rounded-full bg-primary transition-[width]"
                                                style={{
                                                    width: `${percentage}%`,
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <Button asChild variant="ghost" size="sm">
                                        <Link
                                            href={showTreatment([
                                                treatment.pet.id,
                                                treatment.id,
                                            ])}
                                        >
                                            Ver tratamiento
                                            <ArrowRight aria-hidden="true" />
                                        </Link>
                                    </Button>
                                </CardContent>
                            </Card>
                        );
                    })}
                </div>
            )}
        </section>
    );
}

ClientDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Inicio',
            href: dashboard(),
        },
    ],
};
