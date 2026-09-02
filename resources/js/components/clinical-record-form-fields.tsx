import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { clinicalRecordTypeLabel } from '@/lib/clinical-records';
import type { ClinicalRecordFormData } from '@/types';

type Props = {
    record?: ClinicalRecordFormData;
    types: string[];
    errors: Record<string, string | undefined>;
    processing: boolean;
    submitLabel: string;
    defaultType?: string | null;
};

function dateTimeLocal(value?: string): string {
    return value ? value.slice(0, 16) : '';
}

export default function ClinicalRecordFormFields({
    record,
    types,
    errors,
    processing,
    submitLabel,
    defaultType,
}: Props) {
    return (
        <>
            <div className="grid gap-4 sm:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="type">Tipo de registro</Label>
                    <select
                        id="type"
                        name="type"
                        required
                        aria-invalid={Boolean(errors.type)}
                        defaultValue={record?.type ?? defaultType ?? ''}
                        className="h-9 rounded-md border bg-transparent px-3 text-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                    >
                        <option value="">Seleccionar tipo</option>
                        {types.map((type) => (
                            <option key={type} value={type}>
                                {clinicalRecordTypeLabel(type)}
                            </option>
                        ))}
                    </select>
                    <InputError message={errors.type} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="occurred_at">Fecha clínica</Label>
                    <Input
                        id="occurred_at"
                        name="occurred_at"
                        type="datetime-local"
                        required
                        aria-invalid={Boolean(errors.occurred_at)}
                        max={dateTimeLocal(new Date().toISOString())}
                        defaultValue={dateTimeLocal(record?.occurred_at)}
                    />
                    <InputError message={errors.occurred_at} />
                </div>
            </div>

            <div className="grid gap-2">
                <Label htmlFor="title">Título</Label>
                <Input
                    id="title"
                    name="title"
                    required
                    maxLength={255}
                    aria-invalid={Boolean(errors.title)}
                    defaultValue={record?.title ?? ''}
                />
                <InputError message={errors.title} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="content">Contenido clínico</Label>
                <textarea
                    id="content"
                    name="content"
                    required
                    aria-invalid={Boolean(errors.content)}
                    defaultValue={record?.content ?? ''}
                    className="min-h-48 rounded-md border bg-transparent px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                />
                <InputError message={errors.content} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="is_visible_to_client">
                    Visibilidad histórica
                </Label>
                <select
                    id="is_visible_to_client"
                    name="is_visible_to_client"
                    required
                    aria-describedby="historical-visibility-help"
                    aria-invalid={Boolean(errors.is_visible_to_client)}
                    defaultValue={
                        record === undefined
                            ? ''
                            : record.is_visible_to_client
                              ? '1'
                              : '0'
                    }
                    className="h-9 rounded-md border bg-transparent px-3 text-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                >
                    <option value="">Seleccionar referencia</option>
                    <option value="1">Marcado visible</option>
                    <option value="0">Marcado no visible</option>
                </select>
                <p
                    id="historical-visibility-help"
                    className="text-sm text-muted-foreground"
                >
                    Este valor se conserva como referencia histórica y no
                    restringe la lectura del responsable, que puede consultar
                    toda la historia clínica de sus mascotas.
                </p>
                <InputError message={errors.is_visible_to_client} />
            </div>

            <Button disabled={processing}>
                {processing ? 'Guardando...' : submitLabel}
            </Button>
        </>
    );
}
