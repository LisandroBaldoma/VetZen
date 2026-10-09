import { Head, Link } from '@inertiajs/react';
import { CalendarDays, CalendarPlus, ChevronRight } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index, schedule, show } from '@/routes/admin/appointments';

type Appointment = { id: number; service_name: string; pet_name: string; responsible_name: string; starts_at: string };
type Paginated<T> = { data: T[] };
const dateTime = (value: string) => new Intl.DateTimeFormat('es-AR', { dateStyle: 'medium', timeStyle: 'short', timeZone: 'America/Argentina/Buenos_Aires' }).format(new Date(value));

export default function AdminAppointmentsIndex({ appointments }: { appointments: Paginated<Appointment> }) {
    return <><Head title="Turnos pendientes" /><div className="workspace-operational space-y-6"><PageHeader title="Turnos pendientes" description="Solicitudes que requieren revisión administrativa." actions={<div className="flex gap-2"><Button asChild variant="outline"><Link href={schedule()}><CalendarDays aria-hidden />Ver agenda semanal</Link></Button><Button asChild><Link href={create()}><CalendarPlus aria-hidden />Crear turno</Link></Button></div>} />
        {appointments.data.length === 0 ? <section className="rounded-xl border border-dashed bg-card p-6 text-sm text-muted-foreground">No hay solicitudes de turno pendientes.</section> : <div className="grid gap-3">{appointments.data.map((appointment) => <Link className="group rounded-xl border bg-card p-4 shadow-sm transition-colors hover:bg-accent" href={show(appointment.id)} key={appointment.id}><div className="flex items-start justify-between gap-4"><div><h2 className="font-semibold">{appointment.pet_name} · {appointment.service_name}</h2><p className="text-sm text-muted-foreground">{appointment.responsible_name} · {dateTime(appointment.starts_at)}</p></div><ChevronRight aria-hidden className="mt-1 size-5 text-muted-foreground group-hover:text-foreground" /></div></Link>)}</div>}
    </div></>;
}

AdminAppointmentsIndex.layout = { breadcrumbs: [{ title: 'Inicio', href: dashboard() }, { title: 'Turnos', href: index() }] };
