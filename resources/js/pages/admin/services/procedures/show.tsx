import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { edit, index } from '@/routes/admin/services/procedures';
import type { Procedure, Service } from '@/types';

export default function AdminProcedureShow({
    service,
    procedure,
}: {
    service: Service;
    procedure: Procedure;
}) {
    return (
        <>
            <Head title={procedure.name} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <div className="space-y-2">
                        <Heading
                            title={procedure.name}
                            description={`Procedure within ${service.name}.`}
                        />
                        <Badge
                            variant={
                                procedure.is_active ? 'secondary' : 'outline'
                            }
                        >
                            {procedure.is_active ? 'Active' : 'Inactive'}
                        </Badge>
                    </div>
                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={index(service.id)}>
                                Back to procedures
                            </Link>
                        </Button>
                        <Button asChild>
                            <Link href={edit([service.id, procedure.id])}>
                                Edit
                            </Link>
                        </Button>
                    </div>
                </div>
                <div className="space-y-4 rounded-xl border p-6 text-sm">
                    <p className="leading-6 whitespace-pre-wrap">
                        {procedure.description || 'No description provided.'}
                    </p>
                    <p className="text-muted-foreground">
                        Suggested duration:{' '}
                        {procedure.duration_minutes
                            ? `${procedure.duration_minutes} minutes`
                            : 'not specified'}
                    </p>
                </div>
            </div>
        </>
    );
}
