import { Head, Link } from '@inertiajs/react';
import ProcedureStatusController from '@/actions/App/Http/Controllers/Admin/ProcedureStatusController';
import CatalogStatusForm from '@/components/catalog-status-form';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    edit as serviceEdit,
    index as servicesIndex,
} from '@/routes/admin/services';
import { create, edit } from '@/routes/admin/services/procedures';
import type { Procedure, Service } from '@/types';

export default function AdminProceduresIndex({
    service,
    procedures,
}: {
    service: Service;
    procedures: Procedure[];
}) {
    return (
        <>
            <Head title={`Procedimientos — ${service.name}`} />
            <div className="space-y-6 p-4">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <Heading
                        title={`Procedimientos de ${service.name}`}
                        description="Administrá las prácticas asociadas a este servicio."
                    />
                    <div className="flex flex-wrap gap-2">
                        <Button variant="outline" asChild>
                            <Link href={servicesIndex()}>
                                Volver a servicios
                            </Link>
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href={serviceEdit(service.id)}>
                                Editar servicio
                            </Link>
                        </Button>
                        <Button asChild>
                            <Link href={create(service.id)}>
                                Crear procedimiento
                            </Link>
                        </Button>
                    </div>
                </div>
                {procedures.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        Este servicio todavía no tiene procedimientos.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border">
                        <table className="w-full min-w-2xl text-left text-sm">
                            <thead className="bg-muted/50 text-muted-foreground">
                                <tr>
                                    <th className="px-4 py-3">Procedimiento</th>
                                    <th className="px-4 py-3">Duración</th>
                                    <th className="px-4 py-3">Estado</th>
                                    <th className="px-4 py-3 text-right">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {procedures.map((procedure) => (
                                    <tr key={procedure.id} className="border-t">
                                        <td className="px-4 py-3 font-medium">
                                            {procedure.name}
                                        </td>
                                        <td className="px-4 py-3 text-muted-foreground">
                                            {procedure.duration_minutes
                                                ? `${procedure.duration_minutes} min`
                                                : 'Sin especificar'}
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex flex-wrap items-center gap-2">
                                                <Badge
                                                    variant={
                                                        procedure.is_active
                                                            ? 'secondary'
                                                            : 'outline'
                                                    }
                                                >
                                                    {procedure.is_active
                                                        ? 'Activo'
                                                        : 'Inactivo'}
                                                </Badge>
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
                                            </div>
                                        </td>
                                        <td className="px-4 py-3 text-right">
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                asChild
                                            >
                                                <Link
                                                    href={edit([
                                                        service.id,
                                                        procedure.id,
                                                    ])}
                                                >
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

AdminProceduresIndex.layout = {
    breadcrumbs: [
        { title: 'Servicios', href: servicesIndex() },
        { title: 'Procedimientos', href: '#' },
    ],
};
