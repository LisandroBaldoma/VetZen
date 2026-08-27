import { Form, Head } from '@inertiajs/react';
import PetController from '@/actions/App/Http/Controllers/Admin/PetController';
import Heading from '@/components/heading';
import PetFormFields from '@/components/pet-form-fields';

type ClientOption = { id: number; user: { name: string; email: string } };

export default function AdminPetCreate({
    clients,
}: {
    clients: ClientOption[];
}) {
    return (
        <>
            <Head title="Add pet" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title="Add pet"
                    description="Register a pet for a client."
                />
                <Form
                    {...PetController.store.form()}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <PetFormFields
                            clients={clients}
                            errors={errors}
                            processing={processing}
                            submitLabel="Create pet"
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
