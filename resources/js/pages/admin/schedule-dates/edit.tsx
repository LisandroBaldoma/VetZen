import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import ScheduleDateForm from '@/components/schedule-date-form';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index, update } from '@/routes/admin/schedule-dates';

type ScheduleDate = {
    id: number;
    date: string;
    capacity: number;
    blocks: Array<{ starts_at: string; ends_at: string }>;
    starts: Array<{ starts_at: string }>;
    services: Array<{ id: number }>;
};
type Service = {
    id: number;
    name: string;
    appointment_duration_minutes: number;
};

export default function ScheduleDateEdit({
    scheduleDate,
    services,
}: {
    scheduleDate: ScheduleDate;
    services: Service[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Agenda', href: index() },
            { title: scheduleDate.date, href: edit(scheduleDate.id) },
        ],
    });

    return (
        <>
            <Head title={`Agenda ${scheduleDate.date}`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4 md:p-6">
                <Heading
                    title={`Agenda del ${scheduleDate.date}`}
                    description="Los cambios futuros no podrán invalidar reservas pendientes o aprobadas."
                />
                <ScheduleDateForm
                    scheduleDate={scheduleDate}
                    services={services}
                    action={update(scheduleDate.id).url}
                    method="patch"
                    submitLabel="Guardar agenda"
                />
                <Button variant="outline" asChild>
                    <Link href={index()}>Volver a agenda</Link>
                </Button>
            </div>
        </>
    );
}
