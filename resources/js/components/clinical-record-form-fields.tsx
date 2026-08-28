import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { ClinicalRecord } from '@/types';

type Props = {
    record?: ClinicalRecord;
    types: string[];
    errors: Record<string, string | undefined>;
    processing: boolean;
    submitLabel: string;
};

function dateTimeLocal(value?: string): string {
    return value ? value.slice(0, 16) : '';
}

export default function ClinicalRecordFormFields({
    record,
    types,
    errors,
    processing,
    submitLabel,
}: Props) {
    return (
        <>
            <div className="grid gap-4 sm:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="type">Type</Label>
                    <select
                        id="type"
                        name="type"
                        required
                        defaultValue={record?.type ?? ''}
                        className="h-9 rounded-md border bg-transparent px-3 text-sm"
                    >
                        <option value="">Select a type</option>
                        {types.map((type) => (
                            <option key={type} value={type}>
                                {type}
                            </option>
                        ))}
                    </select>
                    <InputError message={errors.type} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="occurred_at">Clinical date</Label>
                    <Input
                        id="occurred_at"
                        name="occurred_at"
                        type="datetime-local"
                        required
                        max={dateTimeLocal(new Date().toISOString())}
                        defaultValue={dateTimeLocal(record?.occurred_at)}
                    />
                    <InputError message={errors.occurred_at} />
                </div>
            </div>

            <div className="grid gap-2">
                <Label htmlFor="title">Title</Label>
                <Input
                    id="title"
                    name="title"
                    required
                    maxLength={255}
                    defaultValue={record?.title ?? ''}
                />
                <InputError message={errors.title} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="content">Clinical content</Label>
                <textarea
                    id="content"
                    name="content"
                    required
                    defaultValue={record?.content ?? ''}
                    className="min-h-48 rounded-md border bg-transparent px-3 py-2 text-sm"
                />
                <InputError message={errors.content} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="is_visible_to_client">Visible to client</Label>
                <select
                    id="is_visible_to_client"
                    name="is_visible_to_client"
                    required
                    defaultValue={
                        record === undefined
                            ? ''
                            : record.is_visible_to_client
                              ? '1'
                              : '0'
                    }
                    className="h-9 rounded-md border bg-transparent px-3 text-sm"
                >
                    <option value="">Choose visibility</option>
                    <option value="1">Visible</option>
                    <option value="0">Not visible</option>
                </select>
                <InputError message={errors.is_visible_to_client} />
            </div>

            <Button disabled={processing}>{submitLabel}</Button>
        </>
    );
}
