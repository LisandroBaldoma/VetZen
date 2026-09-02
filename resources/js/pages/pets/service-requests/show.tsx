import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index, show } from '@/routes/pets/service-requests';
import { show as showTreatment } from '@/routes/pets/treatments';

type PetContext = {
    id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birth_date: string | null;
    has_photo: boolean;
};
type RequestStatus = 'pending' | 'resolved' | 'cancelled';
type ServiceRequest = {
    id: number;
    status: RequestStatus;
    notes: string | null;
    created_at: string;
    service: { id: number; name: string };
    pet_treatment: {
        id: number;
        treatment_name: string;
        planned_sessions: number;
    } | null;
};
const statusLabels: Record<RequestStatus, string> = {
    pending: 'Pendiente',
    resolved: 'Resuelta',
    cancelled: 'Cancelada',
};
const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    dateStyle: 'long',
    timeStyle: 'short',
});

export default function RequestShow({
    pet,
    serviceRequest,
}: {
    pet: PetContext;
    serviceRequest: ServiceRequest;
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Solicitudes de atención', href: index(pet.id) },
            {
                title: `Solicitud #${serviceRequest.id}`,
                href: show([pet.id, serviceRequest.id]),
            },
        ],
    });

    return (
        <>
            <Head title={`Solicitud de ${pet.name}`} />
            <div className="mx-auto max-w-4xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="service-requests"
                    editHref={edit.url(pet.id)}
                />
                <section className="space-y-5 rounded-xl border p-5 sm:p-6">
                    <div className="flex flex-wrap items-start justify-between gap-3">
                        <Heading
                            title={serviceRequest.service.name}
                            description={`Solicitud enviada el ${dateFormatter.format(new Date(serviceRequest.created_at))}`}
                        />
                        <Badge variant="outline">
                            {statusLabels[serviceRequest.status]}
                        </Badge>
                    </div>
                    <div className="space-y-1 border-t pt-4">
                        <h2 className="font-medium">Tu nota</h2>
                        <p className="text-sm whitespace-pre-wrap text-muted-foreground">
                            {serviceRequest.notes || 'No agregaste una nota.'}
                        </p>
                    </div>

                    {serviceRequest.status === 'pending' && (
                        <p className="rounded-lg bg-muted p-4 text-sm text-muted-foreground">
                            La solicitud está pendiente de evaluación
                            profesional. Todavía no representa un turno ni un
                            tratamiento asignado.
                        </p>
                    )}
                    {serviceRequest.status === 'cancelled' && (
                        <p className="rounded-lg bg-muted p-4 text-sm text-muted-foreground">
                            Esta solicitud fue cancelada y se conserva como
                            historial.
                        </p>
                    )}
                    {serviceRequest.pet_treatment && (
                        <div className="space-y-3 rounded-lg border bg-muted/20 p-4">
                            <div>
                                <h2 className="font-semibold">
                                    Tratamiento definido
                                </h2>
                                <p className="text-sm text-muted-foreground">
                                    {
                                        serviceRequest.pet_treatment
                                            .treatment_name
                                    }{' '}
                                    ·{' '}
                                    {
                                        serviceRequest.pet_treatment
                                            .planned_sessions
                                    }{' '}
                                    sesiones previstas
                                </p>
                            </div>
                            <Button asChild>
                                <Link
                                    href={showTreatment([
                                        pet.id,
                                        serviceRequest.pet_treatment.id,
                                    ])}
                                >
                                    Ver tratamiento
                                </Link>
                            </Button>
                        </div>
                    )}
                </section>
            </div>
        </>
    );
}
