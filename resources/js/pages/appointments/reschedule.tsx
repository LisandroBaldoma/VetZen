import { Form, Head, Link } from '@inertiajs/react';
import AppointmentController from '@/actions/App/Http/Controllers/AppointmentController';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { show } from '@/routes/appointments';

type Appointment = { id: number; pet_name: string; service_name: string };
type Availability = { id: number; date: string; starts: Array<{ time: string; ends_at: string }> };

export default function RescheduleAppointment({ appointment, availability }: { appointment: Appointment; availability: Availability[] }) {
    return <><Head title="Reprogramar turno" /><div className="workspace-operational space-y-6"><PageHeader title="Reprogramar turno" description={`${appointment.pet_name} · ${appointment.service_name}. La solicitud quedará pendiente de revisión.`} />
        {availability.map((date) => <section className="rounded-xl border bg-card p-5 shadow-sm" key={date.id}><h2 className="font-semibold">{date.date}</h2><div className="mt-3 flex flex-wrap gap-2">{date.starts.map((start) => <Form {...AppointmentController.reschedule.form(appointment.id)} key={start.time}>{({ errors, processing }) => <><input name="schedule_date_id" type="hidden" value={date.id} /><input name="starts_at" type="hidden" value={start.time} /><Button disabled={processing} type="submit" variant="outline">{start.time} a {start.ends_at}</Button>{errors.starts_at ? <p className="basis-full text-sm text-destructive">{errors.starts_at}</p> : null}</>}</Form>)}</div></section>)}
        {availability.length === 0 ? <p className="rounded-xl border border-dashed p-5 text-sm text-muted-foreground">No hay horarios disponibles.</p> : null}<Button asChild variant="outline"><Link href={show(appointment.id)}>Volver al turno</Link></Button>
    </div></>;
}
