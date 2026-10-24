import { Head, Link, router, setLayoutProps } from '@inertiajs/react';
import { Search, X } from 'lucide-react';
import { useState } from 'react';
import type { FormEvent } from 'react';
import PageHeader from '@/components/page-header';
import PetTreatmentCard from '@/components/pet-treatment-card';
import type { PetTreatmentSummary } from '@/components/pet-treatment-card';
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
import { index as adminIndex } from '@/routes/admin/pet-treatments';
import { show as adminShow } from '@/routes/admin/pets/treatments';
import { index as clientIndex } from '@/routes/my-treatments';
import { show as clientShow } from '@/routes/pets/treatments';

type Filters = { search: string; service: string; pet: string; status: string };
type Treatment = PetTreatmentSummary & {
    pet: { id: number; name: string; client_name?: string };
};
type PaginatedTreatments = {
    data: Treatment[];
    total: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const statuses = [
    ['pending', 'Pendientes'],
    ['in_progress', 'En curso'],
    ['suspended', 'Suspendidos'],
    ['completed', 'Completados'],
    ['cancelled', 'Cancelados'],
] as const;

export default function TreatmentOverview({
    isAdmin,
    treatments,
    services,
    pets,
    filters,
}: {
    isAdmin: boolean;
    treatments: PaginatedTreatments;
    services: { id: number; name: string }[];
    pets: { id: number; name: string }[];
    filters: Filters;
}) {
    const [search, setSearch] = useState(filters.search);
    const hasFilters = Boolean(
        filters.search || filters.service || filters.pet || filters.status,
    );
    const index = isAdmin ? adminIndex : clientIndex;
    const title = isAdmin ? 'Tratamientos de pacientes' : 'Mis tratamientos';

    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title, href: index() },
        ],
    });

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
            <Head title={title} />
            <div className="workspace-operational">
                <PageHeader
                    title={title}
                    description={
                        isAdmin
                            ? 'Consultá los tratamientos asignados, su paciente y su progreso.'
                            : 'Consultá los tratamientos asignados a tus mascotas y su progreso.'
                    }
                />

                <div className="grid gap-3 border-y border-border py-4 md:grid-cols-[minmax(14rem,1fr)_minmax(12rem,auto)_minmax(10rem,auto)_auto] md:items-end">
                    <form onSubmit={submitSearch} className="flex gap-2">
                        <Label htmlFor="treatment-search" className="sr-only">
                            Buscar tratamiento o mascota
                        </Label>
                        <Input
                            id="treatment-search"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            placeholder="Tratamiento o mascota..."
                        />
                        <Button
                            type="submit"
                            size="icon"
                            aria-label="Buscar tratamientos"
                        >
                            <Search aria-hidden="true" />
                        </Button>
                    </form>
                    <div className="grid gap-2">
                        <Label htmlFor="treatment-owner">
                            {isAdmin ? 'Servicio' : 'Mascota'}
                        </Label>
                        <Select
                            value={
                                (isAdmin ? filters.service : filters.pet) ||
                                'all'
                            }
                            onValueChange={(value) =>
                                visit({
                                    ...filters,
                                    search,
                                    [isAdmin ? 'service' : 'pet']:
                                        value === 'all' ? '' : value,
                                })
                            }
                        >
                            <SelectTrigger
                                id="treatment-owner"
                                className="w-full md:w-52"
                            >
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">
                                    {isAdmin
                                        ? 'Todos los servicios'
                                        : 'Todas las mascotas'}
                                </SelectItem>
                                {(isAdmin ? services : pets).map((item) => (
                                    <SelectItem
                                        key={item.id}
                                        value={String(item.id)}
                                    >
                                        {item.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="treatment-status">Estado</Label>
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
                                id="treatment-status"
                                className="w-full md:w-44"
                            >
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">
                                    Todos los estados
                                </SelectItem>
                                {statuses.map(([value, label]) => (
                                    <SelectItem key={value} value={value}>
                                        {label}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                    <Button variant="outline" asChild disabled={!hasFilters}>
                        <Link href={index()} aria-label="Limpiar filtros">
                            <X aria-hidden="true" /> Limpiar
                        </Link>
                    </Button>
                </div>

                {treatments.total === 0 ? (
                    <p className="border-y border-dashed py-8 text-sm text-muted-foreground">
                        {hasFilters
                            ? 'No se encontraron tratamientos con los filtros seleccionados.'
                            : 'Todavía no hay tratamientos asignados.'}
                    </p>
                ) : (
                    <div className="grid gap-4 lg:grid-cols-2">
                        {treatments.data.map((treatment) => (
                            <article key={treatment.id} className="space-y-2">
                                <p className="text-sm text-muted-foreground">
                                    {treatment.pet.name}
                                    {treatment.pet.client_name &&
                                        ` · ${treatment.pet.client_name}`}
                                </p>
                                <PetTreatmentCard
                                    treatment={treatment}
                                    href={(isAdmin
                                        ? adminShow
                                        : clientShow
                                    ).url([treatment.pet.id, treatment.id])}
                                />
                            </article>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
