import { usePage } from '@inertiajs/react';

import { cn } from '@/lib/utils';

export default function AppLogo({
    compact = false,
    className,
}: {
    compact?: boolean;
    className?: string;
}) {
    const { name, logoUrl } = usePage().props;

    return (
        <div className={cn('flex min-w-0 items-center gap-2', className)}>
            <img
                alt={`${name} logo`}
                className="h-8 w-auto shrink-0 object-contain"
                src={logoUrl}
            />
            {!compact && (
                <div className="flex min-w-0 flex-col">
                    <span className="truncate text-sm leading-none font-bold tracking-tight text-foreground">
                        {name}
                    </span>
                    <span className="mt-1 truncate text-xs leading-none text-muted-foreground">
                        Clínica Veterinaria
                    </span>
                </div>
            )}
        </div>
    );
}
