import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { create, show } from '@/routes/pets';
import type { Pet } from '@/types';

export default function PetsIndex({ pets }: { pets: Pet[] }) {
    return (
        <>
            <Head title="My pets" />
            <div className="space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        title="My pets"
                        description="Manage your pets' general information."
                    />
                    <Button asChild>
                        <Link href={create()}>Add pet</Link>
                    </Button>
                </div>
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
