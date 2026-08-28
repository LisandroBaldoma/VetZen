import { Form, Head } from '@inertiajs/react';
import ClinicalRecordController from '@/actions/App/Http/Controllers/Admin/ClinicalRecordController';
import ClinicalRecordFormFields from '@/components/clinical-record-form-fields';
import Heading from '@/components/heading';
import type { ClinicalRecord, Pet } from '@/types';

export default function AdminMedicalRecordEdit({
    pet,
    record,
    types,
}: {
    pet: Pet;
    record: ClinicalRecord;
    types: string[];
}) {
    return (
        <>
            <Head title={`Edit ${record.title}`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <Heading
                    title="Edit clinical record"
                    description={`Update the clinical event for ${pet.name}.`}
                />
                <Form
                    {...ClinicalRecordController.update.form([
                        pet.id,
                        record.id,
                    ])}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ClinicalRecordFormFields
                            record={record}
                            types={types}
                            errors={errors}
                            processing={processing}
                            submitLabel="Save changes"
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
