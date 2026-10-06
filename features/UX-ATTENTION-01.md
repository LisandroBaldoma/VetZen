# UX-ATTENTION-01 - Analisis funcional y UX de Atencion

> Estado: investigacion historica cerrada con Atención V1. Este documento
> conserva el análisis, riesgos y propuestas para una eventual V2; no describe
> cambios de dominio adoptados por V1.

## 1. Resumen ejecutivo

El flujo real actual es:

```text
Cliente elige Servicio -> selecciona Mascota -> crea Solicitud de atencion
-> Admin revisa -> selecciona Plantilla compatible
-> crea Tratamiento asignado y Sesiones
```

La separacion de dominio actual es consistente:

- `ServiceRequest` expresa intencion de atencion, no turno, diagnostico ni
  tratamiento.
- `Treatment` es una plantilla reutilizable de catalogo.
- `PetTreatment` es el tratamiento concreto asignado al paciente.
- `TreatmentSession` es la unidad operativa del tratamiento.

El principal problema es de interaccion: el cliente debe identificar un
servicio tecnico antes de poder explicar que necesita. El modelo actual soporta
una solicitud de atencion para un servicio, pero no una solicitud abierta donde
el profesional determine primero el servicio.

No existe `/public/services`. La ruta real es `/services` y requiere
autenticacion.

## 2. Flujo actual completo

| Paso | Actor | Ruta o pantalla | Accion e informacion | Resultado |
| --- | --- | --- | --- | --- |
| Explorar | Cliente autenticado | `/services` | Ve servicios activos, descripcion y detalle. | Elige un servicio tecnico. |
| Elegir paciente | Cliente | Card de servicio o detalle | Selecciona una mascota propia. | Navega a creacion con `?service={id}`. |
| Crear solicitud | Cliente | `/pets/{pet}/service-requests/create` | `service_id` obligatorio; nota opcional de hasta 2.000 caracteres. | Crea `ServiceRequest(pending)`. |
| Consultar historial | Cliente | `/pets/{pet}/service-requests` | Ve solicitudes de esa mascota, estado, servicio y tratamiento resultante si existe. | Puede abrir solicitud o tratamiento. |
| Consultar detalle | Cliente | `/pets/{pet}/service-requests/{serviceRequest}` | Ve nota, estado, fecha, servicio y tratamiento resultante. | Solo lectura. |
| Revisar cola | Admin | `/admin/service-requests` | Busca por paciente o responsable; filtra por servicio y estado. | Abre solicitud o accede a paciente, responsable o servicio. |
| Comprender solicitud | Admin | `/admin/service-requests/{serviceRequest}` | Ve paciente, responsable, servicio solicitado, nota, fecha, estado y opciones compatibles. | Puede cancelar o resolver. |
| Resolver | Admin | Mismo detalle | Elige plantilla del mismo servicio, sesiones requeridas, precio, fecha, estado inicial y notas. | Crea `PetTreatment` y sesiones; la solicitud pasa a `resolved`. |
| Gestionar tratamiento | Admin | `/admin/pets/{pet}/treatments/{petTreatment}` | Gestiona condiciones, estado y sesiones. | Seguimiento longitudinal. |
| Gestionar sesion | Admin | Dentro del tratamiento | Fecha, precio, notas y estado. | Actualiza progreso y, si aplica, el estado del tratamiento. |

El admin representa actualmente al profesional autorizado. No existe entidad,
rol ni asignacion de profesional diferenciada.

## 3. Entidades y datos involucrados

| Entidad | Responsabilidad actual |
| --- | --- |
| `Pet` | Paciente; conecta propietario, historia clinica, solicitudes y tratamientos. |
| `Service` | Area terapeutica general. Es obligatorio para una solicitud actual. |
| `Procedure` | Procedimiento de catalogo perteneciente a un servicio. |
| `Treatment` | Plantilla reutilizable compatible con un servicio. |
| `ServiceRequest` | Solicitud para un servicio y paciente; contiene estado, nota y tratamiento resultante opcional. |
| `PetTreatment` | Tratamiento asignado al paciente; conserva snapshots, condiciones y progreso. |
| `TreatmentSession` | Sesion operativa; tiene fecha opcional, precio, notas y estado propio. |
| `ClinicalRecord` | Historia clinica longitudinal separada; no es creada ni modificada por una solicitud o sesion. |

Relaciones clave:

```text
Client -> Pet -> ServiceRequest -> PetTreatment -> TreatmentSession
                    |
                    -> Service -> Treatment -> Procedure
```

La resolucion exige que el `Treatment` elegido pertenezca al `Service`
solicitado.

## 4. Estados y transiciones

### 4.1. Solicitud

```text
pending -> resolved
pending -> cancelled
```

| Transicion | Actor | Requisitos | Consecuencia |
| --- | --- | --- | --- |
| Crear -> `pending` | Cliente | Mascota propia, servicio activo, nota opcional. | No crea turno, historia clinica, tratamiento ni sesion. |
| `pending` -> `resolved` | Admin | Servicio aun activo; plantilla activa del mismo servicio; condiciones validas. | Crea tratamiento, snapshots y sesiones en una transaccion. |
| `pending` -> `cancelled` | Admin | Solicitud pendiente. | Conserva historial; no crea recursos. |

No existen turnos ni estados de turnos implementados.

### 4.2. Tratamiento asignado

```text
pending -> in_progress -> completed
pending/in_progress -> suspended
pending/in_progress -> cancelled
suspended -> pending | in_progress
```

- `in_progress` y `completed` se derivan al completar sesiones.
- `suspended` y `cancelled` son decisiones administrativas.
- `completed` y `cancelled` son finales.
- Un tratamiento suspendido no admite cambios de sesiones hasta reanudarlo.

### 4.3. Sesion

```text
pending -> completed
pending -> cancelled
```

- "Programada" no es estado persistido: es `pending` con `scheduled_at`.
- Una sesion cancelada se conserva y puede generar otra pendiente de reemplazo.
- Una sesion no equivale a turno ni reserva confirmada.

## 5. Problemas UX encontrados

1. El cliente debe elegir un servicio antes de poder explicar que necesita.
2. Las cards de servicios incluyen un selector de mascota por cada card, aumentando carga cognitiva y repeticion.
3. La creacion permite cambiar el servicio preseleccionado, debilitando el contexto iniciado desde catalogo.
4. No existe acceso global cliente a "Mis solicitudes"; estan fragmentadas por mascota.
5. El dashboard solo muestra hasta cinco solicitudes pendientes de la mascota seleccionada.
6. No hay prevencion de solicitudes duplicadas para misma mascota y servicio.
7. La lista cliente expone "Nueva solicitud" aun cuando no hay servicios activos; el formulario resultante queda sin opciones validas.
8. Las fechas ISO UTC se formatean en navegador local, con riesgo de cambio de dia cerca de medianoche.
9. La resolucion concentra decision clinica, condiciones economicas y creacion operativa en un mismo formulario.
10. Cancelar solicitud no pide ni conserva motivo.
11. La terminologia de sesiones requeridas cambia entre "previstas", "planificadas" y "requeridas".
12. "Aprobar e iniciar tratamiento" puede sugerir que resolver equivale necesariamente a iniciar atencion clinica.

## 6. Problemas de terminologia

| Concepto interno | Cliente recomendado | Admin o profesional recomendado |
| --- | --- | --- |
| `ServiceRequest` | Solicitud de atencion | Solicitud de atencion |
| `Service` | Area de atencion o servicio disponible | Servicio clinico |
| `Treatment` | No mostrar como decision del cliente | Plantilla de tratamiento |
| `PetTreatment` | Tratamiento definido o tratamiento de la mascota | Tratamiento asignado |
| `TreatmentSession` | Sesion | Sesion |
| `pending` con fecha | Sesion pendiente programada | Sesion pendiente programada |
| `planned_sessions` | Sesiones requeridas | Sesiones requeridas |
| `estimated_sessions` | No mostrar salvo detalle educativo | Sesiones estimadas |
| `ClinicalRecord` | Historia clinica | Historia clinica |
| `Pet` | Mascota | Paciente |

Evitar para cliente:

- "Plantilla compatible".
- "Tratamiento clinico".
- "Sesiones previstas".
- "Resolver solicitud".
- "Turno" para una sesion con fecha.

Evitar para ambos contextos:

- Usar "turno", "reserva" o "confirmado" para `TreatmentSession`.
- Llamar "tratamiento activo" a uno pendiente, suspendido, cancelado o completado.

## 7. Analisis especifico de `/services`

La ruta real es `/services`, no `/public/services`, y requiere sesion
autenticada.

### 7.1. Objetivo actual

Mostrar el catalogo de servicios activos y permitir iniciar una solicitud con
una mascota seleccionada.

### 7.2. Que entiende hoy el cliente

- Nombre del servicio.
- Descripcion breve.
- Procedimientos activos en el detalle.
- Que puede solicitar una evaluacion.

### 7.3. Que no puede inferir correctamente

- Si un servicio corresponde a su problema.
- Que plantilla o tratamiento sera finalmente necesario.
- Cuantas sesiones, precio o continuidad seran adecuados.
- Si necesita atencion general, urgente, seguimiento o un servicio especifico.

### 7.4. Evaluacion del modelo actual

Es coherente con el dominio F08: una solicitud siempre pertenece a un servicio
activo y la decision de tratamiento queda en admin.

La UX puede ser inadecuada para clientes sin conocimiento tecnico. El selector
de servicio funciona mejor si los servicios son categorias que el cliente
reconoce con seguridad, no si son decisiones profesionales.

### 7.5. Alternativas

| Alternativa | Ventaja | Impacto de dominio |
| --- | --- | --- |
| Mantener catalogo seleccionable | Sin cambios estructurales; respeta F08. | Ninguno. |
| Catalogo educativo + "Solicitar atencion" generica | Reduce carga cognitiva; el servicio deja de ser una barrera. | `service_id` hoy es obligatorio y la resolucion depende de el. |
| Solicitud guiada con categoria opcional | Conserva orientacion sin exigir precision. | Requiere distinguir categoria de servicio tecnico o hacer `service_id` opcional. |
| Dos entradas | "Se que servicio necesito" y "Necesito orientacion". | Requiere definir como se clasifica la segunda via antes de resolver. |

Recomendacion conceptual: mantener `/services` como catalogo educativo, pero no
asumir que la seleccion de servicio es obligatoria para toda necesidad de
atencion. La decision depende de aprobar una evolucion del dominio.

## 8. Analisis especifico de `admin/service-requests/{id}`

La pantalla actual contiene:

- Cabecera contextual completa de paciente.
- Datos de solicitud: fecha, estado, paciente, responsable, servicio y nota.
- Formulario de resolucion o estado bloqueado, cancelado o resuelto.
- Navegacion a paciente, responsable, servicio, historia clinica y tratamientos
  mediante enlaces o cabecera.

No carga ni renderiza historia clinica ni tratamientos completos. Solo muestra
la cabecera del paciente y, cuando esta resuelta, un resumen minimo del
tratamiento resultante.

| Grupo | Informacion actual | Evaluacion |
| --- | --- | --- |
| A. Necesaria para resolver | Estado, fecha, servicio solicitado, nota del cliente, paciente, responsable, disponibilidad del servicio y plantillas compatibles. | Debe permanecer visible. |
| B. Contexto util | Identidad basica del paciente, especie, raza, sexo y responsable en cabecera. | Correcto como contexto breve. |
| C. Pertenece a la ficha | Historia clinica, tratamientos existentes, sesiones y evolucion completa. | No se duplica actualmente; debe profundizarse desde navegacion contextual. |
| D. Redundante | Paciente y responsable reaparecen en detalle despues de la cabecera. | Puede simplificarse en un rediseño. |

El proposito se entiende, pero la jerarquia visual coloca solicitud y formulario
de tratamiento como columnas equivalentes. La tarea real deberia leerse como:

```text
Comprender solicitud -> revisar contexto minimo -> decidir -> resolver o cancelar
```

La resolucion deberia ser una accion focal, no una segunda pantalla clinica
implicita.

## 9. Separacion Solicitud vs Paciente

La implementacion actual respeta bien la separacion:

| Solicitud | Paciente |
| --- | --- |
| Evento operativo puntual. | Expediente longitudinal. |
| Quien solicita, para que mascota, servicio, nota, fecha y estado. | Identidad, responsable, historia clinica, tratamientos y sesiones. |
| Se resuelve o cancela. | Continua existiendo antes, durante y despues de la solicitud. |
| Puede enlazar al tratamiento resultante. | Es dueño de solicitudes y tratamientos. |

No se debe convertir el detalle de solicitud en una segunda ficha clinica. La
oportunidad futura es incorporar un resumen clinico explicitamente definido,
solo si se acuerda que datos son relevantes y seguros para decidir.

## 10. Navegacion y consistencia con Pacientes

### 10.1. Patron actual

- Shell admin: `Atencion > Solicitudes de atencion`.
- Lista: breadcrumbs `Inicio / Solicitudes de atencion`.
- Detalle: breadcrumbs `Inicio / Solicitudes de atencion / Solicitud #id`.
- Detalle: `PetContextHeader` muestra navegacion de paciente a Resumen, Historia
  clinica y Tratamientos.

### 10.2. Hallazgos

- No existe ruta admin `/admin/pets/{pet}/service-requests`; UX-03 decidio
  explicitamente no crearla.
- El acceso global desde Atencion es correcto para una cola operativa.
- La cabecera de paciente evita una navegacion paralela, pero recibe
  `active="service-requests"` aunque la variante admin no contiene esa pestaña.
- El retorno al listado depende de breadcrumbs; no existe una accion explicita
  "Volver a solicitudes".
- La navegacion a paciente, historia clinica y tratamientos ya es reutilizable
  desde `PetContextHeader`.

Recomendacion conceptual: mantener la cola global y el detalle global, usar
contexto de paciente como apoyo y agregar una accion visible de retorno a la
cola. No crear una pestaña administrativa de solicitudes por paciente sin
definir antes su endpoint, volumen, filtros y responsabilidad.

## 11. Componentes existentes reutilizables

| Componente o patron | Recomendacion |
| --- | --- |
| `AppLayout`, sidebar, topbar, bottom nav | Reutilizar sin cambios. |
| `PageHeader` | Reutilizar para catalogo y lista operativa. |
| `PetContextHeader` | Componer; no usar como contenido clinico. Revisar el estado activo inexistente en admin. |
| `Card`, `Badge`, `Button`, `Input`, `Select`, `Label` | Reutilizar sin cambios. |
| `EmptyState` | Reutilizar; hoy varias paginas usan estados vacios manuales. |
| `Avatar` | Reutilizar para paciente o mascota. |
| `ConfirmActionDialog` | Extender o adoptar para reemplazar `window.confirm()` de cancelar y resolver. |
| `ResponsiveDataList` | Componer para lista admin table/card responsive. |
| `TreatmentStatusBadge` | Reutilizar solo para tratamientos. |
| `TreatmentProgress` | Reutilizar solo en contexto de tratamiento. |
| `PetTreatmentCard` | Reutilizar para enlaces al tratamiento resultante o resumenes. |
| `TreatmentSessionCard` y `SessionRow` | Reutilizar en tratamiento, no en detalle de solicitud. |
| Badges locales de solicitud | Reemplazar posteriormente por un componente de estado de solicitud especifico. |
| `DashboardActivityList` | Componer solo para priorizacion/dashboard, no como sustituto de cola administrativa paginada. |

## 12. Flujo objetivo recomendado

Propuesta conceptual, pendiente de aprobacion:

```text
Cliente
Necesito atencion
-> elegir mascota
-> explicar motivo o necesidad
-> opcionalmente indicar categoria o servicio si lo reconoce
-> enviar solicitud

Admin o profesional
Nueva solicitud
-> comprender motivo y paciente
-> consultar resumen contextual
-> profundizar en historia clinica solo si corresponde
-> definir servicio o plan de atencion
-> resolver o cancelar
-> crear tratamiento solo cuando aplique
-> administrar sesiones desde tratamiento
```

Principios:

- El cliente expresa necesidad; no prescribe la solucion.
- El admin decide servicio y tratamiento dentro de sus permisos.
- La solicitud no se convierte automaticamente en turno, historia clinica ni
  tratamiento.
- El paciente conserva el contexto longitudinal.
- Las sesiones siguen siendo parte operativa del tratamiento, no agenda.

## 13. Terminologia recomendada

### 13.1. Cliente

- Entrada primaria: "Necesito atencion para una mascota".
- Formulario: "Contanos que necesitas".
- Historial: "Solicitudes de atencion".
- Estado pendiente: "En revision".
- Estado resuelto: "Atencion definida".
- Estado cancelado: "Solicitud cancelada".
- Resultado: "Tratamiento definido", cuando exista.

### 13.2. Admin o profesional

- Modulo: "Atencion".
- Cola: "Solicitudes de atencion".
- Accion primaria: "Definir atencion" o "Resolver solicitud".
- Accion de tratamiento: "Crear tratamiento asignado".
- Catalogo: "Servicios clinicos", "Procedimientos clinicos" y "Plantillas de tratamiento".
- Paciente: "Paciente".
- `planned_sessions`: "Sesiones requeridas".

La decision pendiente es si el estado tecnico `resolved` debe mantenerse como
etiqueta "Resuelta" o presentarse a cliente como "Atencion definida".

## 14. Datos y backend que requeririan cambios

No son cambios aprobados ni implementados.

Para una solicitud abierta de atencion haria falta decidir al menos:

1. Si `service_id` sigue siendo obligatorio.
2. Si se vuelve nullable para solicitudes generales.
3. Si se crea una entidad o categoria de atencion independiente de `Service`.
4. Si el motivo libre actual (`notes`) pasa a ser campo principal con estructura o sigue siendo texto libre.
5. Como se determina el servicio antes de elegir una plantilla.
6. Si se conserva compatibilidad historica con solicitudes existentes de servicio.
7. Si se agregan motivo de cancelacion, prioridad, canal, profesional responsable o comunicacion. Ninguno existe hoy.
8. Si se necesita un listado global cliente de solicitudes.
9. Si un resumen de historia clinica debe llegar al detalle de solicitud y con que criterios.

No se recomienda introducir turnos, disponibilidad, profesionales, agenda,
confirmaciones o salas dentro de este rediseño. Corresponden a la futura
Feature 10.

## 15. Brief visual - Cliente

### Pantallas actuales a representar

- Servicios disponibles.
- Detalle de servicio.
- Nueva solicitud para mascota.
- Solicitudes de una mascota.
- Detalle de solicitud.
- Tratamiento resultante, como destino posterior.

### Jerarquia

1. Mascota afectada.
2. Necesidad o servicio seleccionado.
3. Estado de solicitud.
4. Fecha de envio.
5. Nota del cliente.
6. Tratamiento definido, solo si existe.
7. Acciones contextuales.

### Datos reales disponibles

- Mascota: nombre, especie, raza, sexo y foto.
- Servicio: nombre, descripcion y procedimientos activos en detalle.
- Solicitud: fecha, estado y nota.
- Tratamiento resultante: nombre y sesiones requeridas.

### Estados vacios

- Sin mascotas: registrar mascota.
- Sin servicios: informar indisponibilidad.
- Sin solicitudes para mascota: solicitar atencion.
- Sin nota: explicar que no se agrego detalle.
- Sin tratamiento resultante: no prometer proxima accion inexistente.

### Responsive

- No repetir selector de mascota en cada card si el flujo se rediseña.
- Mantener acciones tactiles de 44 px.
- Usar cards verticales y navegacion contextual desplazable.
- No representar sesion programada como turno.

## 16. Brief visual - Admin o profesional

### Objetivo

```text
Comprender -> decidir -> resolver
```

### Pantallas

- Cola de solicitudes.
- Detalle de solicitud.
- Ficha del paciente.
- Historia clinica.
- Tratamientos del paciente.
- Detalle de tratamiento y sesiones.

### Jerarquia del detalle

1. Estado y antiguedad de la solicitud.
2. Paciente y responsable.
3. Motivo o nota del cliente.
4. Servicio solicitado o categoria futura.
5. Contexto clinico resumido, si se aprueba.
6. Decision principal: resolver, cancelar o identificar bloqueo.
7. Tratamiento resultante como trazabilidad, no como formulario adicional tras resolver.

### Datos reales disponibles

- Solicitud: estado, nota y fecha.
- Paciente: identidad basica y responsable.
- Servicio: nombre y disponibilidad.
- Plantillas compatibles: nombre y sesiones estimadas.
- Tratamiento resultante: nombre y sesiones requeridas.

### Estados

- Pendiente, resuelta, cancelada.
- Servicio inactivo: resolucion bloqueada.
- Sin plantilla compatible: crear plantilla.
- Sin solicitudes: estado vacio de cola.
- Filtros sin resultados.

### Responsive

- Cola: tabla desktop, cards mobile.
- Detalle: una decision principal visible antes de enlaces secundarios.
- Mantener breadcrumbs, retorno a lista y accesos contextuales al paciente.
- No cargar formularios de sesiones ni historia clinica completa dentro de la solicitud.

## 17. Riesgos y decisiones pendientes

1. **Modelo de entrada:** decidir si la solicitud debe seguir requiriendo `Service` o permitir "necesito atencion" sin clasificacion.
2. **Rol profesional:** hoy `admin` representa al profesional. Decidir si debe continuar asi en el proximo alcance.
3. **Servicio vs categoria:** si el cliente puede categorizar, decidir si esa categoria es un `Service`, otra entidad o solo orientacion no persistida.
4. **Estado visible al cliente:** decidir si "Resuelta" comunica suficiente o debe ser "Atencion definida".
5. **Contexto clinico:** definir que resumen minimo puede influir la decision sin duplicar la historia clinica.
6. **Cancelacion:** decidir si se requiere motivo persistido y visible.
7. **Duplicados:** decidir si se permiten solicitudes repetidas deliberadamente o se necesita advertencia.
8. **Acceso cliente global:** decidir si "Mis solicitudes" debe existir fuera de cada mascota.
9. **Documentacion en conflicto:** F08 permite tecnicamente crear solicitudes como admin por Policy; UX-07 declara que solo cliente crea. La UI actual expone el flujo al cliente, pero la regla durable debe unificarse.
10. **Terminologia de turnos:** no debe mezclarse con sesiones hasta que Feature 10 exista.

## 18. Historial de etapas posteriores

Estas etapas son historial de propuestas, no un plan activo ni funcionalidad de
V1. UX-ATTENTION-02 fue parcialmente adoptada en la entrada cliente; las demás
etapas permanecen pospuestas.

- `UX-ATTENTION-02`: propuesta de arquitectura visual; V1 adoptó la selección
  progresiva cliente y el responsive de solicitudes, sin cambiar el dominio.
- `UX-ATTENTION-03`: definir arquitectura de informacion y navegacion Cliente/Admin.
- `UX-ATTENTION-04`: producir referencias Stitch con datos reales y estados aprobados.
- `UX-ATTENTION-05`: migrar flujo Cliente reutilizando design system existente.
- `UX-ATTENTION-06`: migrar cola y detalle Admin/profesional.
- `UX-ATTENTION-07`: cambios de dominio/backend solo si se aprueba solicitud sin servicio obligatorio.
- `UX-ATTENTION-08`: pruebas HTTP, responsive, teclado y revision visual manual.

## Fuentes principales revisadas

- `spec.md`
- `technical.md`
- `features.md`
- `features/08-treatments-and-sessions.md`
- `features/UX-03-patient-and-pet-context.md`
- `features/UX-07-service-requests.md`
- `routes/web.php`
- `app/Http/Controllers/ServiceRequestController.php`
- `app/Http/Controllers/Admin/ServiceRequestController.php`
- `app/Services/ServiceRequestResolutionService.php`
- `resources/js/pages/services/index.tsx`
- `resources/js/pages/pets/service-requests/`
- `resources/js/pages/admin/service-requests/`
