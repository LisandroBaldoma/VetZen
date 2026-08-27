import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Pet } from '@/types';

type Props = {
    pet?: Pet;
    errors: Record<string, string | undefined>;
    processing: boolean;
    submitLabel: string;
    clients?: { id: number; user: { name: string; email: string } }[];
};

export default function PetFormFields({
    pet,
    errors,
    processing,
    submitLabel,
    clients,
}: Props) {
    return (
        <>
            {clients && (
                <div className="grid gap-2">
                    <Label htmlFor="client_id">Client</Label>
                    <select
                        id="client_id"
                        name="client_id"
                        required
                        className="h-9 rounded-md border bg-transparent px-3 text-sm"
                    >
                        <option value="">Select a client</option>
                        {clients.map((client) => (
                            <option key={client.id} value={client.id}>
                                {client.user.name} · {client.user.email}
                            </option>
                        ))}
                    </select>
                    <InputError message={errors.client_id} />
                </div>
            )}
            <div className="grid gap-4 sm:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        defaultValue={pet?.name ?? ''}
                    />
                    <InputError message={errors.name} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="species">Species</Label>
                    <Input
                        id="species"
                        name="species"
                        required
                        defaultValue={pet?.species ?? ''}
                    />
                    <InputError message={errors.species} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="breed">Breed</Label>
                    <Input
                        id="breed"
                        name="breed"
                        defaultValue={pet?.breed ?? ''}
                    />
                    <InputError message={errors.breed} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="sex">Sex</Label>
                    <Input
                        id="sex"
                        name="sex"
                        required
                        defaultValue={pet?.sex ?? ''}
                    />
                    <InputError message={errors.sex} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="birth_date">Date of birth</Label>
                    <Input
                        id="birth_date"
                        name="birth_date"
                        type="date"
                        defaultValue={pet?.birth_date ?? ''}
                    />
                    <InputError message={errors.birth_date} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="weight">Weight</Label>
                    <Input
                        id="weight"
                        name="weight"
                        type="number"
                        min="0"
                        step="0.01"
                        defaultValue={pet?.weight ?? ''}
                    />
                    <InputError message={errors.weight} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="color">Color</Label>
                    <Input
                        id="color"
                        name="color"
                        defaultValue={pet?.color ?? ''}
                    />
                    <InputError message={errors.color} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="photo">Photo</Label>
                    <Input
                        id="photo"
                        name="photo"
                        type="file"
                        accept="image/*"
                    />
                    <InputError message={errors.photo} />
                </div>
            </div>
            <div className="grid gap-2">
                <Label htmlFor="notes">Notes</Label>
                <textarea
                    id="notes"
                    name="notes"
                    defaultValue={pet?.notes ?? ''}
                    className="min-h-24 rounded-md border bg-transparent px-3 py-2 text-sm"
                />
                <InputError message={errors.notes} />
            </div>
            <Button disabled={processing}>{submitLabel}</Button>
        </>
    );
}
