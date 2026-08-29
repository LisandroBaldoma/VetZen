import { Form, Head, Link } from '@inertiajs/react';
import AdminProcedureController from '@/actions/App/Http/Controllers/Admin/ProcedureController';
import Heading from '@/components/heading';
import ProcedureFormFields from '@/components/procedure-form-fields';
import { Button } from '@/components/ui/button';
import { show } from '@/routes/admin/services/procedures';
import type { Procedure, Service } from '@/types';

export default function AdminProcedureEdit({
    service,
    procedure,
}: {
    service: Service;
    procedure: Procedure;
}) {
    return (
        <>
            <Head title={`Edit ${procedure.name}`} />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title={`Edit ${procedure.name}`}
                    description={`Update this procedure within ${service.name}.`}
                />
                <Form
                    {...AdminProcedureController.update.form([
                        service.id,
                        procedure.id,
                    ])}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ProcedureFormFields
                            procedure={procedure}
                            errors={errors}
                            processing={processing}
                        />
                    )}
                </Form>
                <Button variant="outline" asChild>
                    <Link href={show([service.id, procedure.id])}>Cancel</Link>
                </Button>
            </div>
        </>
    );
}
