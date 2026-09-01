import { Head, Link } from '@inertiajs/react';
import PageHeader from '@/components/page-header';
import { dashboard } from '@/routes';
import { index, show } from '@/routes/services';
import type { Service } from '@/types';

export default function ServicesIndex({ services }: { services: Service[] }) {
    return (
        <>
            <Head title="Servicios disponibles" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Servicios disponibles"
                    description="Conocé las terapias complementarias que ofrece VetZen."
                />
                {services.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        There are no services available at the moment.
                    </p>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {services.map((service) => (
                            <Link
                                key={service.id}
                                href={show(service.id)}
                                className="space-y-3 rounded-xl border p-5 transition-colors hover:bg-muted/50"
                            >
                                <h2 className="font-medium">{service.name}</h2>
                                <p className="line-clamp-3 text-sm text-muted-foreground">
                                    {service.description}
                                </p>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

ServicesIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Servicios disponibles', href: index() },
    ],
};
