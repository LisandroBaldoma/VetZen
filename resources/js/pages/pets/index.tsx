import { Head, Link } from '@inertiajs/react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index, show } from '@/routes/pets';
import type { Pet } from '@/types';

export default function PetsIndex({ pets }: { pets: Pet[] }) {
    return (
        <>
            <Head title="Mis mascotas" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Mis mascotas"
                    description="Administrá la información general de tus mascotas."
                    actions={
                        <Button asChild>
                            <Link href={create()}>Nueva mascota</Link>
                        </Button>
                    }
                />
                {pets.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        You do not have any pets yet.
                    </p>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {pets.map((pet) => (
                            <Link
                                key={pet.id}
                                href={show(pet.id)}
                                className="rounded-xl border p-5 hover:bg-muted/50"
                            >
                                <p className="font-medium">{pet.name}</p>
                                <p className="text-sm text-muted-foreground">
                                    {pet.species}
                                </p>
                            </Link>
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
