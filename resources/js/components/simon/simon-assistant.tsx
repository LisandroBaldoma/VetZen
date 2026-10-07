import { usePage } from '@inertiajs/react';
import { Info, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetTitle } from '@/components/ui/sheet';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { useCurrentUrl } from '@/hooks/use-current-url';
import { getNavigationSection, getSimonModule } from '@/lib/navigation';
import type { NavigationSection } from '@/lib/navigation';
import type { Auth } from '@/types';
import { SimonChat } from './simon-chat';
import type { SimonModule } from './simon-chat';

const sectionLabels: Partial<Record<NavigationSection, string>> = {
    clients: 'Clientes',
    'medical-records': 'Historia clínica',
    pets: 'Pacientes',
    procedures: 'Procedimientos',
    'service-requests': 'Solicitudes',
    services: 'Servicios',
    treatments: 'Tratamientos',
};

function useDesktopPanel(): boolean {
    const [isDesktop, setIsDesktop] = useState(false);

    useEffect(() => {
        const query = window.matchMedia('(min-width: 768px)');
        const update = () => setIsDesktop(query.matches);

        update();
        query.addEventListener('change', update);

        return () => query.removeEventListener('change', update);
    }, []);

    return isDesktop;
}

type PanelProps = {
    contextLabel: string;
    module?: SimonModule;
    simonLogoUrl: string;
    onClose: () => void;
};

function SimonPanel({
    contextLabel,
    module,
    simonLogoUrl,
    onClose,
}: PanelProps) {
    return (
        <div className="flex min-h-0 flex-1 flex-col bg-card">
            <header className="border-b border-border bg-card px-4 py-4">
                <div className="flex items-start justify-between gap-3">
                    <div className="flex min-w-0 items-center gap-3">
                        <div className="relative shrink-0">
                            <img
                                src={simonLogoUrl}
                                alt="Simón"
                                className="size-11 rounded-full object-cover shadow-sm ring-2 ring-primary/20"
                            />
                            <span className="absolute right-0 bottom-0 size-3 rounded-full border-2 border-card bg-emerald-500" />
                        </div>
                        <div className="min-w-0">
                            <div className="flex flex-wrap items-center gap-2">
                                <h2 className="text-sm font-bold text-foreground">
                                    Simón
                                </h2>
                                <span className="rounded-full border border-primary/15 bg-primary/10 px-2 py-0.5 text-[10px] font-bold text-primary">
                                    Asistente VetZen
                                </span>
                            </div>
                            <p className="mt-0.5 text-xs text-muted-foreground">
                                Soporte y guía operativa
                            </p>
                        </div>
                    </div>
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        onClick={onClose}
                        aria-label="Cerrar panel de Simón"
                        className="size-9 shrink-0 text-muted-foreground"
                    >
                        <X />
                    </Button>
                </div>
                <div className="mt-3 flex items-center gap-2 border-t border-border/70 pt-3">
                    <span className="inline-flex min-w-0 items-center gap-1.5 rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                        <Info aria-hidden className="size-3.5 shrink-0" />
                        <span className="truncate">
                            Estás en: {contextLabel}
                        </span>
                    </span>
                    <span className="ml-auto shrink-0 text-[10px] text-muted-foreground">
                        Contexto activo
                    </span>
                </div>
            </header>
            <SimonChat compact module={module} />
        </div>
    );
}

export function SimonAssistant() {
    const { auth, simonLogoUrl } = usePage<{
        auth: Auth;
        simonLogoUrl: string;
    }>().props;
    const { currentUrl } = useCurrentUrl();
    const isDesktop = useDesktopPanel();
    const [open, setOpen] = useState(false);
    const role = auth.roles.includes('admin')
        ? 'admin'
        : auth.roles.includes('client')
          ? 'client'
          : null;
    const section = getNavigationSection(currentUrl, role);
    const module = getSimonModule(section);
    const contextLabel = section
        ? (sectionLabels[section] ?? 'VetZen')
        : 'VetZen';

    const trigger = (
        <Tooltip>
            <TooltipTrigger asChild>
                <button
                    type="button"
                    onClick={() => setOpen(true)}
                    aria-label="Abrir asistente Simón"
                    className="fixed right-4 bottom-[calc(4.75rem+env(safe-area-inset-bottom))] z-40 flex size-14 items-center justify-center rounded-full border border-border bg-card p-1.5 shadow-[0_8px_24px_rgb(38_51_40_/_0.16)] transition-[transform,box-shadow] hover:scale-105 hover:shadow-[0_12px_32px_rgb(38_51_40_/_0.2)] focus-visible:ring-3 focus-visible:ring-ring focus-visible:outline-none md:right-6 md:bottom-6"
                >
                    <img
                        src={simonLogoUrl}
                        alt=""
                        className="size-full rounded-full object-cover"
                    />
                </button>
            </TooltipTrigger>
            <TooltipContent side="left">Preguntale a Simón</TooltipContent>
        </Tooltip>
    );

    return (
        <>
            {isDesktop && open && (
                <aside className="hidden h-[100dvh] w-[400px] shrink-0 animate-in border-l border-border bg-card shadow-[-8px_0_24px_rgb(38_51_40_/_0.06)] duration-200 slide-in-from-right-4 md:flex">
                    <SimonPanel
                        contextLabel={contextLabel}
                        module={module}
                        simonLogoUrl={simonLogoUrl}
                        onClose={() => setOpen(false)}
                    />
                </aside>
            )}

            {!isDesktop && (
                <Sheet open={open} onOpenChange={setOpen}>
                    <SheetContent
                        side="right"
                        className="w-[calc(100%-1rem)] max-w-[400px] gap-0 p-0 [&>[data-slot=sheet-close]]:hidden"
                    >
                        <SheetTitle className="sr-only">
                            Asistente Simón
                        </SheetTitle>
                        <SimonPanel
                            contextLabel={contextLabel}
                            module={module}
                            simonLogoUrl={simonLogoUrl}
                            onClose={() => setOpen(false)}
                        />
                    </SheetContent>
                </Sheet>
            )}

            {!open && trigger}
        </>
    );
}
