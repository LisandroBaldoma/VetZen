export type DashboardPet = {
    id: number;
    name: string;
    species: string;
};

export type DashboardRequest = {
    id: number;
    status: 'pending' | 'resolved' | 'cancelled';
    notes?: string | null;
    createdAt: string;
    pet: {
        id: number;
        name: string;
        species?: string;
    };
    service: {
        id: number;
        name: string;
    };
};

export type DashboardTreatment = {
    id: number;
    treatmentName: string;
    status: 'pending' | 'in_progress' | 'suspended';
    plannedSessions: number;
    completedSessions: number;
    nextSession: {
        scheduledAt: string;
        sessionNumber: number;
    } | null;
    pet: {
        id: number;
        name: string;
    };
};

export type DashboardSelectedPet = {
    id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birthDate: string | null;
    weight: string | null;
    hasPhoto: boolean;
    activeTreatmentsCount: number;
};

export type DashboardNextSession = {
    treatmentId: number;
    treatmentName: string;
    sessionNumber: number;
    plannedSessions: number;
    scheduledAt: string;
    status: 'pending';
};

export type AdminDashboardProps = {
    pendingRequestsCount: number;
    requests: DashboardRequest[];
};

export type ClientDashboardProps = {
    pets: DashboardPet[];
    selectedPet: DashboardSelectedPet | null;
    nextSession: DashboardNextSession | null;
    pendingRequests: DashboardRequest[];
    activeTreatments: DashboardTreatment[];
    timezone: string;
};
