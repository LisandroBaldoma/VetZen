import { Head, Link } from '@inertiajs/react';
import ServiceStatusController from '@/actions/App/Http/Controllers/Admin/ServiceStatusController';
import CatalogStatusForm from '@/components/catalog-status-form';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { create, edit, index } from '@/routes/admin/services';
import { index as proceduresIndex } from '@/routes/admin/services/procedures';
import type { Service } from '@/types';

export default function AdminServicesIndex({
    services,
}: {
    services: Service[];
}) {
    return (
        <>
            <Head title="Servicios" />
            <div className="space-y-6 p-4">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <Heading
                        title="Servicios"
                        description="Administrá las terapias y sus procedimientos disponibles."
                    />
                    <Button asChild>
                        <Link href={create()}>Crear servicio</Link>
                    </Button>
                </div>
                {services.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        Todavía no hay servicios. Creá el primero para comenzar.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border">
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
                                {services.map((service) => (
                                    <tr key={service.id} className="border-t">
                                        <td className="px-4 py-3 font-medium">
                                            {service.name}
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex flex-wrap items-center gap-2">
                                                <Badge
                                                    variant={
                                                        service.is_active
                                                            ? 'secondary'
                                                            : 'outline'
                                                    }
                                                >
                                                    {service.is_active
                                                        ? 'Activo'
                                                        : 'Inactivo'}
                                                </Badge>
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
                                            </div>
                                        </td>
                                        <td className="px-4 py-3">
                                            <Link
                                                href={proceduresIndex(
                                                    service.id,
                                                )}
                                                className="font-medium underline-offset-4 hover:underline"
                                            >
                                                {service.procedures_count ?? 0}
                                            </Link>
                                        </td>
                                        <td className="px-4 py-3 text-right">
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                asChild
                                            >
                                                <Link href={edit(service.id)}>
                                                    Editar
                                                </Link>
                                            </Button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>
        </>
    );
}

AdminServicesIndex.layout = {
    breadcrumbs: [{ title: 'Servicios', href: index() }],
};
