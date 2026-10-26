import { Head, router } from '@inertiajs/react';
import { Bell, CheckCheck } from 'lucide-react';
import {
    read,
    readAll,
} from '@/actions/App/Http/Controllers/NotificationController';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';

type Notification = {
    id: string;
    type: string;
    data: {
        event: string;
        pet_name?: string;
        service_name?: string;
        treatment_name?: string;
    };
    read_at: string | null;
    created_at: string;
};

export default function NotificationsIndex({
    notifications,
}: {
    notifications: { data: Notification[] };
}) {
    const unread = notifications.data.some(
        (notification) => notification.read_at === null,
    );

    return (
        <AppLayout
            breadcrumbs={[{ title: 'Notificaciones', href: '/notifications' }]}
        >
            <Head title="Notificaciones" />
            <div className="mx-auto w-full max-w-3xl space-y-6 p-4 md:p-8">
                <div className="flex items-center justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-bold">Notificaciones</h1>
                        <p className="text-sm text-muted-foreground">
                            Avisos sobre tus turnos y tratamientos.
                        </p>
                    </div>
                    {unread && (
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={() => router.patch(readAll().url)}
                        >
                            <CheckCheck /> Marcar todas como leídas
                        </Button>
                    )}
                </div>
                <div className="overflow-hidden rounded-xl border border-border bg-surface-base">
                    {notifications.data.length === 0 ? (
                        <p className="p-8 text-center text-sm text-muted-foreground">
                            No tenés notificaciones.
                        </p>
                    ) : (
                        notifications.data.map((notification) => (
                            <button
                                key={notification.id}
                                type="button"
                                onClick={() =>
                                    !notification.read_at &&
                                    router.patch(read(notification.id).url)
                                }
                                className="flex w-full items-start gap-3 border-b border-border p-4 text-left last:border-0 hover:bg-surface-subtle"
                            >
                                <Bell className="mt-0.5 size-5 shrink-0 text-primary" />
                                <span className="min-w-0 flex-1">
                                    <span className="block font-medium">
                                        {notification.data.treatment_name ??
                                            notification.data.service_name ??
                                            'Actualización'}
                                    </span>
                                    <span className="block text-sm text-muted-foreground">
                                        {notification.data.pet_name
                                            ? `${notification.data.pet_name}: `
                                            : ''}
                                        {notification.data.event}
                                    </span>
                                    <time className="mt-1 block text-xs text-muted-foreground">
                                        {new Date(
                                            notification.created_at,
                                        ).toLocaleString('es-AR')}
                                    </time>
                                </span>
                                {!notification.read_at && (
                                    <span
                                        className="mt-2 size-2 shrink-0 rounded-full bg-primary"
                                        aria-label="No leída"
                                    />
                                )}
                            </button>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
