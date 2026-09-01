import { Head, Link } from '@inertiajs/react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index, show } from '@/routes/admin/pets';
import type { Client, Pet, User } from '@/types';

type AdminPet = Pet & {
    client: Client & { user: Pick<User, 'name' | 'email'> };
};

export default function AdminPetsIndex({ pets }: { pets: AdminPet[] }) {
    return (
        <>
            <Head title="Pacientes" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Pacientes"
                    description="Administrá la información general de los pacientes."
                    actions={
                        <Button asChild>
                            <Link href={create()}>Nuevo paciente</Link>
                        </Button>
                    }
                />
                {pets.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        There are no pets yet.
                    </p>
                ) : (
                    <div className="overflow-hidden rounded-xl border">
                        <table className="w-full text-left text-sm">
                            <thead className="bg-muted/50 text-muted-foreground">
                                <tr>
                                    <th className="px-4 py-3">Pet</th>
                                    <th className="px-4 py-3">Species</th>
                                    <th className="px-4 py-3">Client</th>
                                    <th className="px-4 py-3" />
                                </tr>
                            </thead>
                            <tbody>
                                {pets.map((pet) => (
                                    <tr key={pet.id} className="border-t">
                                        <td className="px-4 py-3">
                                            {pet.name}
                                        </td>
                                        <td className="px-4 py-3">
                                            {pet.species}
                                        </td>
                                        <td className="px-4 py-3">
                                            {pet.client.user.name}
                                        </td>
                                        <td className="px-4 py-3 text-right">
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                asChild
                                            >
                                                <Link href={show(pet.id)}>
                                                    View
                                                </Link>
                                            </Button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
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
