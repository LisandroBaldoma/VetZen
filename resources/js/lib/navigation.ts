import {
    ClipboardList,
    ClipboardPlus,
    LayoutGrid,
    PawPrint,
    Stethoscope,
    Syringe,
    Users,
} from 'lucide-react';
import { dashboard } from '@/routes';
import { index as clientsIndex } from '@/routes/admin/clients';
import { index as petsIndex } from '@/routes/admin/pets';
import { index as proceduresIndex } from '@/routes/admin/procedures';
import { index as serviceRequestsIndex } from '@/routes/admin/service-requests';
import { index as adminServicesIndex } from '@/routes/admin/services';
import { index as treatmentsIndex } from '@/routes/admin/treatments';
import { index as myPetsIndex } from '@/routes/pets';
import { index as servicesIndex } from '@/routes/services';
import type { NavGroup } from '@/types';

export type NavigationSection =
    | 'dashboard'
    | 'clients'
    | 'pets'
    | 'service-requests'
    | 'services'
    | 'procedures'
    | 'treatments';

const matches = (path: string, route: RegExp): boolean => route.test(path);

export function getNavigationSection(
    path: string,
    role: 'admin' | 'client' | null,
): NavigationSection | null {
    if (matches(path, /(?:^|\/)dashboard\/?$/)) {
        return 'dashboard';
    }

    if (role === 'admin') {
        if (matches(path, /(?:^|\/)admin\/clients(?:\/|$)/)) {
            return 'clients';
        }

        if (matches(path, /(?:^|\/)admin\/pets(?:\/|$)/)) {
            return 'pets';
        }

        if (matches(path, /(?:^|\/)admin\/service-requests(?:\/|$)/)) {
            return 'service-requests';
        }

        if (
            matches(path, /(?:^|\/)admin\/procedures(?:\/|$)/) ||
            matches(path, /(?:^|\/)admin\/services\/[^/]+\/procedures(?:\/|$)/)
        ) {
            return 'procedures';
        }

        if (
            matches(path, /(?:^|\/)admin\/treatments(?:\/|$)/) ||
            matches(path, /(?:^|\/)admin\/services\/[^/]+\/treatments(?:\/|$)/)
        ) {
            return 'treatments';
        }

        if (matches(path, /(?:^|\/)admin\/services(?:\/|$)/)) {
            return 'services';
        }
    }

    if (role === 'client') {
        if (matches(path, /(?:^|\/)pets(?:\/|$)/)) {
            return 'pets';
        }

        if (matches(path, /(?:^|\/)services(?:\/|$)/)) {
            return 'services';
        }
    }

    return null;
}

export function getNavigationGroups(
    role: 'admin' | 'client' | null,
    activeSection: NavigationSection | null,
): NavGroup[] {
    return [
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
                              mobileTitle: 'Inicio',
                          },
                      ],
                  },
              ]
            : []),
        ...(role === 'admin'
            ? [
                  {
                      title: 'Pacientes',
                      items: [
                          {
                              title: 'Clientes',
                              href: clientsIndex(),
                              icon: Users,
                              isActive: activeSection === 'clients',
                              mobileTitle: 'Clientes',
                          },
                          {
                              title: 'Pacientes',
                              href: petsIndex(),
                              icon: PawPrint,
                              isActive: activeSection === 'pets',
                              mobileTitle: 'Pacientes',
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
                              mobileTitle: 'Solicitudes',
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
            : role === 'client'
              ? [
                    {
                        title: 'Mis mascotas',
                        items: [
                            {
                                title: 'Mis mascotas',
                                href: myPetsIndex(),
                                icon: PawPrint,
                                isActive: activeSection === 'pets',
                                mobileTitle: 'Mascotas',
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
                                mobileTitle: 'Servicios',
                            },
                        ],
                    },
                ]
              : []),
    ];
}
