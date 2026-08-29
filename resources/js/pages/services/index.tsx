import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { show } from '@/routes/services';
import type { Service } from '@/types';

export default function ServicesIndex({ services }: { services: Service[] }) {
    return (
        <>
            <Head title="Services" />
            <div className="space-y-6 p-4">
                <Heading
                    title="Services"
                    description="Explore the complementary therapies currently offered by VetZen."
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
                                <p className="text-xs text-muted-foreground">
                                    {service.duration_minutes
                                        ? `Approximately ${service.duration_minutes} minutes`
                                        : 'Duration to be arranged'}
                                </p>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
