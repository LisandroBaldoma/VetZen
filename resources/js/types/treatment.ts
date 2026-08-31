import type { Pet } from './auth';
import type { Procedure, Service } from './service';

export type Treatment = {
    id: number;
    service_id: number;
    name: string;
    description: string;
    estimated_sessions: number;
    is_active: boolean;
    procedures?: Procedure[];
    procedures_count?: number;
    service?: Service;
};
export type TreatmentSession = {
    id: number;
    session_number: number;
    scheduled_at: string | null;
    price: string;
    currency: string;
    status: 'pending' | 'completed' | 'cancelled';
    notes: string | null;
};
export type PetTreatment = {
    id: number;
    treatment_name: string;
    treatment_description: string;
    planned_sessions: number;
    default_session_price: string;
    currency: string;
    starts_on: string;
    status: string;
    notes: string | null;
    sessions?: TreatmentSession[];
    sessions_count?: number;
    completed_sessions_count?: number;
    procedure_snapshots?: {
        id: number;
        procedure_name: string;
        procedure_description: string | null;
    }[];
};
export type ServiceRequest = {
    id: number;
    status: 'pending' | 'resolved' | 'cancelled';
    notes: string | null;
    created_at: string;
    pet?: Pet;
    service?: Service;
    pet_treatment?: PetTreatment | null;
};
