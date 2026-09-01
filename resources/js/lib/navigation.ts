export type NavigationSection =
    | 'dashboard'
    | 'clients'
    | 'pets'
    | 'service-requests'
    | 'services'
    | 'procedures'
    | 'treatments';

const matches = (path: string, route: RegExp): boolean => route.test(path);

export function getNavigationSection(
    path: string,
    role: 'admin' | 'client' | null,
): NavigationSection | null {
    if (matches(path, /(?:^|\/)dashboard\/?$/)) {
        return 'dashboard';
    }

    if (role === 'admin') {
        if (matches(path, /(?:^|\/)admin\/clients(?:\/|$)/)) {
            return 'clients';
        }

        if (matches(path, /(?:^|\/)admin\/pets(?:\/|$)/)) {
            return 'pets';
        }

        if (matches(path, /(?:^|\/)admin\/service-requests(?:\/|$)/)) {
            return 'service-requests';
        }

        if (
            matches(path, /(?:^|\/)admin\/procedures(?:\/|$)/) ||
            matches(path, /(?:^|\/)admin\/services\/[^/]+\/procedures(?:\/|$)/)
        ) {
            return 'procedures';
        }

        if (
            matches(path, /(?:^|\/)admin\/treatments(?:\/|$)/) ||
            matches(path, /(?:^|\/)admin\/services\/[^/]+\/treatments(?:\/|$)/)
        ) {
            return 'treatments';
        }

        if (matches(path, /(?:^|\/)admin\/services(?:\/|$)/)) {
            return 'services';
        }
    }

    if (role === 'client') {
        if (matches(path, /(?:^|\/)pets(?:\/|$)/)) {
            return 'pets';
        }

        if (matches(path, /(?:^|\/)services(?:\/|$)/)) {
            return 'services';
        }
    }

    return null;
}
