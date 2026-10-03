import { FileText, Info } from 'lucide-react';
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
    const details = [
        { label: 'Especie', value: pet.species },
        { label: 'Raza', value: pet.breed ?? 'No informada' },
        { label: 'Sexo', value: formatSex(pet.sex) },
        {
            label: 'Fecha de nacimiento',
            value: pet.birth_date ? formatDate(pet.birth_date) : 'No informada',
        },
        {
            label: 'Peso',
            value: pet.weight ? `${pet.weight} kg` : 'No informado',
        },
        { label: 'Color', value: pet.color ?? 'No informado' },
    ];

    return (
        <div className="space-y-6">
            <section>
                <div className="mb-2 flex items-center gap-2 px-1">
                    <Info aria-hidden className="size-5 text-primary" />
                    <h2 className="text-section-title font-semibold">
                        Información general
                    </h2>
                </div>
                <dl className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    {details.map((detail, index) => (
                        <div
                            key={detail.label}
                            className={`flex items-center justify-between gap-4 px-4 py-3.5 text-sm sm:px-5 ${index % 2 === 1 ? 'bg-surface-subtle/55' : ''}`}
                        >
                            <dt className="text-muted-foreground">
                                {detail.label}
                            </dt>
                            <dd className="text-right font-semibold text-foreground">
                                {detail.value}
                            </dd>
                        </div>
                    ))}
                </dl>
            </section>
            {pet.notes && (
                <section>
                    <div className="mb-2 flex items-center gap-2 px-1">
                        <FileText aria-hidden className="size-5 text-primary" />
                        <h2 className="text-section-title font-semibold">
                            Notas
                        </h2>
                    </div>
                    <p className="rounded-xl border bg-card p-4 text-sm leading-6 whitespace-pre-wrap shadow-sm sm:p-5">
                        {pet.notes}
                    </p>
                </section>
            )}
        </div>
    );
}
