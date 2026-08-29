import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import ServiceDetails from '@/components/service-details';
import type { Service } from '@/types';

export default function ServiceShow({ service }: { service: Service }) {
    return (
        <>
            <Head title={service.name} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <Heading
                    title={service.name}
                    description="General commercial information about this therapy."
                />
                <ServiceDetails service={service} />
            </div>
        </>
    );
}
