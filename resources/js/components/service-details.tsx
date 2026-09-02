type Service = {
    description: string;
    procedures?: {
        id: number;
        name: string;
        description: string | null;
        duration_minutes: number | null;
    }[];
};

export default function ServiceDetails({ service }: { service: Service }) {
    return (
        <div className="space-y-6 rounded-xl border p-6">
            <p className="text-sm leading-6 whitespace-pre-wrap">
                {service.description}
            </p>
            {service.procedures !== undefined && (
                <div className="space-y-3 border-t pt-5">
                    <h2 className="font-medium">Procedimientos disponibles</h2>
                    {service.procedures.length === 0 ? (
                        <p className="text-sm text-muted-foreground">
                            Este servicio no tiene procedimientos disponibles en
                            este momento.
                        </p>
                    ) : (
                        <div className="grid gap-3">
                            {service.procedures.map((procedure) => (
                                <div
                                    key={procedure.id}
                                    className="space-y-1 rounded-lg border p-4"
                                >
                                    <div className="flex flex-wrap items-center justify-between gap-2">
                                        <h3 className="font-medium">
                                            {procedure.name}
                                        </h3>
                                        {procedure.duration_minutes ? (
                                            <span className="text-xs text-muted-foreground">
                                                {procedure.duration_minutes} min
                                            </span>
                                        ) : (
                                            <span className="text-xs text-muted-foreground">
                                                Duración no especificada
                                            </span>
                                        )}
                                    </div>
                                    {procedure.description && (
                                        <p className="text-sm leading-6 whitespace-pre-wrap text-muted-foreground">
                                            {procedure.description}
                                        </p>
                                    )}
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}
