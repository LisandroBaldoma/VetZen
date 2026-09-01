import { Head, Link, router } from '@inertiajs/react';
import { Pencil, Search, X } from 'lucide-react';
import { useState } from 'react';
import type { FormEvent } from 'react';
import ProcedureStatusController from '@/actions/App/Http/Controllers/Admin/ProcedureStatusController';
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
import { index } from '@/routes/admin/procedures';
import {
    edit,
    index as serviceProceduresIndex,
} from '@/routes/admin/services/procedures';
import type { Paginated, Procedure, Service } from '@/types';

type Filters = { search: string; service: string; status: string };

export default function AdminProcedureCatalog({
    procedures,
    services,
    filters,
}: {
    procedures: Paginated<Procedure>;
    services: Pick<Service, 'id' | 'name'>[];
    filters: Filters;
}) {
    const [search, setSearch] = useState(filters.search);
    const hasFilters = Boolean(
        filters.search || filters.service || filters.status,
    );

    const visit = (next: Filters) => {
        const query = Object.fromEntries(
            Object.entries(next).filter(([, value]) => value !== ''),
        );

        router.get(index().url, query, { preserveState: true, replace: true });
    };

    const submitSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        visit({ ...filters, search });
    };

    return (
        <>
            <Head title="Procedimientos clínicos" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Procedimientos clínicos"
                    description="Consultá y administrá todos los procedimientos del catálogo."
                />

                <div className="grid gap-3 rounded-xl border p-4 md:grid-cols-[minmax(14rem,1fr)_minmax(12rem,auto)_minmax(10rem,auto)_auto] md:items-end">
                    <form onSubmit={submitSearch} className="flex gap-2">
                        <div className="grid flex-1 gap-2">
                            <Label
                                htmlFor="procedure-search"
                                className="sr-only"
                            >
                                Buscar procedimiento
                            </Label>
                            <Input
                                id="procedure-search"
                                value={search}
                                onChange={(event) =>
                                    setSearch(event.target.value)
                                }
                                placeholder="Buscar procedimiento..."
                            />
                        </div>
                        <Button
                            type="submit"
                            size="icon"
                            aria-label="Buscar procedimientos"
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
                                <SelectItem value="active">Activos</SelectItem>
                                <SelectItem value="inactive">
                                    Inactivos
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

                {procedures.total === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        {hasFilters
                            ? 'No se encontraron procedimientos con los filtros seleccionados.'
                            : 'Todavía no hay procedimientos cargados.'}
                    </p>
                ) : (
                    <>
                        <div className="overflow-x-auto rounded-xl border">
                            <table className="w-full min-w-3xl text-left text-sm">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th className="px-4 py-3">
                                            Procedimiento
                                        </th>
                                        <th className="px-4 py-3">Servicio</th>
                                        <th className="px-4 py-3">Duración</th>
                                        <th className="px-4 py-3">Estado</th>
                                        <th className="px-4 py-3 text-right">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {procedures.data.map((procedure) => {
                                        const service = procedure.service!;

                                        return (
                                            <tr
                                                key={procedure.id}
                                                className="border-t"
                                            >
                                                <td className="px-4 py-3 font-medium">
                                                    {procedure.name}
                                                </td>
                                                <td className="px-4 py-3">
                                                    <Link
                                                        href={serviceProceduresIndex(
                                                            service.id,
                                                        )}
                                                        className="font-medium underline-offset-4 hover:underline"
                                                    >
                                                        {service.name}
                                                    </Link>
                                                </td>
                                                <td className="px-4 py-3 text-muted-foreground">
                                                    {procedure.duration_minutes
                                                        ? `${procedure.duration_minutes} min`
                                                        : 'Sin especificar'}
                                                </td>
                                                <td className="px-4 py-3">
                                                    <CatalogStatusForm
                                                        form={ProcedureStatusController.update.form(
                                                            [
                                                                service.id,
                                                                procedure.id,
                                                            ],
                                                        )}
                                                        isActive={
                                                            procedure.is_active
                                                        }
                                                        subject={`el procedimiento ${procedure.name}`}
                                                    />
                                                </td>
                                                <td className="px-4 py-3 text-right">
                                                    <CatalogIconLink
                                                        href={edit([
                                                            service.id,
                                                            procedure.id,
                                                        ])}
                                                        label={`Editar procedimiento ${procedure.name}`}
                                                        icon={Pencil}
                                                    />
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>

                        {procedures.last_page > 1 && (
                            <nav
                                className="flex flex-wrap justify-center gap-1"
                                aria-label="Paginación de procedimientos"
                            >
                                {procedures.links.map((link, position) => (
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
                                                        procedures.links
                                                            .length -
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

AdminProcedureCatalog.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Procedimientos clínicos', href: index() },
    ],
};
