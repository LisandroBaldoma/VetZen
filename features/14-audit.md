# Feature 14 - Auditoría transversal mínima

## Alcance

Registrar en un log append-only las asignaciones y cambios de estado de
tratamientos, sesiones y turnos.

## Datos registrados

- Actor autenticado.
- Acción estable.
- Recurso afectado mediante relación polimórfica.
- Valores anterior y nuevo para transiciones de estado.
- Momento de la acción.

## Límites

- `clinical_record_audits` conserva los snapshots de Historia Clínica; no se
  duplica contenido clínico en esta auditoría.
- `user_role_changes` conserva los cambios de roles.
- Solo administradores pueden consultar el log transversal.
- El log se guarda en la misma transacción que el cambio auditado.
