import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import type { Pet, PetTreatment } from '@/types';

export default function Treatment({
    pet,
    petTreatment,
}: {
    pet: Pet;
    petTreatment: PetTreatment;
}) {
    return (
        <>
            <Head title={petTreatment.treatment_name} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <Heading
                    title={petTreatment.treatment_name}
                    description={`${pet.name} · ${petTreatment.status}`}
                />
                <p>
                    Progreso:{' '}
                    {petTreatment.sessions?.filter(
                        (session) => session.status === 'completed',
                    ).length ?? 0}{' '}
                    de {petTreatment.planned_sessions}
                </p>
                <div className="grid gap-3">
                    {petTreatment.sessions?.map((session) => (
                        <article
                            key={session.id}
                            className="rounded-xl border p-4"
                        >
                            <strong>Sesión #{session.session_number}</strong>
                            <p className="text-sm text-muted-foreground">
                                {session.status} · {session.price}{' '}
                                {session.currency}
                            </p>
                        </article>
                    ))}
                </div>
            </div>
        </>
    );
}
