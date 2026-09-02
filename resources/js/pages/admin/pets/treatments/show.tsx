import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import PetTreatmentController from '@/actions/App/Http/Controllers/Admin/PetTreatmentController';
import TreatmentSessionController from '@/actions/App/Http/Controllers/Admin/TreatmentSessionController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import PetContextHeader from '@/components/pet-context-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/admin/pets';
import { create as createClinicalRecord } from '@/routes/admin/pets/medical-records';
import { create, index, show } from '@/routes/admin/pets/treatments';

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

type Session = {
    id: number;
    session_number: number;
    scheduled_at: string | null;
    price: string;
    currency: string;
    status: 'pending' | 'completed' | 'cancelled';
    notes: string | null;
};

type PetTreatment = {
    id: number;
    treatment_name: string;
    treatment_description: string;
    planned_sessions: number;
    default_session_price: string;
    currency: string;
    starts_on: string;
    status: 'pending' | 'in_progress' | 'completed' | 'suspended' | 'cancelled';
    notes: string | null;
    procedure_snapshots: {
        id: number;
        procedure_name: string;
        procedure_description: string | null;
    }[];
    sessions: Session[];
};

const statusLabels: Record<string, string> = {
    pending: 'Pendiente',
    in_progress: 'En curso',
    completed: 'Completado',
    suspended: 'Suspendido',
    cancelled: 'Cancelado',
};

const sessionStatusLabels: Record<Session['status'], string> = {
    pending: 'Pendiente',
    completed: 'Completada',
    cancelled: 'Cancelada',
};

const dateFormatter = new Intl.DateTimeFormat('es-AR', { dateStyle: 'long' });
const dateTimeFormatter = new Intl.DateTimeFormat('es-AR', {
    dateStyle: 'medium',
    timeStyle: 'short',
});

function money(value: string, currency: string): string {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency,
    }).format(Number(value));
}

export default function TreatmentShow({
    pet,
    petTreatment,
}: {
    pet: Pet;
    petTreatment: PetTreatment;
}) {
    const completed = petTreatment.sessions.filter(
        (session) => session.status === 'completed',
    ).length;
    const canOperate = ['pending', 'in_progress'].includes(petTreatment.status);
    const isFinal = ['completed', 'cancelled'].includes(petTreatment.status);

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
            <div className="mx-auto max-w-5xl space-y-6 p-4">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />

                <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <Heading
                        title={petTreatment.treatment_name}
                        description={`Tratamiento asignado a ${pet.name}`}
                    />
                    <Badge variant="outline">
                        {statusLabels[petTreatment.status]}
                    </Badge>
                </div>

                <section className="grid gap-4 rounded-xl border p-5 sm:grid-cols-3">
                    <div>
                        <p className="text-sm text-muted-foreground">
                            Progreso
                        </p>
                        <p className="text-2xl font-semibold">
                            {completed} de {petTreatment.planned_sessions}
                        </p>
                    </div>
                    <div>
                        <p className="text-sm text-muted-foreground">Inicio</p>
                        <p className="font-medium">
                            {dateFormatter.format(
                                new Date(
                                    `${petTreatment.starts_on.slice(0, 10)}T00:00:00`,
                                ),
                            )}
                        </p>
                    </div>
                    <div>
                        <p className="text-sm text-muted-foreground">
                            Precio predeterminado
                        </p>
                        <p className="font-medium">
                            {money(
                                petTreatment.default_session_price,
                                petTreatment.currency,
                            )}
                        </p>
                    </div>
                    <div className="sm:col-span-3">
                        <div className="h-2 overflow-hidden rounded-full bg-muted">
                            <div
                                className="h-full rounded-full bg-primary"
                                style={{
                                    width: `${Math.min(100, (completed / petTreatment.planned_sessions) * 100)}%`,
                                }}
                            />
                        </div>
                    </div>
                </section>

                <section className="space-y-4 rounded-xl border p-5">
                    <div>
                        <h2 className="font-semibold">Condiciones acordadas</h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Estos datos y procedimientos son snapshots de la
                            asignación y no dependen de cambios posteriores del
                            catálogo.
                        </p>
                    </div>
                    <p className="whitespace-pre-wrap">
                        {petTreatment.treatment_description}
                    </p>
                    <div>
                        <h3 className="text-sm font-medium">Procedimientos</h3>
                        <ul className="mt-2 grid gap-2 sm:grid-cols-2">
                            {petTreatment.procedure_snapshots.map(
                                (procedure) => (
                                    <li
                                        key={procedure.id}
                                        className="rounded-lg bg-muted/50 p-3"
                                    >
                                        <p className="font-medium">
                                            {procedure.procedure_name}
                                        </p>
                                        {procedure.procedure_description && (
                                            <p className="mt-1 text-sm text-muted-foreground">
                                                {
                                                    procedure.procedure_description
                                                }
                                            </p>
                                        )}
                                    </li>
                                ),
                            )}
                        </ul>
                    </div>
                    <div>
                        <h3 className="text-sm font-medium">Notas generales</h3>
                        <p className="mt-1 text-sm whitespace-pre-wrap text-muted-foreground">
                            {petTreatment.notes || 'Sin notas.'}
                        </p>
                    </div>
                </section>

                {canOperate && (
                    <Form
                        {...PetTreatmentController.update.form([
                            pet.id,
                            petTreatment.id,
                        ])}
                        className="grid gap-4 rounded-xl border p-5 sm:grid-cols-2"
                    >
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="planned_sessions">
                                        Sesiones requeridas
                                    </Label>
                                    <Input
                                        id="planned_sessions"
                                        name="planned_sessions"
                                        type="number"
                                        min="1"
                                        defaultValue={
                                            petTreatment.planned_sessions
                                        }
                                        aria-invalid={Boolean(
                                            errors.planned_sessions,
                                        )}
                                    />
                                    <InputError
                                        message={errors.planned_sessions}
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="default_session_price">
                                        Nuevo precio predeterminado
                                    </Label>
                                    <Input
                                        id="default_session_price"
                                        name="default_session_price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        defaultValue={
                                            petTreatment.default_session_price
                                        }
                                        aria-invalid={Boolean(
                                            errors.default_session_price,
                                        )}
                                    />
                                    <InputError
                                        message={errors.default_session_price}
                                    />
                                </div>
                                <input
                                    type="hidden"
                                    name="currency"
                                    value={petTreatment.currency}
                                />
                                <div className="grid gap-2 sm:col-span-2">
                                    <Label htmlFor="treatment_notes">
                                        Notas
                                    </Label>
                                    <textarea
                                        id="treatment_notes"
                                        name="notes"
                                        defaultValue={petTreatment.notes ?? ''}
                                        className="min-h-24 rounded-md border bg-transparent p-3"
                                    />
                                    <InputError message={errors.notes} />
                                    <InputError message={errors.currency} />
                                </div>
                                <div className="sm:col-span-2">
                                    <p className="mb-3 text-sm text-muted-foreground">
                                        Las sesiones agregadas usarán el nuevo
                                        precio. Las sesiones existentes
                                        conservarán su precio histórico.
                                    </p>
                                    <Button disabled={processing}>
                                        {processing
                                            ? 'Actualizando...'
                                            : 'Actualizar condiciones'}
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                )}

                {!isFinal && (
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <Form
                            {...PetTreatmentController.updateStatus.form([
                                pet.id,
                                petTreatment.id,
                            ])}
                        >
                            {({ processing }) => (
                                <>
                                    <input
                                        type="hidden"
                                        name="status"
                                        value={
                                            petTreatment.status === 'suspended'
                                                ? 'resume'
                                                : 'suspended'
                                        }
                                    />
                                    <Button
                                        variant="outline"
                                        disabled={processing}
                                        className="w-full sm:w-auto"
                                    >
                                        {petTreatment.status === 'suspended'
                                            ? 'Reanudar tratamiento'
                                            : 'Suspender tratamiento'}
                                    </Button>
                                </>
                            )}
                        </Form>
                        <Form
                            {...PetTreatmentController.updateStatus.form([
                                pet.id,
                                petTreatment.id,
                            ])}
                            onBefore={() =>
                                window.confirm(
                                    '¿Confirmás la cancelación? El tratamiento no podrá reabrirse.',
                                )
                            }
                        >
                            {({ processing }) => (
                                <>
                                    <input
                                        type="hidden"
                                        name="status"
                                        value="cancelled"
                                    />
                                    <Button
                                        variant="destructive"
                                        disabled={processing}
                                        className="w-full sm:w-auto"
                                    >
                                        Cancelar tratamiento
                                    </Button>
                                </>
                            )}
                        </Form>
                    </div>
                )}

                {isFinal && (
                    <div className="rounded-xl border bg-muted/30 p-5">
                        <p className="font-medium">
                            Este tratamiento está cerrado y no admite cambios.
                        </p>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Si la atención continúa, iniciá un nuevo tratamiento
                            para conservar este historial sin modificaciones.
                        </p>
                        <Button asChild className="mt-4">
                            <Link href={create.url(pet.id)}>
                                Iniciar nuevo tratamiento
                            </Link>
                        </Button>
                    </div>
                )}

                <section className="space-y-4">
                    <div>
                        <h2 className="text-lg font-semibold">Sesiones</h2>
                        <p className="text-sm text-muted-foreground">
                            Una sesión cancelada queda en el historial. Mientras
                            el tratamiento esté activo, se crea automáticamente
                            un reemplazo pendiente para mantener la cantidad
                            requerida.
                        </p>
                    </div>
                    {petTreatment.sessions.map((session) => {
                        const sessionIsFinal = session.status !== 'pending';

                        return (
                            <article
                                key={session.id}
                                className="space-y-4 rounded-xl border p-4 sm:p-5"
                            >
                                <div className="flex items-center justify-between gap-3">
                                    <h3 className="font-semibold">
                                        Sesión #{session.session_number}
                                    </h3>
                                    <Badge variant="outline">
                                        {sessionStatusLabels[session.status]}
                                    </Badge>
                                </div>

                                {canOperate ? (
                                    <Form
                                        {...TreatmentSessionController.update.form(
                                            session.id,
                                        )}
                                        className="grid gap-4 sm:grid-cols-2"
                                    >
                                        {({ processing, errors }) => (
                                            <>
                                                <div className="grid gap-2">
                                                    <Label
                                                        htmlFor={`scheduled_at_${session.id}`}
                                                    >
                                                        Fecha y hora
                                                    </Label>
                                                    <Input
                                                        id={`scheduled_at_${session.id}`}
                                                        name="scheduled_at"
                                                        type="datetime-local"
                                                        defaultValue={
                                                            session.scheduled_at?.slice(
                                                                0,
                                                                16,
                                                            ) ?? ''
                                                        }
                                                        aria-invalid={Boolean(
                                                            errors.scheduled_at,
                                                        )}
                                                    />
                                                    <InputError
                                                        message={
                                                            errors.scheduled_at
                                                        }
                                                    />
                                                </div>
                                                <div className="grid gap-2">
                                                    <Label
                                                        htmlFor={`price_${session.id}`}
                                                    >
                                                        Precio de la sesión
                                                    </Label>
                                                    <Input
                                                        id={`price_${session.id}`}
                                                        name="price"
                                                        type="number"
                                                        min="0"
                                                        step="0.01"
                                                        defaultValue={
                                                            session.price
                                                        }
                                                        aria-invalid={Boolean(
                                                            errors.price,
                                                        )}
                                                    />
                                                    <InputError
                                                        message={errors.price}
                                                    />
                                                </div>
                                                <input
                                                    type="hidden"
                                                    name="currency"
                                                    value={session.currency}
                                                />
                                                <div className="grid gap-2">
                                                    <Label
                                                        htmlFor={`status_${session.id}`}
                                                    >
                                                        Estado
                                                    </Label>
                                                    {sessionIsFinal ? (
                                                        <>
                                                            <input
                                                                type="hidden"
                                                                name="status"
                                                                value={
                                                                    session.status
                                                                }
                                                            />
                                                            <p className="flex h-9 items-center rounded-md border bg-muted/40 px-3 text-sm">
                                                                {
                                                                    sessionStatusLabels[
                                                                        session
                                                                            .status
                                                                    ]
                                                                }{' '}
                                                                (final)
                                                            </p>
                                                        </>
                                                    ) : (
                                                        <select
                                                            id={`status_${session.id}`}
                                                            name="status"
                                                            defaultValue={
                                                                session.status
                                                            }
                                                            className="h-9 rounded-md border bg-background px-3"
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
                                                    )}
                                                    <InputError
                                                        message={errors.status}
                                                    />
                                                    <InputError
                                                        message={
                                                            errors.currency
                                                        }
                                                    />
                                                </div>
                                                <div className="grid gap-2 sm:col-span-2">
                                                    <Label
                                                        htmlFor={`notes_${session.id}`}
                                                    >
                                                        Notas de la sesión
                                                    </Label>
                                                    <textarea
                                                        id={`notes_${session.id}`}
                                                        name="notes"
                                                        defaultValue={
                                                            session.notes ?? ''
                                                        }
                                                        className="min-h-24 rounded-md border bg-transparent p-3"
                                                    />
                                                    <InputError
                                                        message={errors.notes}
                                                    />
                                                </div>
                                                <div className="sm:col-span-2">
                                                    {sessionIsFinal && (
                                                        <p className="mb-3 text-sm text-muted-foreground">
                                                            El estado es final.
                                                            Podés corregir
                                                            fecha, precio o
                                                            notas mientras el
                                                            tratamiento siga
                                                            activo.
                                                        </p>
                                                    )}
                                                    <Button
                                                        disabled={processing}
                                                    >
                                                        {processing
                                                            ? 'Guardando...'
                                                            : 'Guardar sesión'}
                                                    </Button>
                                                </div>
                                            </>
                                        )}
                                    </Form>
                                ) : (
                                    <dl className="grid gap-3 text-sm sm:grid-cols-3">
                                        <div>
                                            <dt className="text-muted-foreground">
                                                Fecha y hora
                                            </dt>
                                            <dd className="font-medium">
                                                {session.scheduled_at
                                                    ? dateTimeFormatter.format(
                                                          new Date(
                                                              session.scheduled_at,
                                                          ),
                                                      )
                                                    : 'Sin programar'}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt className="text-muted-foreground">
                                                Precio
                                            </dt>
                                            <dd className="font-medium">
                                                {money(
                                                    session.price,
                                                    session.currency,
                                                )}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt className="text-muted-foreground">
                                                Notas
                                            </dt>
                                            <dd className="whitespace-pre-wrap">
                                                {session.notes || 'Sin notas.'}
                                            </dd>
                                        </div>
                                    </dl>
                                )}

                                {session.status === 'completed' && (
                                    <div className="rounded-lg bg-muted/50 p-3 text-sm">
                                        <p className="text-muted-foreground">
                                            Opcional: documentá la evolución
                                            clínica. Se abrirá un formulario
                                            preseleccionado, sin crear ni
                                            vincular registros automáticamente.
                                        </p>
                                        <Button
                                            asChild
                                            variant="outline"
                                            size="sm"
                                            className="mt-3"
                                        >
                                            <Link
                                                href={createClinicalRecord(
                                                    pet.id,
                                                    {
                                                        query: {
                                                            type: 'evolution',
                                                        },
                                                    },
                                                )}
                                            >
                                                Registrar evolución
                                            </Link>
                                        </Button>
                                    </div>
                                )}
                            </article>
                        );
                    })}
                </section>
            </div>
        </>
    );
}
