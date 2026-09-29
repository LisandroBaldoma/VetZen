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
            <div className="mx-auto w-full max-w-7xl space-y-6 p-4 md:p-6">
                <PageHeader
                    title="Clientes"
                    description="Consultá y administrá los datos de los responsables."
                />

                {clients.length === 0 ? (
                    <section className="rounded-xl border border-dashed bg-card p-6 text-center sm:p-10">
                        <h2 className="font-semibold">
                            Todavía no hay clientes
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Los responsables aparecerán aquí al registrarse en
                            VetZen.
                        </p>
                    </section>
                ) : (
                    <>
                        <div className="grid gap-3 md:hidden">
                            {clients.map((client) => (
                                <article
                                    key={client.id}
                                    className="space-y-4 rounded-xl border bg-card p-4 shadow-sm"
                                >
                                    <div className="min-w-0">
                                        <h2 className="font-semibold break-words">
                                            {client.user.name}
                                        </h2>
                                        <p className="mt-1 text-sm break-all text-muted-foreground">
                                            {client.user.email}
                                        </p>
                                    </div>
                                    <dl className="text-sm">
                                        <dt className="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                                            Teléfono
                                        </dt>
                                        <dd className="mt-1 font-medium">
                                            {client.phone}
                                        </dd>
                                    </dl>
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        className="w-full"
                                        asChild
                                    >
                                        <Link href={edit(client.id)}>
                                            Ver y editar datos
                                        </Link>
                                    </Button>
                                </article>
                            ))}
                        </div>

                        <div className="hidden overflow-x-auto rounded-xl border bg-card shadow-sm md:block">
                            <table className="w-full min-w-2xl text-left text-sm">
                                <thead className="bg-muted/60 text-xs font-medium tracking-wide text-muted-foreground uppercase">
                                    <tr>
                                        <th className="px-5 py-3 font-medium">
                                            Responsable
                                        </th>
                                        <th className="px-5 py-3 font-medium">
                                            Correo electrónico
                                        </th>
                                        <th className="px-5 py-3 font-medium">
                                            Teléfono
                                        </th>
                                        <th className="px-5 py-3 text-right">
                                            <span className="sr-only">
                                                Acciones
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {clients.map((client) => (
                                        <tr
                                            key={client.id}
                                            className="border-t transition-colors hover:bg-muted/40"
                                        >
                                            <td className="px-5 py-4 font-semibold">
                                                {client.user.name}
                                            </td>
                                            <td className="px-5 py-4 text-muted-foreground">
                                                {client.user.email}
                                            </td>
                                            <td className="px-5 py-4">
                                                {client.phone}
                                            </td>
                                            <td className="px-5 py-3 text-right">
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    asChild
                                                >
                                                    <Link
                                                        href={edit(client.id)}
                                                    >
                                                        Ver datos
                                                    </Link>
                                                </Button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </>
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
