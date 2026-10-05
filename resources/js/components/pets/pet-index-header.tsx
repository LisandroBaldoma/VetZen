import type { ReactNode } from 'react';
import { Badge } from '@/components/ui/badge';

type Props = {
    title: string;
    description: string;
    count: number;
    countLabel: string;
    actions?: ReactNode;
};

export function PetIndexHeader({
    title,
    description,
    count,
    countLabel,
    actions,
}: Props) {
    return (
        <header className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div className="min-w-0 space-y-1">
                <div className="flex flex-wrap items-center gap-2">
                    <h1 className="text-2xl font-semibold tracking-tight">
                        {title}
                    </h1>
                    <Badge variant="secondary">
                        {count} {countLabel}
                    </Badge>
                </div>
                <p className="text-sm text-muted-foreground">{description}</p>
            </div>
            {actions && (
                <div className="flex w-full shrink-0 flex-wrap gap-2 sm:w-auto">
                    {actions}
                </div>
            )}
        </header>
    );
}
