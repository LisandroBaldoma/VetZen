import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Service } from '@/types';

const modalities = [
    ['clinic', 'At the clinic'],
    ['online', 'Online'],
    ['home_visit', 'Home visit'],
] as const;

type Props = {
    service?: Service;
    errors: Record<string, string | undefined>;
    processing: boolean;
};

export default function ServiceFormFields({
    service,
    errors,
    processing,
}: Props) {
    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    required
                    defaultValue={service?.name ?? ''}
                />
                <InputError message={errors.name} />
            </div>
            <div className="grid gap-2">
                <Label htmlFor="description">Description</Label>
                <textarea
                    id="description"
                    name="description"
                    required
                    defaultValue={service?.description ?? ''}
                    className="min-h-32 rounded-md border bg-transparent px-3 py-2 text-sm"
                />
                <InputError message={errors.description} />
            </div>
            <div className="grid gap-4 sm:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="duration_minutes">
                        Approximate duration (minutes)
                    </Label>
                    <Input
                        id="duration_minutes"
                        name="duration_minutes"
                        type="number"
                        min="1"
                        defaultValue={service?.duration_minutes ?? ''}
                    />
                    <InputError message={errors.duration_minutes} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="price">Indicative base price</Label>
                    <Input
                        id="price"
                        name="price"
                        type="number"
                        min="0"
                        step="0.01"
                        defaultValue={service?.price ?? ''}
                    />
                    <InputError message={errors.price} />
                </div>
            </div>
            <input type="hidden" name="currency" value="ARS" />
            <fieldset className="grid gap-3">
                <legend className="text-sm font-medium">Modalities</legend>
                {modalities.map(([value, label]) => (
                    <div key={value} className="flex items-center gap-2">
                        <Checkbox
                            id={`modality-${value}`}
                            name="modalities[]"
                            value={value}
                            defaultChecked={service?.modalities.includes(value)}
                        />
                        <Label htmlFor={`modality-${value}`}>{label}</Label>
                    </div>
                ))}
                <InputError
                    message={errors.modalities ?? errors['modalities.0']}
                />
            </fieldset>
            <div className="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0" />
                <Checkbox
                    id="is_active"
                    name="is_active"
                    value="1"
                    defaultChecked={service?.is_active ?? true}
                />
                <Label htmlFor="is_active">Active in the client catalog</Label>
                <InputError message={errors.is_active} />
            </div>
            <Button disabled={processing}>
                {processing ? 'Saving…' : 'Save service'}
            </Button>
        </>
    );
}
