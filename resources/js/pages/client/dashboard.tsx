import { Head, Link } from '@inertiajs/react';
import {
    CalendarClockIcon,
    CalendarDaysIcon,
    ClipboardListIcon,
    HeartPulseIcon,
    PawPrintIcon,
    PlusIcon,
    StethoscopeIcon,
} from 'lucide-react';
import { DashboardHero } from '@/components/dashboard/dashboard-hero';
import { DashboardActivityList } from '@/components/dashboard/priority-requests';
import type { DashboardActivity } from '@/components/dashboard/priority-requests';
import PetTreatmentCard from '@/components/pet-treatment-card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { dashboard } from '@/routes';
import {
    create as createPet,
    index as petsIndex,
    photo,
    show as showPet,
} from '@/routes/pets';
import { show as showRequest } from '@/routes/pets/service-requests';
import { show as showTreatment } from '@/routes/pets/treatments';
import { index as servicesIndex } from '@/routes/services';
import type {
    ClientDashboardProps,
    DashboardRequest,
    DashboardSelectedPet,
} from '@/types';

const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    dateStyle: 'medium',
});

function formatSessionDate(value: string, timezone: string): string {
    return new Intl.DateTimeFormat('es-AR', {
        dateStyle: 'medium',
        timeStyle: 'short',
        timeZone: timezone,
    }).format(new Date(value));
}

function formatSex(sex: string): string {
    if (sex.toLowerCase() === 'female') {
        return 'Hembra';
    }

    if (sex.toLowerCase() === 'male') {
        return 'Macho';
    }

    return sex;
}

function age(birthDate: string | null): string | null {
    if (birthDate === null) {
        return null;
    }

    const birth = new Date(`${birthDate}T00:00:00`);
    const today = new Date();
    let years = today.getFullYear() - birth.getFullYear();
    const hasNotHadBirthday =
        today.getMonth() < birth.getMonth() ||
        (today.getMonth() === birth.getMonth() &&
            today.getDate() < birth.getDate());

    if (hasNotHadBirthday) {
        years -= 1;
    }

    return `${years} ${years === 1 ? 'año' : 'años'}`;
}

export default function ClientDashboard({
    pets,
    selectedPet,
    nextSession,
    pendingRequests,
    activeTreatments,
    timezone,
}: ClientDashboardProps) {
    const requestItems = requestActivities(pendingRequests);

    return (
        <>
            <Head title="Inicio" />
            <div className="workspace-reading">
                <DashboardHero
                    showSummary={false}
                    description="Consultá el seguimiento de tus mascotas y su atención en VetZen."
                    action={
                        <Button asChild className="min-h-11">
                            <Link href={servicesIndex()}>
                                <StethoscopeIcon aria-hidden="true" />
                                Explorar servicios
                            </Link>
                        </Button>
                    }
                />

                {selectedPet === null ? (
                    <Card className="items-center border-dashed p-6 text-center shadow-none sm:p-10">
                        <div className="flex size-14 items-center justify-center rounded-full bg-surface-subtle text-primary">
                            <PawPrintIcon aria-hidden className="size-7" />
                        </div>
                        <CardHeader className="px-0">
                            <CardTitle>
                                Todavía no registraste mascotas.
                            </CardTitle>
                            <p className="text-sm text-muted-foreground">
                                Registrá una mascota para consultar su atención
                                y solicitar servicios.
                            </p>
                        </CardHeader>
                        <Button asChild>
                            <Link href={createPet()}>
                                <PlusIcon aria-hidden="true" />
                                Registrar mascota
                            </Link>
                        </Button>
                    </Card>
                ) : (
                    <>
                        {pets.length > 1 && (
                            <nav
                                aria-label="Seleccionar mascota"
                                className="overflow-x-auto pb-1"
                            >
                                <div className="flex min-w-max gap-2">
                                    {pets.map((pet) => {
                                        const isSelected =
                                            pet.id === selectedPet.id;

                                        return (
                                            <Button
                                                key={pet.id}
                                                asChild
                                                variant={
                                                    isSelected
                                                        ? 'default'
                                                        : 'outline'
                                                }
                                                className="min-h-11 rounded-full"
                                            >
                                                <Link
                                                    href={dashboard({
                                                        query: { pet: pet.id },
                                                    })}
                                                    aria-current={
                                                        isSelected
                                                            ? 'page'
                                                            : undefined
                                                    }
                                                >
                                                    <PawPrintIcon aria-hidden />
                                                    {pet.name}
                                                    <span className="text-xs font-medium opacity-75">
                                                        {pet.species}
                                                    </span>
                                                </Link>
                                            </Button>
                                        );
                                    })}
                                </div>
                            </nav>
                        )}

                        <PetOverview
                            pet={selectedPet}
                            nextSession={nextSession}
                            timezone={timezone}
                        />

                        {nextSession && (
                            <Card className="gap-0 overflow-hidden border-primary/30 bg-primary/5 p-0 shadow-sm">
                                <CardHeader className="gap-3 p-4 sm:p-5">
                                    <div className="flex flex-wrap items-center justify-between gap-3">
                                        <Badge className="border-transparent bg-primary/10 text-primary">
                                            <CalendarClockIcon aria-hidden />
                                            Próxima sesión
                                        </Badge>
                                        <span className="text-sm font-semibold text-muted-foreground tabular-nums">
                                            Sesión {nextSession.sessionNumber}{' '}
                                            de {nextSession.plannedSessions}
                                        </span>
                                    </div>
                                    <CardTitle className="text-section-title">
                                        {nextSession.treatmentName}
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="px-4 pb-4 sm:px-5">
                                    <div className="flex items-center gap-3 rounded-xl bg-card p-3 shadow-xs sm:p-4">
                                        <div className="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                            <CalendarDaysIcon
                                                aria-hidden
                                                className="size-5"
                                            />
                                        </div>
                                        <p className="text-section-title font-semibold text-primary tabular-nums">
                                            {formatSessionDate(
                                                nextSession.scheduledAt,
                                                timezone,
                                            )}
                                        </p>
                                    </div>
                                </CardContent>
                                <CardFooter className="justify-end px-4 pt-0 pb-4 sm:px-5 sm:pb-5">
                                    <Button asChild variant="secondary">
                                        <Link
                                            href={showTreatment([
                                                selectedPet.id,
                                                nextSession.treatmentId,
                                            ])}
                                        >
                                            Ver tratamiento
                                        </Link>
                                    </Button>
                                </CardFooter>
                            </Card>
                        )}

                        <section
                            aria-labelledby="active-treatments-title"
                            className="space-y-4"
                        >
                            <div>
                                <h2
                                    id="active-treatments-title"
                                    className="text-xl font-semibold tracking-tight text-balance"
                                >
                                    Tratamientos activos
                                </h2>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    Seguimiento de los planes asignados a{' '}
                                    {selectedPet.name}.
                                </p>
                            </div>
                            {activeTreatments.length === 0 ? (
                                <Card className="gap-2 border-dashed p-4 shadow-none">
                                    <p className="font-semibold">
                                        No hay tratamientos activos.
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        Los tratamientos asignados aparecerán
                                        aquí para seguir su progreso.
                                    </p>
                                </Card>
                            ) : (
                                <div className="grid gap-4 xl:grid-cols-2">
                                    {activeTreatments.map((treatment) => (
                                        <PetTreatmentCard
                                            key={treatment.id}
                                            treatment={{
                                                id: treatment.id,
                                                treatment_name:
                                                    treatment.treatmentName,
                                                planned_sessions:
                                                    treatment.plannedSessions,
                                                completed_sessions_count:
                                                    treatment.completedSessions,
                                                status: treatment.status,
                                                starts_on: null,
                                                next_session:
                                                    treatment.nextSession ===
                                                    null
                                                        ? null
                                                        : {
                                                              scheduled_at:
                                                                  treatment
                                                                      .nextSession
                                                                      .scheduledAt,
                                                              session_number:
                                                                  treatment
                                                                      .nextSession
                                                                      .sessionNumber,
                                                          },
                                            }}
                                            href={showTreatment.url([
                                                selectedPet.id,
                                                treatment.id,
                                            ])}
                                            timezone={timezone}
                                        />
                                    ))}
                                </div>
                            )}
                        </section>

                        {requestItems.length > 0 && (
                            <DashboardActivityList
                                title="Solicitudes pendientes"
                                headingId="pending-requests-title"
                                items={requestItems}
                                compact
                                emptyState={{
                                    title: 'No tenés solicitudes pendientes.',
                                    description:
                                        'Podés explorar los servicios disponibles cuando necesites atención.',
                                }}
                            />
                        )}

                        <div className="flex flex-wrap gap-2 border-t pt-5">
                            <Button asChild variant="outline">
                                <Link href={petsIndex()}>
                                    Ver todas las mascotas
                                </Link>
                            </Button>
                            <Button asChild variant="secondary">
                                <Link href={createPet()}>
                                    <PlusIcon aria-hidden="true" />
                                    Registrar mascota
                                </Link>
                            </Button>
                        </div>
                    </>
                )}
            </div>
        </>
    );
}

function PetOverview({
    pet,
    nextSession,
    timezone,
}: {
    pet: DashboardSelectedPet;
    nextSession: ClientDashboardProps['nextSession'];
    timezone: string;
}) {
    const details = [
        pet.species,
        pet.breed,
        formatSex(pet.sex),
        age(pet.birthDate),
        pet.weight ? `${pet.weight} kg` : null,
    ].filter(Boolean);

    return (
        <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
            <CardContent className="p-4 sm:p-5">
                <div className="flex min-w-0 items-start gap-3.5">
                    <Avatar className="size-16 shrink-0 rounded-full border border-border bg-surface-subtle shadow-sm">
                        {pet.hasPhoto && (
                            <AvatarImage
                                src={photo.url(pet.id)}
                                alt={`Foto de ${pet.name}`}
                                className="object-cover"
                            />
                        )}
                        <AvatarFallback className="rounded-full bg-surface-subtle text-xl font-semibold text-primary">
                            {pet.name.slice(0, 1).toUpperCase()}
                        </AvatarFallback>
                    </Avatar>
                    <div className="min-w-0 flex-1">
                        <div className="flex flex-wrap items-center gap-2">
                            <h2 className="text-page-title font-semibold tracking-tight text-balance break-words">
                                {pet.name}
                            </h2>
                            <Badge variant="outline">{pet.species}</Badge>
                        </div>
                        <p className="mt-1 text-sm text-muted-foreground">
                            {details.join(' · ')}
                        </p>
                        <Button
                            asChild
                            variant="link"
                            className="mt-2 h-auto px-0"
                        >
                            <Link href={showPet(pet.id)}>
                                Ver ficha completa
                            </Link>
                        </Button>
                    </div>
                </div>
                <div className="mt-4 grid grid-cols-2 gap-3 border-t pt-4">
                    <div className="rounded-xl bg-surface-subtle p-3">
                        <HeartPulseIcon
                            aria-hidden
                            className="size-4 text-primary"
                        />
                        <p className="mt-2 text-section-title font-semibold tabular-nums">
                            {pet.activeTreatmentsCount}
                        </p>
                        <p className="text-xs font-medium text-muted-foreground">
                            Tratamientos activos
                        </p>
                    </div>
                    <div className="rounded-xl bg-surface-subtle p-3">
                        <CalendarClockIcon
                            aria-hidden
                            className="size-4 text-primary"
                        />
                        <p className="mt-2 text-sm font-semibold text-foreground tabular-nums">
                            {nextSession
                                ? formatSessionDate(
                                      nextSession.scheduledAt,
                                      timezone,
                                  )
                                : 'Sin programar'}
                        </p>
                        <p className="text-xs font-medium text-muted-foreground">
                            Próxima sesión
                        </p>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}

function requestActivities(requests: DashboardRequest[]): DashboardActivity[] {
    return requests.map((request) => ({
        id: request.id,
        title: request.service.name,
        subtitle: request.pet.name,
        status: 'Pendiente',
        statusTone: 'operational',
        timestamp: dateFormatter.format(new Date(request.createdAt)),
        icon: ClipboardListIcon,
        primaryAction: {
            label: 'Ver solicitud',
            href: showRequest([request.pet.id, request.id]),
        },
    }));
}

ClientDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Inicio',
            href: dashboard(),
        },
    ],
};
