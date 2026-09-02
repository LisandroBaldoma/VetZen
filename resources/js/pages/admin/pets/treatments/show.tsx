import { Form, Head, setLayoutProps } from '@inertiajs/react';
import PetTreatmentController from '@/actions/App/Http/Controllers/Admin/PetTreatmentController';
import TreatmentSessionController from '@/actions/App/Http/Controllers/Admin/TreatmentSessionController';
import Heading from '@/components/heading';
import PetContextHeader from '@/components/pet-context-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/admin/pets';
import { index, show } from '@/routes/admin/pets/treatments';
import type { Pet, PetTreatment } from '@/types';

export default function TreatmentShow({
    pet,
    petTreatment,
}: {
    pet: Pet;
    petTreatment: PetTreatment;
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
            {
                title: petTreatment.treatment_name,
                href: show([pet.id, petTreatment.id]),
            },
        ],
    });

    return (
        <>
            <Head title={petTreatment.treatment_name} />
            <div className="mx-auto max-w-4xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <Heading
                    title={petTreatment.treatment_name}
                    description={`${pet.name} · ${petTreatment.status}`}
                />
                <section className="rounded-xl border p-5">
                    <h2 className="font-semibold">Procedimientos asignados</h2>
                    <ul className="mt-2 list-inside list-disc">
                        {petTreatment.procedure_snapshots?.map((item) => (
                            <li key={item.id}>{item.procedure_name}</li>
                        ))}
                    </ul>
                </section>
                <Form
                    {...PetTreatmentController.update.form([
                        pet.id,
                        petTreatment.id,
                    ])}
                    className="grid gap-3 rounded-xl border p-5 md:grid-cols-3"
                >
                    {({ processing }) => (
                        <>
                            <Input
                                name="planned_sessions"
                                type="number"
                                min="1"
                                defaultValue={petTreatment.planned_sessions}
                            />
                            <Input
                                name="default_session_price"
                                type="number"
                                min="0"
                                step="0.01"
                                defaultValue={
                                    petTreatment.default_session_price
                                }
                            />
                            <input
                                type="hidden"
                                name="currency"
                                value={petTreatment.currency}
                            />
                            <Input
                                name="notes"
                                defaultValue={petTreatment.notes ?? ''}
                                placeholder="Notas"
                            />
                            <Button disabled={processing}>
                                Actualizar condiciones
                            </Button>
                        </>
                    )}
                </Form>
                {petTreatment.status !== 'cancelled' && (
                    <div className="flex flex-wrap gap-2">
                        <Form
                            {...PetTreatmentController.updateStatus.form([
                                pet.id,
                                petTreatment.id,
                            ])}
                        >
                            <input
                                type="hidden"
                                name="status"
                                value={
                                    petTreatment.status === 'suspended'
                                        ? 'resume'
                                        : 'suspended'
                                }
                            />
                            <Button variant="outline">
                                {petTreatment.status === 'suspended'
                                    ? 'Reanudar'
                                    : 'Suspender'}
                            </Button>
                        </Form>
                        <Form
                            {...PetTreatmentController.updateStatus.form([
                                pet.id,
                                petTreatment.id,
                            ])}
                        >
                            <input
                                type="hidden"
                                name="status"
                                value="cancelled"
                            />
                            <Button variant="destructive">
                                Cancelar tratamiento
                            </Button>
                        </Form>
                    </div>
                )}
                <section className="space-y-3">
                    <h2 className="text-lg font-semibold">Sesiones</h2>
                    {petTreatment.sessions?.map((session) => (
                        <Form
                            key={session.id}
                            {...TreatmentSessionController.update.form(
                                session.id,
                            )}
                            className="grid gap-3 rounded-xl border p-4 md:grid-cols-5"
                        >
                            {({ processing }) => (
                                <>
                                    <div className="font-medium">
                                        #{session.session_number}
                                    </div>
                                    <Input
                                        name="scheduled_at"
                                        type="date"
                                        defaultValue={
                                            session.scheduled_at?.slice(
                                                0,
                                                10,
                                            ) ?? ''
                                        }
                                    />
                                    <Input
                                        name="price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        defaultValue={session.price}
                                    />
                                    <input
                                        type="hidden"
                                        name="currency"
                                        value={session.currency}
                                    />
                                    <select
                                        name="status"
                                        defaultValue={session.status}
                                        className="h-9 rounded-md border bg-background px-2"
                                    >
                                        <option value="pending">
                                            Pendiente
                                        </option>
                                        <option value="completed">
                                            Completada
                                        </option>
                                        <option value="cancelled">
                                            Cancelada
                                        </option>
                                    </select>
                                    <Button size="sm" disabled={processing}>
                                        Guardar
                                    </Button>
                                </>
                            )}
                        </Form>
                    ))}
                </section>
            </div>
        </>
    );
}
