import { Head, Link } from '@inertiajs/react';
import {
    ArrowRight,
    ClipboardList,
    Clock3,
    PawPrint,
    Plus,
    Stethoscope,
} from 'lucide-react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import {
    create as createPatient,
    index as patientsIndex,
    show as showPatient,
} from '@/routes/admin/pets';
import {
    index as requestsIndex,
    show as showRequest,
} from '@/routes/admin/service-requests';
import { index as servicesIndex } from '@/routes/admin/services';
import type { AdminDashboardProps, DashboardRequest } from '@/types';

const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
});

const requestStatusLabels: Record<DashboardRequest['status'], string> = {
    pending: 'Pendiente',
    resolved: 'Resuelta',
    cancelled: 'Cancelada',
};

const requestStatusStyles: Record<DashboardRequest['status'], string> = {
    pending: 'bg-operational text-operational-foreground',
    resolved: 'bg-clinical text-clinical-foreground',
    cancelled: 'bg-muted text-muted-foreground',
};

export default function AdminDashboard({
    pendingRequestsCount,
    requests,
}: AdminDashboardProps) {
    return (
        <>
            <Head title="Inicio" />
            <div className="workspace-operational">
                <PageHeader
                    title="Inicio"
                    description="Mesa de trabajo clínica para revisar la atención que requiere definición."
                    actions={
                        <Button asChild className="min-h-11">
                            <Link href={createPatient()}>
                                <Plus aria-hidden="true" />
                                Nuevo paciente
                            </Link>
                        </Button>
                    }
                />

                <div className="grid items-start gap-10 xl:grid-cols-[minmax(0,1fr)_15rem]">
                    <section
                        aria-labelledby="requests-title"
                        className="border-y border-border"
                    >
                        <div className="flex flex-col gap-5 py-6 sm:flex-row sm:items-end sm:justify-between">
                            <div className="space-y-2">
                                <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                    <ClipboardList
                                        className="size-4 text-operational-foreground"
                                        aria-hidden="true"
                                    />
                                    Atención prioritaria
                                </div>
                                <h2
                                    id="requests-title"
                                    className="text-2xl font-semibold tracking-tight text-balance"
                                >
                                    Solicitudes que requieren atención
                                </h2>
                                <p className="max-w-xl text-sm leading-6 text-muted-foreground">
                                    Pendientes primero. Las decisiones clínicas
                                    continúan desde el paciente y el servicio
                                    solicitado.
                                </p>
                            </div>
                            <div className="flex items-end justify-between gap-5 sm:justify-end">
                                <div className="border-l border-operational-foreground/35 pl-4">
                                    <p className="text-4xl font-semibold tracking-tight tabular-nums">
                                        {pendingRequestsCount}
                                    </p>
                                    <p className="text-xs font-semibold tracking-[0.12em] text-muted-foreground uppercase">
                                        pendientes
                                    </p>
                                </div>
                                <Button asChild variant="outline" size="sm">
                                    <Link href={requestsIndex()}>
                                        Ver todas
                                    </Link>
                                </Button>
                            </div>
                        </div>

                        {requests.length === 0 ? (
                            <div className="border-t border-dashed py-10 text-sm text-muted-foreground">
                                Todavía no hay solicitudes de atención.
                            </div>
                        ) : (
                            <div className="divide-y">
                                {requests.map((request) => (
                                    <article
                                        key={request.id}
                                        className="group grid gap-3 py-5 transition-colors hover:bg-operational/25 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center sm:gap-6"
                                    >
                                        <div className="min-w-0 space-y-2">
                                            <div className="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                <Link
                                                    href={showPatient(
                                                        request.pet.id,
                                                    )}
                                                    className="text-lg font-semibold tracking-tight underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                >
                                                    {request.pet.name}
                                                </Link>
                                                <span
                                                    className="text-muted-foreground"
                                                    aria-hidden="true"
                                                >
                                                    /
                                                </span>
                                                <span className="text-sm font-medium text-muted-foreground">
                                                    {request.service.name}
                                                </span>
                                            </div>
                                            <p className="flex items-center gap-1.5 text-sm text-muted-foreground">
                                                <Clock3
                                                    className="size-3.5"
                                                    aria-hidden="true"
                                                />
                                                Recibida el{' '}
                                                {dateFormatter.format(
                                                    new Date(request.createdAt),
                                                )}
                                            </p>
                                        </div>
                                        <div className="flex items-center justify-between gap-3 sm:justify-end">
                                            <span
                                                className={`inline-flex items-center gap-2 rounded-sm px-2 py-1 text-xs font-semibold ${requestStatusStyles[request.status]}`}
                                            >
                                                <span
                                                    className="size-1.5 rounded-full bg-current"
                                                    aria-hidden="true"
                                                />
                                                {
                                                    requestStatusLabels[
                                                        request.status
                                                    ]
                                                }
                                            </span>
                                            <Button
                                                asChild
                                                size="sm"
                                                variant="ghost"
                                            >
                                                <Link
                                                    href={showRequest(
                                                        request.id,
                                                    )}
                                                >
                                                    Revisar
                                                    <ArrowRight aria-hidden="true" />
                                                </Link>
                                            </Button>
                                        </div>
                                    </article>
                                ))}
                            </div>
                        )}
                    </section>

                    <aside
                        aria-labelledby="shortcuts-title"
                        className="border-l border-clinical-foreground/20 pl-5 xl:mt-8"
                    >
                        <div className="space-y-2">
                            <p className="text-xs font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                                Continuidad
                            </p>
                            <h2 id="shortcuts-title" className="font-semibold">
                                Accesos operativos
                            </h2>
                            <p className="text-sm leading-6 text-muted-foreground">
                                Continuá la gestión necesaria fuera de la
                                revisión actual.
                            </p>
                        </div>
                        <div className="mt-5 grid gap-1">
                            <Link
                                href={patientsIndex()}
                                className="group flex min-h-11 items-center justify-between border-b border-border/70 py-2 text-sm font-medium transition-colors hover:text-primary focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                            >
                                <span className="flex items-center gap-2">
                                    <PawPrint
                                        className="size-4 text-primary"
                                        aria-hidden="true"
                                    />
                                    Ver pacientes
                                </span>
                                <ArrowRight
                                    className="size-4 text-muted-foreground transition-transform group-hover:translate-x-0.5"
                                    aria-hidden="true"
                                />
                            </Link>
                            <Link
                                href={requestsIndex()}
                                className="group flex min-h-11 items-center justify-between border-b border-border/70 py-2 text-sm font-medium transition-colors hover:text-primary focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                            >
                                <span className="flex items-center gap-2">
                                    <ClipboardList
                                        className="size-4 text-primary"
                                        aria-hidden="true"
                                    />
                                    Ver solicitudes
                                </span>
                                <ArrowRight
                                    className="size-4 text-muted-foreground transition-transform group-hover:translate-x-0.5"
                                    aria-hidden="true"
                                />
                            </Link>
                            <Link
                                href={servicesIndex()}
                                className="group flex min-h-11 items-center justify-between border-b border-border/70 py-2 text-sm font-medium transition-colors hover:text-primary focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                            >
                                <span className="flex items-center gap-2">
                                    <Stethoscope
                                        className="size-4 text-primary"
                                        aria-hidden="true"
                                    />
                                    Abrir catálogo clínico
                                </span>
                                <ArrowRight
                                    className="size-4 text-muted-foreground transition-transform group-hover:translate-x-0.5"
                                    aria-hidden="true"
                                />
                            </Link>
                        </div>
                    </aside>
                </div>
            </div>
        </>
    );
}

AdminDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Inicio',
            href: dashboard(),
        },
    ],
};
