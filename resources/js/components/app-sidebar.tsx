import { usePage } from '@inertiajs/react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import {
    Sidebar,
    SidebarContent,
    SidebarHeader,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { getNavigationGroups, getNavigationSection } from '@/lib/navigation';
import type { Auth } from '@/types';

export function AppSidebar() {
    const { auth } = usePage<{ auth: Auth }>().props;
    const isAdmin = auth.roles.includes('admin');
    const isClient = auth.roles.includes('client');
    const role = isAdmin ? 'admin' : isClient ? 'client' : null;
    const { currentUrl } = useCurrentUrl();
    const activeSection = getNavigationSection(currentUrl, role);
    const mainNavGroups = getNavigationGroups(role, activeSection);

    return (
        <Sidebar
            collapsible="icon"
            className="border-r border-sidebar-border bg-sidebar"
        >
            <SidebarHeader className="h-16 shrink-0 justify-center border-b border-sidebar-border px-6 py-0 pr-14 group-data-[collapsible=icon]/sidebar-wrapper:hidden md:px-6">
                <AppLogo />
            </SidebarHeader>
            <SidebarContent className="px-2 py-4">
                <NavMain groups={mainNavGroups} />
            </SidebarContent>
        </Sidebar>
    );
}
