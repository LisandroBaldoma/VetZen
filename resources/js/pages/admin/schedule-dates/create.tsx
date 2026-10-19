import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import ScheduleDateForm from '@/components/schedule-date-form';
import type { ScheduleTemplate } from '@/components/schedule-date-form';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index, store } from '@/routes/admin/schedule-dates';
import { index as templatesIndex } from '@/routes/admin/schedule-templates';

type Service = {
    id: number;
    name: string;
    appointment_duration_minutes: number;
};

export default function ScheduleDateCreate({
    services,
    templates = [],
}: {
    services: Service[];
    templates?: ScheduleTemplate[];
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
                    description="Publicá fechas manuales o por rango. Podés reutilizar una plantilla o crear una configuración puntual."
                />
                <div className="rounded-xl border bg-muted/30 p-4 text-sm text-muted-foreground">
                    <p className="font-medium text-foreground">
                        ¿Publicás horarios recurrentes?
                    </p>
                    <p className="mt-1">
                        Creá una plantilla para reutilizar bloques, capacidad,
                        intervalo y servicios en futuras publicaciones.
                    </p>
                    <Button className="mt-3" variant="outline" asChild>
                        <Link href={templatesIndex()}>
                            Administrar plantillas
                        </Link>
                    </Button>
                </div>
                <ScheduleDateForm
                    services={services}
                    templates={templates}
                    action={store().url}
                    method="post"
                    submitLabel="Publicar fecha"
                />
                <Button variant="outline" asChild>
                    <Link href={index()}>Cancelar</Link>
                </Button>
            </div>
        </>
    );
}
