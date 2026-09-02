import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import AdminServiceRequestController from '@/actions/App/Http/Controllers/Admin/ServiceRequestController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import PetContextHeader from '@/components/pet-context-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { edit as editResponsible } from '@/routes/admin/clients';
import { edit as editPet, show as showPet } from '@/routes/admin/pets';
import { show as showPetTreatment } from '@/routes/admin/pets/treatments';
import { index, show } from '@/routes/admin/service-requests';
import { show as showService } from '@/routes/admin/services';
import { create as createTreatment } from '@/routes/admin/services/treatments';

type RequestStatus = 'pending' | 'resolved' | 'cancelled';
type PetContext = {
    id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birth_date: string | null;
    has_photo: boolean;
    client: { id: number; name: string };
};
type ServiceRequest = {
    id: number;
    status: RequestStatus;
    notes: string | null;
    created_at: string;
    pet: PetContext;
    service: { id: number; name: string; is_active: boolean };
    pet_treatment: {
        id: number;
        treatment_name: string;
        planned_sessions: number;
    } | null;
};
type Treatment = { id: number; name: string; estimated_sessions: number };

const statusLabels: Record<RequestStatus, string> = {
    pending: 'Pendiente',
    resolved: 'Resuelta',
    cancelled: 'Cancelada',
};
const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    dateStyle: 'long',
    timeStyle: 'short',
});

export default function AdminRequestShow({
    serviceRequest,
    treatments,
}: {
    serviceRequest: ServiceRequest;
    treatments: Treatment[];
}) {
    const pet = serviceRequest.pet;

    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Solicitudes de atención', href: index() },
            {
                title: `Solicitud #${serviceRequest.id}`,
                href: show(serviceRequest.id),
            },
        ],
    });

    return (
        <>
            <Head title={`Solicitud de ${pet.name}`} />
            <div className="mx-auto max-w-5xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="service-requests"
                    editHref={editPet.url(pet.id)}
                />

                <div className="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(22rem,1fr)]">
                    <section className="space-y-5 rounded-xl border p-5 sm:p-6">
                        <div className="flex flex-wrap items-start justify-between gap-3">
                            <Heading
                                title="Solicitud"
                                description={`Recibida el ${dateFormatter.format(new Date(serviceRequest.created_at))}`}
                            />
                            <Badge variant="outline">
                                {statusLabels[serviceRequest.status]}
                            </Badge>
                        </div>

                        <dl className="grid gap-4 text-sm sm:grid-cols-2">
                            <div>
                                <dt className="text-muted-foreground">
                                    Paciente
                                </dt>
                                <dd>
                                    <Link
                                        className="font-medium hover:underline"
                                        href={showPet(pet.id)}
                                    >
                                        {pet.name}
                                    </Link>
                                </dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground">
                                    Responsable
                                </dt>
                                <dd>
                                    <Link
                                        className="font-medium hover:underline"
                                        href={editResponsible(pet.client.id)}
                                    >
                                        {pet.client.name}
                                    </Link>
                                </dd>
                            </div>
                            <div>
                                <dt className="text-muted-foreground">
                                    Servicio
                                </dt>
                                <dd>
                                    <Link
                                        className="font-medium hover:underline"
                                        href={showService(
                                            serviceRequest.service.id,
                                        )}
                                    >
                                        {serviceRequest.service.name}
                                    </Link>
                                </dd>
                            </div>
                        </dl>

                        <div className="space-y-1 border-t pt-4">
                            <h3 className="text-sm font-medium">
                                Nota del cliente
                            </h3>
                            <p className="text-sm whitespace-pre-wrap text-muted-foreground">
                                {serviceRequest.notes ||
                                    'El cliente no agregó una nota.'}
                            </p>
                        </div>

                        {serviceRequest.status === 'pending' && (
                            <Form
                                {...AdminServiceRequestController.cancel.form(
                                    serviceRequest.id,
                                )}
                                onBefore={() =>
                                    window.confirm(
                                        '¿Confirmás que querés cancelar esta solicitud? Permanecerá disponible como historial.',
                                    )
                                }
                            >
                                {({ processing, errors }) => (
                                    <div className="space-y-2">
                                        <Button
                                            type="submit"
                                            variant="destructive"
                                            disabled={processing}
                                        >
                                            {processing
                                                ? 'Cancelando…'
                                                : 'Cancelar solicitud'}
                                        </Button>
                                        <InputError message={errors.status} />
                                    </div>
                                )}
                            </Form>
                        )}
                    </section>

                    {serviceRequest.status === 'pending' ? (
                        !serviceRequest.service.is_active ? (
                            <section className="space-y-3 rounded-xl border border-destructive/40 p-5 sm:p-6">
                                <Heading
                                    title="Resolución bloqueada"
                                    description="El servicio fue desactivado después de recibir esta solicitud."
                                />
                                <p className="text-sm text-muted-foreground">
                                    Podés conservarla pendiente, cancelarla o
                                    reactivar el servicio antes de resolverla.
                                </p>
                                <Button variant="outline" asChild>
                                    <Link
                                        href={showService(
                                            serviceRequest.service.id,
                                        )}
                                    >
                                        Ver servicio
                                    </Link>
                                </Button>
                            </section>
                        ) : treatments.length === 0 ? (
                            <section className="space-y-3 rounded-xl border p-5 sm:p-6">
                                <Heading
                                    title="Sin plantillas compatibles"
                                    description="Creá una plantilla activa para este servicio antes de resolver la solicitud."
                                />
                                <Button asChild>
                                    <Link
                                        href={createTreatment(
                                            serviceRequest.service.id,
                                        )}
                                    >
                                        Crear plantilla
                                    </Link>
                                </Button>
                            </section>
                        ) : (
                            <Form
                                {...AdminServiceRequestController.resolve.form(
                                    serviceRequest.id,
                                )}
                                className="grid content-start gap-4 rounded-xl border p-5 sm:p-6"
                                onBefore={() =>
                                    window.confirm(
                                        '¿Confirmás la resolución? Se creará el tratamiento del paciente y todas sus sesiones.',
                                    )
                                }
                            >
                                {({ errors, processing }) => (
                                    <>
                                        <Heading
                                            title="Iniciar tratamiento"
                                            description="Definí las condiciones acordadas después de evaluar al paciente."
                                        />

                                        {Object.keys(errors).length > 0 && (
                                            <div
                                                role="alert"
                                                className="rounded-lg border border-destructive/40 bg-destructive/5 p-3 text-sm text-destructive"
                                            >
                                                Revisá los campos marcados antes
                                                de continuar.
                                            </div>
                                        )}
                                        <InputError
                                            message={
                                                errors.status || errors.service
                                            }
                                        />

                                        <div className="grid gap-2">
                                            <Label htmlFor="treatment_id">
                                                Plantilla compatible
                                            </Label>
                                            <select
                                                id="treatment_id"
                                                name="treatment_id"
                                                required
                                                className="h-10 rounded-md border bg-background px-3"
                                            >
                                                <option value="">
                                                    Seleccionar…
                                                </option>
                                                {treatments.map((treatment) => (
                                                    <option
                                                        key={treatment.id}
                                                        value={treatment.id}
                                                    >
                                                        {treatment.name} (
                                                        {
                                                            treatment.estimated_sessions
                                                        }{' '}
                                                        sesiones estimadas)
                                                    </option>
                                                ))}
                                            </select>
                                            <InputError
                                                message={errors.treatment_id}
                                            />
                                        </div>

                                        <div className="grid gap-4 sm:grid-cols-2">
                                            <div className="grid gap-2">
                                                <Label htmlFor="planned_sessions">
                                                    Sesiones previstas
                                                </Label>
                                                <Input
                                                    id="planned_sessions"
                                                    name="planned_sessions"
                                                    type="number"
                                                    min="1"
                                                    max="1000"
                                                    required
                                                />
                                                <InputError
                                                    message={
                                                        errors.planned_sessions
                                                    }
                                                />
                                            </div>
                                            <div className="grid gap-2">
                                                <Label htmlFor="default_session_price">
                                                    Precio por sesión (ARS)
                                                </Label>
                                                <Input
                                                    id="default_session_price"
                                                    name="default_session_price"
                                                    type="number"
                                                    min="0"
                                                    step="0.01"
                                                    required
                                                />
                                                <InputError
                                                    message={
                                                        errors.default_session_price
                                                    }
                                                />
                                            </div>
                                            <div className="grid gap-2">
                                                <Label htmlFor="starts_on">
                                                    Fecha de inicio
                                                </Label>
                                                <Input
                                                    id="starts_on"
                                                    name="starts_on"
                                                    type="date"
                                                    required
                                                />
                                                <InputError
                                                    message={errors.starts_on}
                                                />
                                            </div>
                                            <div className="grid gap-2">
                                                <Label htmlFor="status">
                                                    Estado inicial
                                                </Label>
                                                <select
                                                    id="status"
                                                    name="status"
                                                    className="h-10 rounded-md border bg-background px-3"
                                                    defaultValue="pending"
                                                >
                                                    <option value="pending">
                                                        Pendiente
                                                    </option>
                                                    <option value="in_progress">
                                                        En curso
                                                    </option>
                                                </select>
                                                <InputError
                                                    message={errors.status}
                                                />
                                            </div>
                                        </div>

                                        <input
                                            type="hidden"
                                            name="currency"
                                            value="ARS"
                                        />
                                        <InputError message={errors.currency} />

                                        <div className="grid gap-2">
                                            <Label htmlFor="notes">
                                                Notas del tratamiento
                                            </Label>
                                            <textarea
                                                id="notes"
                                                name="notes"
                                                className="min-h-24 rounded-md border bg-background p-3"
                                            />
                                            <InputError
                                                message={errors.notes}
                                            />
                                        </div>

                                        <p className="text-sm text-muted-foreground">
                                            Al confirmar se crearán el
                                            tratamiento y sus sesiones, y la
                                            solicitud quedará resuelta en una
                                            única operación.
                                        </p>
                                        <Button
                                            type="submit"
                                            disabled={processing}
                                        >
                                            {processing
                                                ? 'Resolviendo…'
                                                : 'Aprobar e iniciar tratamiento'}
                                        </Button>
                                    </>
                                )}
                            </Form>
                        )
                    ) : serviceRequest.pet_treatment ? (
                        <section className="space-y-4 rounded-xl border p-5 sm:p-6">
                            <Heading
                                title="Tratamiento iniciado"
                                description="Esta solicitud ya fue resuelta y permanece vinculada al tratamiento creado."
                            />
                            <div>
                                <p className="font-medium">
                                    {
                                        serviceRequest.pet_treatment
                                            .treatment_name
                                    }
                                </p>
                                <p className="text-sm text-muted-foreground">
                                    {
                                        serviceRequest.pet_treatment
                                            .planned_sessions
                                    }{' '}
                                    sesiones previstas
                                </p>
                            </div>
                            <Button asChild>
                                <Link
                                    href={showPetTreatment([
                                        pet.id,
                                        serviceRequest.pet_treatment.id,
                                    ])}
                                >
                                    Ver tratamiento
                                </Link>
                            </Button>
                        </section>
                    ) : (
                        <section className="rounded-xl border p-5 sm:p-6">
                            <Heading
                                title="Solicitud cancelada"
                                description="Se conserva como historial y no puede resolverse."
                            />
                        </section>
                    )}
                </div>
            </div>
        </>
    );
}
