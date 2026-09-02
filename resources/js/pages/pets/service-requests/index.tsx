import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { create, index, show } from '@/routes/pets/service-requests';
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
    created_at: string;
    service: { id: number; name: string };
    pet_treatment: { id: number; treatment_name: string } | null;
};

const statusLabels: Record<RequestStatus, string> = {
    pending: 'Pendiente',
    resolved: 'Resuelta',
    cancelled: 'Cancelada',
};
const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

export default function RequestsIndex({
    pet,
    requests,
}: {
    pet: PetContext;
    requests: ServiceRequest[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Solicitudes de atención', href: index(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`Solicitudes de ${pet.name}`} />
            <div className="space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="service-requests"
                    editHref={edit.url(pet.id)}
                />
                <div className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <Heading
                        title="Solicitudes de atención"
                        description="Consultá las evaluaciones solicitadas para esta mascota."
                    />
                    <Button asChild>
                        <Link href={create(pet.id)}>Nueva solicitud</Link>
                    </Button>
                </div>

                {requests.length === 0 ? (
                    <div className="space-y-4 rounded-xl border p-6">
                        <p className="text-sm text-muted-foreground">
                            Todavía no solicitaste atención para {pet.name}.
                        </p>
                        <Button asChild>
                            <Link href={create(pet.id)}>
                                Solicitar atención
                            </Link>
                        </Button>
                    </div>
                ) : (
                    <div className="grid gap-3 sm:grid-cols-2">
                        {requests.map((request) => (
                            <article
                                key={request.id}
                                className="flex flex-col gap-4 rounded-xl border p-5"
                            >
                                <div className="flex items-start justify-between gap-3">
                                    <div>
                                        <h2 className="font-semibold">
                                            {request.service.name}
                                        </h2>
                                        <p className="text-sm text-muted-foreground">
                                            {dateFormatter.format(
                                                new Date(request.created_at),
                                            )}
                                        </p>
                                    </div>
                                    <Badge variant="outline">
                                        {statusLabels[request.status]}
                                    </Badge>
                                </div>
                                <div className="mt-auto flex flex-wrap gap-2">
                                    <Button size="sm" variant="outline" asChild>
                                        <Link href={show([pet.id, request.id])}>
                                            Ver solicitud
                                        </Link>
                                    </Button>
                                    {request.pet_treatment && (
                                        <Button size="sm" asChild>
                                            <Link
                                                href={showTreatment([
                                                    pet.id,
                                                    request.pet_treatment.id,
                                                ])}
                                            >
                                                Ver tratamiento
                                            </Link>
                                        </Button>
                                    )}
                                </div>
                            </article>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
