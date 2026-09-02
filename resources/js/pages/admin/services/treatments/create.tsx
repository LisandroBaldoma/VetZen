import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import TreatmentController from '@/actions/App/Http/Controllers/Admin/TreatmentController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { index as proceduresIndex } from '@/routes/admin/services/procedures';
import {
    create,
    index as serviceTemplatesIndex,
} from '@/routes/admin/services/treatments';
import { index as templatesIndex } from '@/routes/admin/treatments';

type Service = { id: number; name: string };
type Procedure = { id: number; name: string };
export default function TreatmentCreate({
    service,
    procedures,
}: {
    service: Service;
    procedures: Procedure[];
}) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'Inicio', href: dashboard() },
            { title: 'Plantillas', href: templatesIndex() },
            { title: service.name, href: serviceTemplatesIndex(service.id) },
            { title: 'Crear plantilla', href: create(service.id) },
        ],
    });

    return (
        <>
            <Head title="Crear plantilla" />
            <div className="mx-auto max-w-2xl space-y-6 p-4">
                <Heading
                    title="Crear plantilla"
                    description={`Servicio: ${service.name}. Definí sus procedimientos y sesiones estimadas.`}
                />
                {procedures.length === 0 ? (
                    <div className="space-y-4 rounded-xl border p-6">
                        <p className="text-sm text-muted-foreground">
                            Este servicio no tiene procedimientos activos. Creá
                            o activá uno antes de crear una plantilla.
                        </p>
                        <Button asChild>
                            <Link href={proceduresIndex(service.id)}>
                                Administrar procedimientos
                            </Link>
                        </Button>
                    </div>
                ) : (
                    <Form
                        {...TreatmentController.store.form(service.id)}
                        className="space-y-5 rounded-xl border p-6"
                    >
                        {({ errors, processing }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Nombre</Label>
                                    <Input id="name" name="name" required />
                                    <InputError message={errors.name} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="description">
                                        Descripción
                                    </Label>
                                    <textarea
                                        id="description"
                                        name="description"
                                        required
                                        className="min-h-28 rounded-md border p-3"
                                    />
                                    <InputError message={errors.description} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="estimated_sessions">
                                        Sesiones estimadas
                                    </Label>
                                    <Input
                                        id="estimated_sessions"
                                        name="estimated_sessions"
                                        type="number"
                                        min="1"
                                        required
                                    />
                                    <InputError
                                        message={errors.estimated_sessions}
                                    />
                                </div>
                                <fieldset className="grid gap-2">
                                    <legend className="font-medium">
                                        Procedimientos
                                    </legend>
                                    {procedures.map((p) => (
                                        <label
                                            key={p.id}
                                            className="flex gap-2"
                                        >
                                            <input
                                                type="checkbox"
                                                name="procedure_ids[]"
                                                value={p.id}
                                            />
                                            {p.name}
                                        </label>
                                    ))}
                                    <InputError
                                        message={errors.procedure_ids}
                                    />
                                </fieldset>
                                <input
                                    type="hidden"
                                    name="is_active"
                                    value="1"
                                />
                                <Button disabled={processing}>
                                    Guardar plantilla
                                </Button>
                            </>
                        )}
                    </Form>
                )}
            </div>
        </>
    );
}
