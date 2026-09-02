import { Form, Head, setLayoutProps } from '@inertiajs/react';
import ClinicalRecordManagementController from '@/actions/App/Http/Controllers/Admin/ClinicalRecordManagementController';
import ClinicalRecordFormFields from '@/components/clinical-record-form-fields';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { dashboard } from '@/routes';
import {
    edit as editPet,
    index as petsIndex,
    show as petShow,
} from '@/routes/admin/pets';
import {
    edit,
    index as medicalRecordsIndex,
    show,
} from '@/routes/admin/pets/medical-records';
import type { ClinicalRecordFormData, PetContext } from '@/types';

export default function AdminMedicalRecordEdit({
    pet,
    record,
    types,
}: {
    pet: PetContext;
    record: ClinicalRecordFormData;
    types: string[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            {
                title: 'Historia clínica',
                href: medicalRecordsIndex(pet.id),
            },
            { title: record.title, href: show([pet.id, record.id]) },
            { title: 'Editar', href: edit([pet.id, record.id]) },
        ],
    });

    return (
        <>
            <Head title={`Editar ${record.title}`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="medical-records"
                    editHref={editPet.url(pet.id)}
                />
                <Heading
                    title="Editar registro clínico"
                    description={`Actualizá la información clínica de ${pet.name}.`}
                />
                <Form
                    {...ClinicalRecordManagementController.update.form([
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
                            submitLabel="Guardar cambios"
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
