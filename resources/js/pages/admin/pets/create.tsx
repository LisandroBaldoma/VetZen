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
            <Head title="Registrar paciente" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title="Registrar paciente"
                    description="Registrá un paciente para un cliente."
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
                            submitLabel="Registrar paciente"
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
