import { BookOpen } from 'lucide-react';
import type { ReactNode } from 'react';
import { Badge } from '@/components/ui/badge';

type Props = {
    petName: string;
    count: number;
    description?: string;
    actions?: ReactNode;
};

export function ClinicalRecordsHeader({
    petName,
    count,
    description = `Evolución y registros clínicos de ${petName}.`,
    actions,
}: Props) {
    return (
        <header className="flex items-start justify-between gap-4 pt-1">
            <div className="min-w-0 space-y-1">
                <div className="flex items-center gap-2">
                    <BookOpen aria-hidden className="size-5 text-primary" />
                    <h2 className="text-section-title font-semibold tracking-tight">
                        Historia clínica
                    </h2>
                </div>
                <p className="text-sm leading-6 text-muted-foreground">
                    {description}
                </p>
            </div>
            <div className="flex shrink-0 items-center gap-2">
                <Badge variant="secondary" className="tabular-nums">
                    {count} {count === 1 ? 'registro' : 'registros'}
                </Badge>
                {actions}
            </div>
        </header>
    );
}
