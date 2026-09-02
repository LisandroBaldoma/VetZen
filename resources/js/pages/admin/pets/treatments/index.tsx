import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/admin/pets';
import { create, index, show } from '@/routes/admin/pets/treatments';
import type { Pet, PetTreatment } from '@/types';

export default function PetTreatments({
    pet,
    petTreatments,
}: {
    pet: Pet;
    petTreatments: PetTreatment[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`Tratamientos de ${pet.name}`} />
            <div className="mx-auto max-w-4xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <div className="flex items-center justify-between">
                    <Heading
                        title={`Tratamientos de ${pet.name}`}
                        description="Seguimiento clínico y sesiones."
                    />
                    <Button asChild>
                        <Link href={create.url(pet.id)}>
                            Asignar tratamiento
                        </Link>
                    </Button>
                </div>
                <div className="grid gap-3">
                    {petTreatments.length === 0 && (
                        <p className="rounded-xl border p-6 text-muted-foreground">
                            Todavía no hay tratamientos asignados.
                        </p>
                    )}
                    {petTreatments.map((item) => (
                        <Link
                            key={item.id}
                            href={show.url([pet.id, item.id])}
                            className="rounded-xl border p-5 hover:bg-muted/40"
                        >
                            <div className="font-semibold">
                                {item.treatment_name}
                            </div>
                            <div className="text-sm text-muted-foreground">
                                {item.completed_sessions_count ?? 0} de{' '}
                                {item.planned_sessions} sesiones completadas ·{' '}
                                {item.status}
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </>
    );
}
