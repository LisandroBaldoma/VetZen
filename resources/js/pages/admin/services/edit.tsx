import { Form, Head, setLayoutProps } from '@inertiajs/react';
import AdminServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import Heading from '@/components/heading';
import ServiceFormFields from '@/components/service-form-fields';
import { dashboard } from '@/routes';
import { edit, index } from '@/routes/admin/services';

type Service = {
    id: number;
    name: string;
    description: string;
    is_active: boolean;
};

export default function AdminServiceEdit({ service }: { service: Service }) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Servicios clínicos', href: index() },
            { title: service.name, href: edit(service.id) },
        ],
    });

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
