import { Form, Head } from '@inertiajs/react';
import ClientProfileController from '@/actions/App/Http/Controllers/Client/ClientProfileController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Client } from '@/types';

type Props = {
    client: Client;
};

export default function ClientProfile({ client }: Props) {
    return (
        <>
            <Head title="Client profile" />

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Client profile"
                    description="Manage your contact and personal information"
                />

                <Form
                    {...ClientProfileController.update.form(client.id)}
                    options={{ preserveScroll: true }}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="phone">Phone</Label>
                                <Input
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    required
                                    autoComplete="tel"
                                    defaultValue={client.phone}
                                />
                                <InputError message={errors.phone} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="address">Address</Label>
                                <Input
                                    id="address"
                                    name="address"
                                    autoComplete="street-address"
                                    defaultValue={client.address ?? ''}
                                />
                                <InputError message={errors.address} />
                            </div>

                            <div className="grid gap-4 sm:grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="city">City</Label>
                                    <Input id="city" name="city" defaultValue={client.city ?? ''} />
                                    <InputError message={errors.city} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="province">Province</Label>
                                    <Input id="province" name="province" defaultValue={client.province ?? ''} />
                                    <InputError message={errors.province} />
                                </div>
                            </div>

                            <div className="grid gap-4 sm:grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="postal_code">Postal code</Label>
                                    <Input id="postal_code" name="postal_code" autoComplete="postal-code" defaultValue={client.postal_code ?? ''} />
                                    <InputError message={errors.postal_code} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="document">Document</Label>
                                    <Input id="document" name="document" defaultValue={client.document ?? ''} />
                                    <InputError message={errors.document} />
                                </div>
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="birth_date">Date of birth</Label>
                                <Input id="birth_date" name="birth_date" type="date" defaultValue={client.birth_date ?? ''} />
                                <InputError message={errors.birth_date} />
                            </div>

                            <Button disabled={processing} data-test="update-client-profile-button">
                                Save client profile
                            </Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
