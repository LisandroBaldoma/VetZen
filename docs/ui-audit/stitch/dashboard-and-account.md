# Fichas Stitch: Inicio y cuenta

## Inicio admin

- **Objetivo:** priorizar solicitudes de atención y abrir trabajo operativo.
- **Usuario:** admin.
- **Información:** contador de pendientes; solicitud con mascota, servicio y enlaces; accesos a pacientes, solicitudes y catálogo.
- **Acciones:** primaria Nuevo paciente; secundarias abrir solicitud/paciente/catálogos.
- **Componentes:** AppShell, Breadcrumb, PageHeader, contador/KPI, lista priorizada, enlaces rápidos, EmptyState.
- **Estados:** sin solicitudes; solicitudes pendientes; navegación/carga Inertia.
- **Navegación:** sidebar o dashboard hacia pacientes, solicitudes y catálogo.
- **Reutilizable:** PageHeader, KPI/priority list, EmptyState. **Específico:** priorización de solicitud.

## Inicio cliente

- **Objetivo:** consultar mascotas, solicitudes pendientes y tratamientos activos.
- **Usuario:** client.
- **Información:** identidad/foto de mascota; solicitud y estado; tratamiento, progreso y sesiones.
- **Acciones:** registrar mascota, abrir mascota/solicitud/tratamiento, explorar servicios.
- **Componentes:** AppShell, PageHeader, PetCard, RequestCard, TreatmentCard, ProgressIndicator, EmptyState.
- **Estados:** ausencia independiente de mascotas/solicitudes/tratamientos; progreso.
- **Navegación:** hacia detalle de mascota y desde servicios a solicitud.

## Perfil, seguridad y apariencia

- **Objetivo:** actualizar identidad, contacto, credenciales y preferencia visual.
- **Usuario:** autenticado; perfil cliente solo dueño autorizado.
- **Información/formularios:** nombre/email; teléfono/dirección/documento/nacimiento; password, 2FA, passkeys; tema claro/oscuro/sistema.
- **Componentes:** SettingsLayout, tabs laterales, FormSection, campos, InputError, Dialog de borrar cuenta, toast/status.
- **Estados:** validación, envío, éxito de sesión/status, confirmación destructiva.
- **Dependencias:** User y Client; Fortify para seguridad.
