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
            <Head title="Tratamientos" />
            <div className="space-y-6 p-4">
                <div className="flex justify-between gap-4">
                    <Heading
                        title={`Tratamientos de ${service.name}`}
                        description="Plantillas reutilizables del catálogo."
                    />
                    <Button asChild>
                        <Link href={create(service.id)}>Crear tratamiento</Link>
                    </Button>
                </div>
                {treatments.length === 0 ? (
                    <p className="rounded-xl border p-6 text-muted-foreground">
                        No hay tratamientos.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border">
                        <table className="w-full text-left text-sm">
                            <thead className="bg-muted/50">
                                <tr>
                                    <th className="p-3">Tratamiento</th>
                                    <th className="p-3">Sesiones</th>
                                    <th className="p-3">Procedimientos</th>
                                    <th className="p-3">Estado</th>
                                    <th className="p-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {treatments.map((t) => (
                                    <tr key={t.id} className="border-t">
                                        <td className="p-3 font-medium">
                                            {t.name}
                                        </td>
                                        <td className="p-3">
                                            {t.estimated_sessions}
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
