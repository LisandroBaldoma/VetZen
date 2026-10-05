import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

type Props = {
    icon?: ReactNode;
    title: string;
    description: string;
    action?: ReactNode;
    className?: string;
};

export function EmptyState({
    icon,
    title,
    description,
    action,
    className,
}: Props) {
    return (
        <section
            className={cn(
                'flex flex-col items-center rounded-xl border bg-card p-6 text-center shadow-sm sm:p-10',
                className,
            )}
        >
            {icon && (
                <div className="mb-3 flex size-14 items-center justify-center rounded-full bg-surface-subtle text-primary">
                    {icon}
                </div>
            )}
            <h2 className="text-section-title font-semibold text-foreground">
                {title}
            </h2>
            <p className="mt-1 max-w-md text-sm text-muted-foreground">
                {description}
            </p>
            {action && <div className="mt-4">{action}</div>}
        </section>
    );
}
