import { usePage } from '@inertiajs/react';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { getNavigationSection } from '@/lib/navigation';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

const areaLabels: Record<string, string> = {
    dashboard: 'Espacio de trabajo',
    clients: 'Pacientes',
    pets: 'Pacientes',
    'service-requests': 'Atención',
    services: 'Catálogo clínico',
    procedures: 'Catálogo clínico',
    treatments: 'Catálogo clínico',
};

export function AppSidebarHeader({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    const { auth } = usePage().props;
    const { currentUrl } = useCurrentUrl();
    const role = auth.roles.includes('admin')
        ? 'admin'
        : auth.roles.includes('client')
          ? 'client'
          : null;
    const section = getNavigationSection(currentUrl, role);
    const area = section ? areaLabels[section] : undefined;

    return (
        <header className="flex min-h-16 shrink-0 items-center gap-3 border-b border-border/70 bg-background/88 px-4 py-3 backdrop-blur-sm transition-[width,height] ease-linear md:px-8">
            <div className="flex min-w-0 items-center gap-3">
                <SidebarTrigger className="-ml-1" />
                <div className="min-w-0 space-y-0.5">
                    {area && (
                        <p className="text-[0.625rem] font-semibold tracking-[0.16em] text-muted-foreground uppercase">
                            {area}
                        </p>
                    )}
                    <Breadcrumbs breadcrumbs={breadcrumbs} />
                </div>
            </div>
        </header>
    );
}
