import { Form } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

type Props = {
    form: { action: string; method: 'post' };
    isActive: boolean;
    subject: string;
};

export default function CatalogStatusForm({ form, isActive, subject }: Props) {
    const action = isActive ? 'desactivar' : 'activar';

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
                        variant="outline"
                        disabled={processing}
                        aria-label={`${isActive ? 'Desactivar' : 'Activar'} ${subject}`}
                    >
                        {processing
                            ? 'Guardando…'
                            : isActive
                              ? 'Desactivar'
                              : 'Activar'}
                    </Button>
                </>
            )}
        </Form>
    );
}
