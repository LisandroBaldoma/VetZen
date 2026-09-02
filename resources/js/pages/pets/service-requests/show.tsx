import { Head, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { Badge } from '@/components/ui/badge';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index, show } from '@/routes/pets/service-requests';
import type { Pet, ServiceRequest } from '@/types';
export default function RequestShow({
    pet,
    serviceRequest,
}: {
    pet: Pet;
    serviceRequest: ServiceRequest;
}) {
    const assigned = serviceRequest.pet_treatment;

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
            <Head title="Solicitud" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="service-requests"
                    editHref={edit.url(pet.id)}
                />
                <Heading
                    title={`${serviceRequest.service?.name} — ${pet.name}`}
                    description="Estado de la solicitud"
                />
                <Badge>{serviceRequest.status}</Badge>
                <p>{serviceRequest.notes || 'Sin nota.'}</p>
                {assigned && (
                    <section className="rounded-xl border p-5">
                        <h2 className="font-semibold">
                            Tratamiento asignado: {assigned.treatment_name}
                        </h2>
                        <p className="text-sm text-muted-foreground">
                            Progreso:{' '}
                            {assigned.sessions?.filter(
                                (s) => s.status === 'completed',
                            ).length ?? 0}{' '}
                            / {assigned.planned_sessions}
                        </p>
                    </section>
                )}
            </div>
        </>
    );
}
