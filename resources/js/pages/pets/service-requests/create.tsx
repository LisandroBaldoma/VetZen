import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import ServiceRequestController from '@/actions/App/Http/Controllers/ServiceRequestController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import PetContextHeader from '@/components/pet-context-header';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index } from '@/routes/pets/service-requests';

type PetContext = {
    id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birth_date: string | null;
    has_photo: boolean;
};
type Service = { id: number; name: string; description: string };

export default function RequestCreate({
    pet,
    services,
    selectedServiceId,
}: {
    pet: PetContext;
    services: Service[];
    selectedServiceId: number | null;
}) {
    const selectedService = services.find(
        (service) => service.id === selectedServiceId,
    );

    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Solicitudes de atención', href: index(pet.id) },
            { title: 'Nueva solicitud' },
        ],
    });

    return (
        <>
            <Head title={`Solicitar atención para ${pet.name}`} />
            <div className="mx-auto max-w-4xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="service-requests"
                    editHref={edit.url(pet.id)}
                />
                <Heading
                    title="Nueva solicitud"
                    description="Solicitá una evaluación profesional para un servicio disponible."
                />

                <div className="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(16rem,1fr)]">
                    <Form
                        {...ServiceRequestController.store.form(pet.id)}
                        className="space-y-5 rounded-xl border p-5 sm:p-6"
                    >
                        {({ errors, processing }) => (
                            <>
                                {Object.keys(errors).length > 0 && (
                                    <p
                                        role="alert"
                                        className="rounded-lg border border-destructive/40 bg-destructive/5 p-3 text-sm text-destructive"
                                    >
                                        Revisá los datos marcados antes de
                                        enviar la solicitud.
                                    </p>
                                )}
                                <div className="grid gap-2">
                                    <Label htmlFor="service_id">Servicio</Label>
                                    <select
                                        id="service_id"
                                        name="service_id"
                                        required
                                        defaultValue={selectedServiceId ?? ''}
                                        className="h-10 rounded-md border bg-background px-3"
                                    >
                                        <option value="">Seleccionar…</option>
                                        {services.map((service) => (
                                            <option
                                                key={service.id}
                                                value={service.id}
                                            >
                                                {service.name}
                                            </option>
                                        ))}
                                    </select>
                                    <InputError message={errors.service_id} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="notes">Nota opcional</Label>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        maxLength={2000}
                                        className="min-h-32 rounded-md border bg-background p-3"
                                        placeholder="Contanos brevemente el motivo de la consulta."
                                    />
                                    <InputError message={errors.notes} />
                                </div>
                                <div className="flex flex-col-reverse gap-2 sm:flex-row">
                                    <Button variant="outline" asChild>
                                        <Link href={index(pet.id)}>
                                            Cancelar
                                        </Link>
                                    </Button>
                                    <Button type="submit" disabled={processing}>
                                        {processing
                                            ? 'Enviando…'
                                            : 'Enviar solicitud'}
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>

                    <aside className="space-y-3 rounded-xl border bg-muted/20 p-5">
                        <h2 className="font-semibold">Resumen</h2>
                        <dl className="grid gap-3 text-sm">
                            <div>
                                <dt className="text-muted-foreground">
                                    Mascota
                                </dt>
                                <dd className="font-medium">{pet.name}</dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground">
                                    Servicio preseleccionado
                                </dt>
                                <dd className="font-medium">
                                    {selectedService?.name ?? 'A elegir'}
                                </dd>
                            </div>
                        </dl>
                        {selectedService && (
                            <p className="text-sm text-muted-foreground">
                                {selectedService.description}
                            </p>
                        )}
                        <p className="border-t pt-3 text-sm text-muted-foreground">
                            Esta solicitud no reserva un turno ni selecciona un
                            tratamiento. Un profesional evaluará a {pet.name} y
                            definirá los próximos pasos.
                        </p>
                    </aside>
                </div>
            </div>
        </>
    );
}
