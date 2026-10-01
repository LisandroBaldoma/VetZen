import { Head, setLayoutProps } from '@inertiajs/react';
import PetContextHeader from '@/components/pet-context-header';
import PetSummary from '@/components/pet-summary';
import { dashboard } from '@/routes';
import { edit, index, show } from '@/routes/pets';
import type { PetContext } from '@/types';

export default function PetShow({ pet }: { pet: PetContext }) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Mis mascotas', href: index() },
            { title: pet.name, href: show(pet.id) },
        ],
    });

    return (
        <>
            <Head title={pet.name} />
            <div className="workspace-clinical">
                <PetContextHeader
                    pet={pet}
                    variant="client"
                    active="summary"
                    editHref={edit.url(pet.id)}
                />
                <PetSummary pet={pet} />
            </div>
        </>
    );
}
