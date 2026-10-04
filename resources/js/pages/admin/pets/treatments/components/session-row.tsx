import { Form, Link } from '@inertiajs/react';
import { ChevronDownIcon } from 'lucide-react';
import TreatmentSessionController from '@/actions/App/Http/Controllers/Admin/TreatmentSessionController';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
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
import { create as createClinicalRecord } from '@/routes/admin/pets/medical-records';

export type TreatmentSession = {
    id: number;
    session_number: number;
    scheduled_at: string | null;
    price: string;
    currency: string;
    status: 'pending' | 'completed' | 'cancelled';
    notes: string | null;
};

const statusLabels: Record<TreatmentSession['status'], string> = {
    pending: 'Pendiente',
    completed: 'Completada',
    cancelled: 'Cancelada',
};

const statusStyles: Record<TreatmentSession['status'], string> = {
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
    session: TreatmentSession;
    petId: number;
    canOperate: boolean;
    highlighted?: boolean;
};

export default function SessionRow({
    session,
    petId,
    canOperate,
    highlighted = false,
}: Props) {
    const sessionIsFinal = session.status !== 'pending';

    return (
        <Card
            className={
                highlighted
                    ? 'gap-0 overflow-hidden border-primary/25 p-0'
                    : 'gap-0 overflow-hidden border-border-subtle p-0 shadow-none'
            }
        >
            <CardHeader className="gap-3 p-4 sm:p-5">
                <div className="flex items-start justify-between gap-3">
                    <CardTitle className="text-section-title">
                        Sesión {session.session_number}
                    </CardTitle>
                    <Badge className={statusStyles[session.status]}>
                        <span
                            className="size-1.5 rounded-full bg-current"
                            aria-hidden="true"
                        />
                        {statusLabels[session.status]}
                    </Badge>
                </div>
                <dl className="grid gap-2 text-sm sm:grid-cols-2">
                    <div className="flex items-baseline justify-between gap-4 sm:block">
                        <dt className="text-muted-foreground">Fecha y hora</dt>
                        <dd className="text-right font-medium sm:mt-1 sm:text-left">
                            {session.scheduled_at
                                ? dateTimeFormatter.format(
                                      new Date(session.scheduled_at),
                                  )
                                : 'Sin programar'}
                        </dd>
                    </div>
                    <div className="flex items-baseline justify-between gap-4 sm:block">
                        <dt className="text-muted-foreground">Precio</dt>
                        <dd className="font-medium tabular-nums sm:mt-1">
                            {money(session.price, session.currency)}
                        </dd>
                    </div>
                </dl>
            </CardHeader>

            <details className="group border-t border-border-subtle">
                <summary className="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 px-4 py-2 text-sm font-semibold text-primary focus-visible:ring-2 focus-visible:ring-focus focus-visible:outline-none sm:px-5">
                    {canOperate ? 'Gestionar sesión' : 'Ver detalle'}
                    <ChevronDownIcon
                        className="size-4 shrink-0 transition-transform group-open:rotate-180"
                        aria-hidden="true"
                    />
                </summary>
                <CardContent className="border-t border-border-subtle px-4 py-4 sm:px-5">
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
                                            message={errors.scheduled_at}
                                        />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor={`price_${session.id}`}>
                                            Precio de la sesión
                                        </Label>
                                        <Input
                                            id={`price_${session.id}`}
                                            name="price"
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            defaultValue={session.price}
                                            aria-invalid={Boolean(errors.price)}
                                        />
                                        <InputError message={errors.price} />
                                    </div>
                                    <input
                                        type="hidden"
                                        name="currency"
                                        value={session.currency}
                                    />
                                    <div className="grid gap-2">
                                        <Label htmlFor={`status_${session.id}`}>
                                            Estado
                                        </Label>
                                        {sessionIsFinal ? (
                                            <>
                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value={session.status}
                                                />
                                                <p className="flex min-h-11 items-center rounded-xl border bg-muted/40 px-3 text-sm">
                                                    {
                                                        statusLabels[
                                                            session.status
                                                        ]
                                                    }{' '}
                                                    (final)
                                                </p>
                                            </>
                                        ) : (
                                            <select
                                                id={`status_${session.id}`}
                                                name="status"
                                                defaultValue={session.status}
                                                className="h-11 rounded-xl border bg-input px-3 shadow-xs focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
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
                                        <InputError message={errors.status} />
                                        <InputError message={errors.currency} />
                                    </div>
                                    <div className="grid gap-2 sm:col-span-2">
                                        <Label htmlFor={`notes_${session.id}`}>
                                            Notas de la sesión
                                        </Label>
                                        <textarea
                                            id={`notes_${session.id}`}
                                            name="notes"
                                            defaultValue={session.notes ?? ''}
                                            className="min-h-24 rounded-xl border bg-input p-3 shadow-xs focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                        />
                                        <InputError message={errors.notes} />
                                    </div>
                                    <div className="sm:col-span-2">
                                        {sessionIsFinal && (
                                            <p className="mb-3 text-sm text-muted-foreground">
                                                El estado es final. Podés
                                                corregir fecha, precio o notas
                                                mientras el tratamiento siga
                                                activo.
                                            </p>
                                        )}
                                        <Button disabled={processing}>
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
                                <dd className="mt-1 font-medium">
                                    {session.scheduled_at
                                        ? dateTimeFormatter.format(
                                              new Date(session.scheduled_at),
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
                                <dt className="text-muted-foreground">Notas</dt>
                                <dd className="mt-1 whitespace-pre-wrap">
                                    {session.notes || 'Sin notas.'}
                                </dd>
                            </div>
                        </dl>
                    )}
                </CardContent>
                {session.status === 'completed' && (
                    <CardFooter className="border-t border-border-subtle px-4 py-4 sm:px-5">
                        <div className="w-full">
                            <p className="text-sm text-muted-foreground">
                                Opcional: documentá la evolución clínica sin
                                crear ni vincular registros automáticamente.
                            </p>
                            <Button
                                asChild
                                variant="outline"
                                size="sm"
                                className="mt-3"
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
                    </CardFooter>
                )}
            </details>
        </Card>
    );
}
