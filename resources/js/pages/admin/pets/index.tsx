import { Head, Link } from '@inertiajs/react';
import { ChevronRight, PawPrint, Pencil, Plus } from 'lucide-react';
import { PetIndexCard } from '@/components/pets/pet-index-card';
import { PetIndexEmptyState } from '@/components/pets/pet-index-empty-state';
import { PetIndexHeader } from '@/components/pets/pet-index-header';
import { ResponsiveDataList } from '@/components/responsive-data-list';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit as editClient } from '@/routes/admin/clients';
import { create, edit, index, show } from '@/routes/admin/pets';
import { photo } from '@/routes/pets';
import type { PetCard } from '@/types';

type AdminPetCard = PetCard & {
    client: {
        id: number;
        name: string;
    };
};

function PetAvatar({ pet }: { pet: AdminPetCard }) {
    return (
        <Avatar className="size-13 rounded-xl border border-border bg-surface-subtle">
            {pet.has_photo && (
                <AvatarImage
                    src={photo.url(pet.id)}
                    alt={`Foto de ${pet.name}`}
                    className="object-cover"
                />
            )}
            <AvatarFallback
                className="relative rounded-xl bg-surface-subtle font-semibold text-primary"
                aria-label={`${pet.name} no tiene foto`}
            >
                <PawPrint
                    aria-hidden
                    className="absolute top-1.5 size-3.5 text-primary/65"
                />
                {pet.name.slice(0, 1).toUpperCase()}
            </AvatarFallback>
        </Avatar>
    );
}

export default function AdminPetsIndex({ pets }: { pets: AdminPetCard[] }) {
    return (
        <>
            <Head title="Pacientes" />
            <div className="mx-auto w-full max-w-7xl space-y-6 px-4 py-6 md:px-8 md:py-8">
                <PetIndexHeader
                    title="Pacientes"
                    description="Localizá pacientes y accedé a su información clínica y de atención."
                    count={pets.length}
                    countLabel={
                        pets.length === 1
                            ? 'paciente registrado'
                            : 'pacientes registrados'
                    }
                    actions={
                        <Button asChild className="min-h-11 w-full sm:w-auto">
                            <Link href={create()}>
                                <Plus aria-hidden className="size-4" />
                                Nuevo paciente
                            </Link>
                        </Button>
                    }
                />

                {pets.length === 0 ? (
                    <PetIndexEmptyState
                        title="No hay pacientes todavía"
                        description="Registrá el primer paciente para comenzar a gestionar su información clínica y su evolución médica."
                        guidanceTitle="Organización clínica centralizada"
                        guidanceDescription="Cada paciente registrado cuenta con su propio historial, fichas de consulta, planes de vacunación y seguimiento de sesiones."
                        action={
                            <Button asChild className="min-h-11">
                                <Link href={create()}>
                                    <Plus aria-hidden className="size-4" />
                                    Nuevo paciente
                                </Link>
                            </Button>
                        }
                    />
                ) : (
                    <ResponsiveDataList
                        desktop={
                            <div className="overflow-x-auto rounded-xl border bg-card shadow-sm">
                                <table className="w-full min-w-[52rem] text-left text-sm">
                                    <thead className="bg-surface-subtle text-meta font-semibold tracking-[0.12em] text-muted-foreground uppercase">
                                        <tr>
                                            <th
                                                scope="col"
                                                className="px-5 py-3"
                                            >
                                                Paciente
                                            </th>
                                            <th
                                                scope="col"
                                                className="px-5 py-3"
                                            >
                                                Especie
                                            </th>
                                            <th
                                                scope="col"
                                                className="px-5 py-3"
                                            >
                                                Raza
                                            </th>
                                            <th
                                                scope="col"
                                                className="px-5 py-3"
                                            >
                                                Responsable
                                            </th>
                                            <th
                                                scope="col"
                                                className="px-5 py-3 text-right"
                                            >
                                                <span className="sr-only">
                                                    Acciones
                                                </span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {pets.map((pet) => (
                                            <tr
                                                key={pet.id}
                                                className="border-t transition-colors hover:bg-surface-subtle/75"
                                            >
                                                <td className="px-5 py-3.5">
                                                    <div className="flex min-w-0 items-center gap-3">
                                                        <PetAvatar pet={pet} />
                                                        <Link
                                                            href={show(pet.id)}
                                                            className="min-w-0 truncate font-semibold underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                        >
                                                            {pet.name}
                                                        </Link>
                                                    </div>
                                                </td>
                                                <td className="px-5 py-3.5">
                                                    {pet.species}
                                                </td>
                                                <td className="px-5 py-3.5 text-muted-foreground">
                                                    {pet.breed ??
                                                        'No informada'}
                                                </td>
                                                <td className="max-w-64 px-5 py-3.5">
                                                    <Link
                                                        href={editClient(
                                                            pet.client.id,
                                                        )}
                                                        className="block truncate underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                    >
                                                        {pet.client.name}
                                                    </Link>
                                                </td>
                                                <td className="px-5 py-2 text-right">
                                                    <div className="flex justify-end gap-1">
                                                        <Button
                                                            variant="ghost"
                                                            size="icon"
                                                            asChild
                                                            className="size-11"
                                                        >
                                                            <Link
                                                                href={edit(
                                                                    pet.id,
                                                                )}
                                                                aria-label={`Editar datos de ${pet.name}`}
                                                            >
                                                                <Pencil
                                                                    aria-hidden
                                                                    className="size-4"
                                                                />
                                                            </Link>
                                                        </Button>
                                                        <Button
                                                            variant="secondary"
                                                            asChild
                                                            className="min-h-11"
                                                        >
                                                            <Link
                                                                href={show(
                                                                    pet.id,
                                                                )}
                                                            >
                                                                Ver ficha
                                                                <ChevronRight
                                                                    aria-hidden
                                                                    className="size-4 text-primary"
                                                                />
                                                            </Link>
                                                        </Button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        }
                        mobile={
                            <div className="grid gap-3">
                                {pets.map((pet) => (
                                    <PetIndexCard
                                        key={pet.id}
                                        pet={pet}
                                        showHref={show.url(pet.id)}
                                        editHref={edit.url(pet.id)}
                                        client={{
                                            name: pet.client.name,
                                            href: editClient.url(pet.client.id),
                                        }}
                                    />
                                ))}
                            </div>
                        }
                    />
                )}
            </div>
        </>
    );
}

AdminPetsIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Pacientes', href: index() },
    ],
};
