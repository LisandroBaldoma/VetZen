import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { create, show } from '@/routes/pets/service-requests';
import type { Pet, ServiceRequest } from '@/types';

export default function RequestsIndex({
    pet,
    requests,
}: {
    pet: Pet;
    requests: ServiceRequest[];
}) {
    return (
        <>
            <Head title={`Solicitudes de ${pet.name}`} />
            <div className="space-y-6 p-4">
                <div className="flex justify-between gap-4">
                    <Heading
                        title={`Solicitudes de ${pet.name}`}
                        description="Atención solicitada por área terapéutica."
                    />
                    <Button asChild>
                        <Link href={create(pet.id)}>Solicitar servicio</Link>
                    </Button>
                </div>
                {requests.length === 0 ? (
                    <p className="rounded-xl border p-6 text-muted-foreground">
                        Todavía no hay solicitudes.
                    </p>
                ) : (
                    <div className="space-y-3">
                        {requests.map((request) => (
                            <Link
                                key={request.id}
                                href={show([pet.id, request.id])}
                                className="flex items-center justify-between rounded-xl border p-4 hover:bg-muted/50"
                            >
                                <div>
                                    <p className="font-medium">
                                        {request.service?.name}
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        {request.notes || 'Sin nota'}
                                    </p>
                                </div>
                                <Badge variant="outline">
                                    {request.status}
                                </Badge>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
