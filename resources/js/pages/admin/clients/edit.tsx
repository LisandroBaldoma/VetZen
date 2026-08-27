import { Form, Head } from '@inertiajs/react';
import ClientController from '@/actions/App/Http/Controllers/Admin/ClientController';
import ClientProfileFields from '@/components/client-profile-fields';
import Heading from '@/components/heading';
import type { Client, User } from '@/types';

type Props = {
    client: Client & { user: Pick<User, 'name' | 'email'> };
};

export default function AdminClientEdit({ client }: Props) {
    return (
        <>
            <Head title={`Client: ${client.user.name}`} />
            <div className="mx-auto w-full max-w-2xl space-y-6 p-4">
                <Heading
                    title={client.user.name}
                    description={client.user.email}
                />
                <Form
                    {...ClientController.update.form(client.id)}
                    options={{ preserveScroll: true }}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ClientProfileFields
                            client={client}
                            errors={errors}
                            processing={processing}
                            submitLabel="Save client details"
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
