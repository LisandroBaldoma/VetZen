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
        <section className="space-y-5 rounded-xl border bg-card p-5 shadow-sm sm:p-6">
            <div>
                <h2 className="text-lg font-semibold">Información general</h2>
                <p className="text-sm text-muted-foreground">
                    Datos básicos y de identificación.
                </p>
            </div>
            <dl className="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                <div>
                    <dt className="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                        Especie
                    </dt>
                    <dd className="mt-1 font-medium">{pet.species}</dd>
                </div>
                <div>
                    <dt className="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                        Sexo
                    </dt>
                    <dd className="mt-1 font-medium">{formatSex(pet.sex)}</dd>
                </div>
                <div>
                    <dt className="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                        Raza
                    </dt>
                    <dd className="mt-1 font-medium">
                        {pet.breed ?? 'No informada'}
                    </dd>
                </div>
                <div>
                    <dt className="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                        Color
                    </dt>
                    <dd className="mt-1 font-medium">
                        {pet.color ?? 'No informado'}
                    </dd>
                </div>
                <div>
                    <dt className="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                        Nacimiento
                    </dt>
                    <dd className="mt-1 font-medium">
                        {pet.birth_date
                            ? formatDate(pet.birth_date)
                            : 'No informado'}
                    </dd>
                </div>
                <div>
                    <dt className="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                        Peso
                    </dt>
                    <dd className="mt-1 font-medium">
                        {pet.weight ? `${pet.weight} kg` : 'No informado'}
                    </dd>
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
