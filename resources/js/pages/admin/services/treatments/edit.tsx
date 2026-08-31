import { Form, Head } from '@inertiajs/react';
import TreatmentController from '@/actions/App/Http/Controllers/Admin/TreatmentController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Procedure, Service, Treatment } from '@/types';

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

    return (
        <>
            <Head title="Editar tratamiento" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title={`Editar tratamiento — ${service.name}`}
                    description="Los cambios no alteran asignaciones históricas."
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
                                        {procedure.name}
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
                                Guardar cambios
                            </Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
