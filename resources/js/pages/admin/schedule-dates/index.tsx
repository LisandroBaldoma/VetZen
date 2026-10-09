import { Head, Link } from '@inertiajs/react';
import { CalendarPlus, Pencil } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, edit, index } from '@/routes/admin/schedule-dates';

type ScheduleDate = {
    id: number;
    date: string;
    capacity: number;
    blocks: Array<{ id: number; starts_at: string; ends_at: string }>;
    starts: Array<{ id: number; starts_at: string }>;
    services: Array<{ id: number; name: string }>;
};

function time(value: string): string {
    return value.slice(0, 5);
}

export default function ScheduleDatesIndex({
    scheduleDates,
}: {
    scheduleDates: ScheduleDate[];
}) {
    return (
        <>
            <Head title="Agenda" />
            <div className="workspace-operational">
                <PageHeader
                    title="Agenda"
                    description="Publicá fechas concretas, horarios disponibles y servicios para reservar."
                    actions={
                        <Button asChild>
                            <Link href={create()}>
                                <CalendarPlus aria-hidden /> Publicar fecha
                            </Link>
                        </Button>
                    }
                />
                {scheduleDates.length === 0 ? (
                    <section className="rounded-xl border border-dashed bg-card p-6 text-sm text-muted-foreground">
                        <p>Todavía no publicaste fechas para la agenda.</p>
                        <Button className="mt-4" asChild>
                            <Link href={create()}>
                                Publicar la primera fecha
                            </Link>
                        </Button>
                    </section>
                ) : (
                    <div className="grid gap-3">
                        {scheduleDates.map((scheduleDate) => (
                            <article
                                key={scheduleDate.id}
                                className="rounded-xl border bg-card p-4 shadow-sm sm:p-5"
                            >
                                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div className="min-w-0 space-y-3">
                                        <div>
                                            <h2 className="font-semibold">
                                                {scheduleDate.date}
                                            </h2>
                                            <p className="text-sm text-muted-foreground">
                                                Capacidad simultánea:{' '}
                                                {scheduleDate.capacity}{' '}
                                                pacientes
                                            </p>
                                        </div>
                                        <p className="text-sm">
                                            <span className="font-medium">
                                                Bloques:
                                            </span>{' '}
                                            {scheduleDate.blocks
                                                .map(
                                                    (block) =>
                                                        `${time(block.starts_at)}–${time(block.ends_at)}`,
                                                )
                                                .join(' · ')}
                                        </p>
                                        <p className="text-sm">
                                            <span className="font-medium">
                                                Inicios:
                                            </span>{' '}
                                            {scheduleDate.starts
                                                .map((start) =>
                                                    time(start.starts_at),
                                                )
                                                .join(', ')}
                                        </p>
                                        <p className="text-sm">
                                            <span className="font-medium">
                                                Servicios:
                                            </span>{' '}
                                            {scheduleDate.services
                                                .map((service) => service.name)
                                                .join(', ')}
                                        </p>
                                    </div>
                                    <Button variant="outline" asChild>
                                        <Link href={edit(scheduleDate.id)}>
                                            <Pencil aria-hidden /> Editar
                                        </Link>
                                    </Button>
                                </div>
                            </article>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

ScheduleDatesIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Agenda', href: index() },
    ],
};
