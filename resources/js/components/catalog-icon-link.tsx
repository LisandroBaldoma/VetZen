import { Link } from '@inertiajs/react';
import type { InertiaLinkProps } from '@inertiajs/react';
import type { LucideIcon } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';

type Props = {
    href: InertiaLinkProps['href'];
    label: string;
    icon: LucideIcon;
};

export default function CatalogIconLink({ href, label, icon: Icon }: Props) {
    return (
        <Tooltip>
            <TooltipTrigger asChild>
                <Button
                    size="icon"
                    variant="outline"
                    className="size-8"
                    asChild
                >
                    <Link href={href} aria-label={label}>
                        <Icon aria-hidden="true" />
                    </Link>
                </Button>
            </TooltipTrigger>
            <TooltipContent>{label}</TooltipContent>
        </Tooltip>
    );
}
