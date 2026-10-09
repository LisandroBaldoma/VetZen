import { useForm } from '@inertiajs/react';
import { Plus, Trash2 } from 'lucide-react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Block = { starts_at: string; ends_at: string };
type Service = {
    id: number;
    name: string;
    appointment_duration_minutes: number;
};
type ScheduleDate = {
    id: number;
    date: string;
    capacity: number;
    blocks: Block[];
    starts: Array<{ starts_at: string }>;
    services: Array<{ id: number }>;
};

type Data = {
    date: string;
    dates: string[];
    capacity: number;
    blocks: Block[];
    starts: string[];
    service_ids: number[];
};

type Props = {
    services: Service[];
    scheduleDate?: ScheduleDate;
    multipleDates?: boolean;
    action: string;
    method: 'post' | 'patch';
    submitLabel: string;
};

function errorFor(
    errors: Record<string, string>,
    field: string,
): string | undefined {
    return errors[field];
}

export default function ScheduleDateForm({
    services,
    scheduleDate,
    multipleDates = false,
    action,
    method,
    submitLabel,
}: Props) {
    const form = useForm<Data>({
        date: scheduleDate?.date ?? '',
        dates: scheduleDate === undefined ? [''] : [],
        capacity: scheduleDate?.capacity ?? 1,
        blocks: scheduleDate?.blocks ?? [
            { starts_at: '10:00', ends_at: '13:00' },
        ],
        starts: scheduleDate?.starts.map((start) => start.starts_at) ?? [
            '10:00',
        ],
        service_ids: scheduleDate?.services.map((service) => service.id) ?? [],
    });

    const updateBlock = (index: number, field: keyof Block, value: string) => {
        form.setData(
            'blocks',
            form.data.blocks.map((block, blockIndex) =>
                blockIndex === index ? { ...block, [field]: value } : block,
            ),
        );
    };

    const updateStart = (index: number, value: string) => {
        form.setData(
            'starts',
            form.data.starts.map((start, startIndex) =>
                startIndex === index ? value : start,
            ),
        );
    };

    const toggleService = (serviceId: number, checked: boolean) => {
        form.setData(
            'service_ids',
            checked
                ? [...form.data.service_ids, serviceId]
                : form.data.service_ids.filter((id) => id !== serviceId),
        );
    };

    return (
        <form
            className="space-y-7 rounded-xl border bg-card p-5 shadow-sm sm:p-6"
            onSubmit={(event) => {
                event.preventDefault();

                if (method === 'post') {
                    form.post(action);
                } else {
                    form.patch(action);
                }
            }}
        >
            <div className="grid gap-5 sm:grid-cols-2">
                <div className="grid gap-2">
                    <Label>
                        {multipleDates
                            ? 'Fechas publicadas'
                            : 'Fecha publicada'}
                    </Label>
                    {multipleDates ? (
                        <div className="space-y-2">
                            {form.data.dates.map((date, index) => (
                                <div
                                    key={index}
                                    className="flex items-center gap-1"
                                >
                                    <Input
                                        id={`date-${index}`}
                                        type="date"
                                        value={date}
                                        onChange={(event) =>
                                            form.setData(
                                                'dates',
                                                form.data.dates.map(
                                                    (currentDate, dateIndex) =>
                                                        dateIndex === index
                                                            ? event.target.value
                                                            : currentDate,
                                                ),
                                            )
                                        }
                                        required
                                    />
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="icon"
                                        disabled={form.data.dates.length === 1}
                                        onClick={() =>
                                            form.setData(
                                                'dates',
                                                form.data.dates.filter(
                                                    (_, dateIndex) =>
                                                        dateIndex !== index,
                                                ),
                                            )
                                        }
                                        aria-label={`Quitar fecha ${date}`}
                                    >
                                        <Trash2 aria-hidden />
                                    </Button>
                                    <InputError
                                        message={errorFor(
                                            form.errors,
                                            `dates.${index}`,
                                        )}
                                    />
                                </div>
                            ))}
                            <InputError
                                message={errorFor(form.errors, 'dates')}
                            />
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() =>
                                    form.setData('dates', [
                                        ...form.data.dates,
                                        '',
                                    ])
                                }
                            >
                                <Plus aria-hidden /> Agregar fecha
                            </Button>
                        </div>
                    ) : (
                        <>
                            <Input
                                id="date"
                                type="date"
                                value={form.data.date}
                                onChange={(event) =>
                                    form.setData('date', event.target.value)
                                }
                                required
                            />
                            <InputError
                                message={errorFor(form.errors, 'date')}
                            />
                        </>
                    )}
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="capacity">Capacidad simultánea</Label>
                    <Input
                        id="capacity"
                        type="number"
                        min="1"
                        max="5"
                        value={form.data.capacity}
                        onChange={(event) =>
                            form.setData('capacity', Number(event.target.value))
                        }
                        required
                    />
                    <p className="text-sm text-muted-foreground">
                        Pacientes que pueden estar en atención al mismo tiempo.
                    </p>
                    <InputError message={errorFor(form.errors, 'capacity')} />
                </div>
            </div>

            <fieldset className="space-y-3 border-t pt-5">
                <legend className="font-medium">Bloques de trabajo</legend>
                <p className="text-sm text-muted-foreground">
                    La atención completa deberá entrar en uno de estos bloques.
                </p>
                {form.data.blocks.map((block, index) => (
                    <div
                        key={index}
                        className="flex flex-wrap items-end gap-3 rounded-lg bg-muted/40 p-3"
                    >
                        <div className="grid gap-2">
                            <Label htmlFor={`block-${index}-start`}>
                                Inicio
                            </Label>
                            <Input
                                id={`block-${index}-start`}
                                type="time"
                                value={block.starts_at}
                                onChange={(event) =>
                                    updateBlock(
                                        index,
                                        'starts_at',
                                        event.target.value,
                                    )
                                }
                                required
                            />
                            <InputError
                                message={errorFor(
                                    form.errors,
                                    `blocks.${index}.starts_at`,
                                )}
                            />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor={`block-${index}-end`}>Fin</Label>
                            <Input
                                id={`block-${index}-end`}
                                type="time"
                                value={block.ends_at}
                                onChange={(event) =>
                                    updateBlock(
                                        index,
                                        'ends_at',
                                        event.target.value,
                                    )
                                }
                                required
                            />
                            <InputError
                                message={errorFor(
                                    form.errors,
                                    `blocks.${index}.ends_at`,
                                )}
                            />
                        </div>
                        <Button
                            type="button"
                            variant="ghost"
                            size="icon"
                            disabled={form.data.blocks.length === 1}
                            onClick={() =>
                                form.setData(
                                    'blocks',
                                    form.data.blocks.filter(
                                        (_, blockIndex) => blockIndex !== index,
                                    ),
                                )
                            }
                            aria-label="Quitar bloque"
                        >
                            <Trash2 aria-hidden />
                        </Button>
                    </div>
                ))}
                <InputError message={errorFor(form.errors, 'blocks')} />
                <Button
                    type="button"
                    variant="outline"
                    onClick={() =>
                        form.setData('blocks', [
                            ...form.data.blocks,
                            { starts_at: '', ends_at: '' },
                        ])
                    }
                >
                    <Plus aria-hidden /> Agregar bloque
                </Button>
            </fieldset>

            <fieldset className="space-y-3 border-t pt-5">
                <legend className="font-medium">Inicios reservables</legend>
                <p className="text-sm text-muted-foreground">
                    Publicá cada horario de inicio de forma explícita.
                </p>
                <div className="flex flex-wrap gap-3">
                    {form.data.starts.map((start, index) => (
                        <div key={index} className="flex items-end gap-1">
                            <div className="grid gap-2">
                                <Label
                                    htmlFor={`start-${index}`}
                                    className="sr-only"
                                >
                                    Inicio reservable {index + 1}
                                </Label>
                                <Input
                                    id={`start-${index}`}
                                    type="time"
                                    value={start}
                                    onChange={(event) =>
                                        updateStart(index, event.target.value)
                                    }
                                    required
                                />
                                <InputError
                                    message={errorFor(
                                        form.errors,
                                        `starts.${index}`,
                                    )}
                                />
                            </div>
                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                disabled={form.data.starts.length === 1}
                                onClick={() =>
                                    form.setData(
                                        'starts',
                                        form.data.starts.filter(
                                            (_, startIndex) =>
                                                startIndex !== index,
                                        ),
                                    )
                                }
                                aria-label={`Quitar inicio ${start}`}
                            >
                                <Trash2 aria-hidden />
                            </Button>
                        </div>
                    ))}
                </div>
                <InputError message={errorFor(form.errors, 'starts')} />
                <Button
                    type="button"
                    variant="outline"
                    onClick={() =>
                        form.setData('starts', [...form.data.starts, ''])
                    }
                >
                    <Plus aria-hidden /> Agregar inicio
                </Button>
            </fieldset>

            <fieldset className="space-y-3 border-t pt-5">
                <legend className="font-medium">Servicios habilitados</legend>
                <p className="text-sm text-muted-foreground">
                    Solo se podrán reservar estos servicios en la fecha
                    publicada.
                </p>
                {services.length === 0 ? (
                    <p className="text-sm text-muted-foreground">
                        No hay servicios activos con duración de turno
                        configurada.
                    </p>
                ) : (
                    <div className="grid gap-2 sm:grid-cols-2">
                        {services.map((service) => (
                            <label
                                key={service.id}
                                className="flex min-h-11 items-center gap-3 rounded-lg bg-muted/40 px-3 text-sm"
                            >
                                <Checkbox
                                    checked={form.data.service_ids.includes(
                                        service.id,
                                    )}
                                    onCheckedChange={(checked) =>
                                        toggleService(
                                            service.id,
                                            checked === true,
                                        )
                                    }
                                />
                                <span>
                                    {service.name}{' '}
                                    <span className="text-muted-foreground">
                                        ({service.appointment_duration_minutes}{' '}
                                        min)
                                    </span>
                                </span>
                            </label>
                        ))}
                    </div>
                )}
                <InputError message={errorFor(form.errors, 'service_ids')} />
            </fieldset>

            <div className="flex justify-end border-t pt-5">
                <Button disabled={form.processing || services.length === 0}>
                    {form.processing ? 'Guardando…' : submitLabel}
                </Button>
            </div>
        </form>
    );
}
