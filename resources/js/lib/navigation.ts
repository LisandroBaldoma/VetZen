import {
    CalendarDays,
    ClipboardList,
    ClipboardPlus,
    LayoutGrid,
    PawPrint,
    ShieldCheck,
    ScrollText,
    Stethoscope,
    Syringe,
    Users,
} from 'lucide-react';
import type { SimonModule } from '@/components/simon/simon-chat';
import { dashboard } from '@/routes';
import { index as administratorsIndex } from '@/routes/admin/administrators';
import { index as auditLogsIndex } from '@/routes/admin/audit-logs';
import { index as adminAppointmentsIndex } from '@/routes/admin/appointments';
import { index as clientsIndex } from '@/routes/admin/clients';
import { index as adminPetTreatmentsIndex } from '@/routes/admin/pet-treatments';
import { index as petsIndex } from '@/routes/admin/pets';
import { index as proceduresIndex } from '@/routes/admin/procedures';
import { index as scheduleDatesIndex } from '@/routes/admin/schedule-dates';
import { index as serviceRequestsIndex } from '@/routes/admin/service-requests';
import { index as adminServicesIndex } from '@/routes/admin/services';
import { index as treatmentsIndex } from '@/routes/admin/treatments';
import { index as clientAppointmentsIndex } from '@/routes/appointments';
import { index as myTreatmentsIndex } from '@/routes/my-treatments';
import { index as myPetsIndex } from '@/routes/pets';
import { index as servicesIndex } from '@/routes/services';
import type { NavGroup } from '@/types';

export type NavigationSection =
    | 'dashboard'
    | 'clients'
    | 'administrators'
    | 'audit-logs'
    | 'appointments'
    | 'medical-records'
    | 'pets'
    | 'service-requests'
    | 'schedule-dates'
    | 'services'
    | 'procedures'
    | 'treatments'
    | 'treatment-catalog';

const matches = (path: string, route: RegExp): boolean => route.test(path);

export function getNavigationSection(
    path: string,
    role: 'admin' | 'client' | null,
): NavigationSection | null {
    if (matches(path, /(?:^|\/)dashboard\/?$/)) {
        return 'dashboard';
    }

    if (role === 'admin') {
        if (matches(path, /(?:^|\/)admin\/administrators(?:\/|$)/)) {
            return 'administrators';
        }

        if (matches(path, /(?:^|\/)admin\/audit-logs(?:\/|$)/)) {
            return 'audit-logs';
        }

        if (matches(path, /(?:^|\/)admin\/clients(?:\/|$)/)) {
            return 'clients';
        }

        if (matches(path, /(?:^|\/)admin\/appointments(?:\/|$)/)) {
            return 'appointments';
        }

        if (matches(path, /(?:^|\/)admin\/pets(?:\/|$)/)) {
            return 'pets';
        }

        if (matches(path, /(?:^|\/)admin\/pet-treatments(?:\/|$)/)) {
            return 'treatments';
        }

        if (matches(path, /(?:^|\/)admin\/service-requests(?:\/|$)/)) {
            return 'service-requests';
        }

        if (matches(path, /(?:^|\/)admin\/schedule-dates(?:\/|$)/)) {
            return 'schedule-dates';
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
            return 'treatment-catalog';
        }

        if (matches(path, /(?:^|\/)admin\/services(?:\/|$)/)) {
            return 'services';
        }
    }

    if (role === 'client') {
        if (matches(path, /(?:^|\/)appointments(?:\/|$)/)) {
            return 'appointments';
        }

        if (matches(path, /(?:^|\/)my-treatments(?:\/|$)/)) {
            return 'treatments';
        }

        if (matches(path, /(?:^|\/)pets(?:\/|$)/)) {
            return 'pets';
        }

        if (matches(path, /(?:^|\/)services(?:\/|$)/)) {
            return 'services';
        }
    }

    return null;
}

export function getSimonModule(
    section: NavigationSection | null,
): SimonModule | undefined {
    switch (section) {
        case 'administrators':
        case 'audit-logs':
            return 'clients';
        case 'clients':
        case 'appointments':
        case 'medical-records':
        case 'pets':
        case 'procedures':
        case 'service-requests':
        case 'services':
        case 'treatments':
            return section;
        case 'treatment-catalog':
            return 'treatments';
        case 'schedule-dates':
            return 'appointments';
        default:
            return undefined;
    }
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
                              title: 'Turnos',
                              href: adminAppointmentsIndex(),
                              icon: CalendarDays,
                              isActive: activeSection === 'appointments',
                              mobileTitle: 'Turnos',
                          },
                          {
                              title: 'Solicitudes',
                              href: serviceRequestsIndex(),
                              icon: ClipboardPlus,
                              isActive: activeSection === 'service-requests',
                              mobileTitle: 'Solicitudes',
                          },
                          {
                              title: 'Tratamientos de pacientes',
                              href: adminPetTreatmentsIndex(),
                              icon: Syringe,
                              isActive: activeSection === 'treatments',
                              mobileTitle: 'Tratamientos',
                          },
                          {
                              title: 'Agenda',
                              href: scheduleDatesIndex(),
                              icon: CalendarDays,
                              isActive: activeSection === 'schedule-dates',
                              mobileTitle: 'Agenda',
                          },
                      ],
                  },
                  {
                      title: 'Catálogo',
                      items: [
                          {
                              title: 'Servicios',
                              href: adminServicesIndex(),
                              icon: Stethoscope,
                              isActive: activeSection === 'services',
                          },
                          {
                              title: 'Procedimientos',
                              href: proceduresIndex(),
                              icon: ClipboardList,
                              isActive: activeSection === 'procedures',
                          },
                          {
                              title: 'Plantillas de tratamientos',
                              href: treatmentsIndex(),
                              icon: Syringe,
                              isActive: activeSection === 'treatment-catalog',
                          },
                      ],
                  },
                  {
                      title: 'Administración',
                      items: [
                          {
                              title: 'Administradores',
                              href: administratorsIndex(),
                              icon: ShieldCheck,
                              isActive: activeSection === 'administrators',
                          },
                          {
                              title: 'Auditoría',
                              href: auditLogsIndex(),
                              icon: ScrollText,
                              isActive: activeSection === 'audit-logs',
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
                                title: 'Turnos',
                                href: clientAppointmentsIndex(),
                                icon: CalendarDays,
                                isActive: activeSection === 'appointments',
                                mobileTitle: 'Turnos',
                            },
                            {
                                title: 'Servicios disponibles',
                                href: servicesIndex(),
                                icon: Stethoscope,
                                isActive: activeSection === 'services',
                                mobileTitle: 'Servicios',
                            },
                            {
                                title: 'Mis tratamientos',
                                href: myTreatmentsIndex(),
                                icon: Syringe,
                                isActive: activeSection === 'treatments',
                                mobileTitle: 'Tratamientos',
                            },
                        ],
                    },
                ]
              : []),
    ];
}
