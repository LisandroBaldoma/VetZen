import { Badge } from '@/components/ui/badge';
import { cn } from '@/lib/utils';

export type TreatmentStatus =
    'pending' | 'in_progress' | 'completed' | 'suspended' | 'cancelled';

const statusLabels: Record<TreatmentStatus, string> = {
    pending: 'Pendiente',
    in_progress: 'En curso',
    completed: 'Completado',
    suspended: 'Suspendido',
    cancelled: 'Cancelado',
};

const statusStyles: Record<TreatmentStatus, string> = {
    pending: 'border-transparent bg-operational text-operational-foreground',
    in_progress: 'border-transparent bg-clinical text-clinical-foreground',
    completed: 'border-transparent bg-clinical text-clinical-foreground',
    suspended: 'border-transparent bg-muted text-muted-foreground',
    cancelled: 'border-destructive/20 bg-destructive/10 text-destructive',
};

type Props = {
    status: TreatmentStatus;
    className?: string;
};

export default function TreatmentStatusBadge({ status, className }: Props) {
    return (
        <Badge className={cn(statusStyles[status], className)}>
            <span
                className="size-1.5 rounded-full bg-current"
                aria-hidden="true"
            />
            {statusLabels[status]}
        </Badge>
    );
}
