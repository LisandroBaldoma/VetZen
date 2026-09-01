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
            <div className="space-y-6 p-4 md:p-6">
                <PageHeader
                    title="Inicio"
                    description="Revisá las solicitudes que requieren atención y continuá con las tareas frecuentes."
                />

                <div className="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(20rem,1.4fr)]">
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <ClipboardList
                                    className="size-5 text-muted-foreground"
                                    aria-hidden="true"
                                />
                                Solicitudes pendientes
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="text-4xl font-semibold tabular-nums">
                                {pendingRequestsCount}
                            </p>
                            <p className="mt-2 text-sm text-muted-foreground">
                                {pendingRequestsCount === 1
                                    ? 'Solicitud que requiere evaluación.'
                                    : 'Solicitudes que requieren evaluación.'}
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Accesos rápidos</CardTitle>
                        </CardHeader>
                        <CardContent className="grid gap-2 sm:grid-cols-2">
                            <Button asChild className="justify-start">
                                <Link href={createPatient()}>
                                    <Plus aria-hidden="true" />
                                    Nuevo paciente
                                </Link>
                            </Button>
                            <Button
                                asChild
                                variant="outline"
                                className="justify-start"
                            >
                                <Link href={patientsIndex()}>
                                    <PawPrint aria-hidden="true" />
                                    Ver pacientes
                                </Link>
                            </Button>
                            <Button
                                asChild
                                variant="outline"
                                className="justify-start"
                            >
                                <Link href={requestsIndex()}>
                                    <ClipboardList aria-hidden="true" />
                                    Ver solicitudes
                                </Link>
                            </Button>
                            <Button
                                asChild
                                variant="outline"
                                className="justify-start"
                            >
                                <Link href={servicesIndex()}>
                                    <Stethoscope aria-hidden="true" />
                                    Abrir catálogo clínico
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>

                <section aria-labelledby="requests-title" className="space-y-3">
                    <div className="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2
                                id="requests-title"
                                className="text-lg font-semibold"
                            >
                                Solicitudes de atención
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Pendientes primero y actividad reciente a
                                continuación.
                            </p>
                        </div>
                        <Button asChild variant="outline" size="sm">
                            <Link href={requestsIndex()}>Ver todas</Link>
                        </Button>
                    </div>

                    {requests.length === 0 ? (
                        <div className="rounded-xl border border-dashed p-6 text-sm text-muted-foreground">
                            Todavía no hay solicitudes de atención.
                        </div>
                    ) : (
                        <div className="grid gap-3">
                            {requests.map((request) => (
                                <article
                                    key={request.id}
                                    className="flex flex-col gap-4 rounded-xl border bg-card p-4 sm:flex-row sm:items-center sm:justify-between"
                                >
                                    <div className="min-w-0 space-y-1">
                                        <div className="flex flex-wrap items-center gap-2">
                                            <Link
                                                href={showPatient(
                                                    request.pet.id,
                                                )}
                                                className="font-medium underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                            >
                                                {request.pet.name}
                                            </Link>
                                            <span aria-hidden="true">·</span>
                                            <span>{request.service.name}</span>
                                        </div>
                                        <p className="text-sm text-muted-foreground">
                                            {dateFormatter.format(
                                                new Date(request.createdAt),
                                            )}
                                        </p>
                                    </div>
                                    <div className="flex flex-wrap items-center gap-3">
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
                                                href={showRequest(request.id)}
                                            >
                                                Ver solicitud
                                                <ArrowRight aria-hidden="true" />
                                            </Link>
                                        </Button>
                                    </div>
                                </article>
                            ))}
                        </div>
                    )}
                </section>
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
