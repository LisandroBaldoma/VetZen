import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import { dashboard } from '@/routes';

export default function AdminDashboard() {
    return (
        <>
            <Head title="Administration" />
            <div className="p-4">
                <Heading
                    title="Administration"
                    description="Manage the information available in VetZen."
                />
            </div>
        </>
    );
}

AdminDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
