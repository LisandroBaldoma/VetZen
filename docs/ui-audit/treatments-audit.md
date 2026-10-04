# Auditoria Treatments

## 1. Resumen ejecutivo

El dominio esta bien delimitado: catalogo reutilizable, asignacion individual,
snapshots historicos y sesiones operativas. Backend, transacciones y ownership
estan mayormente alineados con F08.

Las principales brechas son de presentacion y semantica: la UI llama
"cronologia de atencion realizada" a sesiones que pueden ser solo previstas, no
acciones o formularios sin precarga contextual.

## 2. Alcance revisado

- F07, F08, UX-06, UX-07 y UX-08.
- Modelos, migraciones, Requests, Policies, servicios, rutas, tests y paginas
  React de catalogo, solicitudes, tratamientos y sesiones.
- Referencia visual `docs/ui-audit/stitch/requests-treatments-sessions.md`.

## 3. Diagrama de dominio real

```text
Service
 ├─ hasMany Procedure
 ├─ hasMany Treatment
 └─ hasMany ServiceRequest

Treatment
 └─ belongsToMany Procedure

Pet
 ├─ hasMany ServiceRequest
 └─ hasMany PetTreatment

PetTreatment
 ├─ belongsTo Treatment
 ├─ hasMany PetTreatmentProcedure  (snapshot)
 ├─ hasMany TreatmentSession
 └─ hasOne ServiceRequest          (si resolvio una solicitud)

TreatmentSession
 └─ belongsTo PetTreatment
```

## 4. Service

Area terapeutica general, sin precio ni duracion. Ejemplos seedados:
Fisioterapia, Acupuntura, Fitoterapia y Flores de Bach.

## 5. Procedure

Tecnica perteneciente a un unico `Service`; tiene duracion orientativa opcional,
pero no precio ni sesiones.

## 6. Treatment

Plantilla reutilizable del catalogo. Pertenece a un `Service`, requiere uno o mas
`Procedure` del mismo servicio y propone `estimated_sessions`. No define precios,

## 7. PetTreatment

Instancia concreta de una plantilla para una mascota. Conserva condiciones
acordadas: sesiones requeridas, precio predeterminado, moneda, fecha de inicio,

Tambien congela nombre, descripcion y procedimientos de la plantilla, por lo que
cambios posteriores del catalogo no alteran el historial.

## 8. TreatmentSession

Registro operativo de una sesion prevista, programada, completada o cancelada.
Tiene numero secuencial, fecha opcional, precio propio, moneda, estado y notas.

Una sesion `pending` no prueba atencion realizada: puede no tener fecha
programada.

## 9. ServiceRequest

Solicitud de atencion de un cliente para un `Service` activo y una mascota propia.
No es turno, diagnostico ni tratamiento; no permite elegir plantilla.

Al resolverse, admin elige una plantilla compatible y se crea el `PetTreatment`

## 10. Estados

- Solicitud: `pending`, `resolved`, `cancelled`.
- Tratamiento: `pending`, `in_progress`, `completed`, `suspended`, `cancelled`.
- Sesion: `pending`, `completed`, `cancelled`.

`completed` y `cancelled` son finales para el estado de una sesion, aunque fecha,
precio, moneda y notas pueden corregirse mientras el tratamiento padre siga
activo.

## 11. Sesiones y progreso

La asignacion genera exactamente `planned_sessions` sesiones `pending`, numeradas

El progreso es exclusivamente:

```text
sesiones completed / planned_sessions
```

Las canceladas no cuentan. Si una cancelacion deja menos sesiones pendientes o
completadas que las requeridas, se genera un reemplazo pendiente con el siguiente
numero.

## 12. Precios

El precio se acuerda al asignar un tratamiento, no en catalogo.

- `default_session_price` es el valor de referencia para sesiones futuras.
- Cada sesion conserva su precio propio.
- Cambiar el valor predeterminado no reescribe sesiones existentes.
- Reemplazos o sesiones anadidas usan el valor predeterminado vigente.

## 13. Asignacion directa admin

Admin puede asignar un tratamiento sin solicitud previa desde el paciente.

La operacion verifica plantilla, servicio y procedimientos activos; crea el
tratamiento individual, snapshots y sesiones dentro de una transaccion.

## 14. Resolucion de solicitud

Admin solo puede resolver solicitudes pendientes con un `Treatment` activo del
mismo `Service`.

La resolucion crea asignacion, snapshots y sesiones, enlaza `pet_treatment_id` y
cambia la solicitud a `resolved`, todo atomicamente.

## 15. Catalogo admin

El catalogo global permite buscar, filtrar, paginar, editar y activar o desactivar
plantillas. Tambien existen vistas contextuales bajo cada servicio.

Las plantillas muestran servicio, sesiones estimadas, cantidad de procedimientos y
estado; no muestran precio, coherentemente con el dominio.

## 16. Cliente

El cliente puede:

- Crear solicitudes para mascotas propias.
- Consultar solicitudes, tratamientos, snapshots, sesiones y progreso propios.
- Abrir el tratamiento creado tras resolver una solicitud.

No puede administrar catalogo, resolver solicitudes, asignar tratamientos ni
modificar sesiones.

## 17. Autorizacion

La proteccion backend esta correctamente planteada:

- Middleware `auth`.
- Area administrativa con rol `admin`.
- Policies para solicitudes, tratamientos y sesiones.
- `scopeBindings()` para recursos anidados bajo mascota.
- Asociaciones de ownership derivadas desde la mascota autorizada, no desde
  payload.

## 18. Validacion

Los Form Requests validan precios decimales, fecha, limites, estados, moneda
`ARS`, sesiones entre 1 y 1000 y compatibilidad de plantillas y procedimientos.

Campos de ownership y relaciones no se aceptan por mass assignment.

## 19. Integridad y concurrencia

Los servicios de asignacion y resolucion usan transacciones y bloqueos
`lockForUpdate()`.

Se cubren:

- Duplicacion de resolucion.
- Generacion consecutiva de sesiones.
- Conservacion de canceladas.
- Reemplazos.
- Ajuste de sesiones sin borrar historial.
- Rollback si falla la creacion de sesiones.

## 20. Hallazgos funcionales

1. La pantalla admin de resolucion lista plantillas activas por servicio, pero no
   filtra las que contienen procedimientos desactivados. El backend las rechaza
   correctamente al resolver, pero la UI las presenta como compatibles.
   Referencias: `app/Http/Controllers/Admin/ServiceRequestController.php:80-82`,
   `app/Services/TreatmentAssignmentService.php:27-30`.
2. La asignacion directa muestra "sesiones planificadas" vacia aunque cada
   plantilla posee `estimated_sessions`. El admin debe transcribir manualmente una
   cantidad que deberia actuar como valor inicial.
   Referencia: `resources/js/pages/admin/pets/treatments/create.tsx:120-134`.
3. La asignacion directa fija el estado inicial en `pending`, mientras el backend
   admite `in_progress`. No compromete integridad, pero la capacidad backend no
   esta expuesta de forma coherente.
   Referencias: `app/Http/Requests/PetTreatment/StorePetTreatmentRequest.php:24`,
   `resources/js/pages/admin/pets/treatments/create.tsx:163-172`.

## 21. Hallazgos semanticos y UX

1. La vista cliente describe las sesiones como "Registro cronologico de la
   atencion realizada", pero renderiza sesiones pendientes y sin fecha. Esto
   confunde planificacion con atencion realizada.
   Referencia: `resources/js/pages/pets/treatments/show.tsx:243-304`.
2. Las sesiones se muestran como timeline en todos los tamanos, mientras UX-08
   define tabla en escritorio y tarjetas en movil. Es una divergencia documentada.
   Referencias: `features/UX-08-patient-treatments-and-sessions.md:50-52`,
   `docs/ui-audit/06-user-flows.md:59`.
3. El detalle admin titula el tratamiento como "Plan clinico activo" incluso
   cuando puede estar pendiente, suspendido o cancelado. Conviene usar un termino
   neutral como "Tratamiento asignado".
4. La linea temporal no distingue visualmente sesiones previstas, programadas,
   realizadas y canceladas mas alla del texto de estado. Es especialmente
   relevante porque el numero de sesion representa historial operativo y puede
   superar las sesiones requeridas.

## 22. Discrepancias documentales

1. `docs/ui-audit/06-user-flows.md:21` dice que el cliente no ve precio ni sesion,
   pero F08, Stitch y la implementacion si exponen sesiones y precios a su
   propietario. La especificacion F08 prevalece y el codigo coincide con ella.
2. UX-08 pide tabla desktop y tarjetas movil; el codigo actual usa timeline
   adaptable para ambas resoluciones.
3. F08 y Stitch explican correctamente que `pending` es una sesion prevista o
   programada; el texto actual de cliente la presenta como atencion realizada.

## 23. Pruebas existentes

La cobertura inspeccionada incluye:

- Snapshots de catalogo.
- Precios historicos.
- Reemplazos tras cancelacion.
- Estados finales.
- Ajuste de sesiones.
- Atomicidad y rollback.
- Asignacion directa.
- Resolucion de solicitudes.
- Ownership horizontal.
- Bloqueo de mutaciones del cliente.
- Validaciones de compatibilidad e inactividad.

No se ejecutaron pruebas durante esta auditoria documental.

## 24. Conclusion

El modelo real no es "tratamiento con sesiones realizadas", sino un plan
operativo con sesiones previstas que progresan a completadas o canceladas. La UI
