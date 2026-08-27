import { Form, Head } from '@inertiajs/react';
import PetController from '@/actions/App/Http/Controllers/Pet/PetController';
import Heading from '@/components/heading';
import PetFormFields from '@/components/pet-form-fields';

export default function PetCreate() {
    return (
        <>
            <Head title="Add pet" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title="Add pet"
                    description="Register your pet's general information."
                />
                <Form
                    {...PetController.store.form()}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <PetFormFields
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
