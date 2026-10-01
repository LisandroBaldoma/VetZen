import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

type Props = {
    desktop: ReactNode;
    mobile: ReactNode;
    className?: string;
    desktopClassName?: string;
    mobileClassName?: string;
};

export function ResponsiveDataList({
    desktop,
    mobile,
    className,
    desktopClassName,
    mobileClassName,
}: Props) {
    return (
        <div className={cn('min-w-0', className)}>
            <div className={cn('hidden lg:block', desktopClassName)}>
                {desktop}
            </div>
            <div className={cn('lg:hidden', mobileClassName)}>{mobile}</div>
        </div>
    );
}
