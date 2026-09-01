import { Form, Head } from '@inertiajs/react';
import ClientProfileController from '@/actions/App/Http/Controllers/Client/ClientProfileController';
import ClientProfileFields from '@/components/client-profile-fields';
import PageHeader from '@/components/page-header';
import { dashboard } from '@/routes';
import type { Client } from '@/types';

export default function ClientDashboard({ client }: { client: Client }) {
    return (
        <>
            <Head title="Inicio" />

            <div className="mx-auto w-full max-w-2xl space-y-6 p-4">
                <PageHeader
                    title="Inicio"
                    description="Consultá y actualizá tu información personal y de contacto."
                />

                <Form
                    {...ClientProfileController.update.form(client.id)}
                    options={{ preserveScroll: true }}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ClientProfileFields
                            client={client}
                            errors={errors}
                            processing={processing}
                            submitLabel="Save my details"
                        />
                    )}
                </Form>
            </div>
        </>
    );
}

ClientDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Inicio',
            href: dashboard(),
        },
    ],
};
