import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import type { Pet } from '@/types';

export default function PetSummary({
    pet,
    editHref,
    photoHref,
}: {
    pet: Pet;
    editHref: string;
    photoHref: string;
}) {
    return (
        <div className="space-y-4 rounded-xl border p-6">
            {pet.photo && (
                <img
                    src={photoHref}
                    alt={pet.name}
                    className="h-48 w-48 rounded-lg object-cover"
                />
            )}
            <dl className="grid gap-3 sm:grid-cols-2">
                <div>
                    <dt className="text-sm text-muted-foreground">Species</dt>
                    <dd>{pet.species}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">Sex</dt>
                    <dd>{pet.sex}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">Breed</dt>
                    <dd>{pet.breed ?? '—'}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">Color</dt>
                    <dd>{pet.color ?? '—'}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">
                        Date of birth
                    </dt>
                    <dd>{pet.birth_date ?? '—'}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">Weight</dt>
                    <dd>{pet.weight ?? '—'}</dd>
                </div>
            </dl>
            {pet.notes && (
                <p className="text-sm whitespace-pre-wrap">{pet.notes}</p>
            )}
            <Button asChild>
                <Link href={editHref}>Edit pet</Link>
            </Button>
        </div>
    );
}
