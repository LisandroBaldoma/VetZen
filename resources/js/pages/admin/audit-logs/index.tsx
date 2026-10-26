import { Head, router } from '@inertiajs/react';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/admin/audit-logs';

type AuditLog = {
    id: number;
    action: string;
    resource: string;
    user: string;
    old_values: Record<string, string> | null;
    new_values: Record<string, string> | null;
    created_at: string;
};

export default function AuditLogsIndex({
    logs,
    filters,
    actions,
}: {
    logs: { data: AuditLog[] };
    filters: { action: string };
    actions: string[];
}) {
    return (
        <AppLayout breadcrumbs={[{ title: 'Auditoría', href: index().url }]}>
            <Head title="Auditoría" />
            <div className="space-y-6 p-4 md:p-8">
                <div>
                    <h1 className="text-2xl font-bold">Auditoría</h1>
                    <p className="text-sm text-muted-foreground">
                        Acciones operativas y de seguridad registradas por
                        VetZen.
                    </p>
                </div>
                <Select
                    value={filters.action || 'all'}
                    onValueChange={(action) =>
                        router.get(
                            index().url,
                            action === 'all' ? {} : { action },
                            { preserveState: true },
                        )
                    }
                >
                    <SelectTrigger className="w-full md:w-80">
                        <SelectValue placeholder="Todas las acciones" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">Todas las acciones</SelectItem>
                        {actions.map((action) => (
                            <SelectItem key={action} value={action}>
                                {action}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
                <div className="overflow-hidden rounded-xl border border-border bg-surface-base">
                    {logs.data.length === 0 ? (
                        <p className="p-8 text-center text-sm text-muted-foreground">
                            No hay eventos registrados.
                        </p>
                    ) : (
                        logs.data.map((log) => (
                            <div
                                key={log.id}
                                className="border-b border-border p-4 last:border-0"
                            >
                                <div className="flex flex-wrap justify-between gap-2">
                                    <p className="font-medium">{log.action}</p>
                                    <time className="text-sm text-muted-foreground">
                                        {new Date(
                                            log.created_at,
                                        ).toLocaleString('es-AR')}
                                    </time>
                                </div>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    {log.resource} · {log.user}
                                </p>
                                {log.old_values && log.new_values && (
                                    <p className="mt-2 text-sm">
                                        Estado: {log.old_values.status} →{' '}
                                        {log.new_values.status}
                                    </p>
                                )}
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
