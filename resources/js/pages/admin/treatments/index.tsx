import { Head, Link, router } from '@inertiajs/react';
import { Pencil, Search, X } from 'lucide-react';
import { useState } from 'react';
import type { FormEvent } from 'react';
import TreatmentStatusController from '@/actions/App/Http/Controllers/Admin/TreatmentStatusController';
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
import { index as proceduresIndex } from '@/routes/admin/procedures';
import { create as serviceCreate } from '@/routes/admin/services';
import { create, edit } from '@/routes/admin/services/treatments';
import { index } from '@/routes/admin/treatments';

type Service = { id: number; name: string; is_active?: boolean };
type Treatment = {
    id: number;
    service_id: number;
    name: string;
    estimated_sessions: number;
    is_active: boolean;
    procedures_count: number;
    service: Pick<Service, 'id' | 'name'>;
};
type PaginationLink = { url: string | null; label: string; active: boolean };
type PaginatedTreatments = {
    data: Treatment[];
    links: PaginationLink[];
    last_page: number;
    total: number;
};
type Filters = { search: string; service: string; status: string };

export default function TreatmentCatalog({
    services,
    creationServices,
    treatments,
    filters,
}: {
    services: Service[];
    creationServices: Pick<Service, 'id' | 'name'>[];
    treatments: PaginatedTreatments;
    filters: Filters;
}) {
    const [search, setSearch] = useState(filters.search);
    const hasFilters = Boolean(
        filters.search || filters.service || filters.status,
    );
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
            <Head title="Plantillas" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Plantillas"
                    description="Configuraciones reutilizables de procedimientos y sesiones estimadas."
                />

                <section className="rounded-xl border p-5">
                    <h2 className="font-semibold">Crear plantilla</h2>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Elegí un servicio activo que tenga procedimientos
                        activos.
                    </p>
                    <div className="mt-4 flex flex-wrap gap-2">
                        {creationServices.map((service) => (
                            <Button key={service.id} asChild variant="outline">
                                <Link href={create(service.id)}>
                                    {service.name}
                                </Link>
                            </Button>
                        ))}
                        {creationServices.length === 0 && (
                            <div className="flex flex-wrap items-center gap-3 text-sm text-muted-foreground">
                                <span>
                                    Para crear una plantilla necesitás un
                                    servicio activo con al menos un
                                    procedimiento activo.
                                </span>
                                <Button asChild size="sm" variant="outline">
                                    <Link
                                        href={
                                            services.some(
                                                (service) => service.is_active,
                                            )
                                                ? proceduresIndex()
                                                : serviceCreate()
                                        }
                                    >
                                        {services.some(
                                            (service) => service.is_active,
                                        )
                                            ? 'Administrar procedimientos'
                                            : 'Crear servicio'}
                                    </Link>
                                </Button>
                            </div>
                        )}
                    </div>
                </section>

                <div className="grid gap-3 rounded-xl border p-4 md:grid-cols-[minmax(14rem,1fr)_minmax(12rem,auto)_minmax(10rem,auto)_auto] md:items-end">
                    <form onSubmit={submitSearch} className="flex gap-2">
                        <Label htmlFor="template-search" className="sr-only">
                            Buscar plantilla
                        </Label>
                        <Input
                            id="template-search"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            placeholder="Buscar plantilla..."
                        />
                        <Button
                            type="submit"
                            size="icon"
                            aria-label="Buscar plantillas"
                        >
                            <Search aria-hidden="true" />
                        </Button>
                    </form>
                    <div className="grid gap-2">
                        <Label htmlFor="template-service">Servicio</Label>
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
                                id="template-service"
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
                        <Label htmlFor="template-status">Estado</Label>
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
                                id="template-status"
                                className="w-full md:w-44"
                            >
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">
                                    Todos los estados
                                </SelectItem>
                                <SelectItem value="active">Activas</SelectItem>
                                <SelectItem value="inactive">
                                    Inactivas
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

                {treatments.total === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        {hasFilters
                            ? 'No se encontraron plantillas con los filtros seleccionados.'
                            : 'Todavía no hay plantillas. Elegí un servicio para crear la primera.'}
                    </p>
                ) : (
                    <>
                        <div className="grid gap-3 md:hidden">
                            {treatments.data.map((treatment) => (
                                <article
                                    key={treatment.id}
                                    className="space-y-4 rounded-xl border p-4"
                                >
                                    <div className="flex items-start justify-between gap-3">
                                        <Link
                                            href={edit([
                                                treatment.service_id,
                                                treatment.id,
                                            ])}
                                            className="font-semibold underline-offset-4 hover:underline"
                                        >
                                            {treatment.name}
                                        </Link>
                                        <CatalogStatusForm
                                            form={TreatmentStatusController.update.form(
                                                [
                                                    treatment.service_id,
                                                    treatment.id,
                                                ],
                                            )}
                                            isActive={treatment.is_active}
                                            subject={`la plantilla ${treatment.name}`}
                                        />
                                    </div>
                                    <dl className="grid grid-cols-3 gap-3 text-sm">
                                        <div>
                                            <dt className="text-muted-foreground">
                                                Servicio
                                            </dt>
                                            <dd>{treatment.service.name}</dd>
                                        </div>
                                        <div>
                                            <dt className="text-muted-foreground">
                                                Sesiones
                                            </dt>
                                            <dd>
                                                {treatment.estimated_sessions}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt className="text-muted-foreground">
                                                Procedimientos
                                            </dt>
                                            <dd>
                                                {treatment.procedures_count}
                                            </dd>
                                        </div>
                                    </dl>
                                    <div className="flex justify-end">
                                        <CatalogIconLink
                                            href={edit([
                                                treatment.service_id,
                                                treatment.id,
                                            ])}
                                            label={`Editar plantilla ${treatment.name}`}
                                            icon={Pencil}
                                        />
                                    </div>
                                </article>
                            ))}
                        </div>
                        <div className="hidden overflow-x-auto rounded-xl border md:block">
                            <table className="w-full min-w-3xl text-left text-sm">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th className="p-3">Plantilla</th>
                                        <th className="p-3">Servicio</th>
                                        <th className="p-3">Sesiones</th>
                                        <th className="p-3">Procedimientos</th>
                                        <th className="p-3">Estado</th>
                                        <th className="p-3 text-right">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {treatments.data.map((treatment) => (
                                        <tr
                                            key={treatment.id}
                                            className="border-t"
                                        >
                                            <td className="p-3 font-medium">
                                                <Link
                                                    href={edit([
                                                        treatment.service_id,
                                                        treatment.id,
                                                    ])}
                                                    className="underline-offset-4 hover:underline"
                                                >
                                                    {treatment.name}
                                                </Link>
                                            </td>
                                            <td className="p-3">
                                                {treatment.service.name}
                                            </td>
                                            <td className="p-3">
                                                {treatment.estimated_sessions}
                                            </td>
                                            <td className="p-3">
                                                {treatment.procedures_count}
                                            </td>
                                            <td className="p-3">
                                                <CatalogStatusForm
                                                    form={TreatmentStatusController.update.form(
                                                        [
                                                            treatment.service_id,
                                                            treatment.id,
                                                        ],
                                                    )}
                                                    isActive={
                                                        treatment.is_active
                                                    }
                                                    subject={`la plantilla ${treatment.name}`}
                                                />
                                            </td>
                                            <td className="p-3 text-right">
                                                <CatalogIconLink
                                                    href={edit([
                                                        treatment.service_id,
                                                        treatment.id,
                                                    ])}
                                                    label={`Editar plantilla ${treatment.name}`}
                                                    icon={Pencil}
                                                />
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                        {treatments.last_page > 1 && (
                            <nav
                                className="flex flex-wrap justify-center gap-1"
                                aria-label="Paginación de plantillas"
                            >
                                {treatments.links.map((link, position) => (
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
                                                        treatments.links
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

TreatmentCatalog.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Plantillas', href: index() },
    ],
};
