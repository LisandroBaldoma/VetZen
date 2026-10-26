import { Form, Head, router } from '@inertiajs/react';
import { ShieldCheck } from 'lucide-react';
import { useState } from 'react';
import ClientController from '@/actions/App/Http/Controllers/Admin/ClientController';
import ClientProfileFields from '@/components/client-profile-fields';
import ConfirmActionDialog from '@/components/confirm-action-dialog';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { update as updateRole } from '@/routes/admin/users/role';
import type { Client, User } from '@/types';

type Props = {
    client: Client & { user: Pick<User, 'name' | 'email'> };
    canPromote: boolean;
};

export default function AdminClientEdit({ client, canPromote }: Props) {
    const [confirmingPromotion, setConfirmingPromotion] = useState(false);
    const [promoting, setPromoting] = useState(false);
    const [roleError, setRoleError] = useState<string | null>(null);

    const promote = () => {
        setPromoting(true);
        setRoleError(null);
        router.patch(
            updateRole(client.user_id).url,
            { role: 'admin' },
            {
                onError: (errors) => setRoleError(errors.role ?? null),
                onFinish: () => setPromoting(false),
            },
        );
    };

    return (
        <>
            <Head title={`Cliente: ${client.user.name}`} />
            <div className="mx-auto w-full max-w-2xl space-y-6 p-4">
                <Heading
                    title={client.user.name}
                    description={client.user.email}
                />
                <Form
                    {...ClientController.update.form(client.id)}
                    options={{ preserveScroll: true }}
                    className="space-y-6 rounded-xl border p-6"
                >
                    {({ processing, errors }) => (
                        <ClientProfileFields
                            client={client}
                            errors={errors}
                            processing={processing}
                            submitLabel="Guardar datos del cliente"
                        />
                    )}
                </Form>

                {canPromote && (
                    <section className="space-y-4 rounded-xl border bg-card p-6">
                        <div className="flex items-start gap-3">
                            <div className="rounded-lg bg-primary/10 p-2 text-primary">
                                <ShieldCheck aria-hidden className="size-5" />
                            </div>
                            <div className="space-y-1">
                                <h2 className="font-semibold">
                                    Acceso administrativo
                                </h2>
                                <p className="text-sm text-muted-foreground">
                                    Permitirá administrar clientes, pacientes,
                                    historias clínicas, tratamientos y agenda.
                                    El perfil y los datos actuales se
                                    conservarán.
                                </p>
                            </div>
                        </div>
                        <Button
                            type="button"
                            onClick={() => setConfirmingPromotion(true)}
                        >
                            Convertir en administrador
                        </Button>
                        <InputError message={roleError ?? undefined} />
                    </section>
                )}
            </div>

            <ConfirmActionDialog
                open={confirmingPromotion}
                onOpenChange={setConfirmingPromotion}
                onConfirm={promote}
                title="Conceder acceso administrativo"
                description={`Convertirás a ${client.user.name} en administrador de VetZen.`}
                content={
                    <p className="text-sm text-muted-foreground">
                        Dejará de aparecer en Clientes y tendrá acceso a todas
                        las áreas administrativas. Sus mascotas y datos no se
                        eliminarán.
                    </p>
                }
                confirmLabel="Conceder acceso"
                pendingLabel="Concediendo…"
                pending={promoting}
            />
        </>
    );
}
