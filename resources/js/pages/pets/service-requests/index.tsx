import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { ClipboardListIcon, PlusIcon } from 'lucide-react';
import { EmptyState } from '@/components/empty-state';
import PageHeader from '@/components/page-header';
import PetContextHeader from '@/components/pet-context-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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
            <div className="workspace-reading">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="service-requests"
                    editHref={edit.url(pet.id)}
                />
                <PageHeader
                    title="Solicitudes de atención"
                    description="Consultá las evaluaciones solicitadas para esta mascota."
                    actions={
                        <Button asChild className="min-h-11 w-full sm:w-auto">
                            <Link href={create(pet.id)}>
                                <PlusIcon aria-hidden />
                                Nueva solicitud
                            </Link>
                        </Button>
                    }
                    actionsClassName="w-full sm:w-auto"
                />

                {requests.length === 0 ? (
                    <EmptyState
                        className="border-dashed shadow-none"
                        icon={
                            <ClipboardListIcon aria-hidden className="size-7" />
                        }
                        title={`Todavía no solicitaste atención para ${pet.name}`}
                        description="Cuando necesites atención, podés iniciar una solicitud para esta mascota."
                        action={
                            <Button asChild className="min-h-11">
                                <Link href={create(pet.id)}>
                                    <PlusIcon aria-hidden />
                                    Solicitar atención
                                </Link>
                            </Button>
                        }
                    />
                ) : (
                    <div className="grid gap-4 xl:grid-cols-2">
                        {requests.map((request) => (
                            <Card
                                key={request.id}
                                className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm"
                            >
                                <CardHeader className="gap-3 p-4 pb-3 sm:p-5 sm:pb-3">
                                    <div className="flex min-w-0 items-start justify-between gap-3">
                                        <div className="min-w-0">
                                            <CardTitle className="text-section-title leading-snug break-words">
                                                {request.service.name}
                                            </CardTitle>
                                            <p className="mt-1 text-sm text-muted-foreground">
                                                {dateFormatter.format(
                                                    new Date(
                                                        request.created_at,
                                                    ),
                                                )}
                                            </p>
                                        </div>
                                        <Badge
                                            variant="outline"
                                            className="shrink-0"
                                        >
                                            {statusLabels[request.status]}
                                        </Badge>
                                    </div>
                                </CardHeader>
                                <CardContent className="px-4 pb-3 sm:px-5">
                                    <p className="text-sm text-muted-foreground">
                                        {request.status === 'pending'
                                            ? 'Tu solicitud está pendiente de evaluación.'
                                            : request.status === 'resolved'
                                              ? 'La atención solicitada ya fue definida.'
                                              : 'La solicitud se conserva como historial.'}
                                    </p>
                                </CardContent>
                                <CardFooter className="flex-col items-stretch gap-2 px-4 pt-0 pb-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 sm:pb-5">
                                    <Button
                                        variant="outline"
                                        asChild
                                        className="min-h-11 w-full sm:w-auto"
                                    >
                                        <Link href={show([pet.id, request.id])}>
                                            Ver solicitud
                                        </Link>
                                    </Button>
                                    {request.pet_treatment && (
                                        <Button
                                            asChild
                                            className="min-h-11 w-full sm:w-auto"
                                        >
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
                                </CardFooter>
                            </Card>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
