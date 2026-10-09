import { Head, Link } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useState } from 'react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { dashboard } from '@/routes';
import { index, schedule, show } from '@/routes/admin/appointments';

type Appointment = { id: number; pet_name: string; service_name: string; status: string; starts_at: string; ends_at: string };
type Day = { date: string; capacity: number | null; appointments: Appointment[] };

const labels: Record<string, string> = { pending: 'Pendiente', approved: 'Aprobado', rejected: 'Rechazado', cancelled: 'Cancelado', attended: 'Atendido', absent: 'Ausente' };
const dayFormatter = new Intl.DateTimeFormat('es-AR', { weekday: 'short', day: 'numeric', month: 'short', timeZone: 'America/Argentina/Buenos_Aires' });
const timeFormatter = new Intl.DateTimeFormat('es-AR', { hour: '2-digit', minute: '2-digit', timeZone: 'America/Argentina/Buenos_Aires' });

function DayAppointments({ day, compact = false }: { day: Day; compact?: boolean }) {
    return <section className={compact ? 'min-h-80 p-3' : 'rounded-xl border bg-card p-5 shadow-sm'}><header className="border-b pb-3"><h2 className="font-semibold capitalize">{dayFormatter.format(new Date(`${day.date}T12:00:00Z`))}</h2><p className="text-xs text-muted-foreground">{day.capacity === null ? 'Sin agenda publicada' : `Capacidad simultánea: ${day.capacity}`}</p></header><div className="mt-3 space-y-2">{day.appointments.length === 0 ? <p className="text-xs text-muted-foreground">Sin turnos</p> : day.appointments.map((appointment) => <Link className="block rounded-lg border bg-background p-3 text-sm transition-colors hover:bg-accent" href={show(appointment.id)} key={appointment.id}><p className="font-semibold">{timeFormatter.format(new Date(appointment.starts_at))}–{timeFormatter.format(new Date(appointment.ends_at))}</p><p className="mt-1">{appointment.pet_name}</p><p className="text-muted-foreground">{appointment.service_name}</p><span className="mt-2 inline-block rounded-full bg-muted px-2 py-0.5 text-xs font-medium">{labels[appointment.status]}</span></Link>)}</div></section>;
}

export default function AdminAppointmentSchedule({ weekStart, week }: { weekStart: string; week: Day[] }) {
    const [selectedDate, setSelectedDate] = useState(week[0]?.date ?? '');
    const selectedDay = week.find((day) => day.date === selectedDate) ?? week[0];
    const previous = new Date(`${weekStart}T12:00:00Z`);
    previous.setUTCDate(previous.getUTCDate() - 7);
    const next = new Date(`${weekStart}T12:00:00Z`);
    next.setUTCDate(next.getUTCDate() + 7);
    const date = (value: Date) => value.toISOString().slice(0, 10);

    return <><Head title="Agenda semanal" /><div className="workspace-operational space-y-6"><PageHeader title="Agenda semanal" description="Turnos publicados y su estado durante la semana seleccionada." actions={<div className="flex gap-2"><Button asChild variant="outline"><Link href={schedule({ query: { date: date(previous) } })}><ChevronLeft aria-hidden />Anterior</Link></Button><Button asChild variant="outline"><Link href={schedule({ query: { date: date(next) } })}>Siguiente<ChevronRight aria-hidden /></Link></Button></div>} />
        <div className="md:hidden"><Select onValueChange={setSelectedDate} value={selectedDay?.date}><SelectTrigger className="w-full"><SelectValue placeholder="Elegí un día" /></SelectTrigger><SelectContent>{week.map((day) => <SelectItem key={day.date} value={day.date}>{dayFormatter.format(new Date(`${day.date}T12:00:00Z`))}</SelectItem>)}</SelectContent></Select>{selectedDay ? <div className="mt-4"><DayAppointments day={selectedDay} /></div> : null}</div>
        <div className="hidden overflow-x-auto rounded-xl border bg-card shadow-sm md:block"><div className="grid min-w-[70rem] grid-cols-7 divide-x">{week.map((day) => <DayAppointments compact day={day} key={day.date} />)}</div></div>
        <Button asChild variant="outline"><Link href={index()}>Ver solicitudes pendientes</Link></Button>
    </div></>;
}

AdminAppointmentSchedule.layout = { breadcrumbs: [{ title: 'Inicio', href: dashboard() }, { title: 'Turnos', href: index() }, { title: 'Agenda semanal', href: schedule() }] };
