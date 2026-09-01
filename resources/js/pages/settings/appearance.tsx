import { Head } from '@inertiajs/react';
import AppearanceTabs from '@/components/appearance-tabs';
import Heading from '@/components/heading';
import { dashboard } from '@/routes';
import { edit as editAppearance } from '@/routes/appearance';

export default function Appearance() {
    return (
        <>
            <Head title="Apariencia" />

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Apariencia"
                    description="Elegí cómo querés ver tu cuenta."
                />
                <AppearanceTabs />
            </div>
        </>
    );
}

Appearance.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        {
            title: 'Cuenta',
            href: editAppearance(),
        },
    ],
};
