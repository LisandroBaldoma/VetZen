import { Form } from '@inertiajs/react';
import { useState } from 'react';
import PetTreatmentController from '@/actions/App/Http/Controllers/Admin/PetTreatmentController';
import ConfirmActionDialog from '@/components/confirm-action-dialog';
import InputError from '@/components/input-error';
import type { TreatmentStatus } from '@/components/treatment-status-badge';
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

type Props = {
    treatment: {
        id: number;
        treatment_name: string;
        planned_sessions: number;
        default_session_price: string;
        currency: string;
        notes: string | null;
        status: TreatmentStatus;
    };
    petId: number;
    open: boolean;
    onOpenChange: (open: boolean) => void;
};

export default function TreatmentManagementSheet({
    treatment,
    petId,
    open,
    onOpenChange,
}: Props) {
    const [confirmation, setConfirmation] = useState<
        'suspend' | 'cancel' | null
    >(null);
    const canUpdateConditions = ['pending', 'in_progress'].includes(
        treatment.status,
    );
    const canResume = treatment.status === 'suspended';

    return (
        <Sheet open={open} onOpenChange={onOpenChange}>
            <SheetContent
                side="bottom"
                className="max-h-[90dvh] gap-0 rounded-t-2xl border-x border-t p-0 md:right-auto md:bottom-6 md:left-1/2 md:w-[min(42rem,calc(100vw-3rem))] md:max-w-none md:-translate-x-1/2 md:rounded-2xl md:border"
            >
                <SheetHeader className="border-b border-border-subtle px-4 pt-3 pb-4 sm:px-5">
                    <div className="mb-2 h-1 w-10 self-center rounded-full bg-border-strong md:hidden" />
                    <SheetTitle className="pr-10 text-section-title">
                        Gestionar tratamiento
                    </SheetTitle>
                    <SheetDescription className="pr-10">
                        {treatment.treatment_name}
                    </SheetDescription>
                </SheetHeader>

                <div className="min-h-0 flex-1 space-y-6 overflow-y-auto px-4 py-5 sm:px-5">
                    {canUpdateConditions && (
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
                                    <div>
                                        <h3 className="text-meta font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                                            Condiciones acordadas
                                        </h3>
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            Las sesiones nuevas usarán el precio
                                            actualizado. Las existentes
                                            conservarán su precio histórico.
                                        </p>
                                    </div>
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
                                                message={
                                                    errors.planned_sessions
                                                }
                                            />
                                        </div>
                                        <div className="grid gap-2">
                                            <Label htmlFor="default_session_price">
                                                Precio predeterminado
                                            </Label>
                                            <Input
                                                id="default_session_price"
                                                name="default_session_price"
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                defaultValue={
                                                    treatment.default_session_price
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
                                            value={treatment.currency}
                                        />
                                        <div className="grid gap-2">
                                            <Label htmlFor="treatment_notes">
                                                Notas
                                            </Label>
                                            <textarea
                                                id="treatment_notes"
                                                name="notes"
                                                defaultValue={
                                                    treatment.notes ?? ''
                                                }
                                                className="min-h-28 rounded-xl border bg-input p-3 shadow-xs focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                            />
                                            <InputError
                                                message={errors.notes}
                                            />
                                            <InputError
                                                message={errors.currency}
                                            />
                                        </div>
                                    </div>
                                    <Button
                                        disabled={processing}
                                        className="w-full"
                                    >
                                        {processing
                                            ? 'Actualizando...'
                                            : 'Actualizar condiciones'}
                                    </Button>
                                </>
                            )}
                        </Form>
                    )}

                    <section className="space-y-4 border-t border-border-subtle pt-6">
                        <div>
                            <h3 className="text-meta font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                                Estado del tratamiento
                            </h3>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Las acciones de estado afectan las sesiones y el
                                progreso disponible.
                            </p>
                        </div>
                        {canResume ? (
                            <Form
                                {...PetTreatmentController.updateStatus.form([
                                    petId,
                                    treatment.id,
                                ])}
                                onSuccess={() => onOpenChange(false)}
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
                                            className="w-full"
                                        >
                                            Reanudar tratamiento
                                        </Button>
                                    </>
                                )}
                            </Form>
                        ) : (
                            <Form
                                {...PetTreatmentController.updateStatus.form([
                                    petId,
                                    treatment.id,
                                ])}
                                onSuccess={() => onOpenChange(false)}
                                onError={() => setConfirmation(null)}
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
                                            className="w-full"
                                            onClick={() =>
                                                setConfirmation('suspend')
                                            }
                                        >
                                            Suspender tratamiento
                                        </Button>
                                        <ConfirmActionDialog
                                            open={confirmation === 'suspend'}
                                            onOpenChange={(nextOpen) => {
                                                if (!nextOpen) {
                                                    setConfirmation(null);
                                                }
                                            }}
                                            onConfirm={submit}
                                            title="¿Suspender tratamiento?"
                                            description="No podrás gestionar sesiones hasta reanudar el tratamiento."
                                            confirmLabel="Suspender tratamiento"
                                            pending={processing}
                                        />
                                    </>
                                )}
                            </Form>
                        )}
                        <Form
                            {...PetTreatmentController.updateStatus.form([
                                petId,
                                treatment.id,
                            ])}
                            onSuccess={() => onOpenChange(false)}
                            onError={() => setConfirmation(null)}
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
                                        disabled={processing}
                                        className="w-full border-destructive/30 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                        onClick={() =>
                                            setConfirmation('cancel')
                                        }
                                    >
                                        Cancelar tratamiento
                                    </Button>
                                    <ConfirmActionDialog
                                        open={confirmation === 'cancel'}
                                        onOpenChange={(nextOpen) => {
                                            if (!nextOpen) {
                                                setConfirmation(null);
                                            }
                                        }}
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
                    </section>
                </div>
            </SheetContent>
        </Sheet>
    );
}
