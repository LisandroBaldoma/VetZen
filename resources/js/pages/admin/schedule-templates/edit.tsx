import { Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import ScheduleDateForm from '@/components/schedule-date-form';
import type { ScheduleTemplate } from '@/components/schedule-date-form';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { edit, index, update } from '@/routes/admin/schedule-templates';

type Service = {
    id: number;
    name: string;
    appointment_duration_minutes: number;
};

export default function ScheduleTemplateEdit({
    template,
    services,
}: {
    template: ScheduleTemplate;
    services: Service[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Plantillas de agenda', href: index() },
            { title: template.name, href: edit(template.id) },
        ],
    });

    return (
        <>
            <Head title={`Editar ${template.name}`} />
            <div className="mx-auto max-w-3xl space-y-6 p-4 md:p-6">
                <Heading
                    title={`Editar ${template.name}`}
                    description="Los cambios solo se aplican a publicaciones futuras; las fechas existentes no se modifican."
                />
                <ScheduleDateForm
                    mode="template"
                    template={template}
                    services={services}
                    action={update(template.id).url}
                    method="patch"
                    submitLabel="Guardar plantilla"
                />
                <Button variant="outline" asChild>
                    <Link href={index()}>Volver a plantillas</Link>
                </Button>
            </div>
        </>
    );
}
