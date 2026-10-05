import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import { useState } from 'react';
import PetTreatmentController from '@/actions/App/Http/Controllers/Admin/PetTreatmentController';
import ConfirmActionDialog from '@/components/confirm-action-dialog';
import InputError from '@/components/input-error';
import PetContextHeader from '@/components/pet-context-header';
import TreatmentProgress from '@/components/treatment-progress';
import TreatmentStatusBadge from '@/components/treatment-status-badge';
import type { TreatmentStatus } from '@/components/treatment-status-badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SessionRow from '@/pages/admin/pets/treatments/components/session-row';
import type { TreatmentSession } from '@/pages/admin/pets/treatments/components/session-row';
import { dashboard } from '@/routes';
import { edit, index as petsIndex, show as petShow } from '@/routes/admin/pets';
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

type PetTreatment = {
    id: number;
    treatment_name: string;
    treatment_description: string;
    planned_sessions: number;
    default_session_price: string;
    currency: string;
    starts_on: string;
    status: TreatmentStatus;
    notes: string | null;
    procedure_snapshots: {
        id: number;
        procedure_name: string;
        procedure_description: string | null;
    }[];
    sessions: TreatmentSession[];
};

const dateFormatter = new Intl.DateTimeFormat('es-AR', { dateStyle: 'long' });

function money(value: string, currency: string): string {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency,
    }).format(Number(value));
}

function SessionGroup({
    title,
    sessions,
    canOperate,
    completedSessions,
    plannedSessions,
    petId,
    highlighted = false,
    tone = 'default',
    compact = false,
    collapsible = false,
}: {
    title: string;
    sessions: TreatmentSession[];
    canOperate: boolean;
    completedSessions: number;
    plannedSessions: number;
    petId: number;
    highlighted?: boolean;
    tone?: 'default' | 'completed' | 'cancelled';
    compact?: boolean;
    collapsible?: boolean;
}) {
    if (sessions.length === 0) {
        return null;
    }

    if (collapsible) {
        return (
            <details className="rounded-xl bg-destructive/5 p-4 sm:p-5">
                <summary className="flex cursor-pointer list-none items-baseline justify-between gap-4">
                    <h3 className="text-section-title font-semibold text-destructive">
                        {title}
                    </h3>
                    <span className="text-sm text-muted-foreground tabular-nums">
                        {sessions.length}{' '}
                        {sessions.length === 1 ? 'sesión' : 'sesiones'}
                    </span>
                </summary>
                <div className="mt-3 space-y-3">
                    {sessions.map((session) => (
                        <SessionRow
                            key={session.id}
                            session={session}
                            canOperate={canOperate}
                            completedSessions={completedSessions}
                            plannedSessions={plannedSessions}
                            petId={petId}
                        />
                    ))}
                </div>
            </details>
        );
    }

    return (
        <section
            className={
                tone === 'cancelled'
                    ? 'rounded-xl bg-destructive/5 p-4 sm:p-5'
                    : 'rounded-xl bg-card p-4 shadow-sm sm:p-5'
            }
        >
            <div className="mb-3 flex items-baseline justify-between gap-4">
                <h3
                    className={`text-section-title font-semibold ${tone === 'cancelled' ? 'text-destructive' : tone === 'completed' ? 'text-muted-foreground' : ''}`}
                >
                    {title}
                </h3>
                <span className="text-sm text-muted-foreground tabular-nums">
                    {sessions.length}{' '}
                    {sessions.length === 1 ? 'sesión' : 'sesiones'}
                </span>
            </div>
            <div className="space-y-3">
                {sessions.map((session, index) => (
                    <SessionRow
                        key={session.id}
                        session={session}
                        canOperate={canOperate}
                        completedSessions={completedSessions}
                        plannedSessions={plannedSessions}
                        petId={petId}
                        highlighted={highlighted && index === 0}
                        compact={compact}
                    />
                ))}
            </div>
        </section>
    );
}

function EditTreatmentConditionsDialog({
    treatment,
    petId,
    open,
    onOpenChange,
}: {
    treatment: PetTreatment;
    petId: number;
    open: boolean;
    onOpenChange: (open: boolean) => void;
}) {
    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Editar condiciones</DialogTitle>
                    <DialogDescription>
                        Las sesiones nuevas usarán el precio actualizado. Las
                        existentes conservarán su precio histórico.
                    </DialogDescription>
                </DialogHeader>
                <Form
                    {...PetTreatmentController.update.form([
                        petId,
                        treatment.id,
                    ])}
                    onSuccess={() => onOpenChange(false)}
                    className="space-y-4"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-4">
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
                                            treatment.planned_sessions
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
                                        Valor de referencia por sesión
                                    </Label>
                                    <div className="relative">
                                        <Input
                                            id="default_session_price"
                                            name="default_session_price"
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            defaultValue={
                                                treatment.default_session_price
                                            }
                                            className="pr-14"
                                            aria-invalid={Boolean(
                                                errors.default_session_price,
                                            )}
                                        />
                                        <span className="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-muted-foreground">
                                            {treatment.currency}
                                        </span>
                                    </div>
                                    <InputError
                                        message={errors.default_session_price}
                                    />
                                </div>
                                <input
                                    type="hidden"
                                    name="currency"
                                    value={treatment.currency}
                                />
                                <div className="grid gap-2">
                                    <Label htmlFor="treatment_notes">
                                        Notas
                                    </Label>
                                    <textarea
                                        id="treatment_notes"
                                        name="notes"
                                        defaultValue={treatment.notes ?? ''}
                                        className="min-h-28 rounded-xl border bg-input p-3 shadow-xs focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                    />
                                    <InputError message={errors.notes} />
                                    <InputError message={errors.currency} />
                                </div>
                            </div>
                            <DialogFooter>
                                <Button
                                    type="button"
                                    variant="secondary"
                                    disabled={processing}
                                    onClick={() => onOpenChange(false)}
                                >
                                    Cancelar
                                </Button>
                                <Button disabled={processing}>
                                    {processing
                                        ? 'Guardando...'
                                        : 'Guardar cambios'}
                                </Button>
                            </DialogFooter>
                        </>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}

export default function TreatmentShow({
    pet,
    petTreatment,
}: {
    pet: Pet;
    petTreatment: PetTreatment;
}) {
    const [conditionsDialogOpen, setConditionsDialogOpen] = useState(false);
    const [suspendDialogOpen, setSuspendDialogOpen] = useState(false);
    const [cancelTreatmentOpen, setCancelTreatmentOpen] = useState(false);
    const completed = petTreatment.sessions.filter(
        (session) => session.status === 'completed',
    ).length;
    const canOperate = ['pending', 'in_progress'].includes(petTreatment.status);
    const isFinal = ['completed', 'cancelled'].includes(petTreatment.status);
    const scheduledSessions = petTreatment.sessions
        .filter(
            (session) =>
                session.status === 'pending' && session.scheduled_at !== null,
        )
        .sort((first, second) => {
            const dateDifference =
                new Date(first.scheduled_at as string).getTime() -
                new Date(second.scheduled_at as string).getTime();

            return (
                dateDifference || first.session_number - second.session_number
            );
        });
    const nextSession = scheduledSessions.slice(0, 1);
    const otherScheduledSessions = scheduledSessions.slice(1);
    const pendingSessions = petTreatment.sessions.filter(
        (session) =>
            session.status === 'pending' && session.scheduled_at === null,
    );
    const completedSessions = petTreatment.sessions.filter(
        (session) => session.status === 'completed',
    );
    const cancelledSessions = petTreatment.sessions.filter(
        (session) => session.status === 'cancelled',
    );
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
            <div className="workspace-clinical">
                <PetContextHeader
                    pet={pet}
                    variant="admin"
                    active="treatments"
                    editHref={edit.url(pet.id)}
                />

                <div className="space-y-4">
                    <Button asChild variant="link" className="h-11 px-0">
                        <Link href={index.url(pet.id)}>
                            Volver a tratamientos
                        </Link>
                    </Button>
                    <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
                        <CardContent className="flex items-start justify-between gap-4 p-4 sm:p-5">
                            <div className="min-w-0">
                                <p className="text-meta font-semibold tracking-[0.14em] text-primary uppercase">
                                    Tratamiento asignado
                                </p>
                                <h1 className="mt-1 text-page-title font-semibold tracking-tight text-balance">
                                    {petTreatment.treatment_name}
                                </h1>
                                <div className="mt-3">
                                    <TreatmentStatusBadge
                                        status={petTreatment.status}
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <TreatmentProgress
                    completedSessions={completed}
                    plannedSessions={petTreatment.planned_sessions}
                    showRemaining
                />

                <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
                    <CardHeader className="p-4 sm:p-5">
                        <CardTitle className="text-section-title">
                            Información del tratamiento
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="px-4 pb-4 sm:px-5">
                        <dl className="divide-y divide-border-subtle text-sm">
                            <div className="flex items-baseline justify-between gap-4 py-3 first:pt-0">
                                <dt className="text-muted-foreground">
                                    Inicio
                                </dt>
                                <dd className="text-right font-medium tabular-nums">
                                    {dateFormatter.format(
                                        new Date(
                                            `${petTreatment.starts_on.slice(0, 10)}T00:00:00`,
                                        ),
                                    )}
                                </dd>
                            </div>
                            <div className="flex items-baseline justify-between gap-4 py-3">
                                <dt className="text-muted-foreground">
                                    Sesiones requeridas
                                </dt>
                                <dd className="font-medium tabular-nums">
                                    {petTreatment.planned_sessions}
                                </dd>
                            </div>
                            <div className="flex items-baseline justify-between gap-4 py-3">
                                <dt className="text-muted-foreground">
                                    Precio predeterminado
                                </dt>
                                <dd className="font-medium tabular-nums">
                                    {money(
                                        petTreatment.default_session_price,
                                        petTreatment.currency,
                                    )}
                                </dd>
                            </div>
                            <div className="flex items-baseline justify-between gap-4 py-3 last:pb-0">
                                <dt className="text-muted-foreground">
                                    Moneda
                                </dt>
                                <dd className="font-medium">
                                    {petTreatment.currency}
                                </dd>
                            </div>
                        </dl>
                    </CardContent>
                </Card>

                <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
                    <CardHeader className="p-4 sm:p-5">
                        <CardTitle className="text-section-title">
                            Descripción del tratamiento
                        </CardTitle>
                        <p className="text-sm text-muted-foreground">
                            Estos datos y procedimientos son snapshots de la
                            asignación y no dependen de cambios posteriores del
                            catálogo.
                        </p>
                    </CardHeader>
                    <CardContent className="space-y-5 px-4 pb-4 sm:px-5">
                        <p className="whitespace-pre-wrap">
                            {petTreatment.treatment_description}
                        </p>
                    </CardContent>
                </Card>

                <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
                    <CardHeader className="p-4 sm:p-5">
                        <CardTitle className="text-section-title">
                            Procedimientos incluidos
                        </CardTitle>
                        <p className="text-sm text-muted-foreground">
                            Definidos al momento de la asignación del
                            tratamiento.
                        </p>
                    </CardHeader>
                    <CardContent className="px-4 pb-4 sm:px-5">
                        <ul className="flex flex-wrap gap-2">
                            {petTreatment.procedure_snapshots.map(
                                (procedure) => (
                                    <li
                                        key={procedure.id}
                                        className="rounded-lg bg-surface-subtle px-3 py-2 text-sm"
                                    >
                                        <p className="font-medium">
                                            {procedure.procedure_name}
                                        </p>
                                        {procedure.procedure_description && (
                                            <p className="mt-1 text-muted-foreground">
                                                {
                                                    procedure.procedure_description
                                                }
                                            </p>
                                        )}
                                    </li>
                                ),
                            )}
                        </ul>
                    </CardContent>
                </Card>

                <section className="rounded-xl bg-surface-subtle p-4 shadow-sm sm:p-5">
                    <h2 className="text-meta font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Notas del tratamiento
                    </h2>
                    <p className="mt-2 text-sm leading-relaxed whitespace-pre-wrap text-foreground">
                        {petTreatment.notes || 'Sin notas.'}
                    </p>
                </section>

                {isFinal && (
                    <Card className="gap-0 overflow-hidden border-clinical bg-clinical/30 p-0">
                        <CardContent className="p-4 sm:p-5">
                            <p className="font-semibold">
                                Este tratamiento está cerrado y no admite
                                cambios.
                            </p>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Si la atención continúa, asigná un nuevo
                                tratamiento para conservar este historial sin
                                modificaciones.
                            </p>
                            <Button asChild className="mt-4">
                                <Link href={create.url(pet.id)}>
                                    Asignar nuevo tratamiento
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                )}

                {!isFinal && (
                    <section className="rounded-xl border border-border-subtle bg-card p-4 shadow-sm sm:p-5">
                        <div className="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 className="text-section-title font-semibold">
                                    Estado del tratamiento
                                </h2>
                                <div className="mt-2">
                                    <TreatmentStatusBadge
                                        status={petTreatment.status}
                                    />
                                </div>
                            </div>
                            {canOperate && (
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() =>
                                        setConditionsDialogOpen(true)
                                    }
                                >
                                    Editar condiciones
                                </Button>
                            )}
                        </div>
                        <div className="mt-4 flex flex-col gap-2 sm:flex-row">
                            {petTreatment.status === 'suspended' ? (
                                <Form
                                    {...PetTreatmentController.updateStatus.form(
                                        [pet.id, petTreatment.id],
                                    )}
                                >
                                    {({ processing }) => (
                                        <>
                                            <input
                                                type="hidden"
                                                name="status"
                                                value="resume"
                                            />
                                            <Button
                                                variant="secondary"
                                                disabled={processing}
                                            >
                                                Reanudar tratamiento
                                            </Button>
                                        </>
                                    )}
                                </Form>
                            ) : (
                                <Form
                                    {...PetTreatmentController.updateStatus.form(
                                        [pet.id, petTreatment.id],
                                    )}
                                    onError={() => setSuspendDialogOpen(false)}
                                >
                                    {({ processing, submit }) => (
                                        <>
                                            <input
                                                type="hidden"
                                                name="status"
                                                value="suspended"
                                            />
                                            <Button
                                                type="button"
                                                variant="outline"
                                                disabled={processing}
                                                onClick={() =>
                                                    setSuspendDialogOpen(true)
                                                }
                                            >
                                                Suspender tratamiento
                                            </Button>
                                            <ConfirmActionDialog
                                                open={suspendDialogOpen}
                                                onOpenChange={
                                                    setSuspendDialogOpen
                                                }
                                                onConfirm={submit}
                                                title="¿Suspender tratamiento?"
                                                description="Mientras esté suspendido no podrán gestionarse sus sesiones."
                                                confirmLabel="Suspender tratamiento"
                                                pending={processing}
                                            />
                                        </>
                                    )}
                                </Form>
                            )}
                            <Form
                                {...PetTreatmentController.updateStatus.form([
                                    pet.id,
                                    petTreatment.id,
                                ])}
                                onError={() => setCancelTreatmentOpen(false)}
                            >
                                {({ processing, submit }) => (
                                    <>
                                        <input
                                            type="hidden"
                                            name="status"
                                            value="cancelled"
                                        />
                                        <Button
                                            type="button"
                                            variant="outline"
                                            className="border-destructive/30 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                            disabled={processing}
                                            onClick={() =>
                                                setCancelTreatmentOpen(true)
                                            }
                                        >
                                            Cancelar tratamiento
                                        </Button>
                                        <ConfirmActionDialog
                                            open={cancelTreatmentOpen}
                                            onOpenChange={
                                                setCancelTreatmentOpen
                                            }
                                            onConfirm={submit}
                                            title="¿Cancelar tratamiento?"
                                            description="El tratamiento no podrá reabrirse. Sus sesiones permanecerán disponibles como historial."
                                            confirmLabel="Cancelar tratamiento"
                                            pending={processing}
                                            destructive
                                        />
                                    </>
                                )}
                            </Form>
                        </div>
                    </section>
                )}

                <section className="space-y-6">
                    <div>
                        <h2 className="text-page-title font-semibold">
                            Sesiones
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Las sesiones canceladas se conservan en el
                            historial.
                        </p>
                    </div>
                    <SessionGroup
                        title="Próxima sesión programada"
                        sessions={nextSession}
                        canOperate={canOperate}
                        completedSessions={completed}
                        plannedSessions={petTreatment.planned_sessions}
                        petId={pet.id}
                        highlighted
                    />
                    <SessionGroup
                        title="Sesiones completadas"
                        sessions={completedSessions}
                        canOperate={canOperate}
                        completedSessions={completed}
                        plannedSessions={petTreatment.planned_sessions}
                        petId={pet.id}
                        tone="completed"
                        compact
                    />
                    <section className="space-y-4 rounded-xl bg-card p-4 shadow-sm sm:p-5">
                        <div>
                            <h3 className="text-section-title font-semibold">
                                Plan restante
                            </h3>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Sesiones pendientes de realizar.
                            </p>
                        </div>
                        <SessionGroup
                            title="Otras sesiones programadas"
                            sessions={otherScheduledSessions}
                            canOperate={canOperate}
                            completedSessions={completed}
                            plannedSessions={petTreatment.planned_sessions}
                            petId={pet.id}
                        />
                        <SessionGroup
                            title="Sesiones pendientes sin programar"
                            sessions={pendingSessions}
                            canOperate={canOperate}
                            completedSessions={completed}
                            plannedSessions={petTreatment.planned_sessions}
                            petId={pet.id}
                        />
                    </section>
                    <SessionGroup
                        title="Sesiones canceladas"
                        sessions={cancelledSessions}
                        canOperate={canOperate}
                        completedSessions={completed}
                        plannedSessions={petTreatment.planned_sessions}
                        petId={pet.id}
                        collapsible
                    />
                </section>
                <EditTreatmentConditionsDialog
                    treatment={petTreatment}
                    petId={pet.id}
                    open={conditionsDialogOpen}
                    onOpenChange={setConditionsDialogOpen}
                />
            </div>
        </>
    );
}
