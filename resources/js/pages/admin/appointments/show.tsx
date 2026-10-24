import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft, CalendarClock } from 'lucide-react';
import AdminAppointmentController from '@/actions/App/Http/Controllers/Admin/AppointmentController';
import InputError from '@/components/input-error';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { index } from '@/routes/admin/appointments';
import { edit as reschedule } from '@/routes/admin/appointments/reschedule';

type Appointment = {
    id: number;
    status: string;
    service_name: string;
    pet_name: string;
    responsible_name: string;
    starts_at: string;
    ends_at: string;
    duration_minutes: number;
};
type History = {
    from_status: string;
    to_status: string;
    reason: string | null;
    created_at: string;
    changed_by: string;
};
type ServiceRequestOption = {
    id: number;
    status: string;
    service_name: string;
};
type TreatmentSessionOption = {
    id: number;
    status: string;
    session_number: number;
    treatment_name: string;
};
const labels: Record<string, string> = {
    pending: 'Pendiente',
    approved: 'Aprobado',
    rejected: 'Rechazado',
    cancelled: 'Cancelado',
    attended: 'Atendido',
    absent: 'Ausente',
};
const dateTime = (value: string) =>
    new Intl.DateTimeFormat('es-AR', {
        dateStyle: 'full',
        timeStyle: 'short',
        timeZone: 'America/Argentina/Buenos_Aires',
    }).format(new Date(value));

export default function AdminAppointmentShow({
    appointment,
    history,
    links,
    linkOptions,
}: {
    appointment: Appointment;
    history: History[];
    links: {
        service_request_id: number | null;
        treatment_session_id: number | null;
        locked: boolean;
        history: { created_at: string; changed_by: string }[];
    };
    linkOptions: {
        serviceRequests: ServiceRequestOption[];
        treatmentSessions: TreatmentSessionOption[];
    };
}) {
    const actions =
        appointment.status === 'pending'
            ? [
                  ['approved', 'Aprobar'],
                  ['rejected', 'Rechazar'],
              ]
            : appointment.status === 'approved'
              ? [
                    ['attended', 'Marcar atendido'],
                    ['absent', 'Marcar ausente'],
                ]
              : [];

    return (
        <>
            <Head title={`Turno de ${appointment.pet_name}`} />
            <div className="workspace-operational space-y-6">
                <PageHeader
                    title={`Turno de ${appointment.pet_name}`}
                    description={`${appointment.service_name} · ${appointment.responsible_name}`}
                    actions={
                        <Button asChild variant="outline">
                            <Link href={index()}>
                                <ArrowLeft aria-hidden />
                                Volver a pendientes
                            </Link>
                        </Button>
                    }
                />
                <section className="max-w-3xl rounded-xl border bg-card p-5 shadow-sm">
                    <div className="flex items-start justify-between gap-4">
                        <div>
                            <p className="font-semibold">
                                {dateTime(appointment.starts_at)} a{' '}
                                {dateTime(appointment.ends_at)}
                            </p>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Duración: {appointment.duration_minutes} minutos
                            </p>
                        </div>
                        <span className="rounded-full bg-muted px-2.5 py-1 text-xs font-medium">
                            {labels[appointment.status]}
                        </span>
                    </div>
                    {actions.length > 0 ? (
                        <div className="mt-6 flex flex-wrap gap-2 border-t pt-5">
                            {actions.map(([status, label]) => (
                                <Form
                                    {...AdminAppointmentController.updateStatus.form(
                                        appointment.id,
                                    )}
                                    key={status}
                                >
                                    <input
                                        name="status"
                                        type="hidden"
                                        value={status}
                                    />
                                    <Button
                                        type="submit"
                                        variant={
                                            status === 'rejected'
                                                ? 'destructive'
                                                : 'default'
                                        }
                                    >
                                        {label}
                                    </Button>
                                </Form>
                            ))}
                            <Button asChild variant="outline">
                                <Link href={reschedule(appointment.id)}>
                                    <CalendarClock aria-hidden />
                                    Reprogramar
                                </Link>
                            </Button>
                        </div>
                    ) : null}
                    {['pending', 'approved'].includes(appointment.status) ? (
                        <Form
                            {...AdminAppointmentController.updateStatus.form(
                                appointment.id,
                            )}
                            className="mt-5 space-y-3 border-t pt-5"
                        >
                            {({ errors, processing }) => (
                                <>
                                    <input
                                        name="status"
                                        type="hidden"
                                        value="cancelled"
                                    />
                                    <div className="space-y-2">
                                        <Label htmlFor="reason">
                                            Motivo de cancelación
                                        </Label>
                                        <Input
                                            id="reason"
                                            name="reason"
                                            required
                                        />
                                    </div>
                                    <InputError
                                        message={errors.reason ?? errors.status}
                                    />
                                    <Button
                                        disabled={processing}
                                        type="submit"
                                        variant="destructive"
                                    >
                                        Cancelar turno
                                    </Button>
                                </>
                            )}
                        </Form>
                    ) : null}
                </section>
                <section className="max-w-3xl space-y-3 rounded-xl border bg-card p-5 shadow-sm">
                    <div>
                        <h2 className="font-semibold">Vínculos clínicos</h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Solo se muestran solicitudes y sesiones de la misma
                            mascota y servicio.
                        </p>
                    </div>
                    {links.locked ? (
                        <p className="text-sm text-muted-foreground">
                            Los vínculos están protegidos porque la atención ya
                            tiene documentación clínica resuelta.
                        </p>
                    ) : (
                        <Form
                            {...AdminAppointmentController.updateLinks.form(
                                appointment.id,
                            )}
                            className="grid gap-4 sm:grid-cols-2"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <div className="space-y-2">
                                        <Label htmlFor="service_request_id">
                                            Solicitud de servicio
                                        </Label>
                                        <select
                                            id="service_request_id"
                                            name="service_request_id"
                                            defaultValue={
                                                links.service_request_id ?? ''
                                            }
                                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 text-sm"
                                        >
                                            <option value="">
                                                Sin vincular
                                            </option>
                                            {linkOptions.serviceRequests.map(
                                                (option) => (
                                                    <option
                                                        key={option.id}
                                                        value={option.id}
                                                    >
                                                        {option.service_name} ·
                                                        Solicitud #{option.id} ·{' '}
                                                        {labels[option.status]}
                                                    </option>
                                                ),
                                            )}
                                        </select>
                                        <InputError
                                            message={errors.service_request_id}
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="treatment_session_id">
                                            Sesión de tratamiento
                                        </Label>
                                        <select
                                            id="treatment_session_id"
                                            name="treatment_session_id"
                                            defaultValue={
                                                links.treatment_session_id ?? ''
                                            }
                                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 text-sm"
                                        >
                                            <option value="">
                                                Sin vincular
                                            </option>
                                            {linkOptions.treatmentSessions.map(
                                                (option) => (
                                                    <option
                                                        key={option.id}
                                                        value={option.id}
                                                    >
                                                        {option.treatment_name}{' '}
                                                        · Sesión{' '}
                                                        {option.session_number}{' '}
                                                        ·{' '}
                                                        {labels[option.status]}
                                                    </option>
                                                ),
                                            )}
                                        </select>
                                        <InputError
                                            message={
                                                errors.treatment_session_id ??
                                                errors.links
                                            }
                                        />
                                    </div>
                                    <div className="sm:col-span-2">
                                        <Button
                                            disabled={processing}
                                            type="submit"
                                        >
                                            Guardar vínculos
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    )}
                    {links.history.length > 0 ? (
                        <p className="text-xs text-muted-foreground">
                            Último cambio: {links.history[0].changed_by} ·{' '}
                            {dateTime(links.history[0].created_at)}
                        </p>
                    ) : null}
                </section>
                <section className="max-w-3xl space-y-3">
                    <h2 className="font-semibold">Historial de estados</h2>
                    {history.length === 0 ? (
                        <p className="rounded-xl border border-dashed bg-card p-5 text-sm text-muted-foreground">
                            Todavía no hubo cambios de estado.
                        </p>
                    ) : (
                        <div className="grid gap-3">
                            {history.map((entry) => (
                                <article
                                    className="rounded-xl border bg-card p-4 text-sm"
                                    key={`${entry.created_at}-${entry.to_status}`}
                                >
                                    <p className="font-medium">
                                        {labels[entry.from_status]} a{' '}
                                        {labels[entry.to_status]}
                                    </p>
                                    <p className="text-muted-foreground">
                                        {entry.changed_by} ·{' '}
                                        {dateTime(entry.created_at)}
                                    </p>
                                    {entry.reason ? (
                                        <p className="mt-2">
                                            Motivo: {entry.reason}
                                        </p>
                                    ) : null}
                                </article>
                            ))}
                        </div>
                    )}
                </section>
            </div>
        </>
    );
}

AdminAppointmentShow.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Turnos', href: index() },
        { title: 'Detalle', href: index() },
    ],
};
