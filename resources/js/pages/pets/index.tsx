import { Head, Link } from '@inertiajs/react';
import PageHeader from '@/components/page-header';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index, photo, show } from '@/routes/pets';
import type { PetCard } from '@/types';

export default function PetsIndex({ pets }: { pets: PetCard[] }) {
    return (
        <>
            <Head title="Mis mascotas" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Mis mascotas"
                    description="Consultá sus datos y accedé a su atención en VetZen."
                    actions={
                        <Button asChild className="min-h-11">
                            <Link href={create()}>Registrar mascota</Link>
                        </Button>
                    }
                />

                {pets.length === 0 ? (
                    <section className="rounded-xl border border-dashed p-6 text-center sm:p-10">
                        <h2 className="font-semibold">
                            Todavía no registraste mascotas
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Registrá tu primera mascota para gestionar su
                            información y atención.
                        </p>
                        <Button asChild className="mt-4 min-h-11">
                            <Link href={create()}>Registrar mascota</Link>
                        </Button>
                    </section>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {pets.map((pet) => (
                            <article
                                key={pet.id}
                                className="flex min-w-0 items-center gap-4 rounded-xl border bg-card p-4 shadow-sm transition-shadow hover:shadow-md"
                            >
                                <Avatar className="size-16 rounded-2xl border">
                                    {pet.has_photo && (
                                        <AvatarImage
                                            src={photo.url(pet.id)}
                                            alt={`Foto de ${pet.name}`}
                                            className="object-cover"
                                        />
                                    )}
                                    <AvatarFallback
                                        className="rounded-2xl text-lg font-semibold"
                                        aria-label={`${pet.name} no tiene foto`}
                                    >
                                        {pet.name.slice(0, 1).toUpperCase()}
                                    </AvatarFallback>
                                </Avatar>
                                <div className="min-w-0">
                                    <Link
                                        href={show(pet.id)}
                                        className="font-semibold break-words underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                    >
                                        {pet.name}
                                    </Link>
                                    <p className="text-sm text-muted-foreground">
                                        {pet.species}
                                        {pet.breed ? ` · ${pet.breed}` : ''}
                                    </p>
                                </div>
                            </article>
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
