import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import { useState } from 'react';
import PetTreatmentController from '@/actions/App/Http/Controllers/Admin/PetTreatmentController';
import ConfirmActionDialog from '@/components/confirm-action-dialog';
import InputError from '@/components/input-error';
import PageHeader from '@/components/page-header';
import PetContextHeader from '@/components/pet-context-header';
import TreatmentProgress from '@/components/treatment-progress';
import TreatmentStatusBadge from '@/components/treatment-status-badge';
import type { TreatmentStatus } from '@/components/treatment-status-badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SessionManagementSheet from '@/pages/admin/pets/treatments/components/session-management-sheet';
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
    onManage,
    highlighted = false,
}: {
    title: string;
    sessions: TreatmentSession[];
    canOperate: boolean;
    onManage: (sessionId: number) => void;
    highlighted?: boolean;
}) {
    if (sessions.length === 0) {
        return null;
    }

    return (
        <section>
            <div className="mb-3 flex items-baseline justify-between gap-4">
                <h3 className="text-section-title font-semibold">{title}</h3>
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
                        onManage={onManage}
                        highlighted={highlighted && index === 0}
                    />
                ))}
            </div>
        </section>
    );
}

export default function TreatmentShow({
    pet,
    petTreatment,
}: {
    pet: Pet;
    petTreatment: PetTreatment;
}) {
    const [selectedSessionId, setSelectedSessionId] = useState<number | null>(
        null,
    );
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
    const selectedSession =
        petTreatment.sessions.find(
            (session) => session.id === selectedSessionId,
        ) ?? null;

    function handleSheetOpenChange(open: boolean): void {
        if (!open) {
            setSelectedSessionId(null);
        }
    }

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
                    <PageHeader
                        title={petTreatment.treatment_name}
                        description={`Tratamiento asignado a ${pet.name}`}
                        actions={
                            <TreatmentStatusBadge
                                status={petTreatment.status}
                            />
                        }
                    />
                </div>

                <TreatmentProgress
                    completedSessions={completed}
                    plannedSessions={petTreatment.planned_sessions}
                />

                <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-none">
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

                <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-none">
                    <CardHeader className="p-4 sm:p-5">
                        <CardTitle className="text-section-title">
                            Condiciones acordadas
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
                        <div>
                            <h2 className="text-sm font-semibold">
                                Procedimientos incluidos
                            </h2>
                            <ul className="mt-3 flex flex-wrap gap-2">
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
                        </div>
                        <div className="rounded-xl bg-surface-subtle p-4">
                            <h2 className="text-sm font-semibold">Notas</h2>
                            <p className="mt-1 text-sm whitespace-pre-wrap text-muted-foreground">
                                {petTreatment.notes || 'Sin notas.'}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                {canOperate && (
                    <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-none">
                        <CardHeader className="p-4 sm:p-5">
                            <CardTitle className="text-section-title">
                                Actualizar condiciones
                            </CardTitle>
                            <p className="text-sm text-muted-foreground">
                                Las sesiones agregadas usarán el nuevo precio.
                                Las existentes conservarán su precio histórico.
                            </p>
                        </CardHeader>
                        <CardContent className="px-4 pb-4 sm:px-5">
                            <Form
                                {...PetTreatmentController.update.form([
                                    pet.id,
                                    petTreatment.id,
                                ])}
                                className="grid gap-4 sm:grid-cols-2"
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
                                                message={
                                                    errors.planned_sessions
                                                }
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
                                                message={
                                                    errors.default_session_price
                                                }
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
                                                defaultValue={
                                                    petTreatment.notes ?? ''
                                                }
                                                className="min-h-24 rounded-xl border bg-input p-3 shadow-xs focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                            />
                                            <InputError
                                                message={errors.notes}
                                            />
                                            <InputError
                                                message={errors.currency}
                                            />
                                        </div>
                                        <div className="sm:col-span-2">
                                            <Button disabled={processing}>
                                                {processing
                                                    ? 'Actualizando...'
                                                    : 'Actualizar condiciones'}
                                            </Button>
                                        </div>
                                    </>
                                )}
                            </Form>
                        </CardContent>
                    </Card>
                )}

                {!isFinal && (
                    <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-none">
                        <CardHeader className="p-4 sm:p-5">
                            <CardTitle className="text-section-title">
                                Estado del tratamiento
                            </CardTitle>
                        </CardHeader>
                        <CardFooter className="flex-col items-stretch gap-3 px-4 py-4 sm:flex-row sm:px-5">
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
                                                petTreatment.status ===
                                                'suspended'
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
                                            variant="destructive"
                                            disabled={processing}
                                            className="w-full sm:w-auto"
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
                        </CardFooter>
                    </Card>
                )}

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
                        onManage={setSelectedSessionId}
                        highlighted
                    />
                    <SessionGroup
                        title="Otras sesiones programadas"
                        sessions={otherScheduledSessions}
                        canOperate={canOperate}
                        onManage={setSelectedSessionId}
                    />
                    <SessionGroup
                        title="Sesiones pendientes sin programar"
                        sessions={pendingSessions}
                        canOperate={canOperate}
                        onManage={setSelectedSessionId}
                    />
                    <SessionGroup
                        title="Sesiones completadas"
                        sessions={completedSessions}
                        canOperate={canOperate}
                        onManage={setSelectedSessionId}
                    />
                    <SessionGroup
                        title="Sesiones canceladas"
                        sessions={cancelledSessions}
                        canOperate={canOperate}
                        onManage={setSelectedSessionId}
                    />
                </section>
                <SessionManagementSheet
                    session={selectedSession}
                    open={selectedSession !== null}
                    onOpenChange={handleSheetOpenChange}
                    canOperate={canOperate}
                    completedSessions={completed}
                    plannedSessions={petTreatment.planned_sessions}
                    petId={pet.id}
                    petName={pet.name}
                    treatmentName={petTreatment.treatment_name}
                />
            </div>
        </>
    );
}
