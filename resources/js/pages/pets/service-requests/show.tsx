import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import type { Pet, ServiceRequest } from '@/types';
export default function RequestShow({
    pet,
    serviceRequest,
}: {
    pet: Pet;
    serviceRequest: ServiceRequest;
}) {
    const assigned = serviceRequest.pet_treatment;

    return (
        <>
            <Head title="Solicitud" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
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
