import { TimerIcon } from 'lucide-react';
import { cn } from '@/lib/utils';

type Props = {
    completedSessions: number;
    plannedSessions: number;
    className?: string;
    compact?: boolean;
    showRemaining?: boolean;
};

export default function TreatmentProgress({
    completedSessions,
    plannedSessions,
    className,
    compact = false,
    showRemaining = false,
}: Props) {
    const remainingSessions = Math.max(0, plannedSessions - completedSessions);
    const percentage =
        plannedSessions > 0
            ? Math.min(
                  100,
                  Math.round((completedSessions / plannedSessions) * 100),
              )
            : 0;

    const progressBar = (
        <div
            className="mt-3 h-2 overflow-hidden rounded-full bg-muted"
            role="progressbar"
            aria-valuemin={0}
            aria-valuemax={plannedSessions}
            aria-valuenow={Math.min(completedSessions, plannedSessions)}
            aria-valuetext={`${completedSessions} de ${plannedSessions} sesiones completadas`}
        >
            <div
                className="h-full rounded-full bg-primary transition-[width] duration-200 motion-reduce:transition-none"
                style={{ width: `${percentage}%` }}
            />
        </div>
    );

    if (compact) {
        return (
            <section
                className={cn('rounded-lg bg-surface-subtle p-3', className)}
                aria-label="Progreso terapeutico"
            >
                <div className="flex items-center justify-between gap-3">
                    <p className="text-sm font-semibold text-foreground tabular-nums">
                        {completedSessions} de {plannedSessions} sesiones
                        completadas
                    </p>
                    <span className="shrink-0 text-sm font-semibold text-primary tabular-nums">
                        {percentage}%
                    </span>
                </div>
                {progressBar}
            </section>
        );
    }

    return (
        <section
            className={cn(
                'rounded-xl border border-border-subtle bg-surface-base p-4 shadow-sm',
                className,
            )}
            aria-label="Progreso terapeutico"
        >
            <div className="flex items-start justify-between gap-4">
                <div className="space-y-1">
                    <p className="text-meta font-semibold tracking-[0.14em] text-muted-foreground uppercase">
                        Progreso terapeutico
                    </p>
                    <p className="text-section-title font-semibold text-foreground tabular-nums">
                        {completedSessions} de {plannedSessions} sesiones
                        completadas
                    </p>
                </div>
                <span className="text-section-title font-semibold text-primary tabular-nums">
                    {percentage}%
                </span>
            </div>
            {progressBar}
            {showRemaining && remainingSessions > 0 && (
                <div className="mt-2.5 flex items-center gap-1.5 text-sm font-medium text-muted-foreground">
                    <TimerIcon aria-hidden className="size-4 text-primary" />
                    {remainingSessions} sesiones requeridas por completar
                </div>
            )}
        </section>
    );
}
