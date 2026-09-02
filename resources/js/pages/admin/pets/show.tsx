import { Head, setLayoutProps } from '@inertiajs/react';
import PetContextHeader from '@/components/pet-context-header';
import PetSummary from '@/components/pet-summary';
import { dashboard } from '@/routes';
import { edit, index, show } from '@/routes/admin/pets';
import type { PetContext } from '@/types';

export default function AdminPetShow({ pet }: { pet: PetContext }) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: index() },
            { title: pet.name, href: show(pet.id) },
        ],
    });

    return (
        <>
            <Head title={pet.name} />
            <div className="mx-auto max-w-5xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="summary"
                    editHref={edit.url(pet.id)}
                />
                <PetSummary pet={pet} />
            </div>
        </>
    );
}
