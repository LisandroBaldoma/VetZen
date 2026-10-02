import { Head, Link } from '@inertiajs/react';
import { ChevronRight, Ellipsis, PawPrint } from 'lucide-react';
import { EmptyState } from '@/components/empty-state';
import PageHeader from '@/components/page-header';
import { ResponsiveDataList } from '@/components/responsive-data-list';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
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
            <div className="mx-auto w-full max-w-7xl space-y-6 px-4 py-6 md:px-8 md:py-8">
                <PageHeader
                    title="Pacientes"
                    description="Localizá pacientes y accedé a su información clínica y de atención."
                    className="gap-5"
                    actionsClassName="w-full sm:w-auto"
                    actions={
                        <Button asChild className="min-h-11 w-full sm:w-auto">
                            <Link href={create()}>Nuevo paciente</Link>
                        </Button>
                    }
                />

                {pets.length === 0 ? (
                    <EmptyState
                        icon={<PawPrint aria-hidden className="size-7" />}
                        title="Todavía no hay pacientes"
                        description="Registrá el primer paciente y vinculalo con su responsable."
                        action={
                            <Button asChild className="min-h-11">
                                <Link href={create()}>Nuevo paciente</Link>
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
                                                    <PetActions pet={pet} />
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
                                    <article
                                        key={pet.id}
                                        className="rounded-xl border bg-card p-3.5 shadow-sm"
                                    >
                                        <div className="flex min-w-0 items-start gap-3">
                                            <PetAvatar pet={pet} />
                                            <div className="min-w-0 flex-1">
                                                <div className="flex min-w-0 items-start justify-between gap-2">
                                                    <Link
                                                        href={show(pet.id)}
                                                        className="min-w-0 truncate font-semibold underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                    >
                                                        {pet.name}
                                                    </Link>
                                                    <Badge variant="secondary">
                                                        {pet.species}
                                                    </Badge>
                                                </div>
                                                <p className="mt-0.5 truncate text-sm text-muted-foreground">
                                                    {pet.breed ??
                                                        'Raza no informada'}
                                                </p>
                                                <div className="mt-1.5 flex min-w-0 items-center gap-1.5 text-sm text-muted-foreground">
                                                    <span className="shrink-0">
                                                        Responsable:
                                                    </span>
                                                    <Link
                                                        href={editClient(
                                                            pet.client.id,
                                                        )}
                                                        className="min-w-0 truncate font-semibold text-foreground underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                    >
                                                        {pet.client.name}
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-3 flex items-center gap-2 border-t pt-3">
                                            <Button
                                                variant="secondary"
                                                asChild
                                                className="min-h-11 flex-1 justify-between"
                                            >
                                                <Link href={show(pet.id)}>
                                                    Ver ficha
                                                    <ChevronRight
                                                        aria-hidden
                                                        className="size-4 text-primary"
                                                    />
                                                </Link>
                                            </Button>
                                            <PetActions pet={pet} />
                                        </div>
                                    </article>
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
