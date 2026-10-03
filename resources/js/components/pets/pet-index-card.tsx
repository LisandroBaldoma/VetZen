import { Link } from '@inertiajs/react';
import { ChevronRight, Pencil, PawPrint, UserRound } from 'lucide-react';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { photo } from '@/routes/pets';
import type { PetCard } from '@/types';

type Props = {
    pet: PetCard;
    showHref: string;
    editHref?: string;
    client?: {
        name: string;
        href: string;
    };
};

function formatSex(sex: string): string {
    if (sex.toLowerCase() === 'female') {
        return 'Hembra';
    }

    if (sex.toLowerCase() === 'male') {
        return 'Macho';
    }

    return sex;
}

export function PetIndexCard({ pet, showHref, editHref, client }: Props) {
    return (
        <article className="flex min-w-0 flex-col gap-3 rounded-xl border bg-card p-4 shadow-sm">
            <div className="flex min-w-0 items-start gap-3">
                <Avatar className="size-13 shrink-0 rounded-full border border-border bg-surface-subtle">
                    {pet.has_photo && (
                        <AvatarImage
                            src={photo.url(pet.id)}
                            alt={`Foto de ${pet.name}`}
                            className="object-cover"
                        />
                    )}
                    <AvatarFallback
                        className="relative rounded-full bg-surface-subtle font-semibold text-primary"
                        aria-label={`${pet.name} no tiene foto`}
                    >
                        <PawPrint
                            aria-hidden
                            className="absolute top-1.5 size-3.5 text-primary/65"
                        />
                        {pet.name.slice(0, 1).toUpperCase()}
                    </AvatarFallback>
                </Avatar>
                <div className="min-w-0 flex-1">
                    <div className="flex min-w-0 items-start justify-between gap-2">
                        <Link
                            href={showHref}
                            className="min-w-0 truncate font-semibold underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                        >
                            {pet.name}
                        </Link>
                        <Badge variant="secondary" className="shrink-0">
                            {formatSex(pet.sex)}
                        </Badge>
                    </div>
                    <p className="mt-0.5 truncate text-sm font-medium text-muted-foreground">
                        {pet.species}
                        {pet.breed ? ` · ${pet.breed}` : ''}
                    </p>
                    {client && (
                        <div className="mt-1.5 flex min-w-0 items-center gap-1.5 text-sm text-muted-foreground">
                            <UserRound
                                aria-hidden
                                className="size-3.5 shrink-0"
                            />
                            <span className="shrink-0">Responsable:</span>
                            <Link
                                href={client.href}
                                className="min-w-0 truncate font-semibold text-foreground underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                            >
                                {client.name}
                            </Link>
                        </div>
                    )}
                </div>
            </div>
            <div className="flex items-center justify-end gap-2 border-t pt-3">
                {editHref && (
                    <Button
                        variant="ghost"
                        size="icon"
                        asChild
                        className="size-11"
                    >
                        <Link
                            href={editHref}
                            aria-label={`Editar datos de ${pet.name}`}
                        >
                            <Pencil aria-hidden className="size-4" />
                        </Link>
                    </Button>
                )}
                <Button
                    variant="secondary"
                    asChild
                    className="min-h-11 justify-between"
                >
                    <Link href={showHref}>
                        Ver ficha
                        <ChevronRight
                            aria-hidden
                            className="size-4 text-primary"
                        />
                    </Link>
                </Button>
            </div>
        </article>
    );
}
