import { Form, Head, setLayoutProps } from '@inertiajs/react';
import TreatmentController from '@/actions/App/Http/Controllers/Admin/TreatmentController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import {
    edit,
    index as serviceTemplatesIndex,
} from '@/routes/admin/services/treatments';
import { index as templatesIndex } from '@/routes/admin/treatments';

type Service = { id: number; name: string };
type Procedure = { id: number; name: string; is_active: boolean };
type Treatment = {
    id: number;
    name: string;
    description: string;
    estimated_sessions: number;
    is_active: boolean;
    procedures?: Pick<Procedure, 'id'>[];
};

export default function TreatmentEdit({
    service,
    treatment,
    procedures,
}: {
    service: Service;
    treatment: Treatment;
    procedures: Procedure[];
}) {
    const selected = new Set(
        treatment.procedures?.map((procedure) => procedure.id),
    );
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Plantillas', href: templatesIndex() },
            { title: service.name, href: serviceTemplatesIndex(service.id) },
            { title: treatment.name, href: edit([service.id, treatment.id]) },
        ],
    });

    return (
        <>
            <Head title="Editar plantilla" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title={`Editar ${treatment.name}`}
                    description={`Servicio: ${service.name}. Los cambios no alteran asignaciones históricas.`}
                />
                <Form
                    {...TreatmentController.update.form([
                        service.id,
                        treatment.id,
                    ])}
                    className="space-y-5 rounded-xl border p-6"
                >
                    {({ errors, processing }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Nombre</Label>
                                <Input
                                    id="name"
                                    name="name"
                                    defaultValue={treatment.name}
                                    required
                                />
                                <InputError message={errors.name} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="description">Descripción</Label>
                                <textarea
                                    id="description"
                                    name="description"
                                    defaultValue={treatment.description}
                                    required
                                    maxLength={5000}
                                    className="min-h-28 rounded-md border p-3"
                                />
                                <InputError message={errors.description} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="estimated_sessions">
                                    Sesiones estimadas
                                </Label>
                                <Input
                                    id="estimated_sessions"
                                    name="estimated_sessions"
                                    type="number"
                                    min="1"
                                    defaultValue={treatment.estimated_sessions}
                                    required
                                />
                            </div>
                            <fieldset className="grid gap-2">
                                <legend className="font-medium">
                                    Procedimientos
                                </legend>
                                {procedures.map((procedure) => (
                                    <label
                                        key={procedure.id}
                                        className="flex gap-2"
                                    >
                                        <input
                                            type="checkbox"
                                            name="procedure_ids[]"
                                            value={procedure.id}
                                            defaultChecked={selected.has(
                                                procedure.id,
                                            )}
                                        />
                                        <span>
                                            {procedure.name}
                                            {!procedure.is_active &&
                                                selected.has(procedure.id) && (
                                                    <span className="ml-2 text-xs text-muted-foreground">
                                                        Inactivo, ya asociado
                                                    </span>
                                                )}
                                        </span>
                                    </label>
                                ))}
                                <InputError message={errors.procedure_ids} />
                            </fieldset>
                            <label className="flex gap-2">
                                <input
                                    type="hidden"
                                    name="is_active"
                                    value="0"
                                />
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    defaultChecked={treatment.is_active}
                                />
                                Activo
                            </label>
                            <Button disabled={processing}>
                                Guardar plantilla
                            </Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
