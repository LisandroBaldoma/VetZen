import { Form, Head } from '@inertiajs/react';
import ClinicalRecordController from '@/actions/App/Http/Controllers/Admin/ClinicalRecordController';
import ClinicalRecordFormFields from '@/components/clinical-record-form-fields';
import Heading from '@/components/heading';
import type { Pet } from '@/types';

export default function AdminMedicalRecordCreate({
    pet,
    types,
}: {
    pet: Pet;
    types: string[];
}) {
    return (
        <>
            <Head title={`Add clinical record · ${pet.name}`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <Heading
                    title="Add clinical record"
                    description={`Register a clinical event for ${pet.name}.`}
                />
                <Form
                    {...ClinicalRecordController.store.form(pet.id)}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ClinicalRecordFormFields
                            types={types}
                            errors={errors}
                            processing={processing}
                            submitLabel="Create record"
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
