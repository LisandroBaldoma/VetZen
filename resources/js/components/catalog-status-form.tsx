import { Form } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

type Props = {
    form: { action: string; method: 'post' };
    isActive: boolean;
    subject: string;
};

export default function CatalogStatusForm({ form, isActive, subject }: Props) {
    const action = isActive ? 'desactivar' : 'activar';
    const accessibleSubject = subject.replace(/^el /, '');

    return (
        <Form
            {...form}
            onBefore={() =>
                window.confirm(`¿Confirmás que querés ${action} ${subject}?`)
            }
        >
            {({ processing }) => (
                <>
                    <input
                        type="hidden"
                        name="is_active"
                        value={isActive ? '0' : '1'}
                    />
                    <Button
                        type="submit"
                        size="sm"
                        variant="ghost"
                        className="min-h-9 gap-2 px-2 text-xs font-semibold"
                        disabled={processing}
                        aria-label={`${isActive ? 'Desactivar' : 'Activar'} ${accessibleSubject}`}
                    >
                        <span
                            className={`size-1.5 rounded-full ${isActive ? 'bg-clinical-foreground' : 'bg-muted-foreground'}`}
                            aria-hidden="true"
                        />
                        {processing
                            ? 'Guardando…'
                            : isActive
                              ? 'Activo'
                              : 'Inactivo'}
                    </Button>
                </>
            )}
        </Form>
    );
}
