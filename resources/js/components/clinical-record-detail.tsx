import { Link } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    clinicalRecordTypeLabel,
    formatClinicalDate,
} from '@/lib/clinical-records';
import type { ClinicalRecordDetail as ClinicalRecordDetailData } from '@/types';

export default function ClinicalRecordDetail({
    record,
    editHref,
    showAdminMetadata = false,
}: {
    record: ClinicalRecordDetailData;
    editHref?: string;
    showAdminMetadata?: boolean;
}) {
    return (
        <article className="space-y-5 rounded-xl border p-6">
            <div className="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 className="text-xl font-semibold">{record.title}</h2>
                    <p className="text-sm text-muted-foreground">
                        Fecha clínica: {formatClinicalDate(record.occurred_at)}
                    </p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <Badge variant="secondary">
                        {clinicalRecordTypeLabel(record.type)}
                    </Badge>
                    {showAdminMetadata && (
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
            </div>

            <p className="whitespace-pre-wrap">{record.content}</p>

            {showAdminMetadata && (
                <div className="space-y-3 border-t pt-4">
                    <p className="text-sm text-muted-foreground">
                        La visibilidad se conserva como referencia histórica. El
                        responsable puede consultar toda la historia clínica de
                        sus mascotas.
                    </p>
                    <dl className="grid gap-3 text-sm sm:grid-cols-2">
                        <div>
                            <dt className="text-muted-foreground">
                                Registrado por
                            </dt>
                            <dd>{record.creator?.name ?? 'No informado'}</dd>
                        </div>
                        <div>
                            <dt className="text-muted-foreground">
                                Última actualización por
                            </dt>
                            <dd>{record.updater?.name ?? 'No informado'}</dd>
                        </div>
                    </dl>
                    {editHref && (
                        <Button asChild>
                            <Link href={editHref}>Editar registro</Link>
                        </Button>
                    )}
                </div>
            )}
        </article>
    );
}
