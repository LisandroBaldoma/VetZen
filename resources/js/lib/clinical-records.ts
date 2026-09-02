const clinicalDateFormatter = new Intl.DateTimeFormat('es-AR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
});

const typeLabels: Record<string, string> = {
    consultation: 'Consulta',
    evaluation: 'Evaluación',
    evolution: 'Evolución',
    session: 'Sesión',
    other: 'Otro',
};

export function formatClinicalDate(value: string): string {
    return clinicalDateFormatter.format(new Date(value));
}

export function clinicalRecordTypeLabel(type: string): string {
    return typeLabels[type] ?? type;
}
