import { Link } from '@inertiajs/react';
import {
    ClipboardList,
    FileText,
    LayoutDashboard,
    PawPrint,
    Pencil,
    Stethoscope,
    UserRound,
} from 'lucide-react';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { edit as editClient } from '@/routes/admin/clients';
import { show as adminPetShow } from '@/routes/admin/pets';
import { index as adminMedicalRecordsIndex } from '@/routes/admin/pets/medical-records';
import { index as adminTreatmentsIndex } from '@/routes/admin/pets/treatments';
import { photo, show as petShow } from '@/routes/pets';
import { index as medicalRecordsIndex } from '@/routes/pets/medical-records';
import { index as serviceRequestsIndex } from '@/routes/pets/service-requests';
import { index as treatmentsIndex } from '@/routes/pets/treatments';

type PetContext = {
    id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birth_date: string | null;
    has_photo?: boolean;
    photo?: string | null;
    client?: {
        id: number;
        name?: string;
        user?: { name: string };
    };
};

type Section =
    'summary' | 'medical-records' | 'service-requests' | 'treatments';

type Props = {
    pet: PetContext;
    variant: 'admin' | 'client';
    active: Section;
    editHref: string;
};

function formatSex(sex: string): string {
    if (sex.toLowerCase() === 'female') {
        return 'Hembra';
    }

    if (sex.toLowerCase() === 'male') {
        return 'Macho';
    }

    return sex;
}

export default function PetContextHeader({
    pet,
    variant,
    active,
    editHref,
}: Props) {
    const isAdmin = variant === 'admin';
    const responsibleName = pet.client?.name ?? pet.client?.user?.name;
    const hasPhoto = pet.has_photo ?? Boolean(pet.photo);
    const navItems = isAdmin
        ? [
              {
                  key: 'summary' as const,
                  label: 'Resumen',
                  href: adminPetShow(pet.id),
                  icon: LayoutDashboard,
              },
              {
                  key: 'medical-records' as const,
                  label: 'Historia clínica',
                  href: adminMedicalRecordsIndex(pet.id),
                  icon: FileText,
              },
              {
                  key: 'treatments' as const,
                  label: 'Tratamientos',
                  href: adminTreatmentsIndex(pet.id),
                  icon: Stethoscope,
              },
          ]
        : [
              {
                  key: 'summary' as const,
                  label: 'Resumen',
                  href: petShow(pet.id),
                  icon: LayoutDashboard,
              },
              {
                  key: 'medical-records' as const,
                  label: 'Historia clínica',
                  href: medicalRecordsIndex(pet.id),
                  icon: FileText,
              },
              {
                  key: 'service-requests' as const,
                  label: 'Solicitudes de atención',
                  href: serviceRequestsIndex(pet.id),
                  icon: ClipboardList,
              },
              {
                  key: 'treatments' as const,
                  label: 'Tratamientos',
                  href: treatmentsIndex(pet.id),
                  icon: Stethoscope,
              },
          ];

    return (
        <header className="space-y-4">
            <section className="rounded-xl border bg-card p-4 shadow-sm sm:p-5">
                <div className="flex min-w-0 items-start gap-3.5">
                    <div className="relative shrink-0">
                        <Avatar className="size-16 rounded-full border border-border bg-surface-subtle shadow-sm">
                            {hasPhoto && (
                                <AvatarImage
                                    src={photo.url(pet.id)}
                                    alt={`Foto de ${pet.name}`}
                                    className="object-cover"
                                />
                            )}
                            <AvatarFallback
                                className="rounded-full bg-surface-subtle text-xl font-semibold text-primary"
                                aria-label={`${pet.name} no tiene foto`}
                            >
                                {pet.name.slice(0, 1).toUpperCase()}
                            </AvatarFallback>
                        </Avatar>
                        <span className="absolute right-0 bottom-0 flex size-4 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-sm">
                            <PawPrint aria-hidden className="size-2.5" />
                        </span>
                    </div>

                    <div className="min-w-0 flex-1">
                        <div className="flex flex-wrap items-center gap-2">
                            <h1 className="text-section-title font-semibold tracking-tight text-balance break-words">
                                {pet.name}
                            </h1>
                            <span className="rounded-full bg-surface-subtle px-2 py-0.5 text-xs font-semibold text-muted-foreground">
                                {pet.species}
                            </span>
                        </div>
                        <p className="mt-0.5 text-sm text-muted-foreground">
                            {pet.breed ?? 'Raza no informada'} ·{' '}
                            {formatSex(pet.sex)}
                        </p>
                        {isAdmin && responsibleName && pet.client && (
                            <div className="mt-2 flex min-w-0 items-center gap-1.5 text-sm text-muted-foreground">
                                <UserRound
                                    aria-hidden
                                    className="size-4 shrink-0"
                                />
                                <span className="shrink-0">Responsable:</span>
                                <Link
                                    href={editClient(pet.client.id)}
                                    className="min-w-0 truncate font-semibold text-foreground underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                >
                                    {responsibleName}
                                </Link>
                            </div>
                        )}
                    </div>
                </div>

                <div className="mt-4 flex justify-end border-t pt-3.5">
                    <Button
                        asChild
                        variant="secondary"
                        className="min-h-11 rounded-full"
                    >
                        <Link href={editHref}>
                            <Pencil aria-hidden className="size-4" />
                            {isAdmin ? 'Editar paciente' : 'Editar mascota'}
                        </Link>
                    </Button>
                </div>
            </section>

            <nav
                aria-label={`Secciones de ${pet.name}`}
                className="overflow-x-auto pb-1"
            >
                <div className="flex min-w-max items-center gap-2">
                    {navItems.map((item) => {
                        const isActive = active === item.key;
                        const Icon = item.icon;

                        return (
                            <Link
                                key={item.key}
                                href={item.href}
                                aria-current={isActive ? 'page' : undefined}
                                className={cn(
                                    'inline-flex min-h-11 items-center gap-1.5 rounded-full px-3.5 text-sm font-semibold whitespace-nowrap transition-[color,background-color,box-shadow,transform] focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none active:scale-[0.98]',
                                    isActive
                                        ? 'bg-primary text-primary-foreground shadow-sm'
                                        : 'bg-card text-muted-foreground shadow-sm hover:bg-surface-subtle hover:text-foreground',
                                )}
                            >
                                <Icon aria-hidden className="size-4" />
                                {item.label}
                            </Link>
                        );
                    })}
                </div>
            </nav>
        </header>
    );
}
