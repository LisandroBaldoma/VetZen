import { Head, router } from '@inertiajs/react';
import { KeyRound, ShieldCheck, UserRoundCheck } from 'lucide-react';
import { useState } from 'react';
import ConfirmActionDialog from '@/components/confirm-action-dialog';
import InputError from '@/components/input-error';
import PageHeader from '@/components/page-header';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index } from '@/routes/admin/administrators';
import { update as updateRole } from '@/routes/admin/users/role';

type Administrator = {
    id: number;
    name: string;
    email: string;
    googleLinked: boolean;
    twoFactorEnabled: boolean;
    hasClientProfile: boolean;
    isCurrentUser: boolean;
    canDemote: boolean;
    createdAt: string;
};

export default function AdministratorsIndex({
    administrators,
}: {
    administrators: Administrator[];
}) {
    const [administratorToDemote, setAdministratorToDemote] =
        useState<Administrator | null>(null);
    const [demoting, setDemoting] = useState(false);
    const [roleError, setRoleError] = useState<string | null>(null);

    const demote = () => {
        if (!administratorToDemote) {
            return;
        }

        setDemoting(true);
        setRoleError(null);
        router.patch(
            updateRole(administratorToDemote.id).url,
            { role: 'client' },
            {
                onError: (errors) => setRoleError(errors.role ?? null),
                onSuccess: () => setAdministratorToDemote(null),
                onFinish: () => setDemoting(false),
            },
        );
    };

    return (
        <>
            <Head title="Administradores" />
            <div className="mx-auto w-full max-w-7xl space-y-6 p-4 md:p-6">
                <PageHeader
                    title="Administradores"
                    description="Revisá quién puede acceder a la gestión operativa y clínica de VetZen."
                />

                <div className="grid gap-3 md:hidden">
                    {administrators.map((administrator) => (
                        <article
                            key={administrator.id}
                            className="space-y-4 rounded-xl border bg-card p-4 shadow-sm"
                        >
                            <AdministratorIdentity
                                administrator={administrator}
                            />
                            <AdministratorAccess
                                administrator={administrator}
                            />
                            {administrator.canDemote && (
                                <Button
                                    type="button"
                                    variant="outline"
                                    className="w-full"
                                    onClick={() =>
                                        setAdministratorToDemote(administrator)
                                    }
                                >
                                    Quitar acceso administrativo
                                </Button>
                            )}
                        </article>
                    ))}
                </div>

                <div className="hidden overflow-x-auto rounded-xl border bg-card shadow-sm md:block">
                    <table className="w-full min-w-3xl text-left text-sm">
                        <thead className="bg-muted/60 text-xs font-medium tracking-wide text-muted-foreground uppercase">
                            <tr>
                                <th className="px-5 py-3 font-medium">
                                    Administrador
                                </th>
                                <th className="px-5 py-3 font-medium">
                                    Seguridad
                                </th>
                                <th className="px-5 py-3 font-medium">
                                    Perfil
                                </th>
                                <th className="px-5 py-3 text-right">
                                    <span className="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {administrators.map((administrator) => (
                                <tr
                                    key={administrator.id}
                                    className="border-t transition-colors hover:bg-muted/40"
                                >
                                    <td className="px-5 py-4">
                                        <AdministratorIdentity
                                            administrator={administrator}
                                        />
                                    </td>
                                    <td className="px-5 py-4">
                                        <AdministratorAccess
                                            administrator={administrator}
                                        />
                                    </td>
                                    <td className="px-5 py-4">
                                        <Badge variant="outline">
                                            {administrator.hasClientProfile
                                                ? 'Perfil de cliente conservado'
                                                : 'Administrador interno'}
                                        </Badge>
                                    </td>
                                    <td className="px-5 py-4 text-right">
                                        {administrator.canDemote && (
                                            <Button
                                                type="button"
                                                size="sm"
                                                variant="outline"
                                                onClick={() =>
                                                    setAdministratorToDemote(
                                                        administrator,
                                                    )
                                                }
                                            >
                                                Quitar acceso
                                            </Button>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <InputError message={roleError ?? undefined} />
            </div>

            <ConfirmActionDialog
                open={administratorToDemote !== null}
                onOpenChange={(open) => !open && setAdministratorToDemote(null)}
                onConfirm={demote}
                title="Quitar acceso administrativo"
                description={`Cambiarás a ${administratorToDemote?.name ?? ''} al rol cliente.`}
                content={
                    <p className="text-sm text-muted-foreground">
                        Perderá acceso a las áreas administrativas y volverá a
                        aparecer en Clientes. Su perfil y recursos se
                        conservarán.
                    </p>
                }
                confirmLabel="Quitar acceso"
                pendingLabel="Actualizando…"
                pending={demoting}
                destructive
            />
        </>
    );
}

function AdministratorIdentity({
    administrator,
}: {
    administrator: Administrator;
}) {
    return (
        <div className="min-w-0 space-y-1">
            <div className="flex flex-wrap items-center gap-2">
                <p className="font-semibold break-words">
                    {administrator.name}
                </p>
                {administrator.isCurrentUser && (
                    <Badge variant="secondary">Tu cuenta</Badge>
                )}
            </div>
            <p className="text-sm break-all text-muted-foreground">
                {administrator.email}
            </p>
        </div>
    );
}

function AdministratorAccess({
    administrator,
}: {
    administrator: Administrator;
}) {
    return (
        <div className="flex flex-wrap gap-2">
            <Badge
                variant={administrator.twoFactorEnabled ? 'default' : 'outline'}
            >
                <KeyRound aria-hidden />
                {administrator.twoFactorEnabled ? '2FA activo' : 'Sin 2FA'}
            </Badge>
            {administrator.googleLinked && (
                <Badge variant="outline">
                    <UserRoundCheck aria-hidden /> Google
                </Badge>
            )}
            <Badge variant="outline">
                <ShieldCheck aria-hidden /> Admin
            </Badge>
        </div>
    );
}

AdministratorsIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Administradores', href: index() },
    ],
};
