import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index, show } from '@/routes/pets/treatments';
import type { Pet, PetTreatment } from '@/types';

export default function Treatments({
    pet,
    petTreatments,
}: {
    pet: Pet;
    petTreatments: PetTreatment[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`Tratamientos de ${pet.name}`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <Heading
                    title={`Tratamientos de ${pet.name}`}
                    description="Consulta de progreso y sesiones."
                />
                <div className="grid gap-3">
                    {petTreatments.length === 0 && (
                        <p className="rounded-xl border p-6">
                            No hay tratamientos asignados.
                        </p>
                    )}
                    {petTreatments.map((item) => (
                        <Link
                            key={item.id}
                            href={show.url([pet.id, item.id])}
                            className="rounded-xl border p-5"
                        >
                            <strong>{item.treatment_name}</strong>
                            <p>
                                {item.completed_sessions_count ?? 0} de{' '}
                                {item.planned_sessions} completadas
                            </p>
                        </Link>
                    ))}
                </div>
            </div>
        </>
    );
}
