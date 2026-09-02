import { Link } from '@inertiajs/react';
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
import type { PetContext } from '@/types';

type Section =
    'summary' | 'medical-records' | 'service-requests' | 'treatments';

type Props = {
    pet: PetContext;
    variant: 'admin' | 'client';
    active: Section;
    editHref: string;
};

const dateFormatter = new Intl.DateTimeFormat('es-AR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

function formatBirthDate(date: string): string {
    return dateFormatter.format(new Date(`${date.slice(0, 10)}T00:00:00`));
}

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
              },
              {
                  key: 'medical-records' as const,
                  label: 'Historia clínica',
                  href: adminMedicalRecordsIndex(pet.id),
              },
              {
                  key: 'treatments' as const,
                  label: 'Tratamientos',
                  href: adminTreatmentsIndex(pet.id),
              },
          ]
        : [
              {
                  key: 'summary' as const,
                  label: 'Resumen',
                  href: petShow(pet.id),
              },
              {
                  key: 'medical-records' as const,
                  label: 'Historia clínica',
                  href: medicalRecordsIndex(pet.id),
              },
              {
                  key: 'service-requests' as const,
                  label: 'Solicitudes de atención',
                  href: serviceRequestsIndex(pet.id),
              },
              {
                  key: 'treatments' as const,
                  label: 'Tratamientos',
                  href: treatmentsIndex(pet.id),
              },
          ];

    return (
        <header className="overflow-hidden rounded-2xl border bg-card shadow-sm">
            <div className="flex flex-col gap-5 p-4 sm:flex-row sm:items-center sm:p-6">
                <Avatar className="size-20 rounded-2xl border sm:size-24">
                    {hasPhoto && (
                        <AvatarImage
                            src={photo.url(pet.id)}
                            alt={`Foto de ${pet.name}`}
                            className="object-cover"
                        />
                    )}
                    <AvatarFallback
                        className="rounded-2xl text-2xl font-semibold"
                        aria-label={`${pet.name} no tiene foto`}
                    >
                        {pet.name.slice(0, 1).toUpperCase()}
                    </AvatarFallback>
                </Avatar>

                <div className="min-w-0 flex-1 space-y-2">
                    <div>
                        <p className="text-sm font-medium text-muted-foreground">
                            {isAdmin ? 'Paciente' : 'Mascota'}
                        </p>
                        <h1 className="text-2xl font-semibold tracking-tight break-words">
                            {pet.name}
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            {pet.species}
                            {pet.breed ? ` · ${pet.breed}` : ''}
                        </p>
                    </div>

                    <div className="flex flex-wrap gap-x-4 gap-y-1 text-sm">
                        <span>{formatSex(pet.sex)}</span>
                        {pet.birth_date && (
                            <span>
                                Nacimiento: {formatBirthDate(pet.birth_date)}
                            </span>
                        )}
                        {isAdmin && responsibleName && pet.client && (
                            <span>
                                Responsable:{' '}
                                <Link
                                    href={editClient(pet.client.id)}
                                    className="font-medium underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                >
                                    {responsibleName}
                                </Link>
                            </span>
                        )}
                    </div>
                </div>

                <Button
                    asChild
                    variant="outline"
                    className="min-h-11 sm:self-start"
                >
                    <Link href={editHref}>
                        {isAdmin ? 'Editar paciente' : 'Editar mascota'}
                    </Link>
                </Button>
            </div>

            <nav
                aria-label={`Secciones de ${pet.name}`}
                className="overflow-x-auto border-t px-2 sm:px-4"
            >
                <div className="flex min-w-max gap-1">
                    {navItems.map((item) => {
                        const isActive = active === item.key;

                        return (
                            <Link
                                key={item.key}
                                href={item.href}
                                aria-current={isActive ? 'page' : undefined}
                                className={cn(
                                    'flex min-h-11 items-center border-b-2 px-3 text-sm whitespace-nowrap transition-colors focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none',
                                    isActive
                                        ? 'border-primary bg-muted/60 font-semibold text-foreground'
                                        : 'border-transparent text-muted-foreground hover:bg-muted/40 hover:text-foreground',
                                )}
                            >
                                {item.label}
                            </Link>
                        );
                    })}
                </div>
            </nav>
        </header>
    );
}
