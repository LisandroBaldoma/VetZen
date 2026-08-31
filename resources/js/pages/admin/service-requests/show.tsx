import { Form, Head } from '@inertiajs/react';
import AdminServiceRequestController from '@/actions/App/Http/Controllers/Admin/ServiceRequestController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { ServiceRequest, Treatment } from '@/types';
export default function AdminRequestShow({
    serviceRequest,
    treatments,
}: {
    serviceRequest: ServiceRequest;
    treatments: Treatment[];
}) {
    return (
        <>
            <Head title="Solicitud" />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <Heading
                    title={`${serviceRequest.pet?.name} — ${serviceRequest.service?.name}`}
                    description="Solicitud de atención"
                />
                <Badge>{serviceRequest.status}</Badge>
                <p>{serviceRequest.notes || 'Sin nota.'}</p>
                {serviceRequest.status === 'pending' && (
                    <Form
                        {...AdminServiceRequestController.resolve.form(
                            serviceRequest.id,
                        )}
                        className="grid gap-4 rounded-xl border p-5 md:grid-cols-2"
                    >
                        {({ errors, processing }) => (
                            <>
                                <div className="grid gap-2 md:col-span-2">
                                    <Label htmlFor="treatment_id">
                                        Tratamiento
                                    </Label>
                                    <select
                                        id="treatment_id"
                                        name="treatment_id"
                                        required
                                        className="h-9 rounded-md border bg-background px-3"
                                    >
                                        <option value="">Seleccionar…</option>
                                        {treatments.map((t) => (
                                            <option key={t.id} value={t.id}>
                                                {t.name}
                                            </option>
                                        ))}
                                    </select>
                                    <InputError message={errors.treatment_id} />
                                </div>
                                <div>
                                    <Label htmlFor="planned_sessions">
                                        Sesiones
                                    </Label>
                                    <Input
                                        id="planned_sessions"
                                        name="planned_sessions"
                                        type="number"
                                        min="1"
                                        required
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="default_session_price">
                                        Precio por sesión
                                    </Label>
                                    <Input
                                        id="default_session_price"
                                        name="default_session_price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        required
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="starts_on">Inicio</Label>
                                    <Input
                                        id="starts_on"
                                        name="starts_on"
                                        type="date"
                                        required
                                    />
                                </div>
                                <input
                                    type="hidden"
                                    name="currency"
                                    value="ARS"
                                />
                                <input
                                    type="hidden"
                                    name="status"
                                    value="pending"
                                />
                                <div className="md:col-span-2">
                                    <Label htmlFor="notes">Notas</Label>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        className="min-h-24 w-full rounded-md border p-3"
                                    />
                                </div>
                                <Button disabled={processing}>
                                    Resolver y asignar
                                </Button>
                            </>
                        )}
                    </Form>
                )}
                {serviceRequest.pet_treatment && (
                    <section className="rounded-xl border p-5">
                        <h2 className="font-semibold">
                            {serviceRequest.pet_treatment.treatment_name}
                        </h2>
                        <p>
                            {serviceRequest.pet_treatment.sessions?.length}{' '}
                            sesiones registradas
                        </p>
                    </section>
                )}
            </div>
        </>
    );
}
