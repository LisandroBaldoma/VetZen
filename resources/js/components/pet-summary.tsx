import type { PetContext } from '@/types';

function formatSex(sex: string): string {
    if (sex.toLowerCase() === 'female') {
        return 'Hembra';
    }

    if (sex.toLowerCase() === 'male') {
        return 'Macho';
    }

    return sex;
}

function formatDate(date: string): string {
    return new Intl.DateTimeFormat('es-AR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(`${date.slice(0, 10)}T00:00:00`));
}

export default function PetSummary({ pet }: { pet: PetContext }) {
    return (
        <section className="space-y-4 rounded-xl border p-5 sm:p-6">
            <div>
                <h2 className="text-lg font-semibold">Información general</h2>
                <p className="text-sm text-muted-foreground">
                    Datos básicos y de identificación.
                </p>
            </div>
            <dl className="grid gap-3 sm:grid-cols-2">
                <div>
                    <dt className="text-sm text-muted-foreground">Especie</dt>
                    <dd>{pet.species}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">Sexo</dt>
                    <dd>{formatSex(pet.sex)}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">Raza</dt>
                    <dd>{pet.breed ?? 'No informada'}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">Color</dt>
                    <dd>{pet.color ?? 'No informado'}</dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">
                        Nacimiento
                    </dt>
                    <dd>
                        {pet.birth_date
                            ? formatDate(pet.birth_date)
                            : 'No informado'}
                    </dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">Peso</dt>
                    <dd>{pet.weight ? `${pet.weight} kg` : 'No informado'}</dd>
                </div>
            </dl>
            {pet.notes && (
                <div className="space-y-1 border-t pt-4">
                    <h3 className="text-sm font-medium">Notas</h3>
                    <p className="text-sm whitespace-pre-wrap text-muted-foreground">
                        {pet.notes}
                    </p>
                </div>
            )}
        </section>
    );
}
