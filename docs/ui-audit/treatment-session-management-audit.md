# Auditoria especifica: gestion de TreatmentSession

## 1. Resumen ejecutivo

Un `admin` gestiona sesiones dentro del detalle del tratamiento asignado, no en
una pantalla ni ruta individual. Puede consultar y, si el `PetTreatment` padre
esta `pending` o `in_progress`, actualizar fecha, precio, moneda, estado y
notas. Una sesion `completed` o `cancelled` mantiene su estado final, pero sus
demas campos siguen siendo corregibles mientras el padre siga operativo.

Completar recalcula automaticamente el estado del tratamiento. Cancelar conserva
el registro y puede generar una sesion pendiente de reemplazo. No existe
creacion manual de sesiones.

## 2. Fuentes revisadas

- `AGENTS.md`.
- `spec.md:126-156`.
- `technical.md:207-255`.
- `features.md:160-195`.
- `features/08-treatments-and-sessions.md`.
- `features/UX-08-patient-treatments-and-sessions.md`.
- `docs/ui-audit/stitch/requests-treatments-sessions.md`.
- `docs/ui-audit/06-user-flows.md`.
- Modelos, migracion, controllers, Form Requests, Policies, rutas, UI React y
  pruebas relacionadas.

No se modifico implementacion ni se ejecutaron pruebas durante la auditoria.

## 3. Modelo TreatmentSession

Tabla: `treatment_sessions`.
Migracion: `database/migrations/2026_08_29_000001_expand_therapeutic_catalog.php:69-81`.

| Campo | Tipo / nulabilidad | Significado y edicion |
| --- | --- | --- |
| `id` | bigint, no nullable | Identificador. No editable. |
| `pet_treatment_id` | FK, no nullable | Tratamiento asignado propietario. Se deriva al crear. |
| `session_number` | unsigned integer, no nullable | Orden historico unico por tratamiento. No editable por el endpoint. |
| `scheduled_at` | datetime, nullable | Fecha y hora prevista o registrada. Editable si el padre esta activo. |
| `price` | decimal(12,2), no nullable | Precio efectivo. Editable si el padre esta activo. |
| `currency` | string(3), no nullable | Moneda; actualmente solo `ARS`. |
| `status` | string, no nullable | `pending`, `completed` o `cancelled`. |
| `notes` | text, nullable | Notas propias de la sesion. |
| `created_at`, `updated_at` | timestamps | Timestamps tecnicos. No incluidos en la prop Inertia. |

Modelo, fillable y casts: `app/Models/TreatmentSession.php:10-23`.

No hay enums, constantes de estado, autor, motivo de cancelacion, fecha de
realizacion separada, relacion clinica ni historial de modificaciones.

## 4. Creacion de sesiones

Las sesiones se crean exclusivamente en `TreatmentAssignmentService`:

- Al asignar un tratamiento a una mascota, directa o desde una solicitud.
- Al aumentar `PetTreatment.planned_sessions`.
- Como reemplazo tras una cancelacion cuando falta cobertura.

No existe controller, ruta ni UI de creacion manual.

Para `planned_sessions = 5` se crean las sesiones 1 a 5, todas con:

- `status = pending`.
- `price = PetTreatment.default_session_price`.
- `currency = PetTreatment.currency`.
- `scheduled_at = null`.
- `notes = null`.

Referencia: `app/Services/TreatmentAssignmentService.php:50,151-160`.

## 5. Pantalla actual

- Pagina: `resources/js/pages/admin/pets/treatments/show.tsx`.
- Ruta contenedora: `GET /admin/pets/{pet}/treatments/{petTreatment}`.
- Controller: `Admin\PetTreatmentController@show`.
- Carga de sesiones: `app/Http/Controllers/Admin/PetTreatmentController.php:62-74`.
- Formulario de sesion: `resources/js/pages/admin/pets/treatments/show.tsx:439-605`.

La pagina recibe, por sesion, `id`, `session_number`, `scheduled_at`, `price`,
`currency`, `status` y `notes`. Cuando el padre esta `pending` o `in_progress`,
renderiza un formulario independiente por sesion con fecha, precio, estado,
notas, moneda oculta y boton "Guardar sesion".

El valor inicial de cada campo es el valor actual de la sesion. Para una sesion
final, el estado se muestra como texto y se reenvia oculto, pero fecha, precio y
notas siguen editables si el padre esta activo.

La navegacion usa breadcrumbs y `PetContextHeader`; este ultimo enlaza al
resumen, historia clinica y tratamientos de la mascota.

## 6. `scheduled_at`

- No es obligatorio y puede ser `null`.
- Puede agregarse, modificarse o eliminarse enviando valor vacio.
- No tiene restricciones de pasado, futuro, disponibilidad, solapamiento u
  horario.
- No modifica el estado de la sesion ni crea turnos, agenda, ClinicalRecord u
  otros recursos.
- Puede coexistir con `pending`, `completed` o `cancelled` cuando el padre
  permita la actualizacion.
- Validacion: `nullable|date`.

Referencia: `app/Http/Requests/TreatmentSession/UpdateTreatmentSessionRequest.php:17-23`.

`pending + scheduled_at != null` puede interpretarse visualmente como
"Programada", pero no existe un estado backend `scheduled`; sigue siendo
`pending`.

## 7. Precio

- Es obligatorio en toda actualizacion.
- Se inicializa desde `PetTreatment.default_session_price`.
- Permite `0`; no permite negativos.
- Debe ser decimal con hasta dos posiciones: `decimal:0,2`.
- La moneda permitida es exclusivamente `ARS`.
- Puede editarse individualmente mientras el padre este `pending` o
  `in_progress`.
- No modifica otras sesiones ni `default_session_price`.

`default_session_price` es el valor vigente para futuras sesiones creadas;
`TreatmentSession.price` es el precio efectivo e historico de una sesion.

Referencias: `TreatmentAssignmentService.php:151-160`,
`UpdateTreatmentSessionRequest.php:19-21`.

## 8. Notas

- Nullable, string, maximo 5000 caracteres.
- Son exclusivas de la sesion.
- Admin puede editarlas si el padre esta `pending` o `in_progress`, incluso en
  una sesion final.
- No generan ClinicalRecord ni se muestran automaticamente en Historia Clinica.
- No tienen autor, fecha especifica aparte de timestamps, ni historial de
  cambios.

Referencia: `app/Http/Requests/TreatmentSession/UpdateTreatmentSessionRequest.php:22`.

Tras una sesion completada existe un enlace opcional "Registrar evolucion". Solo
abre el formulario de Historia Clinica con `type=evolution`; no crea ni vincula
un registro automaticamente. Referencia:
`resources/js/pages/admin/pets/treatments/show.tsx:645-673`.

## 9. Estados

Los unicos estados reales son `pending`, `completed` y `cancelled`.

| Estado | Significado real | Inicial | Final para estado | Campos aun editables si padre activo |
| --- | --- | --- | --- | --- |
| `pending` | Sesion prevista, con o sin fecha | Si | No | Todos los expuestos |
| `completed` | Cuenta para el progreso | No | Si | Fecha, precio, moneda, notas |
| `cancelled` | Historial; no cuenta para progreso | No | Si | Fecha, precio, moneda, notas |

Referencia: `features/08-treatments-and-sessions.md:373-399`,
`app/Services/TreatmentAssignmentService.php:100-121`.

## 10. Matriz de transiciones

| From | To | Permitido | Condicion / consecuencia |
| --- | --- | --- | --- |
| `pending` | `pending` | Si | Guarda correcciones y recalcula el padre. |
| `pending` | `completed` | Si | Cuenta para progreso y recalcula el padre. |
| `pending` | `cancelled` | Si | Conserva historial y puede crear reemplazo. |
| `completed` | `completed` | Si | Corrige metadatos si padre activo. |
| `completed` | `pending` | No | Estado final no puede cambiar. |
| `completed` | `cancelled` | No | Estado final no puede cambiar. |
| `cancelled` | `cancelled` | Si | Corrige metadatos si padre activo. |
| `cancelled` | `pending` | No | Estado final no puede cambiar. |
| `cancelled` | `completed` | No | Estado final no puede cambiar. |

Referencia: `app/Services/TreatmentAssignmentService.php:104-106`.

## 11. Completar sesion

Para `pending -> completed`:

1. Se ejecuta `PATCH /admin/treatment-sessions/{session}`.
2. El Form Request autoriza y valida todos los campos.
3. El servicio bloquea sesion y `PetTreatment` padre.
4. El padre debe estar `pending` o `in_progress`.
5. Actualiza la sesion con todos los atributos recibidos.
6. Cuenta sesiones `completed` y `pending`.
7. Si `completed >= planned_sessions`, actualiza el padre a `completed`.
8. Si hay alguna completada pero no alcanza el plan, lo actualiza a
   `in_progress`.
9. Si no hay completadas, lo deja `pending`.

No exige `scheduled_at`, notas ni una fecha de realizacion independiente. Exige
precio, moneda ARS y estado valido. No crea ClinicalRecord. La ultima sesion
requerida lleva automaticamente el padre a `completed`.

Referencias: `routes/web.php:71`,
`app/Services/TreatmentAssignmentService.php:94-124`.

## 12. Cancelar sesion

Para `pending -> cancelled`:

1. Se ejecuta el mismo `PATCH` y se valida el payload.
2. Se bloquean sesion y padre con `lockForUpdate()`.
3. El padre debe estar `pending` o `in_progress`.
4. La sesion se actualiza a `cancelled`, conservando numero, precio, fecha y
   notas enviados.
5. Se cuentan sesiones `completed`.
6. Se cuentan sesiones `pending`.
7. Se compara `completed + pending` con `planned_sessions`.
8. Si es menor, se crea exactamente un reemplazo `pending`.
9. El reemplazo usa `max(session_number) + 1`.
10. Usa `default_session_price` y moneda vigentes del padre.
11. Se crea sin fecha ni notas.
12. Se recalcula el estado del padre segun sesiones completadas.

No se permite `completed -> cancelled`. Tampoco es posible cancelar una sesion
si el padre esta `suspended`, `completed` o `cancelled`.

Referencias: `app/Services/TreatmentAssignmentService.php:108-121,151-160`.

## 13. Reemplazos

No existe un campo persistido que vincule reemplazo y sesion cancelada:

- No `replacement_of`.
- No `replaces_session_id`.
- No `parent_session`.
- No `reason`.
- No `source`.
- No `generated_by_cancellation`.

La unica evidencia es una nueva sesion con numero consecutivo superior y estado
`pending`. La UI no puede afirmar comprobablemente que una sesion es reemplazo

Referencia: migracion `2026_08_29_000001_expand_therapeutic_catalog.php:69-81`.

## 14. Restricciones por estado del PetTreatment

| Estado padre | Ver | Editar fecha, precio, notas | Cambiar estado | Completar | Cancelar | Reemplazo |
| --- | --- | --- | --- | --- | --- | --- |
| `pending` | Si | Si | Si | Si | Si | Si, si falta cobertura |
| `in_progress` | Si | Si | Si | Si | Si | Si, si falta cobertura |
| `suspended` | Si | No | No | No | No | No |
| `completed` | Si | No | No | No | No | No |
| `cancelled` | Si | No | No | No | No | No |

La restriccion backend esta en
`app/Services/TreatmentAssignmentService.php:100-102`.

## 15. Sesion completada

Una sesion completada puede abrirse y leerse. Mientras el padre siga `pending` o
`in_progress`, admin puede corregir fecha, precio, moneda y notas, manteniendo
`status=completed`. No puede reabrirla ni cancelarla.

Si esta sesion completo el total requerido, el padre ya es `completed`; entonces
la correccion queda bloqueada junto con todas las demas sesiones. No hay
confirmacion UI para estas correcciones.

Prueba: `tests/Feature/Treatment/TreatmentAssignmentTest.php:191-230`.

## 16. Sesion cancelada

Una sesion cancelada puede leerse. Mientras el padre siga activo, admin puede
corregir fecha, precio, moneda y notas, manteniendo `status=cancelled`. No puede
reabrirla ni completarla.

La correccion vuelve a evaluar cobertura, pero normalmente no crea otro
reemplazo porque el primero ya restauro `completed + pending` al minimo
requerido.

Prueba: `tests/Feature/Treatment/TreatmentAssignmentTest.php:232-264`.

## 17. `planned_sessions`

Representa sesiones **completadas** requeridas, no la cantidad maxima de
registros.

Mientras el padre esta `pending` o `in_progress`:

- Aumentarlo crea sesiones `pending` consecutivas.
- Las nuevas usan precio y moneda predeterminados vigentes.
- Reducirlo elimina solamente las ultimas sesiones `pending`.
- Nunca se eliminan sesiones `completed` o `cancelled`.
- No puede quedar por debajo de las completadas.
- Puede haber mas registros que `planned_sessions` por reemplazos.

Es una operacion de condiciones del `PetTreatment`, no de la sesion.
Referencia: `app/Services/TreatmentAssignmentService.php:57-90`.

## 18. `default_session_price`

Cambiarlo no reescribe sesiones existentes. Afecta solamente sesiones creadas

Prueba: `tests/Feature/Treatment/TreatmentAssignmentTest.php:99-122`.

## 19. Autorizacion

### Admin

Puede actualizar cualquier sesion mediante la ruta administrativa, sujeto a
estado del padre y reglas de transicion. La Policy permite `update` solo al rol
`admin`.

### Client

Puede leer sesiones solamente dentro de tratamientos de mascotas propias. No hay
ruta cliente de edicion. La Policy niega actualizacion a cualquier no-admin y
el test comprueba que un cliente no puede forzar el endpoint administrativo.

Referencias: `app/Policies/TreatmentSessionPolicy.php:10-18`,
`tests/Feature/Treatment/PetTreatmentManagementTest.php:142-174`.

## 20. Rutas

| Metodo | URI | Nombre | Controller/action | Rol | Proposito |
| --- | --- | --- | --- | --- | --- |
| `PATCH` | `/admin/treatment-sessions/{session}` | `admin.treatment-sessions.update` | `Admin\TreatmentSessionController@update` | `admin` | Actualizar una sesion. |

No hay `GET` individual, `POST`, `DELETE`, ruta cliente de edicion ni ruta de
actualizacion anidada bajo mascota y tratamiento.

Referencia: `routes/web.php:56-71`.

## 21. Validaciones

Form Request: `app/Http/Requests/TreatmentSession/UpdateTreatmentSessionRequest.php:8-24`.

| Campo | Reglas |
| --- | --- |
| `scheduled_at` | `nullable`, `date` |
| `price` | `required`, `decimal:0,2`, `min:0` |
| `currency` | `required`, solo `ARS` |
| `status` | `required`, `pending`, `completed`, `cancelled` |
| `notes` | `nullable`, `string`, maximo 5000 |

Reglas posteriores: el padre debe estar activo y una sesion final no puede
cambiar estado. Referencia: `TreatmentAssignmentService.php:100-121`.

## 22. Transacciones y concurrencia

`TreatmentAssignmentService::updateSession()` usa `DB::transaction()` y
`lockForUpdate()` sobre sesion y padre. Sesion, conteos, reemplazo eventual y
estado del padre se actualizan atomicamente.

Referencia: `app/Services/TreatmentAssignmentService.php:94-124`.

No existe un test de requests concurrentes reales; el codigo contiene los
bloqueos necesarios para serializar operaciones sobre la misma sesion o padre.

## 23. Tests

Cobertura explicita:

- Creacion inicial, numeros y precio: `TreatmentAssignmentTest.php:21-57`.
- Cancelacion y reemplazo: `TreatmentAssignmentTest.php:59-78`.
- Completadas actualizan estado padre: `TreatmentAssignmentTest.php:80-97`.
- Ajuste de sesiones y precios futuros: `TreatmentAssignmentTest.php:99-122`.
- Padre suspendido bloquea sesion: `TreatmentAssignmentTest.php:124-136`.
- Correccion de completada: `TreatmentAssignmentTest.php:191-230`.
- Correccion de cancelada: `TreatmentAssignmentTest.php:232-264`.
- Padres completed/cancelled bloquean sesiones: `TreatmentAssignmentTest.php:266-325`.
- Endpoint HTTP de cancelacion: `PetTreatmentManagementTest.php:123-140`.
- Cliente no puede modificar: `PetTreatmentManagementTest.php:142-174`.

No hay cobertura HTTP exhaustiva de todas las transiciones, ausencia de fecha al
completar, ni concurrencia real.

## 24. Confirmaciones actuales

No hay confirmacion para completar, cancelar, cambiar estado, editar precio,
editar fecha ni editar notas de una sesion. Cada formulario se envia directamente
con "Guardar sesion".

Referencia: `resources/js/pages/admin/pets/treatments/show.tsx:439-605`.

Hay `window.confirm()` solo para cancelar el tratamiento padre:
"¿Confirmas la cancelacion? El tratamiento no podra reabrirse."
Referencia: `resources/js/pages/admin/pets/treatments/show.tsx:356-383`.

## 25. Mensajes actuales

Exito:

- "Sesion actualizada." en
  `app/Http/Controllers/Admin/TreatmentSessionController.php:17`.

Errores de negocio:

- "Only pending or in-progress treatments can have their sessions modified."
- "Completed or cancelled sessions cannot change status."

Referencias: `app/Services/TreatmentAssignmentService.php:100-106`.

Los errores de reglas de Form Request se muestran mediante `InputError` bajo
cada campo. Referencia:
`resources/js/pages/admin/pets/treatments/show.tsx:448-605`.

## 26. Caso completo de ejemplo

Estado inicial:

```text
planned_sessions = 5
default_session_price = 18000.00 ARS
PetTreatment.status = in_progress
Session 1..5 = pending, 18000.00 ARS, sin fecha, sin notas
```

Despues de completar Session 1 y 2, cancelar Session 3, completar Session 4 y
guardar `scheduled_at = 2026-10-18 16:30` en Session 5:

| Sesion | Estado | Precio | Fecha |
| --- | --- | --- | --- |
| 1 | `completed` | ARS 18.000,00 | `null`, si no se envio fecha |
| 2 | `completed` | ARS 18.000,00 | `null`, si no se envio fecha |
| 3 | `cancelled` | ARS 18.000,00 | `null`, si no se envio fecha |
| 4 | `completed` | ARS 18.000,00 | `null`, si no se envio fecha |
| 5 | `pending` | ARS 18.000,00 | 18/10/2026 16:30 |
| 6 | `pending` | ARS 18.000,00 | `null` |

Session 6 existe porque luego de cancelar la 3 habia 2 completed y 2 pending:
la cobertura era 4, menor que las 5 requeridas. Su precio es el predeterminado
vigente, no necesariamente el precio de Session 3.

Progreso final: `3 / 5`, 60%. Padre: `in_progress`.

Admin puede operar Session 5 y 6 normalmente; puede corregir metadatos de
Session 3, pero no cambiar su estado. Cliente ve las seis sesiones, sus precios,
fechas, notas, estados y progreso, sin controles de edicion.

## 27. Comportamiento funcional actual

- Las sesiones son registros operativos, no turnos ni evoluciones clinicas.
- Se generan automaticamente; no se crean manualmente.
- `pending` puede tener fecha o no tenerla.
- Solo `completed` incrementa progreso.
- Cancelar conserva historial y puede anadir reemplazo.
- `planned_sessions` es cantidad requerida de completadas, no maximo de filas.
- El estado del padre controla toda edicion.
- Los estados finales fijan `status`, no necesariamente todos los metadatos.
- Cliente tiene lectura autorizada; admin tiene actualizacion autorizada.

## 28. Problemas UX observados

- No existe una accion o pantalla separada "Gestionar sesion".
- Cancelar una sesion no solicita confirmacion aunque puede crear reemplazo.
- La consecuencia de cancelacion no se informa antes de guardar.
- No existe distincion formal de estado entre pendiente y programada.
- Corregir una sesion final puede confundirse con reabrirla.
- El cliente ve sesiones pendientes bajo texto de atencion realizada.
- La UI no puede identificar un reemplazo con datos persistidos.
- Acciones de tratamiento y sesiones conviven en una pagina extensa.

## 29. Decisiones pendientes

- F08 y UX-08 no declaran decisiones pendientes para este flujo.
- La estrategia global de auditoria sigue pendiente en `technical.md:276-289`;
  no existe historial de cambios ni autor por sesion.
- El codigo permite `pending` con fecha, pero no define formalmente el concepto
  de sesion programada.
- No se persiste la relacion entre una cancelacion y su reemplazo.
- El backend admite correcciones de sesiones finales con padre operativo, pero
  no hay cobertura HTTP exhaustiva de todos los casos.
- UX-08 indica tabla desktop y tarjetas movil, mientras el codigo usa timeline
  en todos los tamanos. Referencias:
  `features/UX-08-patient-treatments-and-sessions.md:50-52`,
  `docs/ui-audit/06-user-flows.md:59`.

## 30. Contrato funcional para Stitch

### Informacion visible admin

- Numero, estado, fecha opcional, precio, ARS y notas de cada sesion.
- Progreso: completadas sobre sesiones requeridas.
- Estado del tratamiento padre.

### Acciones confirmadas admin

- Con padre `pending` o `in_progress`: editar fecha, precio, moneda y notas;
  completar o cancelar una sesion pendiente; corregir metadatos de una final sin
  cambiar su estado.
- Con padre `suspended`, `completed` o `cancelled`: solo lectura.

### Consecuencias confirmadas

- Completar aumenta progreso y puede cerrar automaticamente el tratamiento.
- Cancelar conserva la fila, no reduce el plan y puede crear un pendiente nuevo.
- El reemplazo obtiene numero consecutivo, precio y moneda predeterminados
  vigentes, fecha nula y notas nulas.
- No existe relacion persistida entre reemplazo y cancelada.

### Diferencia admin/client

- Admin puede gestionar sesiones bajo las restricciones anteriores.
- Client solo consulta sesiones de mascotas propias y no puede modificar campos.
