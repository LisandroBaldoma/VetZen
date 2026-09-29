import { Link } from '@inertiajs/react';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import type { NavGroup } from '@/types';

export function NavMain({ groups = [] }: { groups: NavGroup[] }) {
    const { setOpenMobile } = useSidebar();

    return (
        <>
            {groups.map((group) => (
                <SidebarGroup key={group.title} className="px-2 py-2">
                    <SidebarGroupLabel className="px-2 text-[0.6875rem] font-semibold tracking-[0.12em] text-sidebar-foreground/55 uppercase">
                        {group.title}
                    </SidebarGroupLabel>
                    <SidebarMenu>
                        {group.items.map((item) => (
                            <SidebarMenuItem key={item.title}>
                                <SidebarMenuButton
                                    asChild
                                    isActive={item.isActive}
                                    tooltip={{ children: item.title }}
                                >
                                    <Link
                                        href={item.href}
                                        prefetch
                                        aria-current={
                                            item.isActive ? 'page' : undefined
                                        }
                                        onClick={() => setOpenMobile(false)}
                                    >
                                        {item.icon && (
                                            <item.icon aria-hidden="true" />
                                        )}
                                        <span>{item.title}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        ))}
                    </SidebarMenu>
                </SidebarGroup>
            ))}
        </>
    );
}
