import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft, CalendarClock } from 'lucide-react';
import AppointmentController from '@/actions/App/Http/Controllers/AppointmentController';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index } from '@/routes/appointments';

type Appointment = { id: number; pet_name: string; service_name: string; duration_minutes: number; starts_at: string; ends_at: string; status: string };

const labels: Record<string, string> = { pending: 'Pendiente', approved: 'Aprobado', rejected: 'Rechazado', cancelled: 'Cancelado', attended: 'Atendido', absent: 'Ausente' };
const dateTime = (value: string) => new Intl.DateTimeFormat('es-AR', { dateStyle: 'full', timeStyle: 'short', timeZone: 'America/Argentina/Buenos_Aires' }).format(new Date(value));

export default function AppointmentShow({ appointment, canCancel }: { appointment: Appointment; canCancel: boolean }) {
    return <><Head title="Detalle del turno" /><div className="workspace-operational space-y-6"><PageHeader title="Detalle del turno" description="Información de tu solicitud de turno." actions={<Button asChild variant="outline"><Link href={index()}><ArrowLeft aria-hidden />Volver a turnos</Link></Button>} />
        <section className="max-w-2xl rounded-xl border bg-card p-5 shadow-sm"><div className="flex items-start justify-between gap-4"><div><h2 className="text-lg font-semibold">{appointment.service_name}</h2><p className="text-sm text-muted-foreground">{appointment.pet_name}</p></div><span className="rounded-full bg-muted px-2.5 py-1 text-xs font-medium">{labels[appointment.status] ?? appointment.status}</span></div>
            <dl className="mt-6 grid gap-4 border-t pt-5 text-sm sm:grid-cols-2"><div><dt className="text-muted-foreground">Inicio</dt><dd className="mt-1 font-medium">{dateTime(appointment.starts_at)}</dd></div><div><dt className="text-muted-foreground">Fin</dt><dd className="mt-1 font-medium">{dateTime(appointment.ends_at)}</dd></div><div><dt className="text-muted-foreground">Duración</dt><dd className="mt-1 font-medium">{appointment.duration_minutes} minutos</dd></div></dl>
            {appointment.status === 'pending' ? <p className="mt-6 flex gap-2 rounded-lg bg-muted p-3 text-sm"><CalendarClock aria-hidden className="size-5 shrink-0" />Tu solicitud está pendiente de revisión por la veterinaria.</p> : null}
            {canCancel ? <Form {...AppointmentController.cancel.form(appointment.id)} className="mt-5 border-t pt-5" onBefore={() => window.confirm('¿Confirmás que querés cancelar este turno?')}>{({ errors, processing }) => <><Button disabled={processing} type="submit" variant="destructive">Cancelar turno</Button>{errors.status ? <p className="mt-2 text-sm text-destructive">{errors.status}</p> : null}</>}</Form> : null}
        </section>
    </div></>;
}

AppointmentShow.layout = { breadcrumbs: [{ title: 'Inicio', href: dashboard() }, { title: 'Turnos', href: index() }, { title: 'Detalle' }] };
