import { Head, Link, setLayoutProps } from '@inertiajs/react';
import { useState } from 'react';
import Heading from '@/components/heading';
import ServiceDetails from '@/components/service-details';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { create as createPet } from '@/routes/pets';
import { create as createRequest } from '@/routes/pets/service-requests';
import { index, show } from '@/routes/services';

type Procedure = {
    id: number;
    name: string;
    description: string | null;
    duration_minutes: number | null;
};
type Service = {
    id: number;
    name: string;
    description: string;
    procedures: Procedure[];
};
type Pet = { id: number; name: string };

export default function ServiceShow({
    service,
    pets,
}: {
    service: Service;
    pets: Pet[];
}) {
    const [selectedPet, setSelectedPet] = useState('');

    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Servicios disponibles', href: index() },
            { title: service.name, href: show(service.id) },
        ],
    });

    return (
        <>
            <Head title={service.name} />
            <div className="mx-auto max-w-4xl space-y-6 p-4">
                <Heading
                    title={service.name}
                    description="Información general para solicitar una evaluación profesional."
                />
                <div className="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(16rem,1fr)]">
                    <ServiceDetails service={service} />
                    <aside className="h-fit space-y-4 rounded-xl border p-5">
                        <div>
                            <h2 className="font-semibold">
                                Solicitar atención
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Elegí una mascota propia. El tratamiento será
                                definido por un profesional.
                            </p>
                        </div>
                        {pets.length === 0 ? (
                            <Button className="w-full" asChild>
                                <Link href={createPet()}>
                                    Registrar mascota
                                </Link>
                            </Button>
                        ) : (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="pet">Mascota</Label>
                                    <select
                                        id="pet"
                                        value={selectedPet}
                                        onChange={(event) =>
                                            setSelectedPet(event.target.value)
                                        }
                                        className="h-10 rounded-md border bg-background px-3"
                                    >
                                        <option value="">Seleccionar…</option>
                                        {pets.map((pet) => (
                                            <option key={pet.id} value={pet.id}>
                                                {pet.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                {selectedPet ? (
                                    <Button className="w-full" asChild>
                                        <Link
                                            href={createRequest(
                                                Number(selectedPet),
                                                {
                                                    query: {
                                                        service: service.id,
                                                    },
                                                },
                                            )}
                                        >
                                            Continuar solicitud
                                        </Link>
                                    </Button>
                                ) : (
                                    <Button className="w-full" disabled>
                                        Continuar solicitud
                                    </Button>
                                )}
                            </>
                        )}
                    </aside>
                </div>
            </div>
        </>
    );
}
