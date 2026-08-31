import { Form, Head } from '@inertiajs/react';
import AdminServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import Heading from '@/components/heading';
import ServiceFormFields from '@/components/service-form-fields';
import type { Service } from '@/types';

export default function AdminServiceEdit({ service }: { service: Service }) {
    return (
        <>
            <Head title={`Editar ${service.name}`} />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title={`Editar ${service.name}`}
                    description="Actualizá la información comercial del servicio."
                />
                <Form
                    {...AdminServiceController.update.form(service.id)}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ServiceFormFields
                            service={service}
                            errors={errors}
                            processing={processing}
                        />
                    )}
                </Form>
            </div>
        </>
    );
}
