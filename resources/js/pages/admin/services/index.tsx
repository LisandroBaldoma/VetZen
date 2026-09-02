import { Head, Link, router } from '@inertiajs/react';
import { Eye, Pencil, Search, X } from 'lucide-react';
import { useState } from 'react';
import type { FormEvent } from 'react';
import ServiceStatusController from '@/actions/App/Http/Controllers/Admin/ServiceStatusController';
import CatalogIconLink from '@/components/catalog-icon-link';
import CatalogStatusForm from '@/components/catalog-status-form';
import PageHeader from '@/components/page-header';
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
import { create, edit, index } from '@/routes/admin/services';
import { index as proceduresIndex } from '@/routes/admin/services/procedures';

type Service = {
    id: number;
    name: string;
    is_active: boolean;
    procedures_count: number;
};

type PaginationLink = { url: string | null; label: string; active: boolean };
type PaginatedServices = {
    data: Service[];
    links: PaginationLink[];
    last_page: number;
    total: number;
};
type Filters = { search: string; status: string };

export default function AdminServicesIndex({
    services,
    filters,
}: {
    services: PaginatedServices;
    filters: Filters;
}) {
    const [search, setSearch] = useState(filters.search);
    const hasFilters = Boolean(filters.search || filters.status);
    const visit = (next: Filters) =>
        router.get(
            index().url,
            Object.fromEntries(
                Object.entries(next).filter(([, value]) => value !== ''),
            ),
            { preserveState: true, replace: true },
        );
    const submitSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        visit({ ...filters, search });
    };

    return (
        <>
            <Head title="Servicios" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Servicios clínicos"
                    description="Administrá las terapias y sus procedimientos disponibles."
                    actions={
                        <Button asChild>
                            <Link href={create()}>Crear servicio</Link>
                        </Button>
                    }
                />
                <div className="grid gap-3 rounded-xl border p-4 sm:grid-cols-[minmax(14rem,1fr)_minmax(11rem,auto)_auto] sm:items-end">
                    <form onSubmit={submitSearch} className="flex gap-2">
                        <Label htmlFor="service-search" className="sr-only">
                            Buscar servicio
                        </Label>
                        <Input
                            id="service-search"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            placeholder="Buscar servicio..."
                        />
                        <Button
                            type="submit"
                            size="icon"
                            aria-label="Buscar servicios"
                        >
                            <Search aria-hidden="true" />
                        </Button>
                    </form>
                    <div className="grid gap-2">
                        <Label htmlFor="service-status">Estado</Label>
                        <Select
                            value={filters.status || 'all'}
                            onValueChange={(value) =>
                                visit({
                                    search,
                                    status: value === 'all' ? '' : value,
                                })
                            }
                        >
                            <SelectTrigger
                                id="service-status"
                                className="w-full sm:w-44"
                            >
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">
                                    Todos los estados
                                </SelectItem>
                                <SelectItem value="active">Activos</SelectItem>
                                <SelectItem value="inactive">
                                    Inactivos
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    {hasFilters ? (
                        <Button variant="outline" asChild>
                            <Link href={index()}>
                                <X aria-hidden="true" /> Limpiar
                            </Link>
                        </Button>
                    ) : (
                        <Button variant="outline" disabled>
                            <X aria-hidden="true" /> Limpiar
                        </Button>
                    )}
                </div>
                {services.total === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        {hasFilters
                            ? 'No se encontraron servicios con los filtros seleccionados.'
                            : 'Todavía no hay servicios. Creá el primero para comenzar.'}
                    </p>
                ) : (
                    <>
                        <div className="grid gap-3 md:hidden">
                            {services.data.map((service) => (
                                <article
                                    key={service.id}
                                    className="space-y-4 rounded-xl border p-4"
                                >
                                    <div className="flex items-start justify-between gap-3">
                                        <Link
                                            href={proceduresIndex(service.id)}
                                            className="font-semibold underline-offset-4 hover:underline"
                                        >
                                            {service.name}
                                        </Link>
                                        <CatalogStatusForm
                                            form={ServiceStatusController.update.form(
                                                service.id,
                                            )}
                                            isActive={service.is_active}
                                            subject={`el servicio ${service.name}`}
                                        />
                                    </div>
                                    <div className="flex items-center justify-between gap-3 text-sm text-muted-foreground">
                                        <span>
                                            {service.procedures_count}{' '}
                                            procedimientos
                                        </span>
                                        <div className="flex gap-1">
                                            <CatalogIconLink
                                                href={proceduresIndex(
                                                    service.id,
                                                )}
                                                label={`Ver procedimientos de ${service.name}`}
                                                icon={Eye}
                                            />
                                            <CatalogIconLink
                                                href={edit(service.id)}
                                                label={`Editar servicio ${service.name}`}
                                                icon={Pencil}
                                            />
                                        </div>
                                    </div>
                                </article>
                            ))}
                        </div>
                        <div className="hidden overflow-x-auto rounded-xl border md:block">
                            <table className="w-full min-w-2xl text-left text-sm">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th className="px-4 py-3">Servicio</th>
                                        <th className="px-4 py-3">Estado</th>
                                        <th className="px-4 py-3">
                                            Procedimientos
                                        </th>
                                        <th className="px-4 py-3 text-right">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {services.data.map((service) => (
                                        <tr
                                            key={service.id}
                                            className="border-t"
                                        >
                                            <td className="px-4 py-3 font-medium">
                                                <Link
                                                    href={proceduresIndex(
                                                        service.id,
                                                    )}
                                                    className="underline-offset-4 hover:underline"
                                                >
                                                    {service.name}
                                                </Link>
                                            </td>
                                            <td className="px-4 py-3">
                                                <CatalogStatusForm
                                                    form={ServiceStatusController.update.form(
                                                        service.id,
                                                    )}
                                                    isActive={
                                                        service.is_active ??
                                                        false
                                                    }
                                                    subject={`el servicio ${service.name}`}
                                                />
                                            </td>
                                            <td className="px-4 py-3">
                                                <div className="flex items-center gap-2">
                                                    <span>
                                                        {service.procedures_count ??
                                                            0}
                                                    </span>
                                                    <CatalogIconLink
                                                        href={proceduresIndex(
                                                            service.id,
                                                        )}
                                                        label={`Ver procedimientos de ${service.name}`}
                                                        icon={Eye}
                                                    />
                                                </div>
                                            </td>
                                            <td className="px-4 py-3 text-right">
                                                <CatalogIconLink
                                                    href={edit(service.id)}
                                                    label={`Editar servicio ${service.name}`}
                                                    icon={Pencil}
                                                />
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                        {services.last_page > 1 && (
                            <nav
                                className="flex flex-wrap justify-center gap-1"
                                aria-label="Paginación de servicios"
                            >
                                {services.links.map((link, position) => (
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
                                                        services.links.length -
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

AdminServicesIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Servicios clínicos', href: index() },
    ],
};
