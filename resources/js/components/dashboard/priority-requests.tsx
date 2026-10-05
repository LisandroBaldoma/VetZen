import { Link } from '@inertiajs/react';
import type { InertiaLinkProps } from '@inertiajs/react';
import { ArrowRight, Clock3, PawPrint, Stethoscope } from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import type { ReactNode } from 'react';
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

export type DashboardActivity = {
    id: number;
    title: string;
    subtitle: string;
    status: string;
    statusTone?: 'operational' | 'clinical' | 'muted';
    detail?: string | null;
    progress?: { value: number; max: number; label: string };
    timestamp?: string;
    icon?: LucideIcon;
    primaryAction: { label: string; href: InertiaLinkProps['href'] };
    secondaryAction?: { label: string; href: InertiaLinkProps['href'] };
};

export function DashboardActivityList({
    title,
    badge,
    items,
    emptyState,
    allHref,
    headingId = 'dashboard-activity-title',
    compact = false,
}: {
    title: string;
    badge?: string;
    items: DashboardActivity[];
    emptyState: { title: string; description: string; action?: ReactNode };
    allHref?: InertiaLinkProps['href'];
    headingId?: string;
    compact?: boolean;
}) {
    return (
        <section aria-labelledby={headingId} className="space-y-4">
            <div className="flex flex-wrap items-center gap-3">
                <h2
                    id={headingId}
                    className="text-xl font-semibold tracking-tight text-balance"
                >
                    {title}
                </h2>
                {badge && (
                    <span className="rounded-full bg-surface-subtle px-2.5 py-1 text-xs font-bold text-primary">
                        {badge}
                    </span>
                )}
                {allHref && (
                    <Button
                        asChild
                        variant="outline"
                        size="sm"
                        className="ml-auto"
                    >
                        <Link href={allHref}>Ver todas</Link>
                    </Button>
                )}
            </div>
            {items.length === 0 ? (
                <EmptyState
                    icon={<PawPrint className="size-7" aria-hidden="true" />}
                    title={emptyState.title}
                    description={emptyState.description}
                    action={emptyState.action}
                />
            ) : (
                <div
                    className={
                        compact ? 'grid gap-3' : 'grid gap-4 xl:grid-cols-2'
                    }
                >
                    {items.map((item) => {
                        const Icon = item.icon ?? PawPrint;
                        const statusTone =
                            item.statusTone === 'clinical'
                                ? 'bg-clinical text-clinical-foreground'
                                : item.statusTone === 'muted'
                                  ? 'bg-muted text-muted-foreground'
                                  : 'bg-operational/20 text-operational-foreground';

                        return (
                            <article
                                key={item.id}
                                className={`flex flex-col rounded-xl border bg-card shadow-sm ${compact ? 'gap-3 p-4' : 'gap-5 rounded-2xl p-5'}`}
                            >
                                <div className="flex items-start gap-4">
                                    <div
                                        className={`flex shrink-0 items-center justify-center bg-surface-subtle text-primary ${compact ? 'size-11 rounded-xl' : 'size-16 rounded-2xl'}`}
                                    >
                                        <Icon
                                            className={
                                                compact ? 'size-5' : 'size-7'
                                            }
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <div className="flex flex-wrap items-start justify-between gap-2">
                                            <div>
                                                <h3 className="font-semibold tracking-tight">
                                                    {item.title}
                                                </h3>
                                                <p className="text-sm text-muted-foreground">
                                                    {item.subtitle}
                                                </p>
                                            </div>
                                            <span
                                                className={`rounded-full px-2.5 py-1 text-xs font-semibold ${statusTone}`}
                                            >
                                                {item.status}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                {item.detail && (
                                    <p className="rounded-xl bg-surface-subtle p-3 text-sm leading-6 text-muted-foreground">
                                        {item.detail}
                                    </p>
                                )}
                                {item.progress && (
                                    <div className="space-y-2">
                                        <p className="text-sm text-muted-foreground">
                                            {item.progress.label}
                                        </p>
                                        <div
                                            role="progressbar"
                                            aria-label={`Progreso de ${item.title}`}
                                            aria-valuemin={0}
                                            aria-valuemax={item.progress.max}
                                            aria-valuenow={Math.min(
                                                item.progress.value,
                                                item.progress.max,
                                            )}
                                            aria-valuetext={item.progress.label}
                                            className="h-2 overflow-hidden rounded-full bg-muted"
                                        >
                                            <div
                                                className="h-full rounded-full bg-primary transition-[width]"
                                                style={{
                                                    width: `${item.progress.max > 0 ? Math.min(100, Math.round((item.progress.value / item.progress.max) * 100)) : 0}%`,
                                                }}
                                            />
                                        </div>
                                    </div>
                                )}
                                {item.timestamp && (
                                    <p className="flex items-center gap-1.5 text-xs font-medium text-muted-foreground">
                                        <Clock3
                                            className="size-3.5"
                                            aria-hidden="true"
                                        />
                                        {item.timestamp}
                                    </p>
                                )}
                                <div className="flex flex-wrap justify-end gap-2">
                                    {item.secondaryAction && (
                                        <Button
                                            asChild
                                            variant="outline"
                                            size="sm"
                                        >
                                            <Link
                                                href={item.secondaryAction.href}
                                            >
                                                {item.secondaryAction.label}
                                            </Link>
                                        </Button>
                                    )}
                                    <Button asChild size="sm">
                                        <Link href={item.primaryAction.href}>
                                            {item.primaryAction.label}
                                            <ArrowRight aria-hidden="true" />
                                        </Link>
                                    </Button>
                                </div>
                            </article>
                        );
                    })}
                </div>
            )}
        </section>
    );
}

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
        <DashboardActivityList
            title="Solicitudes prioritarias"
            badge={`${pendingRequestsCount} pendientes`}
            allHref={requestsHref}
            emptyState={{
                title: 'Bandeja de triage al día',
                description:
                    'No hay solicitudes de atención pendientes de revisión en este momento.',
            }}
            items={requests.map((request) => ({
                id: request.id,
                title: request.petName,
                subtitle: request.petDetails,
                status: 'Pendiente',
                detail: request.notes,
                timestamp: request.receivedAt,
                icon: Stethoscope,
                secondaryAction: {
                    label: `Ficha de ${request.petName}`,
                    href: request.petHref,
                },
                primaryAction: {
                    label: 'Ver solicitud',
                    href: request.requestHref,
                },
            }))}
        />
    );
}
