import { Head, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/pets';
import { index, show } from '@/routes/pets/treatments';
import type { Pet, PetTreatment } from '@/types';

export default function Treatment({
    pet,
    petTreatment,
}: {
    pet: Pet;
    petTreatment: PetTreatment;
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
            {
                title: petTreatment.treatment_name,
                href: show([pet.id, petTreatment.id]),
            },
        ],
    });

    return (
        <>
            <Head title={petTreatment.treatment_name} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <Heading
                    title={petTreatment.treatment_name}
                    description={`${pet.name} · ${petTreatment.status}`}
                />
                <p>
                    Progreso:{' '}
                    {petTreatment.sessions?.filter(
                        (session) => session.status === 'completed',
                    ).length ?? 0}{' '}
                    de {petTreatment.planned_sessions}
                </p>
                <div className="grid gap-3">
                    {petTreatment.sessions?.map((session) => (
                        <article
                            key={session.id}
                            className="rounded-xl border p-4"
                        >
                            <strong>Sesión #{session.session_number}</strong>
                            <p className="text-sm text-muted-foreground">
                                {session.status} · {session.price}{' '}
                                {session.currency}
                            </p>
                        </article>
                    ))}
                </div>
            </div>
        </>
    );
}
