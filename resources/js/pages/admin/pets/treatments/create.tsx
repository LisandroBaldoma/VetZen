import { Form, Head } from '@inertiajs/react';
import PetTreatmentController from '@/actions/App/Http/Controllers/Admin/PetTreatmentController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Pet, Treatment } from '@/types';

export default function AssignTreatment({
    pet,
    treatments,
}: {
    pet: Pet;
    treatments: Treatment[];
}) {
    return (
        <>
            <Head title="Asignar tratamiento" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title={`Asignar tratamiento a ${pet.name}`}
                    description="Se crearán las sesiones pendientes y los snapshots clínicos."
                />
                <Form
                    {...PetTreatmentController.store.form(pet.id)}
                    className="grid gap-4 rounded-xl border p-6"
                >
                    {({ errors, processing }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="treatment_id">
                                    Tratamiento
                                </Label>
                                <select
                                    id="treatment_id"
                                    name="treatment_id"
                                    required
                                    className="h-9 rounded-md border bg-background px-3"
                                >
                                    <option value="">Seleccionar…</option>
                                    {treatments.map((item) => (
                                        <option key={item.id} value={item.id}>
                                            {item.service?.name} — {item.name}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={errors.treatment_id} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="planned_sessions">
                                    Sesiones planificadas
                                </Label>
                                <Input
                                    id="planned_sessions"
                                    name="planned_sessions"
                                    type="number"
                                    min="1"
                                    required
                                />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="default_session_price">
                                    Precio por sesión
                                </Label>
                                <Input
                                    id="default_session_price"
                                    name="default_session_price"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    required
                                />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="starts_on">
                                    Fecha de inicio
                                </Label>
                                <Input
                                    id="starts_on"
                                    name="starts_on"
                                    type="date"
                                    required
                                />
                            </div>
                            <input type="hidden" name="currency" value="ARS" />
                            <input
                                type="hidden"
                                name="status"
                                value="pending"
                            />
                            <div className="grid gap-2">
                                <Label htmlFor="notes">Notas</Label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    className="min-h-24 rounded-md border p-3"
                                />
                            </div>
                            <Button disabled={processing}>Asignar</Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
