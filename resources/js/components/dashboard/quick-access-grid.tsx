import { Link } from '@inertiajs/react';
import type { InertiaLinkProps } from '@inertiajs/react';
import type { LucideIcon } from 'lucide-react';

export type QuickAccessItem = {
    title: string;
    description: string;
    icon: LucideIcon;
    href: InertiaLinkProps['href'];
};

export function QuickAccessGrid({ items }: { items: QuickAccessItem[] }) {
    return (
        <section aria-labelledby="quick-access-title" className="space-y-4">
            <div>
                <h2
                    id="quick-access-title"
                    className="text-xl font-semibold tracking-tight text-balance"
                >
                    Accesos rápidos
                </h2>
                <p className="mt-1 text-sm text-muted-foreground">
                    Catálogo y procesos esenciales de la práctica clínica.
                </p>
            </div>
            <div className="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-5">
                {items.map((item) => {
                    const Icon = item.icon;

                    return (
                        <Link
                            key={item.title}
                            href={item.href}
                            className="group flex min-h-40 flex-col items-center justify-center rounded-2xl border bg-card p-4 text-center shadow-sm transition-colors hover:bg-surface-subtle focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                        >
                            <div className="mb-3 flex size-12 items-center justify-center rounded-xl bg-surface-subtle text-primary transition-colors group-hover:bg-primary/10">
                                <Icon className="size-6" aria-hidden="true" />
                            </div>
                            <h3 className="font-semibold group-hover:text-primary">
                                {item.title}
                            </h3>
                            <p className="mt-0.5 text-xs leading-5 text-muted-foreground">
                                {item.description}
                            </p>
                        </Link>
                    );
                })}
            </div>
        </section>
    );
}
