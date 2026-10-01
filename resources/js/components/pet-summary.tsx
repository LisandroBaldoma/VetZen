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
        <section className="space-y-7">
            <div className="max-w-xl space-y-1">
                <p className="text-[0.6875rem] font-semibold tracking-[0.16em] text-clinical-foreground uppercase">
                    Identificación
                </p>
                <h2 className="text-2xl font-semibold tracking-tight">
                    Información general
                </h2>
                <p className="text-sm leading-6 text-muted-foreground">
                    Datos básicos que acompañan el expediente clínico de{' '}
                    {pet.name}.
                </p>
            </div>
            <dl className="grid border-y border-border sm:grid-cols-2 lg:grid-cols-3">
                <div className="border-b border-border px-0 py-5 sm:px-5 sm:first:pl-0 lg:border-r lg:pr-8">
                    <dt className="text-[0.625rem] font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Especie
                    </dt>
                    <dd className="mt-1 font-medium">{pet.species}</dd>
                </div>
                <div className="border-b border-border py-5 sm:px-5 lg:border-r lg:pr-8">
                    <dt className="text-[0.625rem] font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Sexo
                    </dt>
                    <dd className="mt-1 font-medium">{formatSex(pet.sex)}</dd>
                </div>
                <div className="border-b border-border py-5 sm:px-5 sm:pr-0 lg:border-r lg:pr-8">
                    <dt className="text-[0.625rem] font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Raza
                    </dt>
                    <dd className="mt-1 font-medium">
                        {pet.breed ?? 'No informada'}
                    </dd>
                </div>
                <div className="border-b border-border py-5 sm:px-0 sm:pr-5 lg:border-r lg:px-5 lg:pr-8">
                    <dt className="text-[0.625rem] font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Color
                    </dt>
                    <dd className="mt-1 font-medium">
                        {pet.color ?? 'No informado'}
                    </dd>
                </div>
                <div className="border-b border-border py-5 sm:px-5 lg:border-r lg:pr-8">
                    <dt className="text-[0.625rem] font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Nacimiento
                    </dt>
                    <dd className="mt-1 font-medium">
                        {pet.birth_date
                            ? formatDate(pet.birth_date)
                            : 'No informado'}
                    </dd>
                </div>
                <div className="border-b border-border py-5 sm:px-5 sm:pr-0 lg:border-r-0 lg:pr-0">
                    <dt className="text-[0.625rem] font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Peso
                    </dt>
                    <dd className="mt-1 font-medium">
                        {pet.weight ? `${pet.weight} kg` : 'No informado'}
                    </dd>
                </div>
            </dl>
            {pet.notes && (
                <div className="max-w-3xl border-l-2 border-clinical-foreground/50 pl-5">
                    <h3 className="text-sm font-semibold">Notas</h3>
                    <p className="mt-1 text-sm leading-6 whitespace-pre-wrap text-muted-foreground">
                        {pet.notes}
                    </p>
                </div>
            )}
        </section>
    );
}
