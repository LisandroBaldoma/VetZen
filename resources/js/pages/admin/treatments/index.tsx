import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { create, edit } from '@/routes/admin/services/treatments';
import type { Service, Treatment } from '@/types';

export default function TreatmentCatalog({
    services,
    treatments,
}: {
    services: Pick<Service, 'id' | 'name'>[];
    treatments: Treatment[];
}) {
    return (
        <>
            <Head title="Tratamientos" />
            <div className="space-y-6 p-4">
                <Heading
                    title="Tratamientos"
                    description="Catálogo de planes reutilizables disponibles para asignar a las mascotas."
                />

                <section className="rounded-xl border p-5">
                    <h2 className="font-semibold">Crear tratamiento</h2>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Elegí el servicio al que pertenecerá el nuevo
                        tratamiento.
                    </p>
                    <div className="mt-4 flex flex-wrap gap-2">
                        {services.map((service) => (
                            <Button key={service.id} asChild variant="outline">
                                <Link href={create.url(service.id)}>
                                    {service.name}
                                </Link>
                            </Button>
                        ))}
                        {services.length === 0 && (
                            <p className="text-sm text-muted-foreground">
                                Primero debés crear y activar un servicio.
                            </p>
                        )}
                    </div>
                </section>

                {treatments.length === 0 ? (
                    <p className="rounded-xl border p-6 text-muted-foreground">
                        Todavía no hay tratamientos. Seleccioná un servicio para
                        crear el primero.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border">
                        <table className="w-full text-left text-sm">
                            <thead className="bg-muted/50">
                                <tr>
                                    <th className="p-3">Tratamiento</th>
                                    <th className="p-3">Servicio</th>
                                    <th className="p-3">Sesiones</th>
                                    <th className="p-3">Procedimientos</th>
                                    <th className="p-3">Estado</th>
                                    <th className="p-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {treatments.map((treatment) => (
                                    <tr key={treatment.id} className="border-t">
                                        <td className="p-3 font-medium">
                                            {treatment.name}
                                        </td>
                                        <td className="p-3">
                                            {treatment.service?.name}
                                        </td>
                                        <td className="p-3">
                                            {treatment.estimated_sessions}
                                        </td>
                                        <td className="p-3">
                                            {treatment.procedures_count}
                                        </td>
                                        <td className="p-3">
                                            <Badge variant="outline">
                                                {treatment.is_active
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
                                                        treatment.service_id,
                                                        treatment.id,
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
