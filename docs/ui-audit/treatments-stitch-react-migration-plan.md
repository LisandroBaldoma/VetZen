# Plan de migracion: Tratamientos Stitch a React/Inertia

## Objetivo

Migrar visualmente la gestion administrativa de tratamientos y sesiones a la direccion Clinica Calida, tomando Stitch solo como referencia visual. Se preservan el dominio, las rutas, Wayfinder, las politicas, los Form Requests, `TreatmentAssignmentService` y las pruebas existentes.

## Alcance

Pantallas afectadas:

- `resources/js/pages/admin/pets/treatments/index.tsx`
- `resources/js/pages/admin/pets/treatments/show.tsx`
- `resources/js/pages/admin/pets/treatments/create.tsx` solo para mantener terminologia y enlaces existentes.

Referencias visuales:

- `resources/views/treatment.blade.php`
- `resources/views/treatment_detail.blade.php`
- `resources/views/treatment_SessionManagementSheet.blade.php`

Las referencias Stitch no son fuente funcional y su mapeo de nombres no coincide completamente con el contenido. `treatment.blade.php` contiene principalmente el detalle operacional; los otros dos Blade contienen variantes del Sheet de gestion de sesiones.

## Decision aprobada

El encabezado mostrara exclusivamente `treatment_name`, que es un snapshot historico.

No se mostrara el servicio. Aunque `PetTreatment` conserva la relacion con `Treatment` y este con `Service`, no existe un snapshot del nombre del servicio. Exponer el nombre actual del catalogo podria representar incorrectamente una asignacion historica.

Por esta decision no se requieren cambios de backend, nuevas props ni consultas adicionales.

## Contrato funcional a preservar

### Tratamientos

Estados de `PetTreatment`:

- `pending`
- `in_progress`
- `completed`
- `suspended`
- `cancelled`

Solo `pending` e `in_progress` permiten cambiar condiciones o sesiones. Los tratamientos `completed` y `cancelled` son finales. Un tratamiento `suspended` tampoco permite editar sesiones.

### Sesiones

Estados de `TreatmentSession`:

- `pending`
- `completed`
- `cancelled`

Una sesion final puede corregir fecha, precio y notas mientras el tratamiento padre este operativo, pero no puede cambiar nuevamente de estado.

Cancelar una sesion puede crear automaticamente una nueva pendiente para conservar la cobertura de `planned_sessions`. No existe una relacion persistida entre una sesion cancelada y su eventual reemplazo; la UI no debe afirmar que una sesion concreta reemplaza a otra.

El progreso siempre se calcula como:

```text
sesiones completadas / sesiones planificadas
```

No debe calcularse con `session_number`, ya que las cancelaciones pueden crear sesiones con numeros superiores a la cantidad planificada.

## Datos disponibles

### Indice

El indice ya recibe:

- `treatment_name`
- `planned_sessions`
- `completed_sessions_count`
- `status`
- `starts_on`

Con esos datos se puede mostrar estado, fecha de inicio, progreso y los grupos de tratamientos actuales e historicos. No hay N+1: el controller utiliza `withCount()`.

### Detalle

El detalle ya recibe:

- Snapshot de nombre y descripcion del tratamiento.
- Condiciones: sesiones planificadas, precio predeterminado, moneda, fecha de inicio y notas.
- Snapshots de procedimientos.
- Sesiones con fecha, precio, moneda, estado y notas.

El frontend puede derivar:

- Cantidad de sesiones completadas.
- Porcentaje de progreso.
- Sesiones pendientes sin fecha.
- Sesiones completadas y canceladas.
- Proxima sesion: pendiente con `scheduled_at`, ordenada por fecha y luego por numero de sesion.
- Modo operativo o de solo lectura del tratamiento padre.

## Direccion visual Stitch reutilizable

- Superficies calidas, bordes sutiles y densidad clinica compacta.
- Jerarquia: tratamiento, progreso, informacion, procedimientos y sesiones.
- Agrupacion semantica de sesiones.
- Bottom sheet en movil y panel lateral en desktop.
- Confirmaciones explicitas para completar y cancelar.
- Targets tactiles de al menos 44 px.
- Acciones finales visualmente diferenciadas.

Se implementara con los tokens de `resources/css/app.css`, `workspace-clinical` y primitives existentes. No se copiara HTML, Tailwind CDN, scripts inline ni datos ficticios de Stitch.

## Elementos Stitch descartados

- Crear manualmente una nueva sesion.
- Agenda, disponibilidad, turnos o asignacion de profesionales.
- Protocolos, identificadores ficticios, notificaciones y navegacion ficticia.
- Motivo de cancelacion separado de `notes`.
- Reabrir sesiones completadas o restaurar sesiones canceladas.
- Mensajes que vinculen una cancelacion con una sesion de reemplazo concreta.
- Datos ficticios de pacientes, fechas, precios, procedimientos, porcentajes y notas.

## Componentes

### Reutilizar

- `PetContextHeader`
- `Button`, `Card`, `Badge`, `Input`, `Label`
- `Sheet` y `Dialog`
- `Heading`, `InputError`, `EmptyState`
- `PageHeader` cuando su composicion encaje sin duplicar jerarquia clinica.

### Crear

- `resources/js/components/treatment-status-badge.tsx`: semantica de estados de tratamiento compartida entre indice y detalle.
- `resources/js/components/treatment-progress.tsx`: conteo, porcentaje y barra de progreso compartidos.
- `resources/js/components/confirm-action-dialog.tsx`: patron compartido para completar/cancelar sesion y cancelar tratamiento.
- `resources/js/pages/admin/pets/treatments/components/session-row.tsx`: item local del detalle.
- `resources/js/pages/admin/pets/treatments/components/session-management-sheet.tsx`: Sheet local para administrar una sesion.

Los componentes de procedimientos e informacion permanecen locales al detalle salvo que surja una segunda necesidad real de reutilizacion.

## Estrategia de UI

### Indice de tratamientos

- Reutilizar `PetContextHeader` con `active="treatments"`.
- Mostrar CTA `Asignar tratamiento` con el enlace Wayfinder existente.
- Agrupar `pending`, `in_progress` y `suspended` como tratamientos actuales.
- Agrupar `completed` y `cancelled` como historial.
- Mostrar nombre snapshot, estado, fecha de inicio y progreso real.
- Mantener el estado vacio y el enlace a detalle.

### Detalle de tratamiento

Orden de contenido:

1. `PetContextHeader` existente.
2. Encabezado con nombre snapshot y estado.
3. Progreso terapeutico derivado.
4. Informacion: inicio, sesiones requeridas, precio predeterminado, moneda y notas.
5. Procedimientos desde `procedure_snapshots`.
6. Acciones existentes del tratamiento: actualizar condiciones, suspender/reanudar y cancelar.
7. Grupos de sesiones: proxima programada, pendientes sin programar, completadas y canceladas.

Las sesiones canceladas permanecen accesibles como historial secundario. No se presentaran como eliminadas.

### Session Management Sheet

- Se abre al seleccionar una sesion existente.
- Mobile: `SheetContent side="bottom"`.
- Desktop: mismo primitive con clases responsive para panel lateral derecho.
- Usa la accion Wayfinder actual de `TreatmentSessionController.update`.
- No se crea una ruta GET, POST o controller adicional.
- Campos: `scheduled_at`, `price`, moneda ARS informativa y `notes`.
- Sesion pendiente: guardar cambios, completar y cancelar.
- Sesion completada o cancelada con padre operativo: corregir datos, sin transicion de estado.
- Padre no operativo: contenido solo lectura, sin campos editables ni acciones de estado.

Despues de un PATCH se usara la respuesta Inertia actual para refrescar sesion, progreso, estado del tratamiento y posibles sesiones nuevas creadas por la regla de reemplazo.

### Confirmaciones

Completar:

- Indica el progreso actual y el posterior.
- Si alcanza `planned_sessions`, informa que el tratamiento se completara automaticamente.

Cancelar:

- Indica que la sesion quedara en historial.
- Indica que puede generarse una nueva pendiente si hace falta para mantener las sesiones requeridas.
- No garantiza un reemplazo concreto antes de recibir la respuesta del backend.

## Backend, rutas y autorizacion

No se requieren cambios en:

- Migraciones, modelos o relaciones.
- Controllers, Policies, Form Requests o Services.
- Rutas existentes.
- Roles y permisos.

Se preservan especialmente:

- `PATCH /admin/treatment-sessions/{session}`.
- `PetTreatmentPolicy` y `TreatmentSessionPolicy`.
- Middleware `admin`.
- `scopeBindings()` de tratamientos anidados.
- `TreatmentAssignmentService` y sus transacciones con bloqueos.

La visibilidad frontend no reemplaza la autorizacion backend.

## Etapas de implementacion

### 1. Patrones de presentacion

- Crear badge de estado, progreso y dialogo de confirmacion.
- Reemplazar duplicacion de estado/progreso en indice y detalle.
- Resultado: semantica visual consistente.

Commit sugerido:

```text
feat(treatments): add shared treatment presentation patterns
```

### 2. Indice administrativo

- Reorganizar el indice en tratamientos actuales e historial.
- Mantener CTA, estado vacio, enlaces y datos actuales.
- Resultado: seguimiento mas legible sin cambios backend.

Commit sugerido:

```text
feat(treatments): redesign assigned treatment list
```

### 3. Detalle y agrupacion de sesiones

- Reorganizar `show.tsx` con jerarquia clinica y grupos de sesiones.
- Sustituir formularios expandidos por filas accionables.
- Mantener acciones de tratamiento y enlace opcional a evolucion clinica.
- Resultado: detalle operacional compacto.

Commit sugerido:

```text
feat(treatments): reorganize treatment detail sessions
```

### 4. Sheet de sesion

- Implementar Sheet responsive y formulario de una sesion seleccionada.
- Preservar payload completo de `UpdateTreatmentSessionRequest`.
- Representar correcciones finales y modo solo lectura correctamente.
- Resultado: administracion de sesiones enfocada, sin rutas nuevas.

Commit sugerido:

```text
feat(treatments): add session management sheet
```

### 5. Confirmaciones y endurecimiento UX

- Integrar confirmaciones para completar/cancelar sesiones.
- Sustituir el `window.confirm()` de cancelacion del tratamiento por el patron compartido cuando se trabaje esa accion.
- Resultado: transiciones explicadas antes de ejecutarse.

Commit sugerido:

```text
feat(treatments): confirm session state transitions
```

### 6. QA

- Validar responsive, accesibilidad y estados de dominio.
- Corregir solo hallazgos concretos.

## Riesgos y controles

- Nunca usar `session_number` como progreso.
- No tratar una pendiente con fecha como un estado backend adicional.
- No asumir que una cancelacion siempre crea reemplazo visible antes del refresh.
- No habilitar correcciones para tratamientos suspendidos, completados o cancelados.
- No ocultar sesiones canceladas que son parte del historial.
- No reemplazar snapshots de procedimiento por datos vivos del catalogo.

## Verificacion

Pruebas PHP focalizadas:

```text
php artisan test tests/Feature/Treatment/TreatmentAssignmentTest.php
php artisan test tests/Feature/Treatment/PetTreatmentManagementTest.php
```

Calidad frontend y PHP:

```text
npm run format:check
npm run lint:check
npm run types:check
npm run build
vendor/bin/pint --dirty --test
```

QA manual:

- 390 px, 768 px y 1280 px.
- Tratamientos en todos los estados.
- Sesiones pendientes con y sin fecha, completadas y canceladas.
- Completar, cancelar y corregir una sesion final con padre operativo.
- Modo solo lectura con padre suspendido, completado o cancelado.
- Navegacion por teclado, Escape, focus trap y retorno de foco en Sheet/Dialog.
- Zoom al 200 %, contenido largo, errores de formulario y ausencia de overflow horizontal.

## Definition of Done

- La migracion usa datos reales y snapshots correctos.
- No se agregan rutas, controllers, migraciones, roles ni relaciones especulativas.
- Wayfinder, politicas, validacion y servicios existentes se preservan.
- El Sheet actualiza exclusivamente sesiones existentes por el endpoint actual.
- El modo solo lectura coincide con las reglas backend.
- La experiencia es usable en movil, tablet y desktop.
- Las verificaciones tecnicas y pruebas focalizadas pasan.
