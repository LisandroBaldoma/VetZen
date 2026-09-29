import { Head, Link } from '@inertiajs/react';
import {
    ArrowRight,
    ClipboardList,
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

export default function AdminDashboard({
    pendingRequestsCount,
    requests,
}: AdminDashboardProps) {
    return (
        <>
            <Head title="Inicio" />
            <div className="mx-auto w-full max-w-7xl space-y-6 p-4 md:p-6">
                <PageHeader
                    title="Inicio"
                    description="Revisá las solicitudes que requieren atención y continuá con las tareas frecuentes."
                    actions={
                        <Button asChild className="min-h-11">
                            <Link href={createPatient()}>
                                <Plus aria-hidden="true" />
                                Nuevo paciente
                            </Link>
                        </Button>
                    }
                />

                <div className="grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_18rem]">
                    <section aria-labelledby="requests-title">
                        <Card className="overflow-hidden border-l-4 border-l-primary py-0">
                            <CardHeader className="gap-4 px-5 py-5 sm:flex-row sm:items-start sm:justify-between sm:px-6">
                                <div className="space-y-1">
                                    <CardTitle
                                        id="requests-title"
                                        className="flex items-center gap-2 text-base"
                                    >
                                        <ClipboardList
                                            className="size-5 text-primary"
                                            aria-hidden="true"
                                        />
                                        Solicitudes de atención
                                    </CardTitle>
                                    <p className="text-sm text-muted-foreground">
                                        Pendientes primero y actividad reciente
                                        a continuación.
                                    </p>
                                </div>
                                <div className="flex items-center gap-3 sm:justify-end">
                                    <div className="text-right">
                                        <p className="text-3xl font-semibold tracking-tight tabular-nums">
                                            {pendingRequestsCount}
                                        </p>
                                        <p className="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                                            pendientes
                                        </p>
                                    </div>
                                    <Button asChild variant="outline" size="sm">
                                        <Link href={requestsIndex()}>
                                            Ver todas
                                        </Link>
                                    </Button>
                                </div>
                            </CardHeader>

                            <CardContent className="px-0">
                                {requests.length === 0 ? (
                                    <div className="border-t border-dashed px-5 py-8 text-sm text-muted-foreground sm:px-6">
                                        Todavía no hay solicitudes de atención.
                                    </div>
                                ) : (
                                    <div className="border-t">
                                        {requests.map((request) => (
                                            <article
                                                key={request.id}
                                                className="flex flex-col gap-4 px-5 py-4 transition-colors hover:bg-muted/45 sm:flex-row sm:items-center sm:justify-between sm:px-6"
                                            >
                                                <div className="min-w-0 space-y-1">
                                                    <div className="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                        <Link
                                                            href={showPatient(
                                                                request.pet.id,
                                                            )}
                                                            className="font-semibold underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                        >
                                                            {request.pet.name}
                                                        </Link>
                                                        <span
                                                            className="text-muted-foreground"
                                                            aria-hidden="true"
                                                        >
                                                            ·
                                                        </span>
                                                        <span className="text-sm text-muted-foreground">
                                                            {
                                                                request.service
                                                                    .name
                                                            }
                                                        </span>
                                                    </div>
                                                    <p className="text-sm text-muted-foreground">
                                                        Recibida el{' '}
                                                        {dateFormatter.format(
                                                            new Date(
                                                                request.createdAt,
                                                            ),
                                                        )}
                                                    </p>
                                                </div>
                                                <div className="flex flex-wrap items-center gap-2 sm:justify-end">
                                                    <Badge variant="outline">
                                                        {
                                                            requestStatusLabels[
                                                                request.status
                                                            ]
                                                        }
                                                    </Badge>
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
                                                            Abrir
                                                            <ArrowRight aria-hidden="true" />
                                                        </Link>
                                                    </Button>
                                                </div>
                                            </article>
                                        ))}
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </section>

                    <aside aria-labelledby="shortcuts-title">
                        <Card className="gap-4 py-5">
                            <CardHeader className="px-5">
                                <CardTitle
                                    id="shortcuts-title"
                                    className="text-base"
                                >
                                    Accesos frecuentes
                                </CardTitle>
                                <p className="text-sm text-muted-foreground">
                                    Continuá tareas de gestión y catálogo.
                                </p>
                            </CardHeader>
                            <CardContent className="grid gap-2 px-5">
                                <Link
                                    href={patientsIndex()}
                                    className="group flex min-h-11 items-center justify-between rounded-lg border bg-input/35 px-3 text-sm font-medium transition-[background-color,color] hover:bg-accent focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
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
                                    className="group flex min-h-11 items-center justify-between rounded-lg border bg-input/35 px-3 text-sm font-medium transition-[background-color,color] hover:bg-accent focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
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
                                    className="group flex min-h-11 items-center justify-between rounded-lg border bg-input/35 px-3 text-sm font-medium transition-[background-color,color] hover:bg-accent focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
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
                            </CardContent>
                        </Card>
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
