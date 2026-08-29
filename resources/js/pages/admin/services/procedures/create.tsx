import { Form, Head, Link } from '@inertiajs/react';
import AdminProcedureController from '@/actions/App/Http/Controllers/Admin/ProcedureController';
import Heading from '@/components/heading';
import ProcedureFormFields from '@/components/procedure-form-fields';
import { Button } from '@/components/ui/button';
import { index } from '@/routes/admin/services/procedures';
import type { Service } from '@/types';

export default function AdminProcedureCreate({
    service,
}: {
    service: Service;
}) {
    return (
        <>
            <Head title={`Add procedure — ${service.name}`} />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title="Add procedure"
                    description={`Create a technique within ${service.name}.`}
                />
                <Form
                    {...AdminProcedureController.store.form(service.id)}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ProcedureFormFields
                            errors={errors}
                            processing={processing}
                        />
                    )}
                </Form>
                <Button variant="outline" asChild>
                    <Link href={index(service.id)}>Cancel</Link>
                </Button>
            </div>
        </>
    );
}
