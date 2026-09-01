export type DashboardPet = {
    id: number;
    name: string;
    species: string;
};

export type DashboardRequest = {
    id: number;
    status: 'pending' | 'resolved' | 'cancelled';
    createdAt: string;
    pet: {
        id: number;
        name: string;
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
    pet: {
        id: number;
        name: string;
    };
};

export type AdminDashboardProps = {
    pendingRequestsCount: number;
    requests: DashboardRequest[];
};

export type ClientDashboardProps = {
    pets: DashboardPet[];
    pendingRequests: DashboardRequest[];
    activeTreatments: DashboardTreatment[];
};
