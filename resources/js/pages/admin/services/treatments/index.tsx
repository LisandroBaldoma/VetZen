import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { create, edit } from '@/routes/admin/services/treatments';
import type { Service, Treatment } from '@/types';
export default function TreatmentsIndex({
    service,
    treatments,
}: {
    service: Service;
    treatments: Treatment[];
}) {
    return (
        <>
            <Head title="Plantillas" />
            <div className="mx-auto w-full max-w-6xl space-y-6 p-4 md:p-6">
                <div className="flex justify-between gap-4">
                    <Heading
                        title={`Plantillas de ${service.name}`}
                        description="Plantillas reutilizables del catálogo."
                    />
                    <Button asChild>
                        <Link href={create(service.id)}>Crear plantilla</Link>
                    </Button>
                </div>
                {treatments.length === 0 ? (
                    <p className="rounded-xl border p-6 text-muted-foreground">
                        No hay plantillas para este servicio.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border bg-card shadow-sm">
                        <table className="w-full text-left text-sm">
                            <thead className="bg-muted/60 text-xs font-medium tracking-wide text-muted-foreground uppercase">
                                <tr>
                                    <th className="p-3">Plantilla</th>
                                    <th className="p-3">Sesiones</th>
                                    <th className="p-3">Procedimientos</th>
                                    <th className="p-3">Estado</th>
                                    <th className="p-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {treatments.map((t) => (
                                    <tr
                                        key={t.id}
                                        className="border-t transition-colors hover:bg-muted/40"
                                    >
                                        <td className="p-3 font-medium">
                                            {t.name}
                                        </td>
                                        <td className="p-3">
                                            {t.estimated_sessions}
                                        </td>
                                        <td className="p-3">
                                            {t.procedures_count}
                                        </td>
                                        <td className="p-3">
                                            <Badge variant="outline">
                                                {t.is_active
                                                    ? 'Activo'
                                                    : 'Inactivo'}
                                            </Badge>
                                        </td>
                                        <td className="p-3">
                                            <Button
                                                asChild
                                                size="sm"
                                                variant="outline"
                                            >
                                                <Link
                                                    href={edit.url([
                                                        service.id,
                                                        t.id,
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
