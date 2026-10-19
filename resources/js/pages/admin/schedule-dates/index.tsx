import { Head, Link } from '@inertiajs/react';
import {
    CalendarDays,
    CalendarPlus,
    ChevronLeft,
    ChevronRight,
    CopyPlus,
    Pencil,
    Plus,
    Users,
} from 'lucide-react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { dashboard } from '@/routes';
import {
    create as createAppointment,
    show as showAppointment,
} from '@/routes/admin/appointments';
import { create, edit, index } from '@/routes/admin/schedule-dates';
import { index as templatesIndex } from '@/routes/admin/schedule-templates';

type AppointmentStatus =
    'pending' | 'approved' | 'attended' | 'absent' | 'cancelled' | 'rejected';
type CalendarView = 'month' | 'week' | 'day';

type Appointment = {
    id: number;
    pet: { id: number; name: string };
    service: { id: number; name: string };
    status: AppointmentStatus;
    starts_at: string;
    ends_at: string;
    duration_minutes: number;
};

type CalendarDay = {
    date: string;
    published: boolean;
    schedule_date_id: number | null;
    capacity: number | null;
    blocks: Array<{ starts_at: string; ends_at: string }>;
    starts: Array<{ starts_at: string }>;
    appointments: Appointment[];
};

type Period = {
    view: CalendarView;
    date: string;
    starts_on: string;
    ends_on: string;
    previous_date: string;
    next_date: string;
    today: string;
};

type Summary = {
    published_dates: number;
    active_appointments: number;
    pending: number;
    approved: number;
    attended: number;
    absent: number;
    cancelled: number;
    rejected: number;
    maximum_active_occupancy: number;
    load_percent: number;
};

const statusLabels: Record<AppointmentStatus, string> = {
    pending: 'Pendiente',
    approved: 'Aprobado',
    attended: 'Atendido',
    absent: 'Ausente',
    cancelled: 'Cancelado',
    rejected: 'Rechazado',
};

const statusClasses: Record<AppointmentStatus, string> = {
    pending:
        'border-amber-300 bg-amber-50 text-amber-950 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-100',
    approved:
        'border-sky-300 bg-sky-50 text-sky-950 dark:border-sky-900 dark:bg-sky-950/40 dark:text-sky-100',
    attended:
        'border-emerald-300 bg-emerald-50 text-emerald-950 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-100',
    absent: 'border-orange-300 bg-orange-50 text-orange-950 dark:border-orange-900 dark:bg-orange-950/40 dark:text-orange-100',
    cancelled:
        'border-zinc-300 bg-zinc-100 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300',
    rejected:
        'border-rose-300 bg-rose-50 text-rose-950 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-100',
};

function dateFromString(value: string): Date {
    const [year, month, day] = value.split('-').map(Number);

    return new Date(year, month - 1, day);
}

function dateLabel(value: string, options: Intl.DateTimeFormatOptions): string {
    return new Intl.DateTimeFormat('es-AR', {
        timeZone: 'America/Argentina/Buenos_Aires',
        ...options,
    }).format(dateFromString(value));
}

function time(value: string): string {
    return new Intl.DateTimeFormat('es-AR', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
        timeZone: 'America/Argentina/Buenos_Aires',
    }).format(new Date(value));
}

function minutes(value: string): number {
    const [hours, minutes] = value.slice(0, 5).split(':').map(Number);

    return hours * 60 + minutes;
}

function appointmentMinutes(value: string): number {
    return minutes(time(value));
}

function timelineAppointments(day: CalendarDay) {
    const lanes: number[] = [];
    const appointments = [...day.appointments]
        .sort(
            (left, right) =>
                appointmentMinutes(left.starts_at) -
                appointmentMinutes(right.starts_at),
        )
        .map((appointment) => {
            const startsAt = appointmentMinutes(appointment.starts_at);
            const endsAt = appointmentMinutes(appointment.ends_at);
            let lane = lanes.findIndex(
                (endsAtInLane) => endsAtInLane <= startsAt,
            );

            if (lane === -1) {
                lane = lanes.length;
                lanes.push(endsAt);
            } else {
                lanes[lane] = endsAt;
            }

            return { appointment, lane, startsAt };
        });

    return { appointments, laneCount: Math.max(lanes.length, 1) };
}

function activeAppointments(day: CalendarDay): Appointment[] {
    return day.appointments.filter((appointment) =>
        ['pending', 'approved'].includes(appointment.status),
    );
}

function occupancy(day: CalendarDay): number {
    if (!day.capacity) {
        return 0;
    }

    const events = activeAppointments(day)
        .flatMap((appointment) => [
            { at: new Date(appointment.starts_at).getTime(), change: 1 },
            { at: new Date(appointment.ends_at).getTime(), change: -1 },
        ])
        .sort((a, b) => a.at - b.at || a.change - b.change);
    let current = 0;
    let maximum = 0;
    events.forEach((event) => {
        current += event.change;
        maximum = Math.max(maximum, current);
    });

    return maximum;
}

function appointmentCard(appointment: Appointment, compact = false) {
    return (
        <Link
            className={cn(
                'block rounded-md border px-2 py-1.5 text-left transition-opacity hover:opacity-75',
                statusClasses[appointment.status],
            )}
            href={showAppointment(appointment.id)}
            key={appointment.id}
        >
            <div className="flex items-center justify-between gap-2 text-xs font-semibold">
                <span>{time(appointment.starts_at)}</span>
                <span className="truncate font-medium">
                    {statusLabels[appointment.status]}
                </span>
            </div>
            <p
                className={cn(
                    'truncate font-semibold',
                    compact ? 'text-xs' : 'text-sm',
                )}
            >
                {appointment.pet.name}
            </p>
            {!compact ? (
                <p className="truncate text-xs opacity-75">
                    {appointment.service.name}
                </p>
            ) : null}
        </Link>
    );
}

function MonthView({ days, period }: { days: CalendarDay[]; period: Period }) {
    const firstWeekday = (dateFromString(period.starts_on).getDay() + 6) % 7;
    const cells = [
        ...Array.from({ length: firstWeekday }, () => null),
        ...days,
    ];

    while (cells.length % 7 !== 0) {
        cells.push(null);
    }

    return (
        <section className="overflow-hidden rounded-xl border bg-card shadow-sm">
            <div className="grid grid-cols-7 border-b bg-muted/40 text-center text-xs font-semibold text-muted-foreground">
                {['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'].map(
                    (label) => (
                        <div className="py-3" key={label}>
                            {label}
                        </div>
                    ),
                )}
            </div>
            <div className="grid grid-cols-7">
                {cells.map((day, position) =>
                    day ? (
                        <MonthCell day={day} key={day.date} period={period} />
                    ) : (
                        <div
                            className="min-h-30 border-r border-b bg-muted/10 last:border-r-0 sm:min-h-36"
                            key={`blank-${position}`}
                        />
                    ),
                )}
            </div>
        </section>
    );
}

function MonthCell({ day, period }: { day: CalendarDay; period: Period }) {
    const peak = occupancy(day);
    const utilization = day.capacity
        ? Math.round((peak / day.capacity) * 100)
        : 0;
    const isToday = day.date === period.today;

    return (
        <article
            className={cn(
                'min-h-30 border-r border-b p-2 sm:min-h-36 sm:p-3',
                isToday && 'bg-primary/5',
            )}
        >
            <div className="flex items-center justify-between gap-1">
                <Link
                    className={cn(
                        'flex size-6 items-center justify-center rounded-full text-sm font-semibold hover:bg-muted',
                        isToday &&
                            'bg-primary text-primary-foreground hover:bg-primary',
                    )}
                    href={index({ query: { view: 'day', date: day.date } })}
                >
                    {dateFromString(day.date).getDate()}
                </Link>
                {day.published ? (
                    <Link
                        aria-label={`Editar agenda del ${day.date}`}
                        className="hidden text-muted-foreground hover:text-foreground sm:block"
                        href={edit(day.schedule_date_id!)}
                    >
                        <Pencil className="size-3.5" />
                    </Link>
                ) : null}
            </div>
            {day.published ? (
                <div className="mt-2 space-y-1.5">
                    <p className="text-[11px] font-medium text-foreground sm:text-xs">
                        {day.capacity}{' '}
                        {day.capacity === 1 ? 'lugar' : 'lugares'}
                    </p>
                    <div className="h-1.5 overflow-hidden rounded-full bg-muted">
                        <div
                            className="h-full rounded-full bg-primary"
                            style={{ width: `${Math.min(utilization, 100)}%` }}
                        />
                    </div>
                    <p className="text-[10px] text-muted-foreground sm:text-xs">
                        {day.appointments.length} turnos ·{' '}
                        {
                            day.appointments.filter(
                                (appointment) =>
                                    appointment.status === 'pending',
                            ).length
                        }{' '}
                        pendientes
                    </p>
                    <div className="hidden space-y-1 sm:block">
                        {day.appointments
                            .slice(0, 2)
                            .map((appointment) =>
                                appointmentCard(appointment, true),
                            )}
                    </div>
                    {day.appointments.length > 2 ? (
                        <p className="hidden text-xs text-muted-foreground sm:block">
                            +{day.appointments.length - 2} más
                        </p>
                    ) : null}
                </div>
            ) : (
                <p className="mt-3 text-[10px] text-muted-foreground sm:text-xs">
                    Sin agenda
                </p>
            )}
        </article>
    );
}

function WeekView({ days, period }: { days: CalendarDay[]; period: Period }) {
    return (
        <>
            <section className="grid gap-3 md:hidden">
                {days.map((day) => (
                    <DayCard day={day} key={day.date} period={period} />
                ))}
            </section>
            <section className="hidden overflow-x-auto rounded-xl border bg-card shadow-sm md:block">
                <div className="grid min-w-225 grid-cols-7">
                    {days.map((day) => (
                        <WeekColumn day={day} key={day.date} period={period} />
                    ))}
                </div>
            </section>
        </>
    );
}

function WeekColumn({ day, period }: { day: CalendarDay; period: Period }) {
    return (
        <article
            className={cn(
                'min-h-125 border-r last:border-r-0',
                day.date === period.today && 'bg-primary/5',
            )}
        >
            <header className="border-b p-3">
                <Link
                    className="block hover:text-primary"
                    href={index({ query: { view: 'day', date: day.date } })}
                >
                    <p className="text-xs text-muted-foreground">
                        {dateLabel(day.date, { weekday: 'short' })}
                    </p>
                    <p className="font-semibold">
                        {dateLabel(day.date, {
                            day: 'numeric',
                            month: 'short',
                        })}
                    </p>
                </Link>
                {day.published ? (
                    <p className="mt-2 text-xs text-muted-foreground">
                        Capacidad {day.capacity} · {day.appointments.length}{' '}
                        turnos
                    </p>
                ) : (
                    <p className="mt-2 text-xs text-muted-foreground">
                        Sin agenda publicada
                    </p>
                )}
            </header>
            {day.published ? (
                <div className="space-y-2 p-2">
                    <div className="rounded-md bg-muted/60 p-2 text-xs text-muted-foreground">
                        {day.blocks
                            .map(
                                (block) =>
                                    `${block.starts_at}–${block.ends_at}`,
                            )
                            .join(' · ')}
                    </div>
                    {day.appointments.length > 0 ? (
                        day.appointments.map((appointment) =>
                            appointmentCard(appointment),
                        )
                    ) : (
                        <p className="p-2 text-xs text-muted-foreground">
                            No hay turnos registrados.
                        </p>
                    )}
                </div>
            ) : null}
        </article>
    );
}

function DayCard({ day, period }: { day: CalendarDay; period: Period }) {
    return (
        <article
            className={cn(
                'rounded-xl border bg-card p-4 shadow-sm',
                day.date === period.today && 'border-primary/50',
            )}
        >
            <Link
                className="block"
                href={index({ query: { view: 'day', date: day.date } })}
            >
                <p className="text-sm text-muted-foreground">
                    {dateLabel(day.date, { weekday: 'long' })}
                </p>
                <h2 className="font-semibold">
                    {dateLabel(day.date, { day: 'numeric', month: 'long' })}
                </h2>
            </Link>
            {day.published ? (
                <div className="mt-3 space-y-2">
                    <p className="text-xs text-muted-foreground">
                        Capacidad {day.capacity} ·{' '}
                        {day.blocks
                            .map(
                                (block) =>
                                    `${block.starts_at}–${block.ends_at}`,
                            )
                            .join(' · ')}
                    </p>
                    {day.appointments.length ? (
                        day.appointments.map((appointment) =>
                            appointmentCard(appointment),
                        )
                    ) : (
                        <p className="rounded-md bg-muted/50 p-3 text-sm text-muted-foreground">
                            No hay turnos registrados.
                        </p>
                    )}
                </div>
            ) : (
                <p className="mt-3 text-sm text-muted-foreground">
                    Sin agenda publicada.
                </p>
            )}
        </article>
    );
}

function DayView({ day }: { day: CalendarDay }) {
    if (!day.published) {
        return (
            <section className="rounded-xl border border-dashed bg-card p-8 text-center shadow-sm">
                <CalendarDays className="mx-auto size-8 text-muted-foreground" />
                <h2 className="mt-3 font-semibold">No hay agenda publicada</h2>
                <p className="mt-1 text-sm text-muted-foreground">
                    Esta fecha no tiene bloques ni horarios publicados.
                </p>
                <Button className="mt-4" asChild>
                    <Link href={create()}>
                        <CalendarPlus aria-hidden />
                        Publicar fecha
                    </Link>
                </Button>
            </section>
        );
    }

    const times = [
        ...day.blocks.flatMap((block) => [
            minutes(block.starts_at),
            minutes(block.ends_at),
        ]),
        ...day.appointments.flatMap((appointment) => [
            appointmentMinutes(appointment.starts_at),
            appointmentMinutes(appointment.ends_at),
        ]),
    ];
    const start = Math.floor((Math.min(...times) - 30) / 60) * 60;
    const end = Math.ceil((Math.max(...times) + 30) / 60) * 60;
    const height = Math.max((end - start) * 1.25, 420);
    const timeline = timelineAppointments(day);

    return (
        <section className="rounded-xl border bg-card shadow-sm">
            <header className="flex flex-col gap-3 border-b p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 className="font-semibold">
                        {dateLabel(day.date, {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                        })}
                    </h2>
                    <p className="text-sm text-muted-foreground">
                        Capacidad simultánea: {day.capacity} ·{' '}
                        {day.starts.length} inicios publicados
                    </p>
                </div>
                <Button variant="outline" asChild>
                    <Link href={edit(day.schedule_date_id!)}>
                        <Pencil aria-hidden />
                        Editar agenda
                    </Link>
                </Button>
            </header>
            <div className="overflow-x-auto">
                <div className="relative min-w-150" style={{ height }}>
                    <div className="absolute inset-y-0 right-0 left-14">
                        {Array.from(
                            { length: Math.ceil((end - start) / 60) + 1 },
                            (_, index) => (
                                <div
                                    className="absolute inset-x-0 border-t border-dashed"
                                    key={index}
                                    style={{ top: `${index * 75}px` }}
                                >
                                    <span className="absolute -top-2.5 -left-12 text-xs text-muted-foreground">
                                        {String(
                                            Math.floor(
                                                (start + index * 60) / 60,
                                            ),
                                        ).padStart(2, '0')}
                                        :00
                                    </span>
                                </div>
                            ),
                        )}
                        {day.blocks.map((block, index) => (
                            <div
                                className="absolute right-3 left-3 rounded-md border border-primary/20 bg-primary/10 px-2 py-1 text-xs text-primary"
                                key={`${block.starts_at}-${index}`}
                                style={{
                                    top: `${(minutes(block.starts_at) - start) * 1.25}px`,
                                    height: `${Math.max((minutes(block.ends_at) - minutes(block.starts_at)) * 1.25, 24)}px`,
                                }}
                            >
                                Bloque de trabajo · {block.starts_at}–
                                {block.ends_at}
                            </div>
                        ))}
                        {day.starts.map((slot) => (
                            <div
                                className="absolute right-3 left-3 border-t border-primary/30"
                                key={slot.starts_at}
                                style={{
                                    top: `${(minutes(slot.starts_at) - start) * 1.25}px`,
                                }}
                            >
                                <span className="absolute -top-4 right-1 bg-card px-1 text-[10px] text-muted-foreground">
                                    Inicio publicado {slot.starts_at}
                                </span>
                            </div>
                        ))}
                        {timeline.appointments.map(
                            ({ appointment, lane, startsAt }) => {
                                const width = 100 / timeline.laneCount;
                                const offset = (startsAt - start) * 1.25;

                                return (
                                    <Link
                                        className={cn(
                                            'absolute z-10 overflow-hidden rounded-md border px-3 py-2 shadow-sm transition-opacity hover:opacity-80',
                                            statusClasses[appointment.status],
                                        )}
                                        href={showAppointment(appointment.id)}
                                        key={appointment.id}
                                        style={{
                                            top: `${offset}px`,
                                            left: `calc(${lane * width}% + 6px)`,
                                            width: `calc(${width}% - 12px)`,
                                            height: `${Math.max(appointment.duration_minutes * 1.25, 36)}px`,
                                        }}
                                    >
                                        <div className="flex justify-between gap-3 text-xs font-semibold">
                                            <span>
                                                {time(appointment.starts_at)}–
                                                {time(appointment.ends_at)}
                                            </span>
                                            <span>
                                                {
                                                    statusLabels[
                                                        appointment.status
                                                    ]
                                                }
                                            </span>
                                        </div>
                                        <p className="truncate text-sm font-semibold">
                                            {appointment.pet.name} ·{' '}
                                            {appointment.service.name}
                                        </p>
                                    </Link>
                                );
                            },
                        )}
                    </div>
                </div>
            </div>
            {day.appointments.length === 0 ? (
                <p className="border-t p-4 text-sm text-muted-foreground">
                    No hay turnos registrados para esta fecha.
                </p>
            ) : null}
        </section>
    );
}

export default function ScheduleDatesIndex({
    days,
    period,
    summary,
}: {
    days: CalendarDay[];
    period: Period;
    summary: Summary;
}) {
    const selectedDay = days[0];
    const periodTitle =
        period.view === 'month'
            ? dateLabel(period.date, { month: 'long', year: 'numeric' })
            : period.view === 'week'
              ? `${dateLabel(period.starts_on, { day: 'numeric', month: 'short' })} – ${dateLabel(period.ends_on, { day: 'numeric', month: 'short', year: 'numeric' })}`
              : dateLabel(period.date, {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                });
    const navigate = (view: CalendarView, date: string) =>
        index({ query: { view, date } });

    return (
        <>
            <Head title="Agenda" />
            <div className="workspace-operational space-y-6">
                <PageHeader
                    title="Agenda"
                    description="Operación diaria de fechas publicadas, capacidad y turnos."
                    actions={
                        <div className="flex flex-wrap gap-2">
                            <Button variant="outline" asChild>
                                <Link href={templatesIndex()}>
                                    <CopyPlus aria-hidden />
                                    Plantillas
                                </Link>
                            </Button>
                            <Button variant="outline" asChild>
                                <Link href={createAppointment()}>
                                    <Plus aria-hidden />
                                    Crear turno
                                </Link>
                            </Button>
                            <Button asChild>
                                <Link href={create()}>
                                    <CalendarPlus aria-hidden />
                                    Publicar fecha
                                </Link>
                            </Button>
                        </div>
                    }
                />
                <section className="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                    <SummaryCard
                        label="Fechas publicadas"
                        value={summary.published_dates}
                        detail="en el período"
                    />
                    <SummaryCard
                        label="Turnos activos"
                        value={summary.active_appointments}
                        detail={`${summary.pending} pendientes · ${summary.approved} aprobados`}
                    />
                    <SummaryCard
                        label="Carga máxima"
                        value={`${summary.load_percent}%`}
                        detail={`${summary.maximum_active_occupancy} ocupando en simultáneo`}
                    />
                    <SummaryCard
                        label="Atendidos"
                        value={summary.attended}
                        detail="histórico del período"
                    />
                    <SummaryCard
                        label="Cierres"
                        value={
                            summary.absent +
                            summary.cancelled +
                            summary.rejected
                        }
                        detail={`${summary.absent} ausentes · ${summary.cancelled + summary.rejected} no concretados`}
                    />
                </section>
                <section className="rounded-xl border bg-card p-3 shadow-sm sm:p-4">
                    <div className="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div className="flex items-center justify-between gap-2">
                            <div className="flex gap-1">
                                <Button
                                    aria-label="Período anterior"
                                    size="icon"
                                    variant="outline"
                                    asChild
                                >
                                    <Link
                                        href={navigate(
                                            period.view,
                                            period.previous_date,
                                        )}
                                    >
                                        <ChevronLeft />
                                    </Link>
                                </Button>
                                <Button variant="outline" asChild>
                                    <Link
                                        href={navigate(
                                            period.view,
                                            period.today,
                                        )}
                                    >
                                        Hoy
                                    </Link>
                                </Button>
                                <Button
                                    aria-label="Período siguiente"
                                    size="icon"
                                    variant="outline"
                                    asChild
                                >
                                    <Link
                                        href={navigate(
                                            period.view,
                                            period.next_date,
                                        )}
                                    >
                                        <ChevronRight />
                                    </Link>
                                </Button>
                            </div>
                            <h2 className="text-right text-sm font-semibold capitalize sm:text-base">
                                {periodTitle}
                            </h2>
                        </div>
                        <div className="grid grid-cols-3 rounded-lg border p-1">
                            <Link
                                className={cn(
                                    'rounded-md px-3 py-1.5 text-center text-sm font-medium',
                                    period.view === 'month'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'hover:bg-muted',
                                )}
                                href={navigate('month', period.date)}
                            >
                                Mes
                            </Link>
                            <Link
                                className={cn(
                                    'rounded-md px-3 py-1.5 text-center text-sm font-medium',
                                    period.view === 'week'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'hover:bg-muted',
                                )}
                                href={navigate('week', period.date)}
                            >
                                Semana
                            </Link>
                            <Link
                                className={cn(
                                    'rounded-md px-3 py-1.5 text-center text-sm font-medium',
                                    period.view === 'day'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'hover:bg-muted',
                                )}
                                href={navigate('day', period.date)}
                            >
                                Día
                            </Link>
                        </div>
                    </div>
                </section>
                {period.view === 'month' ? (
                    <MonthView days={days} period={period} />
                ) : null}
                {period.view === 'week' ? (
                    <WeekView days={days} period={period} />
                ) : null}
                {period.view === 'day' && selectedDay ? (
                    <DayView day={selectedDay} />
                ) : null}
                <section className="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-muted-foreground">
                    <span className="font-medium text-foreground">Estados</span>
                    {Object.entries(statusLabels).map(([status, label]) => (
                        <span
                            className="flex items-center gap-1.5"
                            key={status}
                        >
                            <i
                                className={cn(
                                    'size-2 rounded-full',
                                    statusClasses[status as AppointmentStatus]
                                        .split(' ')
                                        .find((className) =>
                                            className.startsWith('bg-'),
                                        ),
                                )}
                            />
                            {label}
                        </span>
                    ))}
                    <span className="ml-auto flex items-center gap-1">
                        <Users className="size-3.5" />
                        La carga mide ocupación simultánea.
                    </span>
                </section>
            </div>
        </>
    );
}

function SummaryCard({
    label,
    value,
    detail,
}: {
    label: string;
    value: number | string;
    detail: string;
}) {
    return (
        <article className="rounded-xl border bg-card p-4 shadow-sm">
            <p className="text-sm text-muted-foreground">{label}</p>
            <p className="mt-1 text-2xl font-semibold tracking-tight">
                {value}
            </p>
            <p className="mt-1 text-xs text-muted-foreground">{detail}</p>
        </article>
    );
}

ScheduleDatesIndex.layout = {
    breadcrumbs: [
        { title: 'Inicio', href: dashboard() },
        { title: 'Agenda', href: index() },
    ],
};
