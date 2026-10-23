import { Form, Head } from '@inertiajs/react';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/google/registration';

type Props = {
    name: string;
    email: string;
    passwordRules: string;
};

export default function CompleteGoogleRegistration({
    name,
    email,
    passwordRules,
}: Props) {
    return (
        <>
            <Head title="Completá tu registro" />

            <div className="mb-6 rounded-lg border bg-muted/40 p-4 text-sm">
                <p className="font-medium text-foreground">{name}</p>
                <p className="text-muted-foreground">{email}</p>
            </div>

            <Form
                {...store.form()}
                resetOnSuccess={['password', 'password_confirmation']}
                disableWhileProcessing
                className="flex flex-col gap-6"
            >
                {({ processing, errors }) => (
                    <div className="grid gap-6">
                        <div className="grid gap-2">
                            <Label htmlFor="phone">Teléfono</Label>
                            <Input
                                id="phone"
                                type="tel"
                                required
                                autoFocus
                                autoComplete="tel"
                                name="phone"
                                placeholder="Tu número de teléfono"
                            />
                            <InputError message={errors.phone} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password">Contraseña</Label>
                            <PasswordInput
                                id="password"
                                required
                                autoComplete="new-password"
                                name="password"
                                placeholder="Contraseña"
                                passwordrules={passwordRules}
                            />
                            <InputError message={errors.password} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password_confirmation">
                                Confirmá tu contraseña
                            </Label>
                            <PasswordInput
                                id="password_confirmation"
                                required
                                autoComplete="new-password"
                                name="password_confirmation"
                                placeholder="Confirmá tu contraseña"
                                passwordrules={passwordRules}
                            />
                            <InputError
                                message={errors.password_confirmation}
                            />
                        </div>

                        <Button type="submit" className="w-full">
                            {processing && <Spinner />}
                            Crear cuenta
                        </Button>
                    </div>
                )}
            </Form>
        </>
    );
}

CompleteGoogleRegistration.layout = {
    title: 'Completá tu cuenta',
    description:
        'Google confirmó tu identidad. Agregá los datos necesarios para usar VetZen.',
};
