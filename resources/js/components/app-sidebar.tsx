import { Link, usePage } from '@inertiajs/react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { getNavigationGroups, getNavigationSection } from '@/lib/navigation';
import { dashboard } from '@/routes';
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
            <SidebarHeader className="relative border-b border-sidebar-border px-4 pt-4 pb-3 group-data-[collapsible=icon]/sidebar-wrapper:px-2">
                <SidebarTrigger className="absolute top-3 right-3 md:hidden" />
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            size="lg"
                            className="pr-12 md:pr-2"
                            asChild
                        >
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent className="px-2 py-4">
                <NavMain groups={mainNavGroups} />
            </SidebarContent>

            <SidebarFooter className="border-t border-sidebar-border bg-surface-subtle/45 px-4 py-4 group-data-[collapsible=icon]/sidebar-wrapper:px-2">
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
