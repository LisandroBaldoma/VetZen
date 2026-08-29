import { Form, Head } from '@inertiajs/react';
import AdminServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import Heading from '@/components/heading';
import ServiceFormFields from '@/components/service-form-fields';

export default function AdminServiceCreate() {
    return (
        <>
            <Head title="Add service" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title="Add service"
                    description="Create a therapy in the VetZen catalog."
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
