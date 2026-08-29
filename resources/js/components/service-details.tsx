import { Badge } from '@/components/ui/badge';
import type { Service } from '@/types';

const modalityLabels = {
    clinic: 'At the clinic',
    online: 'Online',
    home_visit: 'Home visit',
};

export default function ServiceDetails({ service }: { service: Service }) {
    return (
        <div className="space-y-6 rounded-xl border p-6">
            <p className="text-sm leading-6 whitespace-pre-wrap">
                {service.description}
            </p>
            <dl className="grid gap-4 sm:grid-cols-2">
                <div>
                    <dt className="text-sm text-muted-foreground">
                        Approximate duration
                    </dt>
                    <dd className="font-medium">
                        {service.duration_minutes
                            ? `${service.duration_minutes} minutes`
                            : 'To be arranged'}
                    </dd>
                </div>
                <div>
                    <dt className="text-sm text-muted-foreground">
                        Indicative base price
                    </dt>
                    <dd className="font-medium">
                        {service.price
                            ? `${service.currency} ${service.price}`
                            : 'Ask for pricing'}
                    </dd>
                </div>
            </dl>
            <div className="flex flex-wrap gap-2">
                {service.modalities.length > 0 ? (
                    service.modalities.map((modality) => (
                        <Badge key={modality} variant="secondary">
                            {modalityLabels[modality]}
                        </Badge>
                    ))
                ) : (
                    <span className="text-sm text-muted-foreground">
                        Modalities to be arranged
                    </span>
                )}
            </div>
        </div>
    );
}
