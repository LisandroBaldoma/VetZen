import { Form, Head, Link } from '@inertiajs/react';
import { CalendarClock } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, store } from '@/routes/appointments';

type Option = { id: number; name: string };
type Availability = { id: number; date: string; starts: Array<{ time: string; ends_at: string }> };

export default function AppointmentCreate({ pets, services, selectedPetId, selectedServiceId, availability }: {
    pets: Option[];
    services: Option[];
    selectedPetId: number | null;
    selectedServiceId: number | null;
    availability: Availability[];
}) {
    const selectionUrl = (pet?: number, service?: number) => create({ query: { pet, service } });

    return <>
        <Head title="Solicitar turno" />
        <div className="workspace-operational space-y-6">
            <PageHeader title="Solicitar turno" description="Elegí una mascota y un servicio para ver los horarios disponibles." />
            <section className="grid gap-5 rounded-xl border bg-card p-5 shadow-sm md:grid-cols-2">
                <div className="space-y-3">
                    <h2 className="font-semibold">1. Mascota</h2>
                    <div className="flex flex-wrap gap-2">{pets.map((pet) => <Button asChild key={pet.id} variant={selectedPetId === pet.id ? 'default' : 'outline'}><Link href={selectionUrl(pet.id, selectedServiceId ?? undefined)}>{pet.name}</Link></Button>)}</div>
                </div>
                <div className="space-y-3">
                    <h2 className="font-semibold">2. Servicio</h2>
                    <div className="flex flex-wrap gap-2">{services.map((service) => <Button asChild key={service.id} variant={selectedServiceId === service.id ? 'default' : 'outline'}><Link href={selectionUrl(selectedPetId ?? undefined, service.id)}>{service.name}</Link></Button>)}</div>
                </div>
            </section>
            {selectedPetId && selectedServiceId ? availability.length === 0 ? (
                <section className="rounded-xl border border-dashed bg-card p-6 text-sm text-muted-foreground">No hay horarios disponibles para esta selección.</section>
            ) : <section className="space-y-4">
                <h2 className="font-semibold">3. Elegí un horario</h2>
                {availability.map((date) => <article key={date.id} className="rounded-xl border bg-card p-5 shadow-sm">
                    <h3 className="font-medium">{date.date}</h3>
                    <div className="mt-3 flex flex-wrap gap-2">{date.starts.map((start) => <Form {...store.form()} key={start.time}>
                        {({ errors, processing }) => <>
                            <input name="pet_id" type="hidden" value={selectedPetId} />
                            <input name="service_id" type="hidden" value={selectedServiceId} />
                            <input name="schedule_date_id" type="hidden" value={date.id} />
                            <input name="starts_at" type="hidden" value={start.time} />
                            <Button disabled={processing} type="submit" variant="outline"><CalendarClock aria-hidden />{start.time} a {start.ends_at}</Button>
                            {errors.starts_at ? <p className="basis-full text-sm text-destructive">{errors.starts_at}</p> : null}
                        </>}
                    </Form>)}</div>
                </article>)}
            </section> : null}
        </div>
    </>;
}

AppointmentCreate.layout = { breadcrumbs: [{ title: 'Inicio', href: dashboard() }, { title: 'Turnos', href: create() }] };
