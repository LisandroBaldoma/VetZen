import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Procedure } from '@/types';

type Props = {
    procedure?: Procedure;
    errors: Record<string, string | undefined>;
    processing: boolean;
};

export default function ProcedureFormFields({
    procedure,
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
                    defaultValue={procedure?.name ?? ''}
                />
                <InputError message={errors.name} />
            </div>
            <div className="grid gap-2">
                <Label htmlFor="description">Description (optional)</Label>
                <textarea
                    id="description"
                    name="description"
                    defaultValue={procedure?.description ?? ''}
                    className="min-h-32 rounded-md border bg-transparent px-3 py-2 text-sm"
                />
                <InputError message={errors.description} />
            </div>
            <div className="grid gap-2">
                <Label htmlFor="duration_minutes">
                    Suggested duration in minutes (optional)
                </Label>
                <Input
                    id="duration_minutes"
                    name="duration_minutes"
                    type="number"
                    min="1"
                    max="1440"
                    defaultValue={procedure?.duration_minutes ?? ''}
                />
                <InputError message={errors.duration_minutes} />
            </div>
            <div className="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0" />
                <Checkbox
                    id="is_active"
                    name="is_active"
                    value="1"
                    defaultChecked={procedure?.is_active ?? true}
                />
                <Label htmlFor="is_active">Active</Label>
                <InputError message={errors.is_active} />
            </div>
            <Button disabled={processing}>
                {processing ? 'Saving…' : 'Save procedure'}
            </Button>
        </>
    );
}
