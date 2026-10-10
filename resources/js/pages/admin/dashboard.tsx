import { Head, Link } from '@inertiajs/react';
import {
    ClipboardList,
    FileText,
    PawPrint,
    Plus,
    Stethoscope,
    Workflow,
} from 'lucide-react';
import { DashboardHero } from '@/components/dashboard/dashboard-hero';
import { PriorityRequests } from '@/components/dashboard/priority-requests';
import { QuickAccessGrid } from '@/components/dashboard/quick-access-grid';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { show as showAppointment } from '@/routes/admin/appointments';
import {
    create as createPatient,
    index as patientsIndex,
    show as showPatient,
} from '@/routes/admin/pets';
import { show as showPetTreatment } from '@/routes/admin/pets/treatments';
import { index as proceduresIndex } from '@/routes/admin/procedures';
import {
    index as requestsIndex,
    show as showRequest,
} from '@/routes/admin/service-requests';
import { index as servicesIndex } from '@/routes/admin/services';
import { index as treatmentsIndex } from '@/routes/admin/treatments';
import type { AdminDashboardProps } from '@/types';

const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
});

export default function AdminDashboard({
    pendingRequestsCount,
    requests,
    clinicalTasks,
}: AdminDashboardProps) {
    return (
        <>
            <Head title="Inicio" />
            <div className="workspace-operational">
                <DashboardHero
                    pendingRequestsCount={pendingRequestsCount}
                    action={
                        <Button asChild className="min-h-11">
                            <Link href={createPatient()}>
                                <Plus aria-hidden="true" />
                                Nuevo paciente
                            </Link>
                        </Button>
                    }
                />
                <PriorityRequests
                    pendingRequestsCount={pendingRequestsCount}
                    requestsHref={requestsIndex()}
                    requests={requests.map((request) => ({
                        id: request.id,
                        petName: request.pet.name,
                        petDetails:
                            request.pet.species ?? 'Especie no informada',
                        service: request.service.name,
                        receivedAt: `Recibida el ${dateFormatter.format(new Date(request.createdAt))}`,
                        petHref: showPatient(request.pet.id),
                        requestHref: showRequest(request.id),
                    }))}
                />
                {clinicalTasks.length > 0 && (
                    <section className="rounded-xl border bg-card p-5 shadow-sm">
                        <h2 className="font-semibold">
                            Registros clínicos pendientes
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Atenciones que requieren documentación clínica.
                        </p>
                        <div className="mt-4 grid gap-3">
                            {clinicalTasks.map((task) => (
                                <article
                                    className="rounded-lg border border-border-subtle bg-surface-subtle p-4"
                                    key={task.appointmentId}
                                >
                                    <p className="font-semibold">
                                        {task.pet.name} · {task.serviceName}
                                    </p>
                                    {task.treatment ? (
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            {task.treatment.name} · Sesión{' '}
                                            {task.treatment.sessionNumber} ·{' '}
                                            {task.treatment.status === 'pending'
                                                ? 'Pendiente'
                                                : task.treatment.status ===
                                                    'completed'
                                                  ? 'Completada'
                                                  : 'Cancelada'}
                                        </p>
                                    ) : (
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            Sin sesión de tratamiento vinculada.
                                        </p>
                                    )}
                                    <div className="mt-3 flex flex-wrap gap-2">
                                        <Button asChild size="sm">
                                            <Link
                                                href={showAppointment(
                                                    task.appointmentId,
                                                )}
                                            >
                                                Ver turno
                                            </Link>
                                        </Button>
                                        {task.treatment && (
                                            <Button
                                                asChild
                                                size="sm"
                                                variant="outline"
                                            >
                                                <Link
                                                    href={showPetTreatment([
                                                        task.pet.id,
                                                        task.treatment.id,
                                                    ])}
                                                >
                                                    Ver tratamiento y sesión
                                                </Link>
                                            </Button>
                                        )}
                                    </div>
                                </article>
                            ))}
                        </div>
                    </section>
                )}
                <QuickAccessGrid
                    items={[
                        {
                            title: 'Pacientes',
                            description: 'Consultá y gestioná pacientes.',
                            icon: PawPrint,
                            href: patientsIndex(),
                        },
                        {
                            title: 'Solicitudes',
                            description: 'Revisá solicitudes pendientes.',
                            icon: ClipboardList,
                            href: requestsIndex(),
                        },
                        {
                            title: 'Servicios',
                            description:
                                'Administrá los servicios disponibles.',
                            icon: Stethoscope,
                            href: servicesIndex(),
                        },
                        {
                            title: 'Procedimientos',
                            description:
                                'Administrá los procedimientos disponibles.',
                            icon: Workflow,
                            href: proceduresIndex(),
                        },
                        {
                            title: 'Tratamientos',
                            description:
                                'Administrá tratamientos del catálogo.',
                            icon: FileText,
                            href: treatmentsIndex(),
                        },
                    ]}
                />
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
