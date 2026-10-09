import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import ScheduleDateForm from '@/components/schedule-date-form';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index, store } from '@/routes/admin/schedule-dates';

type Service = {
    id: number;
    name: string;
    appointment_duration_minutes: number;
};

export default function ScheduleDateCreate({
    services,
}: {
    services: Service[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Agenda', href: index() },
            { title: 'Publicar fecha', href: '#' },
        ],
    });

    return (
        <>
            <Head title="Publicar fecha de agenda" />
            <div className="mx-auto max-w-3xl space-y-6 p-4 md:p-6">
                <Heading
                    title="Publicar fecha"
                    description="Definí los bloques de trabajo, inicios y servicios disponibles."
                />
                <ScheduleDateForm
                    services={services}
                    action={store().url}
                    method="post"
                    multipleDates
                    submitLabel="Publicar fecha"
                />
                <Button variant="outline" asChild>
                    <Link href={index()}>Cancelar</Link>
                </Button>
            </div>
        </>
    );
}
