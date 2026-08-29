import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import ServiceDetails from '@/components/service-details';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { edit } from '@/routes/admin/services';
import { index as proceduresIndex } from '@/routes/admin/services/procedures';
import type { Service } from '@/types';

export default function AdminServiceShow({ service }: { service: Service }) {
    return (
        <>
            <Head title={service.name} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <div className="space-y-2">
                        <Heading
                            title={service.name}
                            description="Service catalog details."
                        />
                        <Badge
                            variant={
                                service.is_active ? 'secondary' : 'outline'
                            }
                        >
                            {service.is_active ? 'Active' : 'Inactive'}
                        </Badge>
                    </div>
                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={proceduresIndex(service.id)}>
                                Procedures
                            </Link>
                        </Button>
                        <Button asChild>
                            <Link href={edit(service.id)}>Edit</Link>
                        </Button>
                    </div>
                </div>
                <ServiceDetails service={service} />
            </div>
        </>
    );
}
