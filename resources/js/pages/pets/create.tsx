import { Form, Head } from '@inertiajs/react';
import PetController from '@/actions/App/Http/Controllers/Pet/PetController';
import Heading from '@/components/heading';
import PetFormFields from '@/components/pet-form-fields';

export default function PetCreate() {
    return (
        <>
            <Head title="Registrar mascota" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title="Registrar mascota"
                    description="Registrá la información general de tu mascota."
                />
                <Form
                    {...PetController.store.form()}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <PetFormFields
                            errors={errors}
                            processing={processing}
                            submitLabel="Registrar mascota"
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
