export type Procedure = {
    id: number;
    name: string;
    description: string | null;
    duration_minutes: number | null;
    is_active: boolean;
    service?: Pick<Service, 'id' | 'name'>;
};

export type CommercialProcedure = Omit<Procedure, 'is_active'>;

export type Service = {
    id: number;
    name: string;
    description: string;
    is_active?: boolean;
    procedures?: CommercialProcedure[];
    procedures_count?: number;
};

export type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export type Paginated<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
    links: PaginationLink[];
};
