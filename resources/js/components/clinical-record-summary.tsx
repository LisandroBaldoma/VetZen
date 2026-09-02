import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import {
    clinicalRecordTypeLabel,
    formatClinicalDate,
} from '@/lib/clinical-records';
import type { ClinicalRecordSummary as ClinicalRecordSummaryData } from '@/types';

export default function ClinicalRecordSummary({
    record,
    href,
}: {
    record: ClinicalRecordSummaryData;
    href: string;
}) {
    const hasHistoricalVisibility = record.is_visible_to_client !== undefined;

    return (
        <article>
            <Link
                href={href}
                className="group flex min-h-28 items-center justify-between gap-4 rounded-xl border bg-card p-5 transition-colors hover:bg-accent/50 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none md:min-h-24 md:rounded-none md:border-x-0 md:border-t-0 md:bg-transparent md:px-0"
            >
                <div className="min-w-0 space-y-2">
                    <div className="flex flex-wrap items-center gap-2 text-sm text-muted-foreground">
                        <time dateTime={record.occurred_at}>
                            {formatClinicalDate(record.occurred_at)}
                        </time>
                        <span aria-hidden="true">·</span>
                        <Badge variant="secondary">
                            {clinicalRecordTypeLabel(record.type)}
                        </Badge>
                        {hasHistoricalVisibility && (
                            <Badge
                                variant={
                                    record.is_visible_to_client
                                        ? 'default'
                                        : 'outline'
                                }
                            >
                                {record.is_visible_to_client
                                    ? 'Marcado visible (histórico)'
                                    : 'Marcado no visible (histórico)'}
                            </Badge>
                        )}
                    </div>
                    <h3 className="font-semibold text-foreground group-hover:underline">
                        {record.title}
                    </h3>
                    {record.creator && (
                        <p className="text-sm text-muted-foreground">
                            Registrado por {record.creator.name}
                        </p>
                    )}
                </div>
                <ChevronRight
                    className="size-5 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-1"
                    aria-hidden="true"
                />
            </Link>
        </article>
    );
}
