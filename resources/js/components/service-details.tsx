import type { Service } from '@/types';

export default function ServiceDetails({ service }: { service: Service }) {
    return (
        <div className="space-y-6 rounded-xl border p-6">
            <p className="text-sm leading-6 whitespace-pre-wrap">
                {service.description}
            </p>
            <p className="text-sm text-muted-foreground">
                Procedures and treatments are managed within this therapeutic
                area.
            </p>
        </div>
    );
}
