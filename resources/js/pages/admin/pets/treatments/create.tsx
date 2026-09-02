import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import PetTreatmentController from '@/actions/App/Http/Controllers/Admin/PetTreatmentController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import PetContextHeader from '@/components/pet-context-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/admin/pets';
import { create, index } from '@/routes/admin/pets/treatments';

type Pet = {
    id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birth_date: string | null;
    weight: string | null;
    color: string | null;
    notes: string | null;
    has_photo?: boolean;
    client?: { id: number; name?: string };
};

type Treatment = {
    id: number;
    name: string;
    estimated_sessions: number;
    service: { id: number; name: string };
};

export default function AssignTreatment({
    pet,
    treatments,
}: {
    pet: Pet;
    treatments: Treatment[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Pacientes', href: petsIndex() },
            { title: pet.name, href: petShow(pet.id) },
            { title: 'Tratamientos', href: index(pet.id) },
            { title: 'Iniciar tratamiento', href: create(pet.id) },
        ],
    });

    return (
        <>
            <Head title={`Iniciar tratamiento · ${pet.name}`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />
                <Heading
                    title="Iniciar tratamiento"
                    description={`Definí las condiciones acordadas para ${pet.name}.`}
                />
                <div className="rounded-xl border bg-muted/30 p-4 text-sm text-muted-foreground">
                    Esta es una asignación directa realizada por el profesional.
                    No requiere una solicitud previa. Al confirmar se congelan
                    el nombre, la descripción y los procedimientos de la
                    plantilla, y se crean las sesiones pendientes.
                </div>
                {treatments.length === 0 ? (
                    <div className="space-y-3 rounded-xl border p-6">
                        <p className="font-medium">
                            No hay plantillas disponibles.
                        </p>
                        <p className="text-sm text-muted-foreground">
                            Solo se muestran tratamientos activos, de servicios
                            activos y con procedimientos activos válidos.
                        </p>
                        <Button asChild variant="outline">
                            <Link href={index.url(pet.id)}>
                                Volver a tratamientos
                            </Link>
                        </Button>
                    </div>
                ) : (
                    <Form
                        {...PetTreatmentController.store.form(pet.id)}
                        className="grid gap-5 rounded-xl border p-5 sm:p-6"
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
                                            <option
                                                key={item.id}
                                                value={item.id}
                                            >
                                                {item.service?.name} —{' '}
                                                {item.name}
                                            </option>
                                        ))}
                                    </select>
                                    <InputError message={errors.treatment_id} />
                                    <p className="text-sm text-muted-foreground">
                                        Solo aparecen plantillas activas de
                                        servicios activos.
                                    </p>
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
                                    <InputError
                                        message={errors.planned_sessions}
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
                                    <InputError
                                        message={errors.default_session_price}
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
                                    <InputError message={errors.starts_on} />
                                </div>
                                <input
                                    type="hidden"
                                    name="currency"
                                    value="ARS"
                                />
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
                                        className="min-h-28 rounded-md border bg-transparent p-3"
                                    />
                                    <InputError message={errors.notes} />
                                </div>
                                <InputError message={errors.currency} />
                                <InputError message={errors.status} />
                                <div className="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                    <Button asChild variant="outline">
                                        <Link href={index.url(pet.id)}>
                                            Cancelar
                                        </Link>
                                    </Button>
                                    <Button disabled={processing}>
                                        {processing
                                            ? 'Iniciando...'
                                            : 'Iniciar tratamiento'}
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                )}
            </div>
        </>
    );
}
