import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';

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
    canOperate: boolean;
    onManage: (sessionId: number) => void;
    highlighted?: boolean;
};

export default function SessionRow({
    session,
    canOperate,
    onManage,
    highlighted = false,
}: Props) {
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
            <CardFooter className="border-t border-border-subtle px-4 py-3 sm:px-5">
                <Button
                    type="button"
                    variant="outline"
                    className="w-full sm:w-auto"
                    onClick={() => onManage(session.id)}
                >
                    {canOperate ? 'Gestionar sesión' : 'Ver detalle'}
                </Button>
            </CardFooter>
        </Card>
    );
}
