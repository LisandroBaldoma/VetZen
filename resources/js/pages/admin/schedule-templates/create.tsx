import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import ScheduleDateForm from '@/components/schedule-date-form';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index, store } from '@/routes/admin/schedule-templates';

type Service = {
    id: number;
    name: string;
    appointment_duration_minutes: number;
};

export default function ScheduleTemplateCreate({
    services,
}: {
    services: Service[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Plantillas de agenda', href: index() },
            { title: 'Nueva plantilla', href: create() },
        ],
    });

    return (
        <>
            <Head title="Nueva plantilla de agenda" />
            <div className="mx-auto max-w-3xl space-y-6 p-4 md:p-6">
                <Heading
                    title="Nueva plantilla"
                    description="Guardá una configuración reutilizable para publicar fechas de agenda más rápido."
                />
                <ScheduleDateForm
                    mode="template"
                    services={services}
                    action={store().url}
                    method="post"
                    submitLabel="Crear plantilla"
                />
                <Button variant="outline" asChild>
                    <Link href={index()}>Cancelar</Link>
                </Button>
            </div>
        </>
    );
}
