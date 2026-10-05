import { Head, Link } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { PetIndexCard } from '@/components/pets/pet-index-card';
import { PetIndexEmptyState } from '@/components/pets/pet-index-empty-state';
import { PetIndexHeader } from '@/components/pets/pet-index-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, edit, index, show } from '@/routes/pets';
import type { PetCard } from '@/types';

export default function PetsIndex({ pets }: { pets: PetCard[] }) {
    return (
        <>
            <Head title="Mis mascotas" />
            <div className="space-y-6 p-4">
                <PetIndexHeader
                    title="Mis mascotas"
                    description="Consultá sus datos y accedé a su atención en VetZen."
                    count={pets.length}
                    countLabel={
                        pets.length === 1
                            ? 'mascota registrada'
                            : 'mascotas registradas'
                    }
                    actions={
                        <Button asChild className="min-h-11 w-full sm:w-auto">
                            <Link href={create()}>
                                <Plus aria-hidden className="size-4" />
                                Registrar mascota
                            </Link>
                        </Button>
                    }
                />

                {pets.length === 0 ? (
                    <PetIndexEmptyState
                        title="Todavía no registraste mascotas"
                        description="Registrá tu primera mascota para gestionar su información y atención."
                        guidanceTitle="Organización clínica centralizada"
                        guidanceDescription="Cada mascota registrada cuenta con su propio historial, fichas de consulta, planes de vacunación y seguimiento de sesiones."
                        action={
                            <Button asChild className="min-h-11">
                                <Link href={create()}>
                                    <Plus aria-hidden className="size-4" />
                                    Registrar mascota
                                </Link>
                            </Button>
                        }
                    />
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {pets.map((pet) => (
                            <PetIndexCard
                                key={pet.id}
                                pet={pet}
                                showHref={show.url(pet.id)}
                                editHref={edit.url(pet.id)}
                            />
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

PetsIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Mis mascotas', href: index() },
    ],
};
