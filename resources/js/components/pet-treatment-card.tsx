import { Link } from '@inertiajs/react';
import { ArrowRightIcon, CalendarDaysIcon } from 'lucide-react';
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

export type PetTreatmentSummary = {
    id: number;
    treatment_name: string;
    planned_sessions: number;
    completed_sessions_count: number;
    status: TreatmentStatus;
    starts_on: string | null;
    next_session?: {
        scheduled_at: string;
        session_number: number;
    } | null;
};

const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    dateStyle: 'long',
});

export default function PetTreatmentCard({
    treatment,
    href,
    timezone,
}: {
    treatment: PetTreatmentSummary;
    href: string;
    timezone?: string;
}) {
    return (
        <Card className="gap-0 overflow-hidden border-border-subtle p-0 shadow-sm">
            <CardHeader className="gap-3 p-4 pb-3 sm:p-5 sm:pb-3">
                <div className="flex items-start justify-between gap-3">
                    <CardTitle className="min-w-0 text-section-title leading-snug break-words">
                        {treatment.treatment_name}
                    </CardTitle>
                    <TreatmentStatusBadge
                        status={treatment.status}
                        className="shrink-0"
                    />
                </div>
            </CardHeader>
            <CardContent className="px-4 pb-3 sm:px-5">
                <TreatmentProgress
                    completedSessions={treatment.completed_sessions_count}
                    plannedSessions={treatment.planned_sessions}
                    compact
                />
            </CardContent>
            <CardFooter className="justify-between gap-3 px-4 pt-0 pb-3 sm:px-5 sm:pb-4">
                {treatment.next_session ? (
                    <p className="flex min-w-0 items-center gap-1.5 text-xs font-medium text-muted-foreground tabular-nums">
                        <CalendarDaysIcon
                            aria-hidden
                            className="size-3.5 shrink-0"
                        />
                        <span className="truncate">
                            Próxima ·{' '}
                            {new Intl.DateTimeFormat('es-AR', {
                                dateStyle: 'medium',
                                timeStyle: 'short',
                                timeZone: timezone,
                            }).format(
                                new Date(treatment.next_session.scheduled_at),
                            )}
                        </span>
                    </p>
                ) : treatment.starts_on ? (
                    <p className="flex min-w-0 items-center gap-1.5 text-xs font-medium text-muted-foreground tabular-nums">
                        <CalendarDaysIcon
                            aria-hidden
                            className="size-3.5 shrink-0"
                        />
                        <span className="truncate">
                            Inicio ·{' '}
                            {dateFormatter.format(
                                new Date(
                                    `${treatment.starts_on.slice(0, 10)}T00:00:00`,
                                ),
                            )}
                        </span>
                    </p>
                ) : (
                    <span />
                )}
                <Button
                    asChild
                    variant="ghost"
                    className="shrink-0 px-2 text-primary hover:text-primary"
                >
                    <Link href={href}>
                        Ver tratamiento
                        <ArrowRightIcon aria-hidden="true" />
                    </Link>
                </Button>
            </CardFooter>
        </Card>
    );
}
