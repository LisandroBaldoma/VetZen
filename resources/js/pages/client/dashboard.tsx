import { Form, Head } from '@inertiajs/react';
import ClientProfileController from '@/actions/App/Http/Controllers/Client/ClientProfileController';
import ClientProfileFields from '@/components/client-profile-fields';
import Heading from '@/components/heading';
import { dashboard } from '@/routes';
import type { Client } from '@/types';

export default function ClientDashboard({ client }: { client: Client }) {
    return (
        <>
            <Head title="My dashboard" />

            <div className="mx-auto w-full max-w-2xl space-y-6 p-4">
                <Heading
                    title="My details"
                    description="Review and update your personal and contact information"
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
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
