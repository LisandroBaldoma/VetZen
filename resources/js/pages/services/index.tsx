import { Head, Link } from '@inertiajs/react';
import { ArrowRight, Check, PawPrint, Stethoscope } from 'lucide-react';
import { useState } from 'react';
import { EmptyState } from '@/components/empty-state';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { cn } from '@/lib/utils';
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
    const [selectedServiceId, setSelectedServiceId] = useState<number | null>(
        null,
    );
    const [selectedPetId, setSelectedPetId] = useState<number | null>(null);
    const selectedService = services.find(
        (service) => service.id === selectedServiceId,
    );

    return (
        <>
            <Head title="Servicios disponibles" />
            <div className="workspace-reading">
                <PageHeader
                    title="¿Qué tipo de atención necesitás para tu mascota?"
                    description="Elegí una opción para comenzar tu solicitud de atención."
                />

                {services.length === 0 ? (
                    <EmptyState
                        icon={<Stethoscope aria-hidden className="size-7" />}
                        title="No hay tipos de atención disponibles"
                        description="No hay servicios disponibles en este momento."
                    />
                ) : (
                    <div className="space-y-8">
                        <section
                            aria-labelledby="attention-types-title"
                            className="space-y-4"
                        >
                            <div>
                                <h2
                                    id="attention-types-title"
                                    className="text-xl font-semibold tracking-tight text-balance"
                                >
                                    Tipos de atención
                                </h2>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    Elegí el tipo de atención por el que querés
                                    consultar.
                                </p>
                            </div>

                            <div className="grid gap-3 lg:grid-cols-2">
                                {services.map((service) => {
                                    const isSelected =
                                        service.id === selectedServiceId;

                                    return (
                                        <Card
                                            key={service.id}
                                            className={cn(
                                                'gap-0 overflow-hidden p-0 transition-colors',
                                                isSelected
                                                    ? 'border-primary bg-primary/5 shadow-none'
                                                    : 'hover:border-primary/40',
                                            )}
                                        >
                                            <CardContent className="p-0">
                                                <button
                                                    type="button"
                                                    aria-pressed={isSelected}
                                                    onClick={() =>
                                                        setSelectedServiceId(
                                                            service.id,
                                                        )
                                                    }
                                                    className="group flex min-h-28 w-full items-start gap-4 p-4 text-left transition-colors outline-none hover:bg-surface-subtle/70 focus-visible:bg-surface-subtle/70 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-inset sm:p-5"
                                                >
                                                    <div
                                                        className={cn(
                                                            'mt-0.5 flex size-10 shrink-0 items-center justify-center rounded-xl bg-surface-subtle text-primary transition-colors',
                                                            isSelected &&
                                                                'bg-primary text-primary-foreground',
                                                        )}
                                                    >
                                                        {isSelected ? (
                                                            <Check
                                                                aria-hidden
                                                                className="size-5"
                                                            />
                                                        ) : (
                                                            <Stethoscope
                                                                aria-hidden
                                                                className="size-5"
                                                            />
                                                        )}
                                                    </div>
                                                    <span className="min-w-0 flex-1">
                                                        <span className="flex items-start justify-between gap-3">
                                                            <span className="text-section-title font-semibold text-foreground">
                                                                {service.name}
                                                            </span>
                                                            <ArrowRight
                                                                aria-hidden
                                                                className={cn(
                                                                    'mt-0.5 size-4 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-0.5',
                                                                    isSelected &&
                                                                        'text-primary',
                                                                )}
                                                            />
                                                        </span>
                                                        <span className="mt-1.5 block text-sm leading-6 text-muted-foreground">
                                                            {
                                                                service.description
                                                            }
                                                        </span>
                                                        {isSelected && (
                                                            <span className="mt-3 flex items-center gap-1.5 text-sm font-semibold text-primary">
                                                                <Check
                                                                    aria-hidden
                                                                    className="size-4"
                                                                />
                                                                Tipo de atención
                                                                seleccionado
                                                            </span>
                                                        )}
                                                    </span>
                                                </button>
                                            </CardContent>
                                            <CardFooter className="justify-end border-t bg-card/70 px-4 py-2.5 sm:px-5">
                                                <Button
                                                    variant="link"
                                                    asChild
                                                    className="h-auto min-h-11 px-0"
                                                >
                                                    <Link
                                                        href={show(service.id)}
                                                    >
                                                        Ver detalles
                                                    </Link>
                                                </Button>
                                            </CardFooter>
                                        </Card>
                                    );
                                })}
                            </div>
                        </section>

                        {selectedService && (
                            <section
                                aria-labelledby="pet-selection-title"
                                className="space-y-4 border-t pt-6 sm:pt-8"
                            >
                                <div className="flex flex-wrap items-end justify-between gap-3">
                                    <div>
                                        <p className="text-sm font-medium text-primary">
                                            Tipo de atención seleccionado
                                        </p>
                                        <h2
                                            id="pet-selection-title"
                                            className="mt-1 text-xl font-semibold tracking-tight text-balance"
                                        >
                                            ¿Para cuál de tus mascotas?
                                        </h2>
                                    </div>
                                    <p className="rounded-full bg-surface-subtle px-3 py-1 text-sm font-semibold text-foreground">
                                        {selectedService.name}
                                    </p>
                                </div>

                                {pets.length === 0 ? (
                                    <EmptyState
                                        className="border-dashed shadow-none sm:p-7"
                                        icon={
                                            <PawPrint
                                                aria-hidden
                                                className="size-7"
                                            />
                                        }
                                        title="Registrá una mascota para solicitar atención"
                                        description="Cuando registres una mascota, vas a poder enviar esta solicitud."
                                        action={
                                            <Button
                                                asChild
                                                className="min-h-11"
                                            >
                                                <Link href={createPet()}>
                                                    Registrar mascota
                                                </Link>
                                            </Button>
                                        }
                                    />
                                ) : (
                                    <>
                                        <div
                                            className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3"
                                            aria-label="Seleccionar mascota"
                                        >
                                            {pets.map((pet) => {
                                                const isSelected =
                                                    pet.id === selectedPetId;

                                                return (
                                                    <button
                                                        key={pet.id}
                                                        type="button"
                                                        aria-pressed={
                                                            isSelected
                                                        }
                                                        onClick={() =>
                                                            setSelectedPetId(
                                                                pet.id,
                                                            )
                                                        }
                                                        className={cn(
                                                            'flex min-h-16 items-center gap-3 rounded-xl border bg-card p-3.5 text-left shadow-sm transition-colors outline-none hover:border-primary/40 hover:bg-surface-subtle/70 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2',
                                                            isSelected &&
                                                                'border-primary bg-primary/5 ring-1 ring-primary',
                                                        )}
                                                    >
                                                        <span
                                                            className={cn(
                                                                'flex size-10 shrink-0 items-center justify-center rounded-full bg-surface-subtle font-semibold text-primary',
                                                                isSelected &&
                                                                    'bg-primary text-primary-foreground',
                                                            )}
                                                            aria-hidden
                                                        >
                                                            {pet.name
                                                                .slice(0, 1)
                                                                .toUpperCase()}
                                                        </span>
                                                        <span className="min-w-0 flex-1 truncate font-semibold">
                                                            {pet.name}
                                                        </span>
                                                        {isSelected && (
                                                            <Check
                                                                aria-label="Seleccionada"
                                                                className="size-5 shrink-0 text-primary"
                                                            />
                                                        )}
                                                    </button>
                                                );
                                            })}
                                        </div>

                                        <div className="flex flex-col gap-3 rounded-xl border bg-card p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-5">
                                            <div>
                                                <p className="font-semibold">
                                                    {selectedPetId
                                                        ? 'Ya podés continuar con tu solicitud.'
                                                        : 'Elegí una mascota para continuar.'}
                                                </p>
                                                <p className="mt-1 text-sm text-muted-foreground">
                                                    Vas a poder contarnos qué
                                                    necesitás en el siguiente
                                                    paso.
                                                </p>
                                            </div>
                                            <Button
                                                asChild={selectedPetId !== null}
                                                disabled={
                                                    selectedPetId === null
                                                }
                                                className="min-h-11 shrink-0 sm:min-w-32"
                                            >
                                                {selectedPetId === null ? (
                                                    <span>Continuar</span>
                                                ) : (
                                                    <Link
                                                        href={createRequest(
                                                            selectedPetId,
                                                            {
                                                                query: {
                                                                    service:
                                                                        selectedService.id,
                                                                },
                                                            },
                                                        )}
                                                    >
                                                        Continuar
                                                        <ArrowRight
                                                            aria-hidden
                                                        />
                                                    </Link>
                                                )}
                                            </Button>
                                        </div>
                                    </>
                                )}
                            </section>
                        )}
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
