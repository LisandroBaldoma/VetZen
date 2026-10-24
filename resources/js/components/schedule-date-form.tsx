import { useForm } from '@inertiajs/react';
import { Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

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
export type ScheduleTemplate = {
    id: number;
    name: string;
    capacity: number;
    interval_minutes: number;
    blocks: Block[];
    services: Service[];
};

type Data = {
    name: string;
    date: string;
    dates: string[];
    date_from: string;
    date_to: string;
    weekdays: number[];
    template_id: number | null;
    interval_minutes: number;
    capacity: number;
    blocks: Block[];
    starts: string[];
    service_ids: number[];
};

type Props = {
    services: Service[];
    scheduleDate?: ScheduleDate;
    template?: ScheduleTemplate;
    templates?: ScheduleTemplate[];
    mode?: 'schedule-date' | 'template';
    action: string;
    method: 'post' | 'patch';
    submitLabel: string;
};

const hours = Array.from({ length: 24 }, (_, hour) =>
    hour.toString().padStart(2, '0'),
);
const minutes = Array.from({ length: 60 }, (_, minute) =>
    minute.toString().padStart(2, '0'),
);
const weekdays = [
    ['Dom', 0],
    ['Lun', 1],
    ['Mar', 2],
    ['Mié', 3],
    ['Jue', 4],
    ['Vie', 5],
    ['Sáb', 6],
] as const;

function TimeSelect({
    id,
    value,
    onChange,
}: {
    id: string;
    value: string;
    onChange: (value: string) => void;
}) {
    const [selectedHour = '', selectedMinute = ''] = value.split(':');
    const updateTime = (hour: string, minute: string) =>
        onChange(`${hour || '00'}:${minute || '00'}`);

    return (
        <div className="flex items-center gap-1">
            <Select
                value={selectedHour}
                onValueChange={(hour) => updateTime(hour, selectedMinute)}
            >
                <SelectTrigger id={`${id}-hour`} className="w-20">
                    <SelectValue placeholder="Hora" />
                </SelectTrigger>
                <SelectContent>
                    {hours.map((hour) => (
                        <SelectItem key={hour} value={hour}>
                            {hour}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
            <span aria-hidden>:</span>
            <Select
                value={selectedMinute}
                onValueChange={(minute) => updateTime(selectedHour, minute)}
            >
                <SelectTrigger id={`${id}-minute`} className="w-20">
                    <SelectValue placeholder="Minutos" />
                </SelectTrigger>
                <SelectContent>
                    {minutes.map((minute) => (
                        <SelectItem key={minute} value={minute}>
                            {minute}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
        </div>
    );
}

function errorFor(errors: Record<string, string>, field: string) {
    return errors[field];
}

function generatedStarts(blocks: Block[], interval: number) {
    return blocks.flatMap((block) => {
        const starts: string[] = [];
        const [startHour, startMinute] = block.starts_at.split(':').map(Number);
        const [endHour, endMinute] = block.ends_at.split(':').map(Number);
        let current = startHour * 60 + startMinute;
        const end = endHour * 60 + endMinute;

        while (Number.isFinite(current) && current < end) {
            starts.push(
                `${Math.floor(current / 60)
                    .toString()
                    .padStart(
                        2,
                        '0',
                    )}:${(current % 60).toString().padStart(2, '0')}`,
            );
            current += interval;
        }

        return starts;
    });
}

function without(data: Data, fields: Array<keyof Data>): Data {
    const payload: Partial<Data> = { ...data };

    fields.forEach((field) => delete payload[field]);

    return payload as Data;
}

export default function ScheduleDateForm({
    services,
    scheduleDate,
    template,
    templates = [],
    mode = 'schedule-date',
    action,
    method,
    submitLabel,
}: Props) {
    const isTemplate = mode === 'template';
    const isEditingDate = Boolean(scheduleDate);
    const form = useForm<Data>({
        name: template?.name ?? '',
        date: scheduleDate?.date ?? '',
        dates: [''],
        date_from: '',
        date_to: '',
        weekdays: [1, 2, 3, 4, 5],
        template_id: null,
        interval_minutes: template?.interval_minutes ?? 30,
        capacity: template?.capacity ?? scheduleDate?.capacity ?? 1,
        blocks: template?.blocks ??
            scheduleDate?.blocks ?? [{ starts_at: '10:00', ends_at: '13:00' }],
        starts: scheduleDate?.starts.map((start) => start.starts_at) ?? [
            '10:00',
        ],
        service_ids:
            template?.services.map((service) => service.id) ??
            scheduleDate?.services.map((service) => service.id) ??
            [],
    });
    const [dateMode, setDateMode] = useState<'manual' | 'range'>('manual');
    const [adjustStarts, setAdjustStarts] = useState(false);
    const selectedTemplate = templates.find(
        (item) => item.id === form.data.template_id,
    );
    const useTemplate = !isTemplate && !isEditingDate && selectedTemplate;
    const previewStarts = generatedStarts(
        selectedTemplate?.blocks ?? form.data.blocks,
        selectedTemplate?.interval_minutes ?? form.data.interval_minutes,
    );

    const updateBlock = (index: number, field: keyof Block, value: string) => {
        form.setData(
            'blocks',
            form.data.blocks.map((block, blockIndex) =>
                blockIndex === index ? { ...block, [field]: value } : block,
            ),
        );
    };
    const toggleService = (serviceId: number, checked: boolean) =>
        form.setData(
            'service_ids',
            checked
                ? [...form.data.service_ids, serviceId]
                : form.data.service_ids.filter((id) => id !== serviceId),
        );
    const toggleWeekday = (weekday: number, checked: boolean) =>
        form.setData(
            'weekdays',
            checked
                ? [...form.data.weekdays, weekday].sort()
                : form.data.weekdays.filter((item) => item !== weekday),
        );

    const submit = () => {
        form.transform((data) => {
            if (isTemplate) {
                return without(data, [
                    'date',
                    'dates',
                    'date_from',
                    'date_to',
                    'weekdays',
                    'template_id',
                    'starts',
                ]);
            }

            if (isEditingDate) {
                return without(data, [
                    'name',
                    'dates',
                    'date_from',
                    'date_to',
                    'weekdays',
                    'template_id',
                    'interval_minutes',
                ]);
            }

            if (dateMode === 'manual') {
                if (selectedTemplate) {
                    return {
                        dates: data.dates,
                        template_id: selectedTemplate.id,
                    };
                }

                return without(data, [
                    'name',
                    'date',
                    'date_from',
                    'date_to',
                    'weekdays',
                    'template_id',
                    ...(adjustStarts ? [] : ['starts']),
                ] as Array<keyof Data>);
            }

            if (selectedTemplate) {
                return {
                    date_from: data.date_from,
                    date_to: data.date_to,
                    weekdays: data.weekdays,
                    template_id: selectedTemplate.id,
                };
            }

            return without(data, [
                'name',
                'date',
                'dates',
                'template_id',
                ...(adjustStarts ? [] : ['starts']),
            ] as Array<keyof Data>);
        });

        if (method === 'post') {
            form.post(action);
        } else {
            form.patch(action);
        }
    };

    return (
        <form
            className="space-y-7 rounded-xl border bg-card p-5 shadow-sm sm:p-6"
            onSubmit={(event) => {
                event.preventDefault();
                submit();
            }}
        >
            {isTemplate && (
                <div className="grid gap-2">
                    <Label htmlFor="name">Nombre de la plantilla</Label>
                    <Input
                        id="name"
                        value={form.data.name}
                        onChange={(event) =>
                            form.setData('name', event.target.value)
                        }
                        required
                    />
                    <InputError message={errorFor(form.errors, 'name')} />
                </div>
            )}

            {!isTemplate && !isEditingDate && (
                <>
                    <fieldset className="space-y-3">
                        <legend className="font-medium">
                            Fechas a publicar
                        </legend>
                        <div
                            className="flex flex-wrap gap-2"
                            role="radiogroup"
                            aria-label="Modo de selección de fechas"
                        >
                            <Button
                                type="button"
                                variant={
                                    dateMode === 'manual'
                                        ? 'default'
                                        : 'outline'
                                }
                                onClick={() => setDateMode('manual')}
                                aria-pressed={dateMode === 'manual'}
                            >
                                Fechas manuales
                            </Button>
                            <Button
                                type="button"
                                variant={
                                    dateMode === 'range' ? 'default' : 'outline'
                                }
                                onClick={() => setDateMode('range')}
                                aria-pressed={dateMode === 'range'}
                            >
                                Rango y días
                            </Button>
                        </div>
                        {dateMode === 'manual' ? (
                            <div className="space-y-2">
                                {form.data.dates.map((date, index) => (
                                    <div
                                        key={index}
                                        className="flex flex-wrap items-start gap-2"
                                    >
                                        <div className="grid flex-1 gap-2">
                                            <Label
                                                htmlFor={`date-${index}`}
                                                className="sr-only"
                                            >
                                                Fecha {index + 1}
                                            </Label>
                                            <Input
                                                id={`date-${index}`}
                                                type="date"
                                                value={date}
                                                onChange={(event) =>
                                                    form.setData(
                                                        'dates',
                                                        form.data.dates.map(
                                                            (
                                                                item,
                                                                itemIndex,
                                                            ) =>
                                                                itemIndex ===
                                                                index
                                                                    ? event
                                                                          .target
                                                                          .value
                                                                    : item,
                                                        ),
                                                    )
                                                }
                                                required
                                            />
                                        </div>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            disabled={
                                                form.data.dates.length === 1
                                            }
                                            onClick={() =>
                                                form.setData(
                                                    'dates',
                                                    form.data.dates.filter(
                                                        (_, itemIndex) =>
                                                            itemIndex !== index,
                                                    ),
                                                )
                                            }
                                            aria-label={`Quitar fecha ${date || index + 1}`}
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
                            <div className="space-y-4">
                                <div className="grid gap-4 sm:grid-cols-2">
                                    <div className="grid gap-2">
                                        <Label htmlFor="date_from">Desde</Label>
                                        <Input
                                            id="date_from"
                                            type="date"
                                            value={form.data.date_from}
                                            onChange={(event) =>
                                                form.setData(
                                                    'date_from',
                                                    event.target.value,
                                                )
                                            }
                                            required
                                        />
                                        <InputError
                                            message={errorFor(
                                                form.errors,
                                                'date_from',
                                            )}
                                        />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="date_to">Hasta</Label>
                                        <Input
                                            id="date_to"
                                            type="date"
                                            value={form.data.date_to}
                                            onChange={(event) =>
                                                form.setData(
                                                    'date_to',
                                                    event.target.value,
                                                )
                                            }
                                            required
                                        />
                                        <InputError
                                            message={errorFor(
                                                form.errors,
                                                'date_to',
                                            )}
                                        />
                                    </div>
                                </div>
                                <fieldset className="space-y-2">
                                    <legend className="text-sm font-medium">
                                        Días a incluir
                                    </legend>
                                    <div className="flex flex-wrap gap-2">
                                        {weekdays.map(([label, value]) => (
                                            <label
                                                key={value}
                                                className="flex min-h-11 items-center gap-2 rounded-lg bg-muted/40 px-3 text-sm"
                                            >
                                                <Checkbox
                                                    checked={form.data.weekdays.includes(
                                                        value,
                                                    )}
                                                    onCheckedChange={(
                                                        checked,
                                                    ) =>
                                                        toggleWeekday(
                                                            value,
                                                            checked === true,
                                                        )
                                                    }
                                                />
                                                {label}
                                            </label>
                                        ))}
                                    </div>
                                    <InputError
                                        message={errorFor(
                                            form.errors,
                                            'weekdays',
                                        )}
                                    />
                                </fieldset>
                            </div>
                        )}
                    </fieldset>
                    <fieldset className="space-y-3 border-t pt-5">
                        <legend className="font-medium">Configuración</legend>
                        <div className="grid gap-2">
                            <Label htmlFor="template_id">Usar plantilla</Label>
                            <Select
                                value={
                                    form.data.template_id?.toString() ??
                                    'custom'
                                }
                                onValueChange={(value) =>
                                    form.setData(
                                        'template_id',
                                        value === 'custom'
                                            ? null
                                            : Number(value),
                                    )
                                }
                            >
                                <SelectTrigger id="template_id">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="custom">
                                        Configuración personalizada
                                    </SelectItem>
                                    {templates.map((item) => (
                                        <SelectItem
                                            key={item.id}
                                            value={item.id.toString()}
                                        >
                                            {item.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {templates.length === 0 && (
                                <p className="text-sm text-muted-foreground">
                                    No hay plantillas disponibles. Configurá
                                    esta publicación manualmente.
                                </p>
                            )}
                            <InputError
                                message={errorFor(form.errors, 'template_id')}
                            />
                        </div>
                        {useTemplate && (
                            <div className="rounded-lg bg-muted/40 p-4 text-sm">
                                <p className="font-medium">
                                    {selectedTemplate.name}
                                </p>
                                <p className="mt-1 text-muted-foreground">
                                    Capacidad {selectedTemplate.capacity} · Cada{' '}
                                    {selectedTemplate.interval_minutes} min
                                </p>
                                <p className="mt-2">
                                    Bloques:{' '}
                                    {selectedTemplate.blocks
                                        .map(
                                            (block) =>
                                                `${block.starts_at}–${block.ends_at}`,
                                        )
                                        .join(' · ')}
                                </p>
                                <p className="mt-1">
                                    Servicios:{' '}
                                    {selectedTemplate.services
                                        .map((service) => service.name)
                                        .join(', ')}
                                </p>
                            </div>
                        )}
                    </fieldset>
                </>
            )}

            {isEditingDate && (
                <div className="grid gap-2">
                    <Label htmlFor="date">Fecha publicada</Label>
                    <Input
                        id="date"
                        type="date"
                        value={form.data.date}
                        onChange={(event) =>
                            form.setData('date', event.target.value)
                        }
                        required
                    />
                    <InputError message={errorFor(form.errors, 'date')} />
                </div>
            )}

            {!useTemplate && (
                <>
                    <div className="grid gap-5 sm:grid-cols-2">
                        <div className="grid gap-2">
                            <Label htmlFor="capacity">
                                Capacidad simultánea
                            </Label>
                            <Input
                                id="capacity"
                                type="number"
                                min="1"
                                max="5"
                                value={form.data.capacity}
                                onChange={(event) =>
                                    form.setData(
                                        'capacity',
                                        Number(event.target.value),
                                    )
                                }
                                required
                            />
                            <p className="text-sm text-muted-foreground">
                                Pacientes que pueden estar en atención al mismo
                                tiempo.
                            </p>
                            <InputError
                                message={errorFor(form.errors, 'capacity')}
                            />
                        </div>
                        {!isEditingDate && (
                            <div className="grid gap-2">
                                <Label htmlFor="interval_minutes">
                                    Intervalo entre inicios
                                </Label>
                                <Select
                                    value={form.data.interval_minutes.toString()}
                                    onValueChange={(value) =>
                                        form.setData(
                                            'interval_minutes',
                                            Number(value),
                                        )
                                    }
                                >
                                    <SelectTrigger id="interval_minutes">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {[15, 30, 45, 60].map((interval) => (
                                            <SelectItem
                                                key={interval}
                                                value={interval.toString()}
                                            >
                                                {interval} minutos
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError
                                    message={errorFor(
                                        form.errors,
                                        'interval_minutes',
                                    )}
                                />
                            </div>
                        )}
                    </div>
                    <fieldset className="space-y-3 border-t pt-5">
                        <legend className="font-medium">
                            Bloques de trabajo
                        </legend>
                        <p className="text-sm text-muted-foreground">
                            La atención completa deberá entrar en uno de estos
                            bloques.
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
                                    <TimeSelect
                                        id={`block-${index}-start`}
                                        value={block.starts_at}
                                        onChange={(value) =>
                                            updateBlock(
                                                index,
                                                'starts_at',
                                                value,
                                            )
                                        }
                                    />
                                    <InputError
                                        message={errorFor(
                                            form.errors,
                                            `blocks.${index}.starts_at`,
                                        )}
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor={`block-${index}-end`}>
                                        Fin
                                    </Label>
                                    <TimeSelect
                                        id={`block-${index}-end`}
                                        value={block.ends_at}
                                        onChange={(value) =>
                                            updateBlock(index, 'ends_at', value)
                                        }
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
                                                (_, blockIndex) =>
                                                    blockIndex !== index,
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
                    {!isEditingDate && !isTemplate && (
                        <section
                            className="space-y-2 rounded-lg bg-muted/40 p-4"
                            aria-live="polite"
                        >
                            <h2 className="font-medium">
                                Inicios que se publicarán
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Se generan automáticamente al guardar y no se
                                envían desde este formulario.
                            </p>
                            <p className="text-sm font-medium tabular-nums">
                                {previewStarts.length
                                    ? previewStarts.join(', ')
                                    : 'Completá bloques válidos para ver los inicios.'}
                            </p>
                            {!adjustStarts && (
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    onClick={() => {
                                        form.setData('starts', previewStarts);
                                        setAdjustStarts(true);
                                    }}
                                >
                                    Ajustar inicios
                                </Button>
                            )}
                        </section>
                    )}
                    {adjustStarts && !isEditingDate && !isTemplate && (
                        <fieldset className="space-y-3 border-t pt-5">
                            <legend className="font-medium">
                                Ajustar inicios generados
                            </legend>
                            <p className="text-sm text-muted-foreground">
                                Podés quitar o agregar horarios para esta
                                publicación puntual.
                            </p>
                            <div className="flex flex-wrap gap-3">
                                {form.data.starts.map((start, index) => (
                                    <div
                                        key={index}
                                        className="flex items-end gap-1"
                                    >
                                        <TimeSelect
                                            id={`generated-start-${index}`}
                                            value={start}
                                            onChange={(value) =>
                                                form.setData(
                                                    'starts',
                                                    form.data.starts.map(
                                                        (item, itemIndex) =>
                                                            itemIndex === index
                                                                ? value
                                                                : item,
                                                    ),
                                                )
                                            }
                                        />
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            disabled={
                                                form.data.starts.length === 1
                                            }
                                            onClick={() =>
                                                form.setData(
                                                    'starts',
                                                    form.data.starts.filter(
                                                        (_, itemIndex) =>
                                                            itemIndex !== index,
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
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() =>
                                    form.setData('starts', [
                                        ...form.data.starts,
                                        '',
                                    ])
                                }
                            >
                                <Plus aria-hidden /> Agregar inicio
                            </Button>
                            <InputError
                                message={errorFor(form.errors, 'starts')}
                            />
                        </fieldset>
                    )}
                    {isEditingDate && (
                        <fieldset className="space-y-3 border-t pt-5">
                            <legend className="font-medium">
                                Inicios reservables
                            </legend>
                            <p className="text-sm text-muted-foreground">
                                Podés mantener o ajustar los horarios publicados
                                para esta fecha.
                            </p>
                            <div className="flex flex-wrap gap-3">
                                {form.data.starts.map((start, index) => (
                                    <div
                                        key={index}
                                        className="flex items-end gap-1"
                                    >
                                        <div className="grid gap-2">
                                            <Label
                                                htmlFor={`start-${index}`}
                                                className="sr-only"
                                            >
                                                Inicio reservable {index + 1}
                                            </Label>
                                            <TimeSelect
                                                id={`start-${index}`}
                                                value={start}
                                                onChange={(value) =>
                                                    form.setData(
                                                        'starts',
                                                        form.data.starts.map(
                                                            (
                                                                item,
                                                                itemIndex,
                                                            ) =>
                                                                itemIndex ===
                                                                index
                                                                    ? value
                                                                    : item,
                                                        ),
                                                    )
                                                }
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
                                            disabled={
                                                form.data.starts.length === 1
                                            }
                                            onClick={() =>
                                                form.setData(
                                                    'starts',
                                                    form.data.starts.filter(
                                                        (_, itemIndex) =>
                                                            itemIndex !== index,
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
                            <InputError
                                message={errorFor(form.errors, 'starts')}
                            />
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() =>
                                    form.setData('starts', [
                                        ...form.data.starts,
                                        '',
                                    ])
                                }
                            >
                                <Plus aria-hidden /> Agregar inicio
                            </Button>
                        </fieldset>
                    )}
                    <fieldset className="space-y-3 border-t pt-5">
                        <legend className="font-medium">
                            Servicios habilitados
                        </legend>
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
                                                (
                                                {
                                                    service.appointment_duration_minutes
                                                }{' '}
                                                min)
                                            </span>
                                        </span>
                                    </label>
                                ))}
                            </div>
                        )}
                        <InputError
                            message={errorFor(form.errors, 'service_ids')}
                        />
                    </fieldset>
                </>
            )}
            {useTemplate && (
                <section
                    className="space-y-2 rounded-lg bg-muted/40 p-4"
                    aria-live="polite"
                >
                    <h2 className="font-medium">Inicios que se publicarán</h2>
                    <p className="text-sm text-muted-foreground">
                        Se generarán automáticamente con el intervalo de la
                        plantilla.
                    </p>
                    <p className="text-sm font-medium tabular-nums">
                        {previewStarts.join(', ')}
                    </p>
                </section>
            )}
            <div className="flex justify-end border-t pt-5">
                <Button
                    disabled={
                        form.processing ||
                        (!useTemplate && services.length === 0)
                    }
                >
                    {form.processing ? 'Guardando…' : submitLabel}
                </Button>
            </div>
        </form>
    );
}
