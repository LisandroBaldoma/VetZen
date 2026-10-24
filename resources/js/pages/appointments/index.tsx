import { Head, Link } from '@inertiajs/react';
import { CalendarPlus, ChevronRight } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index, show } from '@/routes/appointments';

type Appointment = {
    id: number;
    pet_name: string;
    service_name: string;
    starts_at: string;
    ends_at: string;
    status: string;
};

const statusLabels: Record<string, string> = {
    pending: 'Pendiente',
    approved: 'Aprobado',
    rejected: 'Rechazado',
    cancelled: 'Cancelado',
    attended: 'Atendido',
    absent: 'Ausente',
};

function dateTime(value: string): string {
    return new Intl.DateTimeFormat('es-AR', {
        dateStyle: 'full',
        timeStyle: 'short',
        timeZone: 'America/Argentina/Buenos_Aires',
    }).format(new Date(value));
}

function AppointmentList({
    appointments,
    empty,
}: {
    appointments: Appointment[];
    empty: string;
}) {
    if (appointments.length === 0) {
        return (
            <p className="rounded-xl border border-dashed bg-card p-5 text-sm text-muted-foreground">
                {empty}
            </p>
        );
    }

    return (
        <div className="grid gap-3">
            {appointments.map((appointment) => (
                <Link
                    className="group rounded-xl border bg-card p-4 shadow-sm transition-colors hover:bg-accent"
                    href={show(appointment.id)}
                    key={appointment.id}
                >
                    <div className="flex items-start justify-between gap-4">
                        <div>
                            <p className="font-semibold">
                                {appointment.service_name}
                            </p>
                            <p className="text-sm text-muted-foreground">
                                {appointment.pet_name} ·{' '}
                                {dateTime(appointment.starts_at)}
                            </p>
                        </div>
                        <ChevronRight
                            aria-hidden
                            className="mt-1 size-5 shrink-0 text-muted-foreground group-hover:text-foreground"
                        />
                    </div>
                    <span className="mt-3 inline-flex rounded-full bg-muted px-2.5 py-1 text-xs font-medium">
                        {statusLabels[appointment.status] ?? appointment.status}
                    </span>
                </Link>
            ))}
        </div>
    );
}

export default function AppointmentsIndex({
    upcoming,
    history,
}: {
    upcoming: Appointment[];
    history: Appointment[];
}) {
    return (
        <>
            <Head title="Mis turnos" />
            <div className="workspace-operational space-y-7">
                <PageHeader
                    title="Mis turnos"
                    description="Consultá los próximos turnos y el historial de tus mascotas."
                    actions={
                        <Button asChild>
                            <Link href={create()}>
                                <CalendarPlus aria-hidden />
                                Solicitar turno
                            </Link>
                        </Button>
                    }
                />
                <section className="space-y-3">
                    <h2 className="font-semibold">Próximos</h2>
                    <AppointmentList
                        appointments={upcoming}
                        empty="Todavía no tenés turnos próximos."
                    />
                </section>
                <section className="space-y-3">
                    <h2 className="font-semibold">Historial</h2>
                    <AppointmentList
                        appointments={history}
                        empty="Todavía no hay turnos en el historial."
                    />
                </section>
            </div>
        </>
    );
}

AppointmentsIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Turnos', href: index() },
    ],
};
