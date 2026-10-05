import { Form, Head, setLayoutProps } from '@inertiajs/react';
import AdminServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import Heading from '@/components/heading';
import ServiceFormFields from '@/components/service-form-fields';
import { dashboard } from '@/routes';
import { create, index } from '@/routes/admin/services';

export default function AdminServiceCreate() {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Servicios clínicos', href: index() },
            { title: 'Crear servicio', href: create() },
        ],
    });

    return (
        <>
            <Head title="Crear servicio" />
            <div className="mx-auto max-w-2xl space-y-6 p-4 md:p-6">
                <Heading
                    title="Crear servicio"
                    description="Agregá una terapia al catálogo de VetZen."
                />
                <Form
                    {...AdminServiceController.store.form()}
                    className="space-y-6 rounded-xl border bg-card p-5 shadow-sm sm:p-6"
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
