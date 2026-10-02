import { usePage } from '@inertiajs/react';
import AppLogo from '@/components/app-logo';
import { Breadcrumbs } from '@/components/breadcrumbs';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger, useSidebar } from '@/components/ui/sidebar';
import { UserInfo } from '@/components/user-info';
import { UserMenuContent } from '@/components/user-menu-content';
import type { Auth, BreadcrumbItem as BreadcrumbItemType } from '@/types';

export function AppTopbar({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    const { auth } = usePage<{ auth: Auth }>().props;
    const { isMobile, state } = useSidebar();
    const currentTitle = breadcrumbs.at(-1)?.title ?? 'Inicio';
    const showLogo = isMobile || state === 'collapsed';

    return (
        <header className="sticky top-0 z-30 flex min-h-16 shrink-0 items-center border-b border-border bg-surface-base/95 px-4 py-2 shadow-sm backdrop-blur-md md:px-8">
            <div className="flex min-w-0 flex-1 items-center gap-2">
                <SidebarTrigger className="text-muted-foreground hover:bg-surface-subtle hover:text-foreground" />
                <div className="flex min-w-0 items-center gap-2.5">
                    {showLogo && <AppLogo compact />}
                    <div className="min-w-0">
                        {breadcrumbs.length > 0 ? (
                            <Breadcrumbs breadcrumbs={breadcrumbs} />
                        ) : (
                            <p className="truncate text-sm font-semibold text-foreground">
                                {currentTitle}
                            </p>
                        )}
                    </div>
                </div>
            </div>
            {auth.user && (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <button
                            type="button"
                            className="ml-3 flex size-11 shrink-0 items-center justify-center rounded-full transition-colors outline-none hover:bg-surface-subtle focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                            aria-label="Abrir menú de cuenta"
                        >
                            <UserInfo user={auth.user} showDetails={false} />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        align="end"
                        className="min-w-56 rounded-xl"
                    >
                        <UserMenuContent user={auth.user} />
                    </DropdownMenuContent>
                </DropdownMenu>
            )}
        </header>
    );
}
