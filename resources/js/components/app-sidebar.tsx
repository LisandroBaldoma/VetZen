import { Link, usePage } from '@inertiajs/react';
import {
    ClipboardList,
    ClipboardPlus,
    LayoutGrid,
    PawPrint,
    Stethoscope,
    Syringe,
    Users,
} from 'lucide-react';
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
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { getNavigationSection } from '@/lib/navigation';
import { dashboard } from '@/routes';
import { index as clientsIndex } from '@/routes/admin/clients';
import { index as petsIndex } from '@/routes/admin/pets';
import { index as proceduresIndex } from '@/routes/admin/procedures';
import { index as serviceRequestsIndex } from '@/routes/admin/service-requests';
import { index as adminServicesIndex } from '@/routes/admin/services';
import { index as treatmentsIndex } from '@/routes/admin/treatments';
import { index as myPetsIndex } from '@/routes/pets';
import { index as servicesIndex } from '@/routes/services';
import type { Auth, NavGroup } from '@/types';

export function AppSidebar() {
    const { auth } = usePage<{ auth: Auth }>().props;
    const isAdmin = auth.roles.includes('admin');
    const isClient = auth.roles.includes('client');
    const role = isAdmin ? 'admin' : isClient ? 'client' : null;
    const { currentUrl } = useCurrentUrl();
    const activeSection = getNavigationSection(currentUrl, role);
    const mainNavGroups: NavGroup[] = [
        ...(role
            ? [
                  {
                      title: 'General',
                      items: [
                          {
                              title: 'Inicio',
                              href: dashboard(),
                              icon: LayoutGrid,
                              isActive: activeSection === 'dashboard',
                          },
                      ],
                  },
              ]
            : []),
        ...(isAdmin
            ? [
                  {
                      title: 'Pacientes',
                      items: [
                          {
                              title: 'Clientes',
                              href: clientsIndex(),
                              icon: Users,
                              isActive: activeSection === 'clients',
                          },
                          {
                              title: 'Pacientes',
                              href: petsIndex(),
                              icon: PawPrint,
                              isActive: activeSection === 'pets',
                          },
                      ],
                  },
                  {
                      title: 'Atención',
                      items: [
                          {
                              title: 'Solicitudes de atención',
                              href: serviceRequestsIndex(),
                              icon: ClipboardPlus,
                              isActive: activeSection === 'service-requests',
                          },
                      ],
                  },
                  {
                      title: 'Catálogo clínico',
                      items: [
                          {
                              title: 'Servicios clínicos',
                              href: adminServicesIndex(),
                              icon: Stethoscope,
                              isActive: activeSection === 'services',
                          },
                          {
                              title: 'Procedimientos clínicos',
                              href: proceduresIndex(),
                              icon: ClipboardList,
                              isActive: activeSection === 'procedures',
                          },
                          {
                              title: 'Plantillas de tratamiento',
                              href: treatmentsIndex(),
                              icon: Syringe,
                              isActive: activeSection === 'treatments',
                          },
                      ],
                  },
              ]
            : isClient
              ? [
                    {
                        title: 'Mis mascotas',
                        items: [
                            {
                                title: 'Mis mascotas',
                                href: myPetsIndex(),
                                icon: PawPrint,
                                isActive: activeSection === 'pets',
                            },
                        ],
                    },
                    {
                        title: 'Atención',
                        items: [
                            {
                                title: 'Servicios disponibles',
                                href: servicesIndex(),
                                icon: Stethoscope,
                                isActive: activeSection === 'services',
                            },
                        ],
                    },
                ]
              : []),
    ];

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain groups={mainNavGroups} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
