import { Form, Head, Link } from '@inertiajs/react';
import { CalendarPlus } from 'lucide-react';
import AdminAppointmentController from '@/actions/App/Http/Controllers/Admin/AppointmentController';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index } from '@/routes/admin/appointments';

type Pet = { id: number; name: string; responsible_name: string };
type Service = { id: number; name: string };
type Availability = {
    id: number;
    date: string;
    starts: Array<{ time: string; ends_at: string }>;
};

export default function AdminAppointmentCreate({
    pets,
    services,
    selectedPetId,
    selectedServiceId,
    availability,
}: {
    pets: Pet[];
    services: Service[];
    selectedPetId: number | null;
    selectedServiceId: number | null;
    availability: Availability[];
}) {
    const selectionUrl = (pet?: number, service?: number) =>
        create({ query: { pet, service } });

    return (
        <>
            <Head title="Crear turno" />
            <div className="workspace-operational space-y-6">
                <PageHeader
                    title="Crear turno"
                    description="El turno administrativo se crea directamente como aprobado."
                />
                <section className="grid gap-5 rounded-xl border bg-card p-5 shadow-sm md:grid-cols-2">
                    <div className="space-y-3">
                        <h2 className="font-semibold">1. Paciente</h2>
                        <div className="flex max-h-56 flex-wrap gap-2 overflow-y-auto">
                            {pets.map((pet) => (
                                <Button
                                    asChild
                                    key={pet.id}
                                    variant={
                                        selectedPetId === pet.id
                                            ? 'default'
                                            : 'outline'
                                    }
                                >
                                    <Link
                                        href={selectionUrl(
                                            pet.id,
                                            selectedServiceId ?? undefined,
                                        )}
                                    >
                                        {pet.name} · {pet.responsible_name}
                                    </Link>
                                </Button>
                            ))}
                        </div>
                    </div>
                    <div className="space-y-3">
                        <h2 className="font-semibold">2. Servicio</h2>
                        <div className="flex flex-wrap gap-2">
                            {services.map((service) => (
                                <Button
                                    asChild
                                    key={service.id}
                                    variant={
                                        selectedServiceId === service.id
                                            ? 'default'
                                            : 'outline'
                                    }
                                >
                                    <Link
                                        href={selectionUrl(
                                            selectedPetId ?? undefined,
                                            service.id,
                                        )}
                                    >
                                        {service.name}
                                    </Link>
                                </Button>
                            ))}
                        </div>
                    </div>
                </section>
                {selectedPetId && selectedServiceId ? (
                    availability.length === 0 ? (
                        <section className="rounded-xl border border-dashed bg-card p-6 text-sm text-muted-foreground">
                            No hay horarios disponibles para esta selección.
                        </section>
                    ) : (
                        <section className="space-y-4">
                            <h2 className="font-semibold">
                                3. Elegí un horario
                            </h2>
                            {availability.map((date) => (
                                <article
                                    className="rounded-xl border bg-card p-5 shadow-sm"
                                    key={date.id}
                                >
                                    <h3 className="font-medium">{date.date}</h3>
                                    <div className="mt-3 flex flex-wrap gap-2">
                                        {date.starts.map((start) => (
                                            <Form
                                                {...AdminAppointmentController.store.form()}
                                                key={start.time}
                                            >
                                                {({ errors, processing }) => (
                                                    <>
                                                        <input
                                                            name="pet_id"
                                                            type="hidden"
                                                            value={
                                                                selectedPetId
                                                            }
                                                        />
                                                        <input
                                                            name="service_id"
                                                            type="hidden"
                                                            value={
                                                                selectedServiceId
                                                            }
                                                        />
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
                                                            disabled={
                                                                processing
                                                            }
                                                            type="submit"
                                                            variant="outline"
                                                        >
                                                            <CalendarPlus
                                                                aria-hidden
                                                            />
                                                            {start.time} a{' '}
                                                            {start.ends_at}
                                                        </Button>
                                                        {errors.starts_at ? (
                                                            <p className="basis-full text-sm text-destructive">
                                                                {
                                                                    errors.starts_at
                                                                }
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
                    )
                ) : null}
                <Button asChild variant="outline">
                    <Link href={index()}>Volver a turnos</Link>
                </Button>
            </div>
        </>
    );
}

AdminAppointmentCreate.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Turnos', href: index() },
        { title: 'Crear turno', href: create() },
    ],
};
