import { Button } from '@/components/ui/button';

export default function GoogleAuthButton({
    href,
    label = 'Continuar con Google',
}: {
    href: string;
    label?: string;
}) {
    return (
        <Button type="button" variant="outline" className="w-full" asChild>
            <a href={href}>
                <span
                    aria-hidden
                    className="flex size-5 items-center justify-center rounded-full bg-white text-xs font-bold text-slate-700 shadow-sm ring-1 ring-slate-200"
                >
                    G
                </span>
                {label}
            </a>
        </Button>
    );
}
