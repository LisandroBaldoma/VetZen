import type { FormComponentRef } from '@inertiajs/core';
import { Form, Link } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import TreatmentSessionController from '@/actions/App/Http/Controllers/Admin/TreatmentSessionController';
import ConfirmActionDialog from '@/components/confirm-action-dialog';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import type { TreatmentSession } from '@/pages/admin/pets/treatments/components/session-row';
import { create as createClinicalRecord } from '@/routes/admin/pets/medical-records';

type SessionStatus = TreatmentSession['status'];
type ConfirmationAction = Extract<SessionStatus, 'completed' | 'cancelled'>;

const statusLabels: Record<SessionStatus, string> = {
    pending: 'Pendiente',
    completed: 'Completada',
    cancelled: 'Cancelada',
};

const statusStyles: Record<SessionStatus, string> = {
    pending: 'border-transparent bg-operational text-operational-foreground',
    completed: 'border-transparent bg-clinical text-clinical-foreground',
    cancelled: 'border-destructive/20 bg-destructive/10 text-destructive',
};

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

type Props = {
    session: TreatmentSession | null;
    open: boolean;
    onOpenChange: (open: boolean) => void;
    canOperate: boolean;
    completedSessions: number;
    plannedSessions: number;
    petId: number;
    petName: string;
    treatmentName: string;
};

export default function SessionManagementSheet({
    session,
    open,
    onOpenChange,
    canOperate,
    completedSessions,
    plannedSessions,
    petId,
    petName,
    treatmentName,
}: Props) {
    const formRef = useRef<FormComponentRef>(null);
    const [confirmationAction, setConfirmationAction] =
        useState<ConfirmationAction | null>(null);
    const [statusToSubmit, setStatusToSubmit] =
        useState<ConfirmationAction | null>(null);

    useEffect(() => {
        if (statusToSubmit) {
            formRef.current?.submit();
        }
    }, [statusToSubmit]);

    if (!session) {
        return null;
    }

    const sessionIsFinal = session.status !== 'pending';
    const completedAfterTransition = completedSessions + 1;
    const completesTreatment = completedAfterTransition >= plannedSessions;

    return (
        <Sheet open={open} onOpenChange={onOpenChange}>
            <SheetContent
                side="bottom"
                className="max-h-[90dvh] gap-0 rounded-t-2xl border-x border-t p-0 md:right-auto md:bottom-6 md:left-1/2 md:w-[min(42rem,calc(100vw-3rem))] md:max-w-none md:-translate-x-1/2 md:rounded-2xl md:border"
            >
                <SheetHeader className="border-b border-border-subtle px-4 pt-3 pb-4 sm:px-5">
                    <div className="mb-2 h-1 w-10 self-center rounded-full bg-border-strong md:hidden" />
                    <div className="flex flex-wrap items-center gap-2 pr-10">
                        <SheetTitle className="text-section-title">
                            {canOperate
                                ? `Gestionar sesión ${session.session_number}`
                                : `Sesión ${session.session_number}`}
                        </SheetTitle>
                        <Badge className={statusStyles[session.status]}>
                            <span
                                className="size-1.5 rounded-full bg-current"
                                aria-hidden="true"
                            />
                            {statusLabels[session.status]}
                        </Badge>
                    </div>
                    <SheetDescription className="pr-10">
                        {treatmentName} · {petName}
                    </SheetDescription>
                </SheetHeader>

                <div className="min-h-0 flex-1 overflow-y-auto px-4 py-5 sm:px-5">
                    {canOperate ? (
                        <Form
                            ref={formRef}
                            {...TreatmentSessionController.update.form(
                                session.id,
                            )}
                            onSuccess={() => onOpenChange(false)}
                            onError={() => {
                                setConfirmationAction(null);
                                setStatusToSubmit(null);
                            }}
                            className="space-y-6"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <section className="space-y-4">
                                        <div>
                                            <h3 className="text-meta font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                                                Datos de la sesión
                                            </h3>
                                            <p className="mt-1 text-sm text-muted-foreground">
                                                La moneda se mantiene según las
                                                condiciones del tratamiento.
                                            </p>
                                        </div>
                                        <div className="grid gap-4">
                                            <div className="grid gap-2">
                                                <Label
                                                    htmlFor={`scheduled_at_${session.id}`}
                                                >
                                                    Fecha y hora programada
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
                                                <div className="relative">
                                                    <Input
                                                        id={`price_${session.id}`}
                                                        name="price"
                                                        type="number"
                                                        min="0"
                                                        step="0.01"
                                                        defaultValue={
                                                            session.price
                                                        }
                                                        className="pr-14"
                                                        aria-invalid={Boolean(
                                                            errors.price,
                                                        )}
                                                    />
                                                    <span className="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-muted-foreground">
                                                        {session.currency}
                                                    </span>
                                                </div>
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
                                                <p className="text-sm font-medium">
                                                    Estado
                                                </p>
                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value={
                                                        statusToSubmit ??
                                                        session.status
                                                    }
                                                />
                                                <p className="flex min-h-11 items-center rounded-xl border bg-muted/40 px-3 text-sm">
                                                    {
                                                        statusLabels[
                                                            session.status
                                                        ]
                                                    }
                                                    {sessionIsFinal &&
                                                        ' (final)'}
                                                </p>
                                                <InputError
                                                    message={errors.status}
                                                />
                                                <InputError
                                                    message={errors.currency}
                                                />
                                            </div>
                                            <div className="grid gap-2">
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
                                                    className="min-h-28 rounded-xl border bg-input p-3 shadow-xs focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                                />
                                                <InputError
                                                    message={errors.notes}
                                                />
                                            </div>
                                        </div>
                                    </section>

                                    {sessionIsFinal ? (
                                        <>
                                            <div className="rounded-xl bg-surface-subtle p-4 text-sm text-muted-foreground">
                                                El estado es final. Podés
                                                corregir fecha, precio o notas
                                                mientras el tratamiento siga
                                                activo.
                                            </div>
                                            <Button
                                                disabled={processing}
                                                className="w-full"
                                            >
                                                {processing
                                                    ? 'Guardando...'
                                                    : 'Corregir datos de la sesión'}
                                            </Button>
                                        </>
                                    ) : (
                                        <section className="space-y-4 border-t border-border-subtle pt-6">
                                            <div>
                                                <h3 className="text-meta font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                                                    Estado de la sesión
                                                </h3>
                                                <p className="mt-1 text-sm text-muted-foreground">
                                                    Estas acciones actualizan el
                                                    progreso del tratamiento.
                                                </p>
                                            </div>
                                            <Button
                                                type="button"
                                                variant="secondary"
                                                className="w-full"
                                                disabled={processing}
                                                onClick={() =>
                                                    setConfirmationAction(
                                                        'completed',
                                                    )
                                                }
                                            >
                                                Marcar como completada
                                            </Button>
                                            <Button
                                                type="button"
                                                variant="outline"
                                                className="w-full border-destructive/30 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                disabled={processing}
                                                onClick={() =>
                                                    setConfirmationAction(
                                                        'cancelled',
                                                    )
                                                }
                                            >
                                                Cancelar sesión
                                            </Button>
                                            <Button
                                                disabled={processing}
                                                className="w-full"
                                            >
                                                {processing
                                                    ? 'Guardando...'
                                                    : 'Guardar cambios'}
                                            </Button>
                                        </section>
                                    )}

                                    <ConfirmActionDialog
                                        open={
                                            confirmationAction === 'completed'
                                        }
                                        onOpenChange={(open) => {
                                            if (!open) {
                                                setConfirmationAction(null);
                                            }
                                        }}
                                        onConfirm={() =>
                                            setStatusToSubmit('completed')
                                        }
                                        title={`¿Marcar sesión ${session.session_number} como completada?`}
                                        description={
                                            completesTreatment
                                                ? `Esta sesión completará las ${plannedSessions} sesiones requeridas. Al confirmar, el tratamiento quedará completado.`
                                                : `Esta acción sumará una sesión al progreso. El tratamiento pasará de ${completedSessions} a ${completedAfterTransition} sesiones completadas.`
                                        }
                                        confirmLabel="Completar sesión"
                                        pending={
                                            processing ||
                                            statusToSubmit === 'completed'
                                        }
                                    />
                                    <ConfirmActionDialog
                                        open={
                                            confirmationAction === 'cancelled'
                                        }
                                        onOpenChange={(open) => {
                                            if (!open) {
                                                setConfirmationAction(null);
                                            }
                                        }}
                                        onConfirm={() =>
                                            setStatusToSubmit('cancelled')
                                        }
                                        title={`¿Cancelar sesión ${session.session_number}?`}
                                        description="La sesión permanecerá en el historial. VetZen generará una nueva sesión pendiente si es necesaria para mantener la cantidad de sesiones requeridas."
                                        confirmLabel="Cancelar sesión"
                                        pending={
                                            processing ||
                                            statusToSubmit === 'cancelled'
                                        }
                                        destructive
                                    />
                                </>
                            )}
                        </Form>
                    ) : (
                        <div className="space-y-5">
                            <div className="rounded-xl bg-surface-subtle p-4 text-sm text-muted-foreground">
                                Este tratamiento no permite modificar sesiones
                                en su estado actual.
                            </div>
                            <dl className="space-y-4 text-sm">
                                <div>
                                    <dt className="text-muted-foreground">
                                        Fecha y hora
                                    </dt>
                                    <dd className="mt-1 font-medium">
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
                                    <dd className="mt-1 font-medium tabular-nums">
                                        {money(session.price, session.currency)}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-muted-foreground">
                                        Notas
                                    </dt>
                                    <dd className="mt-1 whitespace-pre-wrap">
                                        {session.notes || 'Sin notas.'}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    )}

                    {session.status === 'completed' && (
                        <div className="mt-6 border-t border-border-subtle pt-5">
                            <p className="text-sm text-muted-foreground">
                                Opcional: documentá la evolución clínica sin
                                crear ni vincular registros automáticamente.
                            </p>
                            <Button
                                asChild
                                variant="outline"
                                className="mt-3 w-full"
                            >
                                <Link
                                    href={createClinicalRecord(petId, {
                                        query: { type: 'evolution' },
                                    })}
                                >
                                    Registrar evolución
                                </Link>
                            </Button>
                        </div>
                    )}
                </div>
            </SheetContent>
        </Sheet>
    );
}
