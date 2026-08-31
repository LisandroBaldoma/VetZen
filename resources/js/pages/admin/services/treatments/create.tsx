import { Form, Head } from '@inertiajs/react';
import TreatmentController from '@/actions/App/Http/Controllers/Admin/TreatmentController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Procedure, Service } from '@/types';
export default function TreatmentCreate({
    service,
    procedures,
}: {
    service: Service;
    procedures: Procedure[];
}) {
    return (
        <>
            <Head title="Crear tratamiento" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title={`Crear tratamiento — ${service.name}`}
                    description="Definí la plantilla y sus procedimientos."
                />
                <Form
                    {...TreatmentController.store.form(service.id)}
                    className="space-y-5 rounded-xl border p-6"
                >
                    {({ errors, processing }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Nombre</Label>
                                <Input id="name" name="name" required />
                                <InputError message={errors.name} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="description">Descripción</Label>
                                <textarea
                                    id="description"
                                    name="description"
                                    required
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
                                    required
                                />
                                <InputError
                                    message={errors.estimated_sessions}
                                />
                            </div>
                            <fieldset className="grid gap-2">
                                <legend className="font-medium">
                                    Procedimientos
                                </legend>
                                {procedures.map((p) => (
                                    <label key={p.id} className="flex gap-2">
                                        <input
                                            type="checkbox"
                                            name="procedure_ids[]"
                                            value={p.id}
                                        />
                                        {p.name}
                                    </label>
                                ))}
                                <InputError message={errors.procedure_ids} />
                            </fieldset>
                            <input type="hidden" name="is_active" value="1" />
                            <Button disabled={processing}>
                                Guardar tratamiento
                            </Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
