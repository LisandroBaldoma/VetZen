import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import ServiceDetails from '@/components/service-details';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { edit, index } from '@/routes/admin/services';
import { index as proceduresIndex } from '@/routes/admin/services/procedures';
import { index as treatmentsIndex } from '@/routes/admin/services/treatments';
import type { Service } from '@/types';

export default function AdminServiceShow({ service }: { service: Service }) {
    return (
        <>
            <Head title={service.name} />
            <div className="mx-auto max-w-4xl space-y-6 p-4 md:p-6">
                <section className="flex flex-col gap-5 rounded-xl border border-l-4 border-l-primary bg-card p-5 shadow-sm sm:p-6">
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div className="space-y-2">
                            <Heading
                                title={service.name}
                                description="Área terapéutica y procedimientos disponibles en el catálogo clínico."
                            />
                            <Badge
                                variant={
                                    service.is_active ? 'secondary' : 'outline'
                                }
                            >
                                {service.is_active ? 'Activo' : 'Inactivo'}
                            </Badge>
                        </div>
                        <Button variant="outline" asChild>
                            <Link href={index()}>Volver a servicios</Link>
                        </Button>
                    </div>
                    <div className="flex flex-wrap gap-2 border-t pt-5">
                        <Button variant="outline" asChild>
                            <Link href={proceduresIndex(service.id)}>
                                Procedimientos
                            </Link>
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href={treatmentsIndex(service.id)}>
                                Plantillas
                            </Link>
                        </Button>
                        <Button asChild>
                            <Link href={edit(service.id)}>Editar servicio</Link>
                        </Button>
                    </div>
                </section>
                <ServiceDetails service={service} />
            </div>
        </>
    );
}
