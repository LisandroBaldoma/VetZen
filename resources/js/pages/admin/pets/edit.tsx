import { Form, Head } from '@inertiajs/react';
import PetController from '@/actions/App/Http/Controllers/Admin/PetController';
import ClientPetController from '@/actions/App/Http/Controllers/Pet/PetController';
import Heading from '@/components/heading';
import PetFormFields from '@/components/pet-form-fields';
import type { Pet } from '@/types';

export default function AdminPetEdit({ pet }: { pet: Pet }) {
    return (
        <>
            <Head title={`Editar ${pet.name}`} />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title={`Editar ${pet.name}`}
                    description="Actualizá la información del paciente."
                />
                <Form
                    {...PetController.update.form(pet.id)}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <PetFormFields
                            pet={pet}
                            errors={errors}
                            processing={processing}
                            submitLabel="Guardar paciente"
                        />
                    )}
                </Form>
                {pet.photo && (
                    <Form {...ClientPetController.destroyPhoto.form(pet.id)}>
                        <button
                            type="submit"
                            className="text-sm text-destructive underline"
                        >
                            Eliminar foto
                        </button>
                    </Form>
                )}
            </div>
        </>
    );
}
