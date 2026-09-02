export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    client?: Client;
    [key: string]: unknown;
};

export type Client = {
    id: number;
    user_id: number;
    phone: string;
    address: string | null;
    city: string | null;
    province: string | null;
    postal_code: string | null;
    document: string | null;
    birth_date: string | null;
};

export type Pet = {
    id: number;
    client_id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birth_date: string | null;
    weight: string | null;
    color: string | null;
    notes: string | null;
    photo: string | null;
};

export type PetCard = Pick<Pet, 'id' | 'name' | 'species' | 'breed'> & {
    has_photo: boolean;
};

export type PetContext = Omit<Pet, 'client_id' | 'photo'> & {
    client_id?: number;
    photo?: string | null;
    has_photo?: boolean;
    client?: {
        id: number;
        name?: string;
        user?: Pick<User, 'name'>;
    };
};

export type ClinicalRecord = {
    id: number;
    pet_id: number;
    created_by: number;
    updated_by: number;
    type: 'consultation' | 'evaluation' | 'evolution' | 'session' | 'other';
    title: string;
    content: string;
    occurred_at: string;
    is_visible_to_client: boolean;
    created_at: string;
    updated_at: string;
    creator?: Pick<User, 'id' | 'name'>;
    updater?: Pick<User, 'id' | 'name'>;
};

export type Auth = {
    user: User;
    roles: string[];
};

export type Passkey = {
    id: number;
    name: string;
    authenticator: string | null;
    created_at_diff: string;
    last_used_at_diff: string | null;
};

export type TwoFactorSetupData = {
    svg: string;
    url: string;
};

export type TwoFactorSecretKey = {
    secretKey: string;
};
