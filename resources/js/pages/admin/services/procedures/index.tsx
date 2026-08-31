import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Pencil } from 'lucide-react';
import ProcedureStatusController from '@/actions/App/Http/Controllers/Admin/ProcedureStatusController';
import CatalogIconLink from '@/components/catalog-icon-link';
import CatalogStatusForm from '@/components/catalog-status-form';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { index as servicesIndex } from '@/routes/admin/services';
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
                    <div className="flex items-start gap-3">
                        <CatalogIconLink
                            href={servicesIndex()}
                            label="Volver a servicios"
                            icon={ArrowLeft}
                        />
                        <Heading
                            title={`Procedimientos de ${service.name}`}
                            description="Administrá las prácticas asociadas a este servicio."
                        />
                    </div>
                    <Button asChild>
                        <Link href={create(service.id)}>
                            Crear procedimiento
                        </Link>
                    </Button>
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
                                            <CatalogStatusForm
                                                form={ProcedureStatusController.update.form(
                                                    [service.id, procedure.id],
                                                )}
                                                isActive={procedure.is_active}
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
