import { Link } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';
import {
    clinicalRecordTypeLabel,
    formatClinicalDate,
} from '@/lib/clinical-records';
import type { ClinicalRecordSummary as ClinicalRecordSummaryData } from '@/types';

const typeStyles: Record<string, string> = {
    consultation: 'bg-info',
    evaluation: 'bg-operational-foreground',
    evolution: 'bg-clinical-foreground',
    session: 'bg-primary',
    other: 'bg-muted-foreground',
};

export default function ClinicalRecordSummary({
    record,
    href,
}: {
    record: ClinicalRecordSummaryData;
    href: string;
}) {
    const hasHistoricalVisibility = record.is_visible_to_client !== undefined;

    return (
        <article className="group relative">
            <Link
                href={href}
                className="grid gap-3 py-6 transition-colors hover:bg-clinical/30 focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none sm:grid-cols-[8.5rem_minmax(0,1fr)_auto] sm:items-start sm:gap-6 sm:px-4"
            >
                <div className="space-y-1 text-sm text-muted-foreground">
                    <p className="font-semibold tracking-tight text-foreground">
                        <time dateTime={record.occurred_at}>
                            {formatClinicalDate(record.occurred_at)}
                        </time>
                    </p>
                    <p className="text-xs font-semibold tracking-[0.12em] uppercase">
                        Registro clínico
                    </p>
                </div>
                <div className="min-w-0 space-y-2">
                    <div className="flex flex-wrap items-center gap-2">
                        <span className="inline-flex items-center gap-2 text-xs font-semibold tracking-[0.1em] text-muted-foreground uppercase">
                            <span
                                className={`size-2 rounded-full ${typeStyles[record.type] ?? typeStyles.other}`}
                                aria-hidden="true"
                            />
                            {clinicalRecordTypeLabel(record.type)}
                        </span>
                        {hasHistoricalVisibility && (
                            <span className="text-xs text-muted-foreground">
                                {record.is_visible_to_client
                                    ? 'Visible (histórico)'
                                    : 'No visible (histórico)'}
                            </span>
                        )}
                    </div>
                    <h3 className="text-lg font-semibold tracking-tight text-foreground underline-offset-4 group-hover:underline">
                        {record.title}
                    </h3>
                    {record.creator && (
                        <p className="text-sm text-muted-foreground">
                            Registrado por {record.creator.name}
                        </p>
                    )}
                </div>
                <ArrowUpRight
                    className="size-4 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                    aria-hidden="true"
                />
            </Link>
        </article>
    );
}
