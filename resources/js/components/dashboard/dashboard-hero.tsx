import { Activity, CalendarCheck2, Stethoscope } from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import type { ReactNode } from 'react';
import PageHeader from '@/components/page-header';

type MetricTone = 'operational' | 'clinical' | 'primary';

export type DashboardMetric = {
    label: string;
    value: string;
    status: string;
    description: string;
    icon: LucideIcon;
    tone: MetricTone;
};

const supportingMetrics: DashboardMetric[] = [
    {
        label: 'Terapias hoy',
        value: '12',
        status: 'En cronograma',
        description: '8 completadas y 4 programadas para el turno tarde.',
        icon: CalendarCheck2,
        tone: 'clinical',
    },
    {
        label: 'Adherencia médica',
        value: '98.4%',
        status: 'Óptima',
        description:
            'Cumplimiento activo de protocolos terapéuticos semanales.',
        icon: Stethoscope,
        tone: 'primary',
    },
];

const toneClasses: Record<MetricTone, { icon: string; status: string }> = {
    operational: {
        icon: 'bg-operational text-operational-foreground',
        status: 'bg-operational/20 text-operational-foreground',
    },
    clinical: {
        icon: 'bg-clinical text-clinical-foreground',
        status: 'bg-clinical text-clinical-foreground',
    },
    primary: {
        icon: 'bg-primary/10 text-primary',
        status: 'bg-primary/10 text-primary',
    },
};

export function DashboardHero({
    pendingRequestsCount,
    action,
    metrics,
    description = 'Mesa de trabajo clínica para revisar la atención que requiere definición.',
    overviewTitle = 'Resumen clínico diario',
    overviewBadge = 'Panel operativo clínico',
    showSummary = true,
}: {
    pendingRequestsCount?: number;
    action: ReactNode;
    metrics?: DashboardMetric[];
    description?: string;
    overviewTitle?: string;
    overviewBadge?: string;
    showSummary?: boolean;
}) {
    const defaultMetrics: DashboardMetric[] = [
        {
            label: 'Solicitudes pendientes',
            value: (pendingRequestsCount ?? 0).toString(),
            status: 'Requieren revisión',
            description:
                'Pacientes esperando confirmación de triage y evaluación inicial.',
            icon: Activity,
            tone: 'operational',
        },
        ...supportingMetrics,
    ];

    return (
        <section
            aria-labelledby={
                showSummary ? 'dashboard-overview-title' : undefined
            }
            aria-label={showSummary ? undefined : 'Inicio'}
            className="space-y-4"
        >
            <PageHeader
                title="Inicio"
                description={description}
                actions={action}
            />
            {showSummary && (
                <>
                    <div className="flex flex-wrap items-center gap-2">
                        <h2
                            id="dashboard-overview-title"
                            className="text-xl font-semibold tracking-tight text-balance"
                        >
                            {overviewTitle}
                        </h2>
                        <span className="inline-flex items-center gap-2 rounded-full bg-surface-subtle px-3 py-1 text-xs font-semibold text-primary">
                            <span
                                className="size-1.5 rounded-full bg-primary"
                                aria-hidden="true"
                            />
                            {overviewBadge}
                        </span>
                    </div>
                    <div className="grid gap-4 md:grid-cols-3">
                        {(metrics ?? defaultMetrics).map((metric) => {
                            const Icon = metric.icon;
                            const tone = toneClasses[metric.tone];

                            return (
                                <article
                                    key={metric.label}
                                    className="flex min-h-48 flex-col justify-between rounded-2xl border bg-card p-5 shadow-sm"
                                >
                                    <div className="flex items-start justify-between gap-3">
                                        <div className="flex items-center gap-3">
                                            <div
                                                className={`flex size-12 shrink-0 items-center justify-center rounded-xl ${tone.icon}`}
                                            >
                                                <Icon
                                                    className="size-6"
                                                    aria-hidden="true"
                                                />
                                            </div>
                                            <div>
                                                <p className="text-3xl font-bold tracking-tight tabular-nums">
                                                    {metric.value}
                                                </p>
                                                <p className="text-sm font-semibold">
                                                    {metric.label}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            className={`rounded-full px-2.5 py-1 text-xs font-semibold ${tone.status}`}
                                        >
                                            {metric.status}
                                        </span>
                                    </div>
                                    <p className="mt-5 border-t border-border pt-3 text-sm leading-5 text-muted-foreground">
                                        {metric.description}
                                    </p>
                                </article>
                            );
                        })}
                    </div>
                </>
            )}
        </section>
    );
}
