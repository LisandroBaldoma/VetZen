import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import ServiceDetails from '@/components/service-details';
import { Button } from '@/components/ui/button';
import { edit, index } from '@/routes/admin/services';
import { index as proceduresIndex } from '@/routes/admin/services/procedures';
import { index as treatmentsIndex } from '@/routes/admin/services/treatments';
import type { Service } from '@/types';

export default function AdminServiceShow({ service }: { service: Service }) {
    return (
        <>
            <Head title={service.name} />
            <div className="workspace-operational max-w-5xl">
                <section className="flex flex-col gap-5 border-y border-border py-6 sm:py-7">
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div className="space-y-2">
                            <Heading
                                title={service.name}
                                description="Área terapéutica y procedimientos disponibles en el catálogo clínico."
                            />
                            <span
                                className={`inline-flex items-center gap-2 rounded-sm px-2 py-1 text-xs font-semibold ${service.is_active ? 'bg-clinical text-clinical-foreground' : 'bg-muted text-muted-foreground'}`}
                            >
                                <span
                                    className="size-1.5 rounded-full bg-current"
                                    aria-hidden="true"
                                />
                                {service.is_active ? 'Activo' : 'Inactivo'}
                            </span>
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
