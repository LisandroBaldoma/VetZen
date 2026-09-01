import { Head, Link } from '@inertiajs/react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index } from '@/routes/admin/clients';
import type { Client, User } from '@/types';

type ClientListItem = Client & { user: Pick<User, 'name' | 'email'> };

export default function AdminClientsIndex({
    clients,
}: {
    clients: ClientListItem[];
}) {
    return (
        <>
            <Head title="Clientes" />
            <div className="space-y-6 p-4">
                <PageHeader
                    title="Clientes"
                    description="Consultá y administrá los datos de los responsables."
                />

                {clients.length === 0 ? (
                    <p className="rounded-xl border p-6 text-sm text-muted-foreground">
                        There are no clients yet.
                    </p>
                ) : (
                    <div className="overflow-hidden rounded-xl border">
                        <table className="w-full text-left text-sm">
                            <thead className="bg-muted/50 text-muted-foreground">
                                <tr>
                                    <th className="px-4 py-3 font-medium">
                                        Name
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Email
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Phone
                                    </th>
                                    <th className="px-4 py-3" />
                                </tr>
                            </thead>
                            <tbody>
                                {clients.map((client) => (
                                    <tr key={client.id} className="border-t">
                                        <td className="px-4 py-3">
                                            {client.user.name}
                                        </td>
                                        <td className="px-4 py-3">
                                            {client.user.email}
                                        </td>
                                        <td className="px-4 py-3">
                                            {client.phone}
                                        </td>
                                        <td className="px-4 py-3 text-right">
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                asChild
                                            >
                                                <Link href={edit(client.id)}>
                                                    View details
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

AdminClientsIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Clientes', href: index() },
    ],
};
