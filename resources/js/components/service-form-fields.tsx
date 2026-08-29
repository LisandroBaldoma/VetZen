import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Service } from '@/types';

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
