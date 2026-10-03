import { Link } from '@inertiajs/react';
import type { InertiaLinkProps } from '@inertiajs/react';
import { ArrowRight, Clock3, PawPrint, Stethoscope } from 'lucide-react';
import { EmptyState } from '@/components/empty-state';
import { Button } from '@/components/ui/button';

export type PriorityRequest = {
    id: number;
    petName: string;
    petDetails: string;
    service: string;
    notes: string | null;
    receivedAt: string;
    petHref: InertiaLinkProps['href'];
    requestHref: InertiaLinkProps['href'];
};

export function PriorityRequests({
    pendingRequestsCount,
    requests,
    requestsHref,
}: {
    pendingRequestsCount: number;
    requests: PriorityRequest[];
    requestsHref: InertiaLinkProps['href'];
}) {
    return (
        <section
            aria-labelledby="priority-requests-title"
            className="space-y-4"
        >
            <div className="flex flex-wrap items-center gap-3">
                <h2
                    id="priority-requests-title"
                    className="text-xl font-semibold tracking-tight text-balance"
                >
                    Solicitudes prioritarias
                </h2>
                <span className="rounded-full bg-surface-subtle px-2.5 py-1 text-xs font-bold text-primary">
                    {pendingRequestsCount} pendientes
                </span>
                <Button asChild variant="outline" size="sm" className="ml-auto">
                    <Link href={requestsHref}>Ver todas</Link>
                </Button>
            </div>
            {requests.length === 0 ? (
                <EmptyState
                    icon={<PawPrint className="size-7" aria-hidden="true" />}
                    title="Bandeja de triage al día"
                    description="No hay solicitudes de atención pendientes de revisión en este momento."
                />
            ) : (
                <div className="grid gap-4 xl:grid-cols-2">
                    {requests.map((request) => (
                        <article
                            key={request.id}
                            className="flex flex-col gap-5 rounded-2xl border bg-card p-5 shadow-sm"
                        >
                            <div className="flex items-start gap-4">
                                <div className="flex size-16 shrink-0 items-center justify-center rounded-2xl bg-surface-subtle text-primary">
                                    <PawPrint
                                        className="size-7"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <div className="flex flex-wrap items-start justify-between gap-2">
                                        <div>
                                            <h3 className="font-semibold tracking-tight">
                                                {request.petName}
                                            </h3>
                                            <p className="text-sm text-muted-foreground">
                                                {request.petDetails}
                                            </p>
                                        </div>
                                        <span className="rounded-full bg-operational/20 px-2.5 py-1 text-xs font-semibold text-operational-foreground">
                                            Pendiente
                                        </span>
                                    </div>
                                    <p className="mt-3 flex items-center gap-2 text-sm font-semibold text-primary">
                                        <Stethoscope
                                            className="size-4"
                                            aria-hidden="true"
                                        />
                                        {request.service}
                                    </p>
                                </div>
                            </div>
                            {request.notes && (
                                <p className="rounded-xl bg-surface-subtle p-3 text-sm leading-6 text-muted-foreground">
                                    {request.notes}
                                </p>
                            )}
                            <p className="flex items-center gap-1.5 text-xs font-medium text-muted-foreground">
                                <Clock3
                                    className="size-3.5"
                                    aria-hidden="true"
                                />
                                {request.receivedAt}
                            </p>
                            <div className="flex flex-wrap justify-end gap-2">
                                <Button asChild variant="outline" size="sm">
                                    <Link href={request.petHref}>
                                        Ficha de {request.petName}
                                    </Link>
                                </Button>
                                <Button asChild size="sm">
                                    <Link href={request.requestHref}>
                                        Ver solicitud
                                        <ArrowRight aria-hidden="true" />
                                    </Link>
                                </Button>
                            </div>
                        </article>
                    ))}
                </div>
            )}
        </section>
    );
}
