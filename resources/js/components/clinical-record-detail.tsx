import { Link } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { ClinicalRecord } from '@/types';

export default function ClinicalRecordDetail({
    record,
    editHref,
}: {
    record: ClinicalRecord;
    editHref?: string;
}) {
    return (
        <article className="space-y-5 rounded-xl border p-6">
            <div className="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 className="text-xl font-semibold">{record.title}</h2>
                    <p className="text-sm text-muted-foreground">
                        Occurred {new Date(record.occurred_at).toLocaleString()}
                    </p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <Badge variant="secondary">{record.type}</Badge>
                    {editHref && (
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

            <p className="whitespace-pre-wrap">{record.content}</p>

            {editHref && (
                <div className="space-y-3 border-t pt-4">
                    <dl className="grid gap-3 text-sm sm:grid-cols-2">
                        <div>
                            <dt className="text-muted-foreground">
                                Created by
                            </dt>
                            <dd>{record.creator?.name ?? '—'}</dd>
                        </div>
                        <div>
                            <dt className="text-muted-foreground">
                                Updated by
                            </dt>
                            <dd>{record.updater?.name ?? '—'}</dd>
                        </div>
                    </dl>
                    <Button asChild>
                        <Link href={editHref}>Edit record</Link>
                    </Button>
                </div>
            )}
        </article>
    );
}
