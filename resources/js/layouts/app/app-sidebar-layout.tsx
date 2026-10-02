import { AppBottomNav } from '@/components/app-bottom-nav';
import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { AppTopbar } from '@/components/app-topbar';
import type { AppLayoutProps } from '@/types';

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
}: AppLayoutProps) {
    return (
        <AppShell variant="sidebar">
            <AppSidebar />
            <AppContent
                variant="sidebar"
                className="min-w-0 overflow-x-clip bg-background pb-[calc(4rem+env(safe-area-inset-bottom))] md:pb-0"
            >
                <AppTopbar breadcrumbs={breadcrumbs} />
                {children}
            </AppContent>
            <AppBottomNav />
        </AppShell>
    );
}
