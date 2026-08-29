import { Form, Head, Link } from '@inertiajs/react';
import AdminProcedureController from '@/actions/App/Http/Controllers/Admin/ProcedureController';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { show as serviceShow } from '@/routes/admin/services';
import { create, show } from '@/routes/admin/services/procedures';
import type { Procedure, Service } from '@/types';

export default function AdminProceduresIndex({
    service,
    procedures,
}: {
    service: Service;
    procedures: Procedure[];
}) {
    return (
        <>
            <Head title={`Procedures — ${service.name}`} />
            <div className="space-y-6 p-4">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <Heading
                        title="Procedures"
                        description={`Manage the techniques available for ${service.name}.`}
                    />
                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={serviceShow(service.id)}>
                                Back to service
                            </Link>
                        </Button>
                        <Button asChild>
                            <Link href={create(service.id)}>Add procedure</Link>
                        </Button>
                    </div>
                </div>
                {procedures.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        This service has no procedures yet.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border">
                        <table className="w-full text-left text-sm">
                            <thead className="bg-muted/50 text-muted-foreground">
                                <tr>
                                    <th className="px-4 py-3">Procedure</th>
                                    <th className="px-4 py-3">Duration</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3" />
                                </tr>
                            </thead>
                            <tbody>
                                {procedures.map((procedure) => (
                                    <tr key={procedure.id} className="border-t">
                                        <td className="px-4 py-3 font-medium">
                                            {procedure.name}
                                        </td>
                                        <td className="px-4 py-3 text-muted-foreground">
                                            {procedure.duration_minutes
                                                ? `${procedure.duration_minutes} min`
                                                : 'Not specified'}
                                        </td>
                                        <td className="px-4 py-3">
                                            <Badge
                                                variant={
                                                    procedure.is_active
                                                        ? 'secondary'
                                                        : 'outline'
                                                }
                                            >
                                                {procedure.is_active
                                                    ? 'Active'
                                                    : 'Inactive'}
                                            </Badge>
                                        </td>
                                        <td className="flex justify-end gap-2 px-4 py-3">
                                            <Form
                                                {...AdminProcedureController.update.form(
                                                    [service.id, procedure.id],
                                                )}
                                            >
                                                <input
                                                    type="hidden"
                                                    name="name"
                                                    value={procedure.name}
                                                />
                                                <input
                                                    type="hidden"
                                                    name="description"
                                                    value={
                                                        procedure.description ??
                                                        ''
                                                    }
                                                />
                                                <input
                                                    type="hidden"
                                                    name="duration_minutes"
                                                    value={
                                                        procedure.duration_minutes ??
                                                        ''
                                                    }
                                                />
                                                <input
                                                    type="hidden"
                                                    name="is_active"
                                                    value={
                                                        procedure.is_active
                                                            ? '0'
                                                            : '1'
                                                    }
                                                />
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                >
                                                    {procedure.is_active
                                                        ? 'Deactivate'
                                                        : 'Activate'}
                                                </Button>
                                            </Form>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                asChild
                                            >
                                                <Link
                                                    href={show([
                                                        service.id,
                                                        procedure.id,
                                                    ])}
                                                >
                                                    View
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
