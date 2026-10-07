import { Link, usePage } from '@inertiajs/react';
import { HeartPulse, PawPrint, ShieldCheck } from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import type { ReactNode } from 'react';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types';

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    const { name, logoUrl } = usePage<{ name: string; logoUrl: string }>()
        .props;

    return (
        <main className="min-h-svh bg-surface-subtle text-foreground">
            <div className="grid min-h-svh lg:grid-cols-[minmax(0,1.2fr)_minmax(28rem,0.8fr)]">
                <section className="relative hidden overflow-hidden bg-surface-base p-12 lg:flex lg:flex-col xl:p-16">
                    <div className="absolute -top-32 -left-32 size-96 rounded-full bg-operational/20 blur-3xl" />
                    <div className="absolute right-0 -bottom-24 size-80 rounded-full bg-primary/15 blur-3xl" />
                    <div className="absolute top-1/2 -right-24 size-96 -translate-y-1/2 rounded-full border border-primary/15" />
                    <div className="absolute top-1/2 -right-8 size-64 -translate-y-1/2 rounded-full border border-dashed border-primary/20" />

                    <Link
                        href={home()}
                        className="relative z-10 flex w-fit items-center gap-4 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-4 focus-visible:outline-none"
                    >
                        <span className="flex size-16 items-center justify-center rounded-full bg-card p-2 shadow-sm">
                            <img
                                alt={`Logo de ${name}`}
                                className="size-full object-contain"
                                src={logoUrl}
                            />
                        </span>
                        <span>
                            <span className="block text-2xl font-bold tracking-tight">
                                {name}
                            </span>
                            <span className="mt-1 block text-xs font-semibold tracking-[0.16em] text-primary uppercase">
                                Gestión veterinaria
                            </span>
                        </span>
                    </Link>

                    <div className="relative z-10 my-auto max-w-xl">
                        <span className="inline-flex items-center gap-2 rounded-full bg-card/80 px-3.5 py-1.5 text-xs font-semibold text-muted-foreground shadow-sm">
                            <span className="size-2 rounded-full bg-primary" />
                            Acompañamiento veterinario integral
                        </span>
                        <h2 className="mt-6 text-4xl leading-tight font-semibold tracking-tight text-balance xl:text-5xl">
                            Bienestar animal y seguimiento en cada etapa.
                        </h2>
                        <p className="mt-5 max-w-lg text-lg leading-8 text-muted-foreground">
                            Una plataforma serena y clara para acompañar a cada
                            paciente, su familia y el equipo profesional.
                        </p>
                        <div className="mt-10 grid max-w-lg grid-cols-2 gap-4">
                            <Feature
                                icon={HeartPulse}
                                title="Seguimiento claro"
                            >
                                Información organizada para cada tratamiento.
                            </Feature>
                            <Feature icon={PawPrint} title="Atención cercana">
                                El bienestar de cada mascota siempre primero.
                            </Feature>
                        </div>
                    </div>

                    <p className="relative z-10 flex items-center gap-2 text-sm text-muted-foreground">
                        <ShieldCheck
                            className="size-4 text-primary"
                            aria-hidden
                        />
                        Acceso seguro para profesionales y clientes autorizados.
                    </p>
                </section>

                <section className="flex min-w-0 items-center justify-center bg-card px-5 py-10 sm:px-8 lg:px-12 xl:px-16">
                    <div className="w-full max-w-md">
                        <Link
                            href={home()}
                            className="mb-8 flex items-center gap-3 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-4 focus-visible:outline-none lg:hidden"
                        >
                            <img
                                alt={`Logo de ${name}`}
                                className="size-11 object-contain"
                                src={logoUrl}
                            />
                            <span>
                                <span className="block text-xl font-bold tracking-tight">
                                    {name}
                                </span>
                                <span className="block text-xs font-semibold tracking-[0.12em] text-primary uppercase">
                                    Gestión veterinaria
                                </span>
                            </span>
                        </Link>

                        <header className="mb-8">
                            <h1 className="text-3xl font-bold tracking-tight text-balance">
                                {title}
                            </h1>
                            <p className="mt-2 leading-6 text-muted-foreground">
                                {description}
                            </p>
                        </header>

                        <div className="rounded-2xl border border-border-subtle bg-card p-5 shadow-sm sm:p-7 lg:border-0 lg:p-0 lg:shadow-none">
                            {children}
                        </div>
                    </div>
                </section>
            </div>
        </main>
    );
}

function Feature({
    icon: Icon,
    title,
    children,
}: {
    icon: LucideIcon;
    title: string;
    children: ReactNode;
}) {
    return (
        <div className="rounded-xl bg-card/80 p-4 shadow-sm backdrop-blur-sm">
            <Icon className="size-5 text-primary" aria-hidden />
            <p className="mt-4 font-semibold">{title}</p>
            <p className="mt-1 text-sm leading-5 text-muted-foreground">
                {children}
            </p>
        </div>
    );
}
