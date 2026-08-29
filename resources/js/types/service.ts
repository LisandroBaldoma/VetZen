export type Service = {
    id: number;
    name: string;
    description: string;
    duration_minutes: number | null;
    price: string | null;
    currency: 'ARS';
    modalities: ('clinic' | 'online' | 'home_visit')[];
    is_active?: boolean;
};
