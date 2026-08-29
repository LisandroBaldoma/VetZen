import { Form, Head, Link } from '@inertiajs/react';
import AdminServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { create, show } from '@/routes/admin/services';
import type { Service } from '@/types';

export default function AdminServicesIndex({
    services,
}: {
    services: Service[];
}) {
    return (
        <>
            <Head title="Services" />
            <div className="space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        title="Services"
                        description="Manage the therapies offered in the client catalog."
                    />
                    <Button asChild>
                        <Link href={create()}>Add service</Link>
                    </Button>
                </div>
                {services.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        There are no services yet.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border">
                        <table className="w-full text-left text-sm">
                            <thead className="bg-muted/50 text-muted-foreground">
                                <tr>
                                    <th className="px-4 py-3">Service</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3" />
                                </tr>
                            </thead>
                            <tbody>
                                {services.map((service) => (
                                    <tr key={service.id} className="border-t">
                                        <td className="px-4 py-3 font-medium">
                                            {service.name}
                                        </td>
                                        <td className="px-4 py-3">
                                            <Badge
                                                variant={
                                                    service.is_active
                                                        ? 'secondary'
                                                        : 'outline'
                                                }
                                            >
                                                {service.is_active
                                                    ? 'Active'
                                                    : 'Inactive'}
                                            </Badge>
                                        </td>
                                        <td className="flex justify-end gap-2 px-4 py-3">
                                            <Form
                                                {...AdminServiceController.update.form(
                                                    service.id,
                                                )}
                                            >
                                                <input
                                                    type="hidden"
                                                    name="name"
                                                    value={service.name}
                                                />
                                                <input
                                                    type="hidden"
                                                    name="description"
                                                    value={service.description}
                                                />
                                                <input
                                                    type="hidden"
                                                    name="is_active"
                                                    value={
                                                        service.is_active
                                                            ? '0'
                                                            : '1'
                                                    }
                                                />
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                >
                                                    {service.is_active
                                                        ? 'Deactivate'
                                                        : 'Activate'}
                                                </Button>
                                            </Form>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                asChild
                                            >
                                                <Link href={show(service.id)}>
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
