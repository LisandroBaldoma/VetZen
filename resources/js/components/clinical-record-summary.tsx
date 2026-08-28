import { Link } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { ClinicalRecord } from '@/types';

export default function ClinicalRecordSummary({
    record,
    href,
    showVisibility = false,
}: {
    record: ClinicalRecord;
    href: string;
    showVisibility?: boolean;
}) {
    return (
        <article className="space-y-3 rounded-xl border p-5">
            <div className="flex flex-wrap items-start justify-between gap-3">
                <div className="space-y-1">
                    <h2 className="font-semibold">{record.title}</h2>
                    <p className="text-sm text-muted-foreground">
                        {new Date(record.occurred_at).toLocaleString()}
                    </p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <Badge variant="secondary">{record.type}</Badge>
                    {showVisibility && (
                        <Badge
                            variant={
                                record.is_visible_to_client
                                    ? 'default'
                                    : 'outline'
                            }
                        >
                            {record.is_visible_to_client
                                ? 'Client visible'
                                : 'Client hidden'}
                        </Badge>
                    )}
                </div>
            </div>
            <p className="line-clamp-3 text-sm whitespace-pre-wrap">
                {record.content}
            </p>
            <Button asChild variant="outline" size="sm">
                <Link href={href}>View record</Link>
            </Button>
        </article>
    );
}
