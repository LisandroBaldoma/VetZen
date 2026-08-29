export type Procedure = {
    id: number;
    name: string;
    description: string | null;
    duration_minutes: number | null;
    is_active: boolean;
};

export type CommercialProcedure = Omit<Procedure, 'is_active'>;

export type Service = {
    id: number;
    name: string;
    description: string;
    is_active?: boolean;
    procedures?: CommercialProcedure[];
};
