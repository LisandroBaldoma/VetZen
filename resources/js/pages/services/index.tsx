import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { create as createPet } from '@/routes/pets';
import { create as createRequest } from '@/routes/pets/service-requests';
import { index, show } from '@/routes/services';

type Service = { id: number; name: string; description: string };
type Pet = { id: number; name: string };

export default function ServicesIndex({
    services,
    pets,
}: {
    services: Service[];
    pets: Pet[];
}) {
    const [selectedPets, setSelectedPets] = useState<Record<number, string>>(
        {},
    );

    return (
        <>
            <Head title="Servicios disponibles" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Servicios disponibles"
                    description="Conocé las terapias complementarias y solicitá una evaluación para una mascota propia."
                />
                {services.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        No hay servicios disponibles en este momento.
                    </p>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {services.map((service) => {
                            const selectedPet = selectedPets[service.id] ?? '';

                            return (
                                <article
                                    key={service.id}
                                    className="flex flex-col gap-4 rounded-xl border p-5"
                                >
                                    <div className="space-y-2">
                                        <h2 className="font-semibold">
                                            {service.name}
                                        </h2>
                                        <p className="line-clamp-3 text-sm text-muted-foreground">
                                            {service.description}
                                        </p>
                                    </div>
                                    <Button variant="outline" asChild>
                                        <Link href={show(service.id)}>
                                            Ver detalles
                                        </Link>
                                    </Button>
                                    <div className="mt-auto space-y-3 border-t pt-4">
                                        {pets.length === 0 ? (
                                            <div className="space-y-2">
                                                <p className="text-sm text-muted-foreground">
                                                    Registrá una mascota para
                                                    solicitar atención.
                                                </p>
                                                <Button
                                                    className="w-full"
                                                    asChild
                                                >
                                                    <Link href={createPet()}>
                                                        Registrar mascota
                                                    </Link>
                                                </Button>
                                            </div>
                                        ) : (
                                            <>
                                                <div className="grid gap-2">
                                                    <Label
                                                        htmlFor={`pet-${service.id}`}
                                                    >
                                                        Mascota
                                                    </Label>
                                                    <select
                                                        id={`pet-${service.id}`}
                                                        value={selectedPet}
                                                        onChange={(event) =>
                                                            setSelectedPets(
                                                                (current) => ({
                                                                    ...current,
                                                                    [service.id]:
                                                                        event
                                                                            .target
                                                                            .value,
                                                                }),
                                                            )
                                                        }
                                                        className="h-10 rounded-md border bg-background px-3"
                                                    >
                                                        <option value="">
                                                            Seleccionar…
                                                        </option>
                                                        {pets.map((pet) => (
                                                            <option
                                                                key={pet.id}
                                                                value={pet.id}
                                                            >
                                                                {pet.name}
                                                            </option>
                                                        ))}
                                                    </select>
                                                </div>
                                                {selectedPet ? (
                                                    <Button
                                                        className="w-full"
                                                        asChild
                                                    >
                                                        <Link
                                                            href={createRequest(
                                                                Number(
                                                                    selectedPet,
                                                                ),
                                                                {
                                                                    query: {
                                                                        service:
                                                                            service.id,
                                                                    },
                                                                },
                                                            )}
                                                        >
                                                            Solicitar atención
                                                        </Link>
                                                    </Button>
                                                ) : (
                                                    <Button
                                                        className="w-full"
                                                        disabled
                                                    >
                                                        Solicitar atención
                                                    </Button>
                                                )}
                                            </>
                                        )}
                                    </div>
                                </article>
                            );
                        })}
                    </div>
                )}
            </div>
        </>
    );
}

ServicesIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Servicios disponibles', href: index() },
    ],
};
