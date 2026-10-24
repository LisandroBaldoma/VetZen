import { Form, Head } from '@inertiajs/react';
import { ArrowRight, LockKeyhole, Mail } from 'lucide-react';
import AuthSeparator from '@/components/auth-separator';
import GoogleAuthButton from '@/components/google-auth-button';
import InputError from '@/components/input-error';
import PasskeyVerify from '@/components/passkey-verify';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { redirect as googleRedirect } from '@/routes/google';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

type Props = {
    status?: string;
    googleError?: string;
    canResetPassword: boolean;
};

export default function Login({
    status,
    googleError,
    canResetPassword,
}: Props) {
    return (
        <>
            <Head title="Iniciar sesión" />

            <div className="grid gap-2">
                <GoogleAuthButton href={googleRedirect.url()} />
                <PasskeyVerify showSeparator={false} />
            </div>

            <AuthSeparator />

            <Form {...store.form()} resetOnSuccess={['password']}>
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-5">
                            <div className="grid gap-2">
                                <Label htmlFor="email">
                                    Correo electrónico
                                </Label>
                                <div className="relative">
                                    <Mail
                                        aria-hidden
                                        className="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-muted-foreground"
                                    />
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        required
                                        autoFocus
                                        tabIndex={1}
                                        autoComplete="email"
                                        placeholder="correo@ejemplo.com"
                                        className="h-12 bg-surface-subtle pr-4 pl-10 focus-visible:bg-card"
                                    />
                                </div>
                                <InputError message={errors.email} />
                            </div>

                            <div className="grid gap-2">
                                <div className="flex items-center">
                                    <Label htmlFor="password">Contraseña</Label>
                                    {canResetPassword && (
                                        <TextLink
                                            href={request()}
                                            className="ml-auto text-sm"
                                            tabIndex={5}
                                        >
                                            ¿Olvidaste tu contraseña?
                                        </TextLink>
                                    )}
                                </div>
                                <div className="relative">
                                    <LockKeyhole
                                        aria-hidden
                                        className="pointer-events-none absolute top-1/2 left-3.5 z-10 size-4 -translate-y-1/2 text-muted-foreground"
                                    />
                                    <PasswordInput
                                        id="password"
                                        name="password"
                                        required
                                        tabIndex={2}
                                        autoComplete="current-password"
                                        placeholder="Ingresá tu contraseña"
                                        className="h-12 bg-surface-subtle pr-10 pl-10 focus-visible:bg-card"
                                    />
                                </div>
                                <InputError message={errors.password} />
                            </div>

                            <div className="flex items-center gap-2.5 pt-1">
                                <Checkbox
                                    id="remember"
                                    name="remember"
                                    tabIndex={3}
                                />
                                <Label htmlFor="remember">Recordarme</Label>
                            </div>

                            <Button
                                type="submit"
                                className="mt-2 h-12 w-full"
                                tabIndex={4}
                                disabled={processing}
                                data-test="login-button"
                            >
                                {processing && <Spinner />}
                                Ingresar
                                {!processing && <ArrowRight aria-hidden />}
                            </Button>
                        </div>

                        <div className="text-center text-sm text-muted-foreground">
                            ¿No tenés una cuenta?{' '}
                            <TextLink href={register()} tabIndex={5}>
                                Registrate
                            </TextLink>
                        </div>
                    </>
                )}
            </Form>

            {status && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            {googleError && (
                <div className="mb-4 text-center text-sm font-medium text-destructive">
                    {googleError}
                </div>
            )}
        </>
    );
}

Login.layout = {
    title: 'Iniciá sesión en tu cuenta',
    description:
        'Ingresá tu correo electrónico y contraseña para iniciar sesión',
};
