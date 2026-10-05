import { Link, usePage } from '@inertiajs/react';
import { Menu } from 'lucide-react';
import { useSidebar } from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { getNavigationGroups, getNavigationSection } from '@/lib/navigation';
import type { Auth, NavItem } from '@/types';

export function AppBottomNav() {
    const { auth } = usePage<{ auth: Auth }>().props;
    const role = auth.roles.includes('admin')
        ? 'admin'
        : auth.roles.includes('client')
          ? 'client'
          : null;
    const { currentUrl } = useCurrentUrl();
    const { openMobile, toggleSidebar } = useSidebar();
    const items = getNavigationGroups(
        role,
        getNavigationSection(currentUrl, role),
    )
        .flatMap((group) => group.items)
        .filter((item): item is NavItem & { mobileTitle: string } =>
            Boolean(item.mobileTitle),
        );
    const hasMore =
        items.length <
        getNavigationGroups(role, null).flatMap((group) => group.items).length;

    if (items.length === 0) {
        return null;
    }

    return (
        <nav
            aria-label="Navegación principal"
            className="fixed inset-x-0 bottom-0 z-30 border-t border-border bg-surface-base/95 pb-[env(safe-area-inset-bottom)] shadow-[0_-1px_8px_rgb(38_51_40_/_0.04)] backdrop-blur-md md:hidden"
        >
            <div className="mx-auto flex h-16 max-w-lg items-center justify-around px-1">
                {items.map((item) => {
                    const Icon = item.icon;

                    return (
                        <Link
                            key={item.title}
                            href={item.href}
                            prefetch
                            aria-current={item.isActive ? 'page' : undefined}
                            className="flex min-h-11 min-w-14 flex-1 flex-col items-center justify-center gap-0.5 rounded-lg text-meta font-semibold text-muted-foreground transition-colors hover:text-primary focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none data-[active=true]:text-primary"
                            data-active={item.isActive}
                        >
                            {Icon && <Icon aria-hidden className="size-5" />}
                            <span className="truncate">{item.mobileTitle}</span>
                        </Link>
                    );
                })}
                {hasMore && (
                    <button
                        type="button"
                        aria-label="Abrir más opciones de navegación"
                        aria-expanded={openMobile}
                        onClick={toggleSidebar}
                        className="flex min-h-11 min-w-14 flex-1 flex-col items-center justify-center gap-0.5 rounded-lg text-meta font-semibold text-muted-foreground transition-colors hover:text-primary focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                    >
                        <Menu aria-hidden className="size-5" />
                        <span>Más</span>
                    </button>
                )}
            </div>
        </nav>
    );
}
