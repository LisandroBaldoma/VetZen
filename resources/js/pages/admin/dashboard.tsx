import { Head } from '@inertiajs/react';
import PageHeader from '@/components/page-header';
import { dashboard } from '@/routes';

export default function AdminDashboard() {
    return (
        <>
            <Head title="Inicio" />
            <div className="p-4">
                <PageHeader
                    title="Inicio"
                    description="Accedé a las principales áreas de gestión de VetZen."
                />
            </div>
        </>
    );
}

AdminDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Inicio',
            href: dashboard(),
        },
    ],
};
