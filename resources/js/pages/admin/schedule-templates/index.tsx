import { Head, Link, router } from '@inertiajs/react';
import { CopyPlus, Pencil, Trash2 } from 'lucide-react';
import { useState } from 'react';
import ConfirmActionDialog from '@/components/confirm-action-dialog';
import PageHeader from '@/components/page-header';
import type { ScheduleTemplate } from '@/components/schedule-date-form';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import {
    create,
    destroy,
    edit,
    index,
} from '@/routes/admin/schedule-templates';

function time(value: string) {
    return value.slice(0, 5);
}

export default function ScheduleTemplatesIndex({
    templates,
}: {
    templates: ScheduleTemplate[];
}) {
    const [templateToDelete, setTemplateToDelete] =
        useState<ScheduleTemplate | null>(null);
    const [deleting, setDeleting] = useState(false);

    const deleteTemplate = () => {
        if (!templateToDelete) {
            return;
        }

        setDeleting(true);
        router.delete(destroy(templateToDelete.id).url, {
            onFinish: () => setDeleting(false),
            onSuccess: () => setTemplateToDelete(null),
        });
    };

    return (
        <>
            <Head title="Plantillas de agenda" />
            <div className="workspace-operational">
                <PageHeader
                    title="Plantillas de agenda"
                    description="Configuraciones globales reutilizables para publicar fechas. No cambian fechas ya publicadas."
                    actions={
                        <Button asChild>
                            <Link href={create()}>
                                <CopyPlus aria-hidden /> Nueva plantilla
                            </Link>
                        </Button>
                    }
                />
                {templates.length === 0 ? (
                    <section className="rounded-xl border border-dashed bg-card p-6 text-sm text-muted-foreground">
                        <p>
                            Todavía no hay plantillas. Creá una para reutilizar
                            horarios, capacidad y servicios al publicar fechas.
                        </p>
                        <Button className="mt-4" asChild>
                            <Link href={create()}>
                                Crear la primera plantilla
                            </Link>
                        </Button>
                    </section>
                ) : (
                    <div className="grid gap-3">
                        {templates.map((template) => (
                            <article
                                key={template.id}
                                className="rounded-xl border bg-card p-4 shadow-sm sm:p-5"
                            >
                                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div className="min-w-0 space-y-3">
                                        <div>
                                            <h2 className="font-semibold">
                                                {template.name}
                                            </h2>
                                            <p className="text-sm text-muted-foreground">
                                                Capacidad {template.capacity} ·
                                                intervalo de{' '}
                                                {template.interval_minutes} min
                                            </p>
                                        </div>
                                        <p className="text-sm">
                                            <span className="font-medium">
                                                Bloques:
                                            </span>{' '}
                                            {template.blocks
                                                .map(
                                                    (block) =>
                                                        `${time(block.starts_at)}–${time(block.ends_at)}`,
                                                )
                                                .join(' · ')}
                                        </p>
                                        <p className="text-sm">
                                            <span className="font-medium">
                                                Servicios:
                                            </span>{' '}
                                            {template.services
                                                .map((service) => service.name)
                                                .join(', ')}
                                        </p>
                                    </div>
                                    <div className="flex flex-wrap gap-2">
                                        <Button variant="outline" asChild>
                                            <Link href={edit(template.id)}>
                                                <Pencil aria-hidden /> Editar
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            onClick={() =>
                                                setTemplateToDelete(template)
                                            }
                                        >
                                            <Trash2 aria-hidden /> Eliminar
                                        </Button>
                                    </div>
                                </div>
                            </article>
                        ))}
                    </div>
                )}
            </div>
            <ConfirmActionDialog
                open={templateToDelete !== null}
                onOpenChange={(open) => !open && setTemplateToDelete(null)}
                onConfirm={deleteTemplate}
                title="Eliminar plantilla"
                description={`Eliminarás “${templateToDelete?.name ?? ''}”. Las fechas ya publicadas no se modificarán.`}
                confirmLabel="Eliminar plantilla"
                pendingLabel="Eliminando…"
                pending={deleting}
                destructive
            />
        </>
    );
}

ScheduleTemplatesIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Plantillas de agenda', href: index() },
    ],
};
