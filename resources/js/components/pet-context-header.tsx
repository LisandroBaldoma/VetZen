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
        <header className="border-b border-border">
            <div className="grid gap-6 py-6 sm:grid-cols-[auto_minmax(0,1fr)] sm:items-center md:py-9 lg:grid-cols-[auto_minmax(0,1fr)_auto]">
                <Avatar className="size-20 rounded-2xl border border-clinical-foreground/15 bg-clinical/55 sm:size-24">
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

                <div className="min-w-0 space-y-4">
                    <div className="space-y-1.5">
                        <p className="text-[0.6875rem] font-semibold tracking-[0.16em] text-clinical-foreground uppercase">
                            {isAdmin
                                ? 'Expediente del paciente'
                                : 'Expediente de mi mascota'}
                        </p>
                        <h1 className="text-3xl font-semibold tracking-tight text-balance break-words sm:text-4xl">
                            {pet.name}
                        </h1>
                        <p className="text-base text-muted-foreground">
                            {pet.species}
                            {pet.breed ? ` · ${pet.breed}` : ''}
                        </p>
                    </div>

                    <dl className="flex flex-wrap gap-x-6 gap-y-3 text-sm">
                        <div>
                            <dt className="text-[0.625rem] font-semibold tracking-[0.12em] text-muted-foreground uppercase">
                                Sexo
                            </dt>
                            <dd className="mt-0.5 font-medium text-foreground">
                                {formatSex(pet.sex)}
                            </dd>
                        </div>
                        {pet.birth_date && (
                            <div>
                                <dt className="text-[0.625rem] font-semibold tracking-[0.12em] text-muted-foreground uppercase">
                                    Nacimiento
                                </dt>
                                <dd className="mt-0.5 text-foreground">
                                    {formatBirthDate(pet.birth_date)}
                                </dd>
                            </div>
                        )}
                        {isAdmin && responsibleName && pet.client && (
                            <div>
                                <dt className="text-[0.625rem] font-semibold tracking-[0.12em] text-muted-foreground uppercase">
                                    Responsable
                                </dt>
                                <dd className="mt-0.5">
                                    <Link
                                        href={editClient(pet.client.id)}
                                        className="font-medium underline-offset-4 hover:underline focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                                    >
                                        {responsibleName}
                                    </Link>
                                </dd>
                            </div>
                        )}
                    </dl>
                </div>

                <Button
                    asChild
                    variant="outline"
                    className="min-h-11 sm:col-start-2 lg:col-start-auto lg:self-start"
                >
                    <Link href={editHref}>
                        {isAdmin ? 'Editar paciente' : 'Editar mascota'}
                    </Link>
                </Button>
            </div>

            <nav
                aria-label={`Secciones de ${pet.name}`}
                className="overflow-x-auto"
            >
                <div className="flex min-w-max gap-6 sm:gap-8">
                    {navItems.map((item) => {
                        const isActive = active === item.key;

                        return (
                            <Link
                                key={item.key}
                                href={item.href}
                                aria-current={isActive ? 'page' : undefined}
                                className={cn(
                                    'flex min-h-12 items-center border-b-2 py-1 text-sm whitespace-nowrap transition-colors focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none',
                                    isActive
                                        ? 'border-clinical-foreground font-semibold text-foreground'
                                        : 'border-transparent text-muted-foreground hover:text-foreground',
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
