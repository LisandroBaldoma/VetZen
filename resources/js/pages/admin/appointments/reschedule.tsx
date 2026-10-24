import { Form, Head, Link } from '@inertiajs/react';
import AdminAppointmentController from '@/actions/App/Http/Controllers/Admin/AppointmentController';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { index, show } from '@/routes/admin/appointments';

type Appointment = { id: number; pet_name: string; service_name: string };
type Availability = {
    id: number;
    date: string;
    starts: Array<{ time: string; ends_at: string }>;
};

export default function AdminAppointmentReschedule({
    appointment,
    availability,
}: {
    appointment: Appointment;
    availability: Availability[];
}) {
    return (
        <>
            <Head title="Reprogramar turno" />
            <div className="workspace-operational space-y-6">
                <PageHeader
                    title="Reprogramar turno"
                    description={`${appointment.pet_name} · ${appointment.service_name}. El turno conservará su estado aprobado.`}
                />
                {availability.length === 0 ? (
                    <section className="rounded-xl border border-dashed bg-card p-6 text-sm text-muted-foreground">
                        No hay horarios disponibles.
                    </section>
                ) : (
                    <section className="space-y-4">
                        {availability.map((date) => (
                            <article
                                className="rounded-xl border bg-card p-5 shadow-sm"
                                key={date.id}
                            >
                                <h2 className="font-semibold">{date.date}</h2>
                                <div className="mt-3 flex flex-wrap gap-2">
                                    {date.starts.map((start) => (
                                        <Form
                                            {...AdminAppointmentController.reschedule.form(
                                                appointment.id,
                                            )}
                                            key={start.time}
                                        >
                                            {({ errors, processing }) => (
                                                <>
                                                    <input
                                                        name="schedule_date_id"
                                                        type="hidden"
                                                        value={date.id}
                                                    />
                                                    <input
                                                        name="starts_at"
                                                        type="hidden"
                                                        value={start.time}
                                                    />
                                                    <Button
                                                        disabled={processing}
                                                        type="submit"
                                                        variant="outline"
                                                    >
                                                        {start.time} a{' '}
                                                        {start.ends_at}
                                                    </Button>
                                                    {errors.starts_at ? (
                                                        <p className="basis-full text-sm text-destructive">
                                                            {errors.starts_at}
                                                        </p>
                                                    ) : null}
                                                </>
                                            )}
                                        </Form>
                                    ))}
                                </div>
                            </article>
                        ))}
                    </section>
                )}
                <Button asChild variant="outline">
                    <Link href={show(appointment.id)}>Volver al turno</Link>
                </Button>
            </div>
        </>
    );
}

AdminAppointmentReschedule.layout = {
    breadcrumbs: [{ title: 'Turnos', href: index() }],
};
