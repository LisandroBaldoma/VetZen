import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { create, show } from '@/routes/admin/pets';
import type { Client, Pet, User } from '@/types';

type AdminPet = Pet & {
    client: Client & { user: Pick<User, 'name' | 'email'> };
};

export default function AdminPetsIndex({ pets }: { pets: AdminPet[] }) {
    return (
        <>
            <Head title="Pets" />
            <div className="space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        title="Pets"
                        description="Manage patient information."
                    />
                    <Button asChild>
                        <Link href={create()}>Add pet</Link>
                    </Button>
                </div>
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
