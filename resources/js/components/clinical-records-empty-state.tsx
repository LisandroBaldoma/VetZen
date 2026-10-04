import { BookOpen } from 'lucide-react';
import type { ReactNode } from 'react';

type Props = {
    petName: string;
    title?: string;
    description?: string;
    action?: ReactNode;
};

export function ClinicalRecordsEmptyState({
    petName,
    title = 'Todavía no hay registros clínicos',
    description = `Cuando se registren novedades clínicas de ${petName}, aparecerán en esta cronología.`,
    action,
}: Props) {
    return (
        <section className="flex flex-col items-center rounded-xl border bg-card p-6 text-center shadow-sm sm:p-10">
            <div className="mb-4 flex size-16 items-center justify-center rounded-full bg-surface-subtle text-primary">
                <BookOpen aria-hidden className="size-8" />
            </div>
            <h3 className="text-section-title font-semibold">{title}</h3>
            <p className="mt-1 max-w-md text-sm text-muted-foreground">
                {description}
            </p>
            {action && <div className="mt-4">{action}</div>}
        </section>
    );
}
