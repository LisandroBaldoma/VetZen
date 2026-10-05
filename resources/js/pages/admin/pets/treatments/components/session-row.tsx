import { Form, Link } from '@inertiajs/react';
import {
    CalendarDaysIcon,
    CircleCheckIcon,
    CircleXIcon,
    FilePlus2Icon,
    PencilIcon,
} from 'lucide-react';
import { useState } from 'react';
import TreatmentSessionController from '@/actions/App/Http/Controllers/Admin/TreatmentSessionController';
import ConfirmActionDialog from '@/components/confirm-action-dialog';
import InputError from '@/components/input-error';
import TreatmentSessionCard from '@/components/treatment-session-card';
import type { TreatmentSession } from '@/components/treatment-session-card';
import { Button } from '@/components/ui/button';
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
import { create as createClinicalRecord } from '@/routes/admin/pets/medical-records';

export type { TreatmentSession } from '@/components/treatment-session-card';

type Props = {
    session: TreatmentSession;
    canOperate: boolean;
    completedSessions: number;
    plannedSessions: number;
    petId: number;
    highlighted?: boolean;
    compact?: boolean;
};

function EditSessionDialog({
    session,
    open,
    mode,
    onOpenChange,
}: {
    session: TreatmentSession;
    open: boolean;
    mode: 'schedule' | 'edit' | 'correct';
    onOpenChange: (open: boolean) => void;
}) {
    const title =
        mode === 'schedule'
            ? 'Definir fecha y hora'
            : mode === 'correct'
              ? 'Corregir datos de sesión'
              : 'Editar datos de sesión';

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{title}</DialogTitle>
                    <DialogDescription>
                        Sesión {session.session_number}
                    </DialogDescription>
                </DialogHeader>
                <Form
                    {...TreatmentSessionController.update.form(session.id)}
                    onSuccess={() => onOpenChange(false)}
                    className="space-y-4"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-4">
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
                                        autoFocus={mode === 'schedule'}
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
                                    <InputError message={errors.scheduled_at} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor={`price_${session.id}`}>
                                        Precio
                                    </Label>
                                    <div className="relative">
                                        <Input
                                            id={`price_${session.id}`}
                                            name="price"
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            defaultValue={session.price}
                                            className="pr-14"
                                            aria-invalid={Boolean(errors.price)}
                                        />
                                        <span className="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-muted-foreground">
                                            {session.currency}
                                        </span>
                                    </div>
                                    <InputError message={errors.price} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor={`notes_${session.id}`}>
                                        Notas
                                    </Label>
                                    <textarea
                                        id={`notes_${session.id}`}
                                        name="notes"
                                        defaultValue={session.notes ?? ''}
                                        className="min-h-28 rounded-xl border bg-input p-3 shadow-xs focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                    />
                                    <InputError message={errors.notes} />
                                </div>
                            </div>
                            <input
                                type="hidden"
                                name="currency"
                                value={session.currency}
                            />
                            <input
                                type="hidden"
                                name="status"
                                value={session.status}
                            />
                            <InputError message={errors.status} />
                            <InputError message={errors.currency} />
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

export default function SessionRow({
    session,
    canOperate,
    completedSessions,
    plannedSessions,
    petId,
    highlighted = false,
    compact = false,
}: Props) {
    const [editMode, setEditMode] = useState<
        'schedule' | 'edit' | 'correct' | null
    >(null);
    const [confirmation, setConfirmation] = useState<
        'completed' | 'cancelled' | null
    >(null);
    const isPending = session.status === 'pending';
    const isFinal = !isPending;
    const completedAfterTransition = completedSessions + 1;
    const completesTreatment = completedAfterTransition >= plannedSessions;

    const actions = canOperate && (
        <div className="mt-1 flex flex-wrap justify-start gap-1.5 sm:mt-0 sm:justify-end sm:gap-2">
            {isPending && !session.scheduled_at && (
                <Button
                    type="button"
                    size="sm"
                    className="size-10 px-0 sm:h-9 sm:w-auto sm:px-3"
                    aria-label="Definir fecha y hora"
                    onClick={() => setEditMode('schedule')}
                >
                    <CalendarDaysIcon aria-hidden />
                    <span className="hidden sm:inline">Definir fecha</span>
                </Button>
            )}
            {isPending && (
                <Button
                    type="button"
                    size="sm"
                    variant="secondary"
                    className="size-10 px-0 sm:h-9 sm:w-auto sm:px-3"
                    aria-label="Marcar como completada"
                    onClick={() => setConfirmation('completed')}
                >
                    <CircleCheckIcon aria-hidden />
                    <span className="hidden sm:inline">Completar</span>
                </Button>
            )}
            <Button
                type="button"
                size="sm"
                variant="outline"
                className="size-10 px-0 sm:h-9 sm:w-auto sm:px-3"
                aria-label={isFinal ? 'Corregir datos' : 'Editar datos'}
                onClick={() => setEditMode(isFinal ? 'correct' : 'edit')}
            >
                <PencilIcon aria-hidden />
                <span className="hidden sm:inline">
                    {isFinal ? 'Corregir' : 'Editar'}
                </span>
            </Button>
            {session.status === 'completed' && (
                <Button
                    asChild
                    size="sm"
                    variant="outline"
                    className="size-10 px-0 sm:h-9 sm:w-auto sm:px-3"
                    aria-label="Registrar evolución"
                >
                    <Link
                        href={createClinicalRecord(petId, {
                            query: { type: 'evolution' },
                        })}
                    >
                        <FilePlus2Icon aria-hidden />
                        <span className="hidden sm:inline">Evolución</span>
                    </Link>
                </Button>
            )}
            {isPending && (
                <Button
                    type="button"
                    size="sm"
                    variant="outline"
                    className="size-10 border-destructive/30 px-0 text-destructive hover:bg-destructive/10 hover:text-destructive sm:h-9 sm:w-auto sm:px-3"
                    aria-label="Cancelar sesión"
                    onClick={() => setConfirmation('cancelled')}
                >
                    <CircleXIcon aria-hidden />
                    <span className="hidden sm:inline">Cancelar</span>
                </Button>
            )}
        </div>
    );

    return (
        <>
            <TreatmentSessionCard
                session={session}
                actions={actions}
                highlighted={highlighted}
                compact={compact}
            />
            <EditSessionDialog
                session={session}
                open={editMode !== null}
                mode={editMode ?? 'edit'}
                onOpenChange={(open) => {
                    if (!open) {
                        setEditMode(null);
                    }
                }}
            />
            <Form {...TreatmentSessionController.update.form(session.id)}>
                {({ processing, submit }) => (
                    <>
                        <input
                            type="hidden"
                            name="scheduled_at"
                            value={session.scheduled_at ?? ''}
                        />
                        <input
                            type="hidden"
                            name="price"
                            value={session.price}
                        />
                        <input
                            type="hidden"
                            name="currency"
                            value={session.currency}
                        />
                        <input
                            type="hidden"
                            name="notes"
                            value={session.notes ?? ''}
                        />
                        <input
                            type="hidden"
                            name="status"
                            value={confirmation ?? session.status}
                        />
                        <ConfirmActionDialog
                            open={confirmation === 'completed'}
                            onOpenChange={(open) => {
                                if (!open) {
                                    setConfirmation(null);
                                }
                            }}
                            onConfirm={submit}
                            title={`¿Marcar sesión ${session.session_number} como completada?`}
                            description={
                                completesTreatment
                                    ? `Esta sesión completará las ${plannedSessions} sesiones requeridas. Al confirmar, el tratamiento quedará completado.`
                                    : `Esta acción sumará una sesión al progreso. El tratamiento pasará de ${completedSessions} a ${completedAfterTransition} sesiones completadas.`
                            }
                            confirmLabel="Completar sesión"
                            pending={processing}
                        />
                        <ConfirmActionDialog
                            open={confirmation === 'cancelled'}
                            onOpenChange={(open) => {
                                if (!open) {
                                    setConfirmation(null);
                                }
                            }}
                            onConfirm={submit}
                            title={`¿Cancelar sesión ${session.session_number}?`}
                            description="La sesión permanecerá en el historial. VetZen generará una nueva sesión pendiente si es necesaria para mantener la cantidad de sesiones requeridas."
                            confirmLabel="Cancelar sesión"
                            pending={processing}
                            destructive
                        />
                    </>
                )}
            </Form>
        </>
    );
}
