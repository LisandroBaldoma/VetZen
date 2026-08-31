import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { index, show } from '@/routes/admin/service-requests';
import type { Paginated, ServiceRequest } from '@/types';
export default function AdminRequests({
    requests,
    status,
}: {
    requests: Paginated<ServiceRequest>;
    status: string;
}) {
    return (
        <>
            <Head title="Solicitudes" />
            <div className="space-y-6 p-4">
                <Heading
                    title="Solicitudes de servicios"
                    description="Evaluación y asignación profesional."
                />
                <div className="flex gap-2">
                    {['', 'pending', 'resolved', 'cancelled'].map((s) => (
                        <Button
                            key={s}
                            size="sm"
                            variant={status === s ? 'default' : 'outline'}
                            asChild
                        >
                            <Link
                                href={index({ query: s ? { status: s } : {} })}
                            >
                                {s || 'Todas'}
                            </Link>
                        </Button>
                    ))}
                </div>
                {requests.data.length === 0 ? (
                    <p className="rounded-xl border p-6">No hay solicitudes.</p>
                ) : (
                    <div className="space-y-3">
                        {requests.data.map((r) => (
                            <Link
                                key={r.id}
                                href={show(r.id)}
                                className="flex items-center justify-between rounded-xl border p-4 hover:bg-muted/50"
                            >
                                <div>
                                    <p className="font-medium">
                                        {r.pet?.name} — {r.service?.name}
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        {r.notes || 'Sin nota'}
                                    </p>
                                </div>
                                <Badge variant="outline">{r.status}</Badge>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
