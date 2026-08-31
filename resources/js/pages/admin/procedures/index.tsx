import { Head, Link } from '@inertiajs/react';
import ProcedureStatusController from '@/actions/App/Http/Controllers/Admin/ProcedureStatusController';
import CatalogStatusForm from '@/components/catalog-status-form';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { index } from '@/routes/admin/procedures';
import {
    edit,
    index as serviceProceduresIndex,
} from '@/routes/admin/services/procedures';
import type { Procedure } from '@/types';

export default function AdminProcedureCatalog({
    procedures,
}: {
    procedures: Procedure[];
}) {
    return (
        <>
            <Head title="Procedimientos" />
            <div className="space-y-6 p-4">
                <Heading
                    title="Procedimientos"
                    description="Consultá y administrá todos los procedimientos del catálogo."
                />
                {procedures.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        Todavía no hay procedimientos cargados.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border">
                        <table className="w-full min-w-3xl text-left text-sm">
                            <thead className="bg-muted/50 text-muted-foreground">
                                <tr>
                                    <th className="px-4 py-3">Procedimiento</th>
                                    <th className="px-4 py-3">Servicio</th>
                                    <th className="px-4 py-3">Duración</th>
                                    <th className="px-4 py-3">Estado</th>
                                    <th className="px-4 py-3 text-right">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {procedures.map((procedure) => {
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
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>
        </>
    );
}

AdminProcedureCatalog.layout = {
    breadcrumbs: [{ title: 'Procedimientos', href: index() }],
};
