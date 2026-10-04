import { Link } from '@inertiajs/react';
import {
    ArrowRight,
    ClipboardList,
    HeartPulse,
    Stethoscope,
    TrendingUp,
    Pencil,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    clinicalRecordTypeLabel,
    formatClinicalDate,
} from '@/lib/clinical-records';
import type { ClinicalRecordSummary } from '@/types';

type Props = {
    records: ClinicalRecordSummary[];
    recordHref: (record: ClinicalRecordSummary) => string;
    editHref?: (record: ClinicalRecordSummary) => string;
};

const typeStyles: Record<
    string,
    {
        icon: typeof Stethoscope;
        iconClassName: string;
        badgeClassName: string;
    }
> = {
    consultation: {
        icon: Stethoscope,
        iconClassName: 'bg-surface-subtle text-primary',
        badgeClassName: 'bg-surface-subtle text-primary',
    },
    evaluation: {
        icon: ClipboardList,
        iconClassName: 'bg-destructive/10 text-destructive',
        badgeClassName: 'bg-destructive/10 text-destructive',
    },
    evolution: {
        icon: TrendingUp,
        iconClassName: 'bg-operational/15 text-operational-foreground',
        badgeClassName: 'bg-operational/15 text-operational-foreground',
    },
    session: {
        icon: HeartPulse,
        iconClassName: 'bg-clinical/50 text-clinical-foreground',
        badgeClassName: 'bg-clinical/50 text-clinical-foreground',
    },
    other: {
        icon: ClipboardList,
        iconClassName: 'bg-muted text-muted-foreground',
        badgeClassName: 'bg-muted text-muted-foreground',
    },
};

export function ClinicalRecordsTimeline({
    records,
    recordHref,
    editHref,
}: Props) {
    return (
        <ol className="relative flex flex-col pt-2 before:absolute before:top-6 before:bottom-6 before:left-[13px] before:w-0.5 before:bg-border">
            {records.map((record) => {
                const style = typeStyles[record.type] ?? typeStyles.other;
                const Icon = style.icon;

                return (
                    <li
                        key={record.id}
                        className="relative flex items-start gap-3.5 pb-6 last:pb-0"
                    >
                        <span
                            className={`z-10 mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full shadow-sm ${style.iconClassName}`}
                        >
                            <Icon aria-hidden className="size-3.5" />
                        </span>
                        <article className="group flex min-w-0 flex-1 flex-col gap-2 rounded-xl border bg-card p-4 shadow-sm transition-[color,box-shadow,transform] hover:bg-surface-subtle hover:shadow-md">
                            <div className="flex flex-wrap items-center gap-2">
                                <time
                                    dateTime={record.occurred_at}
                                    className="text-xs font-bold tracking-[0.1em] text-muted-foreground uppercase"
                                >
                                    {formatClinicalDate(record.occurred_at)}
                                </time>
                                <span
                                    className={`rounded-full px-2 py-0.5 text-xs font-semibold ${style.badgeClassName}`}
                                >
                                    {clinicalRecordTypeLabel(record.type)}
                                </span>
                            </div>
                            <h3 className="mt-2 font-semibold tracking-tight text-foreground group-hover:underline">
                                {record.title}
                            </h3>
                            {(record.creator ||
                                record.is_visible_to_client !== undefined) && (
                                <div className="text-sm text-muted-foreground">
                                    {record.creator && (
                                        <span>
                                            Registrado por {record.creator.name}
                                        </span>
                                    )}
                                    {record.creator &&
                                        record.is_visible_to_client !==
                                            undefined &&
                                        ' · '}
                                    {record.is_visible_to_client !==
                                        undefined && (
                                        <span>
                                            {record.is_visible_to_client
                                                ? 'Visible (histórico)'
                                                : 'No visible (histórico)'}
                                        </span>
                                    )}
                                </div>
                            )}
                            <div className="mt-1 flex items-center justify-between gap-2">
                                <Button
                                    variant="secondary"
                                    asChild
                                    className="min-h-11"
                                >
                                    <Link href={recordHref(record)}>
                                        Ver registro
                                        <ArrowRight
                                            aria-hidden
                                            className="size-4"
                                        />
                                    </Link>
                                </Button>
                                {editHref && (
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        asChild
                                        className="size-11"
                                    >
                                        <Link
                                            href={editHref(record)}
                                            aria-label={`Editar ${record.title}`}
                                        >
                                            <Pencil
                                                aria-hidden
                                                className="size-4"
                                            />
                                        </Link>
                                    </Button>
                                )}
                            </div>
                        </article>
                    </li>
                );
            })}
        </ol>
    );
}
