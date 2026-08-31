import { Form, Head, Link } from '@inertiajs/react';
import ServiceRequestController from '@/actions/App/Http/Controllers/ServiceRequestController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/pets/service-requests';
import type { Pet, Service } from '@/types';

export default function RequestCreate({
    pet,
    services,
}: {
    pet: Pet;
    services: Service[];
}) {
    return (
        <>
            <Head title="Solicitar servicio" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title={`Solicitar atención para ${pet.name}`}
                    description="Elegí el área terapéutica. El tratamiento será determinado por un profesional."
                />
                <Form
                    {...ServiceRequestController.store.form(pet.id)}
                    className="space-y-5 rounded-xl border p-6"
                >
                    {({ errors, processing }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="service_id">Servicio</Label>
                                <select
                                    id="service_id"
                                    name="service_id"
                                    required
                                    className="h-9 rounded-md border bg-background px-3"
                                >
                                    <option value="">Seleccionar…</option>
                                    {services.map((s) => (
                                        <option key={s.id} value={s.id}>
                                            {s.name}
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
                                    className="min-h-28 rounded-md border bg-background p-3"
                                />
                                <InputError message={errors.notes} />
                            </div>
                            <Button disabled={processing}>
                                {processing ? 'Enviando…' : 'Enviar solicitud'}
                            </Button>
                        </>
                    )}
                </Form>
                <Button variant="outline" asChild>
                    <Link href={index(pet.id)}>Cancelar</Link>
                </Button>
            </div>
        </>
    );
}
