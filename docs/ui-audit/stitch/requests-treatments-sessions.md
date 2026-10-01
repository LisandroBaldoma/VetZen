# Fichas Stitch: Solicitudes, tratamientos y sesiones

## Solicitudes de atención cliente

- **Objetivo:** pedir evaluación para un servicio activo y mascota propia sin seleccionar un tratamiento.
- **Usuario:** client.
- **Información:** mascota contextual, servicio, notas, fecha, estado y tratamiento resultante si está resuelta.
- **Acciones:** crear solicitud, abrir detalle y abrir tratamiento resuelto.
- **Formulario:** servicio requerido, notas opcionales; ayuda contextual sobre límites de la solicitud.
- **Componentes:** PetContextHeader, RequestCard, RequestStatusBadge, form, ValidationSummary, EmptyState.
- **Estados:** pending/resolved/cancelled; sin solicitudes; servicios activos disponibles.

## Solicitudes admin

- **Objetivo:** priorizar, revisar y resolver/cancelar solicitud de cliente.
- **Usuario:** admin.
- **Información:** responsable, mascota, servicio, notas, fecha, estado; en detalle, plantillas compatibles.
- **Acciones:** filtrar, abrir, resolver mediante asignación o cancelar.
- **Componentes:** PageHeader, FilterBar, ResponsiveDataList, RequestStatusBadge, entity context, resolution form, ConfirmActionDialog futuro.
- **Estados:** paginación, filtro vacío, pending/resolved/cancelled, validación/procesando.
- **Dependencias:** ServiceRequest, Pet, Client, Treatment compatible.

## Tratamientos cliente

- **Objetivo:** consultar seguimiento en solo lectura.
- **Usuario:** client owner.
- **Información:** servicio/plantilla snapshot, procedimientos, sesiones previstas/completadas, progreso, fecha inicio, notas, sesiones con fecha/precio/moneda/estado/notas.
- **Acciones:** abrir detalle desde mascota, dashboard o solicitud resuelta.
- **Componentes:** PetContextHeader, TreatmentCard, TreatmentStatusBadge, ProgressIndicator, session Timeline, EmptyState.
- **Estados:** pending/in_progress/completed/suspended/cancelled y sesiones pending/completed/cancelled.

## Tratamientos y sesiones admin

- **Objetivo:** asignar condiciones concretas, seguir progreso y actualizar sesiones.
- **Usuario:** admin.
- **Información:** igual que lectura cliente más formularios y acciones de estado.
- **Acciones:** asignar tratamiento, modificar condiciones, suspender/cancelar/reactivar, actualizar fecha/precio/estado/notas de sesión; crear evolución después de sesión completada.
- **Formulario de asignación:** plantilla, sesiones previstas, precio ARS, inicio, estado inicial, notas.
- **Componentes:** PetContextHeader, assignment form, TreatmentSummary, status/action controls, session editor, timeline/progress.
- **Estados:** sesiones canceladas son historial y pueden generar reemplazo; no simplificar este comportamiento en el diseño.
