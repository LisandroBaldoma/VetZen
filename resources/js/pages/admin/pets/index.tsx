import { Head, Link } from '@inertiajs/react';
import { Ellipsis } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
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
        <Avatar className="size-11 rounded-xl border">
            {pet.has_photo && (
                <AvatarImage
                    src={photo.url(pet.id)}
                    alt={`Foto de ${pet.name}`}
                    className="object-cover"
                />
            )}
            <AvatarFallback
                className="rounded-xl font-semibold"
                aria-label={`${pet.name} no tiene foto`}
            >
                {pet.name.slice(0, 1).toUpperCase()}
            </AvatarFallback>
        </Avatar>
    );
}

function PetActions({ pet }: { pet: AdminPetCard }) {
    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    size="icon"
                    variant="ghost"
                    className="size-11"
                    aria-label={`Acciones para ${pet.name}`}
                >
                    <Ellipsis aria-hidden />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuItem asChild>
                    <Link href={show(pet.id)}>Ver paciente</Link>
                </DropdownMenuItem>
                <DropdownMenuItem asChild>
                    <Link href={edit(pet.id)}>Editar paciente</Link>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}

export default function AdminPetsIndex({ pets }: { pets: AdminPetCard[] }) {
    return (
        <>
            <Head title="Pacientes" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Pacientes"
                    description="Localizá pacientes y accedé a su información clínica y de atención."
                    actions={
                        <Button asChild className="min-h-11">
                            <Link href={create()}>Nuevo paciente</Link>
                        </Button>
                    }
                />

                {pets.length === 0 ? (
                    <section className="rounded-xl border border-dashed p-6 text-center sm:p-10">
                        <h2 className="font-semibold">
                            Todavía no hay pacientes
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Registrá el primer paciente y vinculalo con su
                            responsable.
                        </p>
                        <Button asChild className="mt-4 min-h-11">
                            <Link href={create()}>Nuevo paciente</Link>
                        </Button>
                    </section>
                ) : (
                    <>
                        <div className="hidden overflow-hidden rounded-xl border md:block">
                            <table className="w-full text-left text-sm">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th scope="col" className="px-4 py-3">
                                            Paciente
                                        </th>
                                        <th scope="col" className="px-4 py-3">
                                            Especie
                                        </th>
                                        <th scope="col" className="px-4 py-3">
                                            Raza
                                        </th>
                                        <th scope="col" className="px-4 py-3">
                                            Responsable
                                        </th>
                                        <th
                                            scope="col"
                                            className="px-4 py-3 text-right"
                                        >
                                            <span className="sr-only">
                                                Acciones
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {pets.map((pet) => (
                                        <tr key={pet.id} className="border-t">
                                            <td className="px-4 py-3">
                                                <div className="flex items-center gap-3">
                                                    <PetAvatar pet={pet} />
                                                    <Link
                                                        href={show(pet.id)}
                                                        className="font-semibold underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                    >
                                                        {pet.name}
                                                    </Link>
                                                </div>
                                            </td>
                                            <td className="px-4 py-3">
                                                {pet.species}
                                            </td>
                                            <td className="px-4 py-3 text-muted-foreground">
                                                {pet.breed ?? 'No informada'}
                                            </td>
                                            <td className="px-4 py-3">
                                                <Link
                                                    href={editClient(
                                                        pet.client.id,
                                                    )}
                                                    className="underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                >
                                                    {pet.client.name}
                                                </Link>
                                            </td>
                                            <td className="px-4 py-2 text-right">
                                                <PetActions pet={pet} />
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <div className="grid gap-3 md:hidden">
                            {pets.map((pet) => (
                                <article
                                    key={pet.id}
                                    className="rounded-xl border p-4"
                                >
                                    <div className="flex min-w-0 items-start gap-3">
                                        <PetAvatar pet={pet} />
                                        <div className="min-w-0 flex-1">
                                            <Link
                                                href={show(pet.id)}
                                                className="font-semibold break-words underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                            >
                                                {pet.name}
                                            </Link>
                                            <p className="text-sm text-muted-foreground">
                                                {pet.species}
                                                {pet.breed
                                                    ? ` · ${pet.breed}`
                                                    : ''}
                                            </p>
                                            <p className="mt-2 text-sm">
                                                Responsable:{' '}
                                                <Link
                                                    href={editClient(
                                                        pet.client.id,
                                                    )}
                                                    className="font-medium underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                >
                                                    {pet.client.name}
                                                </Link>
                                            </p>
                                        </div>
                                        <PetActions pet={pet} />
                                    </div>
                                </article>
                            ))}
                        </div>
                    </>
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
