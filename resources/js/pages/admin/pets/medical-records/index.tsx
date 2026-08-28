import { Head, Link } from '@inertiajs/react';
import ClinicalRecordSummary from '@/components/clinical-record-summary';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { create, show } from '@/routes/admin/pets/medical-records';
import type { ClinicalRecord, Pet } from '@/types';

export default function AdminMedicalRecordsIndex({
    pet,
    records,
}: {
    pet: Pet;
    records: ClinicalRecord[];
}) {
    return (
        <>
            <Head title={`${pet.name} · Medical records`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <Heading
                        title="Medical records"
                        description={`Complete clinical history for ${pet.name}.`}
                    />
                    <Button asChild>
                        <Link href={create.url(pet.id)}>Add record</Link>
                    </Button>
                </div>
                {records.length === 0 ? (
                    <div className="rounded-xl border border-dashed p-8 text-center text-sm text-muted-foreground">
                        No clinical records have been added yet.
                    </div>
                ) : (
                    <div className="grid gap-4">
                        {records.map((record) => (
                            <ClinicalRecordSummary
                                key={record.id}
                                record={record}
                                href={show.url([pet.id, record.id])}
                                showVisibility
                            />
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
