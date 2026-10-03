import { BadgeCheck, ClipboardList, PawPrint, Plus } from 'lucide-react';
import type { ReactNode } from 'react';

type Props = {
    title: string;
    description: string;
    action: ReactNode;
    guidanceTitle: string;
    guidanceDescription: string;
};

export function PetIndexEmptyState({
    title,
    description,
    action,
    guidanceTitle,
    guidanceDescription,
}: Props) {
    return (
        <div className="space-y-4">
            <section className="flex flex-col items-center rounded-xl border bg-card p-6 text-center shadow-sm sm:p-10">
                <div className="relative mb-4">
                    <div className="flex size-20 items-center justify-center rounded-full bg-surface-subtle text-primary shadow-inner">
                        <PawPrint aria-hidden className="size-9" />
                    </div>
                    <div className="absolute -right-1 -bottom-1 flex size-7 items-center justify-center rounded-full bg-primary/15 text-primary shadow-sm">
                        <Plus aria-hidden className="size-4" />
                    </div>
                </div>
                <h2 className="text-section-title font-semibold text-foreground">
                    {title}
                </h2>
                <p className="mt-1 max-w-md text-sm text-muted-foreground">
                    {description}
                </p>
                <div className="mt-4">{action}</div>
            </section>

            <section className="flex items-start gap-3 rounded-xl bg-surface-subtle p-4 shadow-sm">
                <div className="flex size-8 shrink-0 items-center justify-center rounded-lg bg-card text-primary">
                    <BadgeCheck aria-hidden className="size-5" />
                </div>
                <div>
                    <h2 className="text-sm font-semibold">{guidanceTitle}</h2>
                    <p className="mt-0.5 text-sm leading-snug text-muted-foreground">
                        {guidanceDescription}
                    </p>
                </div>
            </section>

            <div className="grid grid-cols-2 gap-3">
                <section className="rounded-xl border bg-card p-4 shadow-sm">
                    <div className="mb-2 flex size-7 items-center justify-center rounded-full bg-surface-subtle text-primary">
                        <ClipboardList aria-hidden className="size-4" />
                    </div>
                    <h2 className="text-sm font-semibold">Identificación</h2>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Microchip, especie, raza y responsable a cargo.
                    </p>
                </section>
                <section className="rounded-xl border bg-card p-4 shadow-sm">
                    <div className="mb-2 flex size-7 items-center justify-center rounded-full bg-surface-subtle text-primary">
                        <PawPrint aria-hidden className="size-4" />
                    </div>
                    <h2 className="text-sm font-semibold">Anamnesis</h2>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Alergias, patologías previas y peso actual.
                    </p>
                </section>
            </div>
        </div>
    );
}
