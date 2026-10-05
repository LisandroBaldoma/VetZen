import { CalendarDaysIcon, CircleCheckIcon } from 'lucide-react';
import type { ReactNode } from 'react';
import { Badge } from '@/components/ui/badge';
import { Card, CardHeader } from '@/components/ui/card';

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
    actions?: ReactNode;
    highlighted?: boolean;
    compact?: boolean;
    showNotes?: boolean;
};

export default function TreatmentSessionCard({
    session,
    actions,
    highlighted = false,
    compact = false,
    showNotes = false,
}: Props) {
    const content = (
        <>
            <div className="flex min-w-0 items-start gap-3">
                {compact && (
                    <div className="flex size-7 shrink-0 items-center justify-center rounded-full bg-clinical text-clinical-foreground">
                        <CircleCheckIcon aria-hidden className="size-4" />
                    </div>
                )}
                <div className="min-w-0 flex-1">
                    <div className="flex flex-wrap items-center gap-2">
                        <p className="font-semibold">
                            Sesión {session.session_number}
                        </p>
                        {!compact && (
                            <Badge className={statusStyles[session.status]}>
                                <span
                                    className="size-1.5 rounded-full bg-current"
                                    aria-hidden="true"
                                />
                                {statusLabels[session.status]}
                            </Badge>
                        )}
                    </div>
                    <p className="mt-1 flex items-center gap-1.5 text-sm text-muted-foreground tabular-nums">
                        <CalendarDaysIcon aria-hidden className="size-3.5" />
                        {session.scheduled_at
                            ? dateTimeFormatter.format(
                                  new Date(session.scheduled_at),
                              )
                            : 'Sin programar'}
                        <span aria-hidden>·</span>
                        {money(session.price, session.currency)}
                    </p>
                    {showNotes && (
                        <p className="mt-2 text-sm whitespace-pre-wrap text-muted-foreground">
                            {session.notes || 'Sin notas.'}
                        </p>
                    )}
                </div>
            </div>
            {actions}
        </>
    );

    if (compact) {
        return (
            <div className="flex flex-col gap-3 rounded-lg bg-surface-subtle p-3 sm:flex-row sm:items-center sm:justify-between">
                {content}
            </div>
        );
    }

    return (
        <Card
            className={
                highlighted
                    ? 'relative gap-0 overflow-hidden border-primary/30 p-0 shadow-md before:absolute before:inset-y-0 before:left-0 before:w-1 before:bg-primary'
                    : 'gap-0 overflow-hidden border-border-subtle p-0 shadow-sm'
            }
        >
            <CardHeader className="p-4 sm:p-5">
                <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    {content}
                </div>
            </CardHeader>
        </Card>
    );
}
