import { Link } from '@inertiajs/react';
import { Fragment } from 'react';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

export function Breadcrumbs({
    breadcrumbs,
}: {
    breadcrumbs: BreadcrumbItemType[];
}) {
    return (
        <>
            {breadcrumbs.length > 0 && (
                <Breadcrumb aria-label="Migas de pan" className="min-w-0">
                    <BreadcrumbList className="min-w-0 flex-nowrap overflow-hidden">
                        {breadcrumbs.map((item, index) => {
                            const isLast = index === breadcrumbs.length - 1;
                            const hideOnMobile = index < breadcrumbs.length - 2;

                            return (
                                <Fragment key={index}>
                                    <BreadcrumbItem
                                        className={`min-w-0 ${hideOnMobile ? 'hidden sm:inline-flex' : ''}`}
                                    >
                                        {isLast ? (
                                            <BreadcrumbPage className="truncate">
                                                {item.title}
                                            </BreadcrumbPage>
                                        ) : (
                                            <BreadcrumbLink
                                                asChild
                                                className="truncate"
                                            >
                                                <Link href={item.href}>
                                                    {item.title}
                                                </Link>
                                            </BreadcrumbLink>
                                        )}
                                    </BreadcrumbItem>
                                    {!isLast && (
                                        <BreadcrumbSeparator
                                            className={
                                                hideOnMobile
                                                    ? 'hidden sm:list-item'
                                                    : undefined
                                            }
                                        />
                                    )}
                                </Fragment>
                            );
                        })}
                    </BreadcrumbList>
                </Breadcrumb>
            )}
        </>
    );
}
