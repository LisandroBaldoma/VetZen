import { Form, Head } from '@inertiajs/react';
import AdminServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import Heading from '@/components/heading';
import ServiceFormFields from '@/components/service-form-fields';

export default function AdminServiceCreate() {
    return (
        <>
            <Head title="Crear servicio" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title="Crear servicio"
                    description="Agregá una terapia al catálogo de VetZen."
                />
                <Form
                    {...AdminServiceController.store.form()}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ServiceFormFields
                            errors={errors}
                            processing={processing}
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
