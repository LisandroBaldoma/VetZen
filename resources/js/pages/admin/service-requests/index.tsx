import { Head, Link, router } from '@inertiajs/react';
import { Search, X } from 'lucide-react';
import { useState } from 'react';
import type { FormEvent } from 'react';
import PageHeader from '@/components/page-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { dashboard } from '@/routes';
import { edit as editResponsible } from '@/routes/admin/clients';
import { show as showPet } from '@/routes/admin/pets';
import { index, show } from '@/routes/admin/service-requests';
import { show as showService } from '@/routes/admin/services';

type RequestStatus = 'pending' | 'resolved' | 'cancelled';
type ServiceRequest = {
    id: number;
    status: RequestStatus;
    created_at: string;
    pet: { id: number; name: string };
    responsible: { id: number; name: string };
    service: { id: number; name: string };
};
type PaginationLink = { url: string | null; label: string; active: boolean };
type PaginatedRequests = {
    data: ServiceRequest[];
    total: number;
    last_page: number;
    links: PaginationLink[];
};
type Filters = { search: string; service: string; status: string };

const statusLabels: Record<RequestStatus, string> = {
    pending: 'Pendiente',
    resolved: 'Resuelta',
    cancelled: 'Cancelada',
};
const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
});

function StatusBadge({ status }: { status: RequestStatus }) {
    return <Badge variant="outline">{statusLabels[status]}</Badge>;
}

export default function AdminRequests({
    requests,
    services,
    filters,
}: {
    requests: PaginatedRequests;
    services: { id: number; name: string }[];
    filters: Filters;
}) {
    const [search, setSearch] = useState(filters.search);
    const hasFilters = Boolean(
        filters.search || filters.service || filters.status,
    );

    const visit = (next: Filters) => {
        router.get(
            index().url,
            Object.fromEntries(
                Object.entries(next).filter(([, value]) => value !== ''),
            ),
            { preserveState: true, replace: true },
        );
    };

    const submitSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        visit({ ...filters, search });
    };

    return (
        <>
            <Head title="Solicitudes de atención" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Solicitudes de atención"
                    description="Revisá solicitudes y definí el tratamiento luego de la evaluación profesional."
                />

                <div className="grid gap-3 rounded-xl border p-4 md:grid-cols-[minmax(14rem,1fr)_minmax(12rem,auto)_minmax(10rem,auto)_auto] md:items-end">
                    <form onSubmit={submitSearch} className="flex gap-2">
                        <div className="grid flex-1 gap-2">
                            <Label htmlFor="request-search" className="sr-only">
                                Buscar paciente o responsable
                            </Label>
                            <Input
                                id="request-search"
                                value={search}
                                onChange={(event) =>
                                    setSearch(event.target.value)
                                }
                                placeholder="Paciente o responsable..."
                            />
                        </div>
                        <Button
                            type="submit"
                            size="icon"
                            aria-label="Buscar solicitudes"
                        >
                            <Search aria-hidden="true" />
                        </Button>
                    </form>

                    <div className="grid gap-2">
                        <Label htmlFor="service-filter">Servicio</Label>
                        <Select
                            value={filters.service || 'all'}
                            onValueChange={(value) =>
                                visit({
                                    ...filters,
                                    search,
                                    service: value === 'all' ? '' : value,
                                })
                            }
                        >
                            <SelectTrigger
                                id="service-filter"
                                className="w-full md:w-52"
                            >
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">
                                    Todos los servicios
                                </SelectItem>
                                {services.map((service) => (
                                    <SelectItem
                                        key={service.id}
                                        value={String(service.id)}
                                    >
                                        {service.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="status-filter">Estado</Label>
                        <Select
                            value={filters.status || 'all'}
                            onValueChange={(value) =>
                                visit({
                                    ...filters,
                                    search,
                                    status: value === 'all' ? '' : value,
                                })
                            }
                        >
                            <SelectTrigger
                                id="status-filter"
                                className="w-full md:w-44"
                            >
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">
                                    Todos los estados
                                </SelectItem>
                                <SelectItem value="pending">
                                    Pendientes
                                </SelectItem>
                                <SelectItem value="resolved">
                                    Resueltas
                                </SelectItem>
                                <SelectItem value="cancelled">
                                    Canceladas
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <Button variant="outline" asChild disabled={!hasFilters}>
                        <Link href={index()} aria-label="Limpiar filtros">
                            <X aria-hidden="true" /> Limpiar
                        </Link>
                    </Button>
                </div>

                {requests.total === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        {hasFilters
                            ? 'No se encontraron solicitudes con los filtros seleccionados.'
                            : 'Todavía no hay solicitudes de atención.'}
                    </p>
                ) : (
                    <>
                        <div className="hidden overflow-x-auto rounded-xl border md:block">
                            <table className="w-full min-w-4xl text-left text-sm">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th className="px-4 py-3">Paciente</th>
                                        <th className="px-4 py-3">
                                            Responsable
                                        </th>
                                        <th className="px-4 py-3">Servicio</th>
                                        <th className="px-4 py-3">Fecha</th>
                                        <th className="px-4 py-3">Estado</th>
                                        <th className="px-4 py-3 text-right">
                                            Acción
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {requests.data.map((request) => (
                                        <tr
                                            key={request.id}
                                            className="border-t"
                                        >
                                            <td className="px-4 py-3 font-medium">
                                                <Link
                                                    className="hover:underline"
                                                    href={showPet(
                                                        request.pet.id,
                                                    )}
                                                >
                                                    {request.pet.name}
                                                </Link>
                                            </td>
                                            <td className="px-4 py-3">
                                                <Link
                                                    className="hover:underline"
                                                    href={editResponsible(
                                                        request.responsible.id,
                                                    )}
                                                >
                                                    {request.responsible.name}
                                                </Link>
                                            </td>
                                            <td className="px-4 py-3">
                                                <Link
                                                    className="hover:underline"
                                                    href={showService(
                                                        request.service.id,
                                                    )}
                                                >
                                                    {request.service.name}
                                                </Link>
                                            </td>
                                            <td className="px-4 py-3 text-muted-foreground">
                                                {dateFormatter.format(
                                                    new Date(
                                                        request.created_at,
                                                    ),
                                                )}
                                            </td>
                                            <td className="px-4 py-3">
                                                <StatusBadge
                                                    status={request.status}
                                                />
                                            </td>
                                            <td className="px-4 py-3 text-right">
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    asChild
                                                >
                                                    <Link
                                                        href={show(request.id)}
                                                    >
                                                        Abrir
                                                    </Link>
                                                </Button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <div className="grid gap-3 md:hidden">
                            {requests.data.map((request) => (
                                <article
                                    key={request.id}
                                    className="space-y-4 rounded-xl border p-4"
                                >
                                    <div className="flex items-start justify-between gap-3">
                                        <div>
                                            <Link
                                                className="font-semibold hover:underline"
                                                href={showPet(request.pet.id)}
                                            >
                                                {request.pet.name}
                                            </Link>
                                            <p className="text-sm text-muted-foreground">
                                                {dateFormatter.format(
                                                    new Date(
                                                        request.created_at,
                                                    ),
                                                )}
                                            </p>
                                        </div>
                                        <StatusBadge status={request.status} />
                                    </div>
                                    <dl className="grid gap-2 text-sm">
                                        <div>
                                            <dt className="text-muted-foreground">
                                                Responsable
                                            </dt>
                                            <dd>
                                                <Link
                                                    className="font-medium hover:underline"
                                                    href={editResponsible(
                                                        request.responsible.id,
                                                    )}
                                                >
                                                    {request.responsible.name}
                                                </Link>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt className="text-muted-foreground">
                                                Servicio
                                            </dt>
                                            <dd>
                                                <Link
                                                    className="font-medium hover:underline"
                                                    href={showService(
                                                        request.service.id,
                                                    )}
                                                >
                                                    {request.service.name}
                                                </Link>
                                            </dd>
                                        </div>
                                    </dl>
                                    <Button
                                        className="w-full"
                                        variant="outline"
                                        asChild
                                    >
                                        <Link href={show(request.id)}>
                                            Abrir solicitud
                                        </Link>
                                    </Button>
                                </article>
                            ))}
                        </div>

                        {requests.last_page > 1 && (
                            <nav
                                className="flex flex-wrap justify-center gap-1"
                                aria-label="Paginación de solicitudes"
                            >
                                {requests.links.map((link, position) => (
                                    <Button
                                        key={`${link.label}-${position}`}
                                        size="sm"
                                        variant={
                                            link.active ? 'default' : 'outline'
                                        }
                                        disabled={!link.url}
                                        asChild={Boolean(link.url)}
                                    >
                                        {link.url ? (
                                            <Link href={link.url} preserveState>
                                                {position === 0
                                                    ? 'Anterior'
                                                    : position ===
                                                        requests.links.length -
                                                            1
                                                      ? 'Siguiente'
                                                      : link.label}
                                            </Link>
                                        ) : (
                                            <span>
                                                {position === 0
                                                    ? 'Anterior'
                                                    : 'Siguiente'}
                                            </span>
                                        )}
                                    </Button>
                                ))}
                            </nav>
                        )}
                    </>
                )}
            </div>
        </>
    );
}

AdminRequests.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Solicitudes de atención', href: index() },
    ],
};
