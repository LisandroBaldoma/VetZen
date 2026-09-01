import { Form, Head } from '@inertiajs/react';
import ClientProfileController from '@/actions/App/Http/Controllers/Client/ClientProfileController';
import ClientProfileFields from '@/components/client-profile-fields';
import Heading from '@/components/heading';
import { dashboard } from '@/routes';
import { edit as editProfile } from '@/routes/profile';
import type { Client } from '@/types';

type Props = {
    client: Client;
};

export default function ClientProfile({ client }: Props) {
    return (
        <>
            <Head title="Datos personales" />

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Datos personales"
                    description="Administrá tu información personal y de contacto."
                />

                <Form
                    {...ClientProfileController.update.form(client.id)}
                    options={{ preserveScroll: true }}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <ClientProfileFields
                            client={client}
                            errors={errors}
                            processing={processing}
                        />
                    )}
                </Form>
            </div>
        </>
    );
}

ClientProfile.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Cuenta', href: editProfile() },
    ],
};
