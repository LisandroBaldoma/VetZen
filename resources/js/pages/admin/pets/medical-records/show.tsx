import { Head } from '@inertiajs/react';
import ClinicalRecordDetail from '@/components/clinical-record-detail';
import Heading from '@/components/heading';
import { edit } from '@/routes/admin/pets/medical-records';
import type { ClinicalRecord, Pet } from '@/types';

export default function AdminMedicalRecordShow({
    pet,
    record,
}: {
    pet: Pet;
    record: ClinicalRecord;
}) {
    return (
        <>
            <Head title={record.title} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <Heading
                    title="Medical record"
                    description={`Clinical history for ${pet.name}.`}
                />
                <ClinicalRecordDetail
                    record={record}
                    editHref={edit.url([pet.id, record.id])}
                />
            </div>
        </>
    );
}
