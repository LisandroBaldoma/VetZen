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

export type Auth = {
    user: User;
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
