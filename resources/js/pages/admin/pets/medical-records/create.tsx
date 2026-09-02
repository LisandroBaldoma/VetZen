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
    create,
    index as medicalRecordsIndex,
} from '@/routes/admin/pets/medical-records';
import type { PetContext } from '@/types';

export default function AdminMedicalRecordCreate({
    pet,
    types,
    prefill,
}: {
    pet: PetContext;
    types: string[];
    prefill: { type: string | null };
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
            { title: 'Nuevo registro', href: create(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`Nuevo registro · ${pet.name}`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="medical-records"
                    editHref={editPet.url(pet.id)}
                />
                <Heading
                    title="Nuevo registro clínico"
                    description={`Registrá un evento clínico para ${pet.name}.`}
                />
                <Form
                    {...ClinicalRecordManagementController.store.form(pet.id)}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ClinicalRecordFormFields
                            types={types}
                            errors={errors}
                            processing={processing}
                            submitLabel="Crear registro"
                            defaultType={prefill.type}
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
