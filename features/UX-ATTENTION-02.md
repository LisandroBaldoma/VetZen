# UX-ATTENTION-02 - Rediseño visual del flujo de Atención

> Estado: pendiente de aprobación para referencias visuales en Stitch. No
> implementar código, backend ni cambios de dominio a partir de este documento
> sin una decisión posterior explícita.

## 1. Alcance confirmado

UX-ATTENTION-02 rediseña exclusivamente la experiencia, arquitectura de
información, jerarquía visual, navegación, terminología visible, composición de
pantallas y reutilización de componentes del módulo Atención.

El flujo funcional se conserva sin excepciones:

```text
Cliente: Servicio -> Mascota -> Solicitud de atención
Admin: Solicitud -> Servicio solicitado -> Plantilla compatible
       -> Tratamiento asignado -> Sesiones
```

Se mantienen sin cambios:

- `service_id` obligatorio en `ServiceRequest`.
- Modelos, relaciones, rutas, Policies, validaciones y ownership.
- Estados internos de solicitudes, tratamientos y sesiones.
- Resolución transaccional: crear `PetTreatment`, snapshots y
  `TreatmentSession`, enlazar la solicitud y marcarla `resolved`.
- Estructura de base de datos y queries actuales.

No forma parte del alcance:

- Solicitudes generales sin servicio o nuevas categorías.
- Turnos, agenda, reservas, confirmaciones, disponibilidad o calendario.
- Profesionales asignados, prioridades o nuevos estados.
- Motivos de cancelación, prevención de duplicados o reglas nuevas.
- Migraciones, cambios de controllers, modelos, Form Requests, Policies,
  resources, queries o base de datos.

## 2. Flujo Cliente conservando funcionalidad actual

| Etapa                | Ruta existente                                  | Acción actual conservada                                           | Propósito UX rediseñado                                                                   |
| -------------------- | ----------------------------------------------- | ------------------------------------------------------------------ | ----------------------------------------------------------------------------------------- |
| Orientación          | `/services`                                     | Consulta Services activos.                                         | Ayudar a reconocer el tipo de atención por el que desea consultar, no vender un servicio. |
| Comprensión          | `/services/{service}`                           | Consulta detalle y procedimientos activos.                         | Aclarar el alcance del tipo de atención antes de solicitarlo.                             |
| Selección de mascota | Desde servicios o detalle                       | Elige una Pet propia; la ruta resultante conserva `?service={id}`. | Hacer explícita la secuencia: tipo de atención, mascota, motivo.                          |
| Solicitud            | `/pets/{pet}/service-requests/create`           | Envía `service_id` obligatorio y `notes` opcional.                 | Presentar una solicitud simple para una mascota concreta.                                 |
| Seguimiento          | `/pets/{pet}/service-requests`                  | Consulta solicitudes de la mascota.                                | Entender qué se solicitó, su estado y el resultado si existe.                             |
| Detalle              | `/pets/{pet}/service-requests/{serviceRequest}` | Consulta nota, estado y tratamiento resultante.                    | Comunicar el avance sin prometer turnos ni tratamiento antes de la resolución.            |
| Continuidad          | Tratamiento resultante existente                | Abre el `PetTreatment` propio.                                     | Conectar la solicitud resuelta con el seguimiento real ya existente.                      |

### 2.1. Secuencia visual cliente

La UI debe comunicar, sin incorporar pasos funcionales:

```text
1. Elegí el tipo de atención.
2. Elegí qué mascota necesita atención.
3. Contanos brevemente qué necesitás.
4. Enviá la solicitud.
```

El cliente selecciona un `Service` porque el dominio lo exige. La interfaz debe
explicarlo como el tipo de atención por el que quiere consultar y no como una
decisión clínica, diagnóstico, turno, compra o tratamiento ya definido.

## 3. Arquitectura de información Cliente

### 3.1. Servicios disponibles

**Objetivo:** orientar al cliente sobre los tipos de atención disponibles.

**Jerarquía:**

1. Pregunta guía: "¿Qué tipo de atención necesitás para tu mascota?"
2. Descripción breve que aclare que se solicitará una evaluación.
3. Servicios activos: nombre y descripción existente.
4. Acción por servicio: "Solicitar atención".
5. Acceso secundario: "Ver detalles".

Las cards no deben parecer productos, precios ni planes contratables. No se
inventan beneficios clínicos ni explicaciones médicas que no existan en
`Service.description`.

### 3.2. Selección de mascota

La selección hoy se repite dentro de cada card de servicio. La referencia visual
debe resolver esta repetición como composición, sin cambiar URL, payload ni
secuencia:

- El cliente elige primero un servicio.
- La interfaz presenta después la selección de una mascota propia.
- La elección conduce a la ruta existente de creación con el servicio
  preseleccionado.

La solución visual puede ser una superficie focal, diálogo o paso contextual
si usa únicamente las mascotas ya recibidas y termina en la misma ruta. No debe
crear persistencia temporal, ruta nueva ni un paso de negocio adicional.

### 3.3. Crear solicitud

**Objetivo:** enviar una solicitud de atención simple y comprensible.

**Orden visual:**

1. "Solicitud de atención".
2. Contexto de mascota: identidad básica y enlace a ficha si ya existe en el
   patrón de pantalla.
3. "Tipo de atención": `service_id`, fijado cuando proviene del catálogo;
   conserva el selector solo al acceder directamente sin una preselección.
4. "Contanos qué necesitás": `notes`, opcional.
5. Mensaje de alcance: la solicitud será evaluada; no reserva turno ni define
   tratamiento.
6. Acción primaria: "Enviar solicitud".
7. Acción secundaria: "Cancelar".

No prometer profesional, fecha, confirmación, turno ni tratamiento futuro.

### 3.4. Solicitudes de una mascota

**Objetivo:** permitir seguir solicitudes en el contexto correcto.

Cada registro prioriza:

```text
Qué solicité -> Para qué mascota -> En qué estado está -> Qué ocurrió después
```

La mascota ya está establecida por `PetContextHeader`; la card no necesita
repetirla como título principal. Debe mostrar servicio, fecha, etiqueta de
estado, acceso al detalle y, cuando exista, acceso al tratamiento definido.

### 3.5. Detalle de solicitud

**Objetivo:** explicar la situación de una solicitud sin introducir acciones
clínicas al cliente.

Orden visual:

1. Servicio o tipo de atención solicitado.
2. Estado comprensible.
3. Fecha de envío.
4. Nota del cliente, o ausencia explícita de nota.
5. Mensaje contextual según estado.
6. Resumen y enlace al tratamiento definido, solo cuando exista.

## 4. Flujo Admin conservando funcionalidad actual

| Etapa       | Ruta existente                             | Acción actual conservada                            | Propósito UX rediseñado                                              |
| ----------- | ------------------------------------------ | --------------------------------------------------- | -------------------------------------------------------------------- |
| Cola        | `/admin/service-requests`                  | Busca, filtra, pagina y abre solicitudes.           | Priorizar rápidamente qué solicitud requiere revisión.               |
| Comprensión | `/admin/service-requests/{serviceRequest}` | Lee servicio, nota, paciente, responsable y estado. | Comprender el evento operativo y su contexto mínimo.                 |
| Resolución  | Mismo detalle                              | Selecciona plantilla compatible y condiciones.      | Definir el tratamiento aplicable y resolver la solicitud.            |
| Cancelación | Mismo detalle                              | Cancela una solicitud pendiente.                    | Finalizar una solicitud que no será resuelta, conservando historial. |
| Continuidad | Tratamiento resultante                     | Navega al `PetTreatment` creado.                    | Pasar de la decisión a la gestión longitudinal y sesiones.           |

El objetivo operativo es:

```text
Comprender -> Decidir -> Resolver
```

## 5. Arquitectura de información Admin

### 5.1. Cola de solicitudes

**Objetivo:** ser una cola operativa, no un catálogo ni una ficha de paciente.

Prioridad de lectura por fila o card:

1. Estado.
2. Antigüedad o fecha.
3. Paciente.
4. Responsable.
5. Servicio solicitado.
6. Acción "Abrir solicitud".

Se conservan búsqueda, filtros de servicio y estado, limpieza de filtros,
paginación y enlaces actuales hacia paciente, responsable y servicio. La
composición debe reducir información secundaria para permitir escaneo rápido.

### 5.2. Detalle de solicitud

La navegación principal permanece en Atención. El paciente es contexto
relacionado, no la sección activa de la solicitud.

**Bloque Solicitud, visible primero:**

- Estado.
- Fecha de recepción.
- Servicio solicitado.
- Nota del cliente.

**Bloque Paciente, compacto:**

- Nombre.
- Especie.
- Raza.
- Sexo.
- Responsable.
- Acciones de contexto: "Ver paciente", "Historia clínica" y
  "Tratamientos".

**Bloque Resolución, tarea principal mientras la solicitud está pendiente:**

- Servicio solicitado como contexto no editable.
- Plantillas compatibles.
- Sesiones requeridas.
- Precio por sesión.
- Fecha de inicio.
- Estado inicial del tratamiento.
- Notas del tratamiento.
- Acción principal: "Resolver solicitud".

**Estados alternativos existentes:**

- Servicio inactivo: resolución bloqueada, acceso al servicio y cancelación
  disponible.
- Sin plantilla compatible: acceso actual para crear plantilla.
- Solicitud resuelta: resumen del tratamiento asignado y enlace.
- Solicitud cancelada: estado histórico sin resolución.

No se incrustan historia clínica, tratamientos, sesiones ni formularios de
paciente dentro del detalle de solicitud.

## 6. Terminología visual definitiva

Los nombres técnicos, tablas, clases, valores de estado y contratos no cambian.

### 6.1. Cliente

| Comportamiento real     | Etiqueta visual               |
| ----------------------- | ----------------------------- |
| Catálogo de `Service`   | Tipos de atención disponibles |
| `Service` seleccionado  | Tipo de atención              |
| Crear `ServiceRequest`  | Solicitud de atención         |
| Campo `notes`           | Contanos qué necesitás        |
| `pending`               | En revisión                   |
| `resolved`              | Atención definida             |
| `cancelled`             | Solicitud cancelada           |
| `PetTreatment` enlazado | Tratamiento definido          |

"Solicitar atención" es la acción más precisa para el catálogo: expresa el
comportamiento real de crear una solicitud, sin afirmar que el servicio ya fue
asignado como atención clínica ni que existe turno.

### 6.2. Admin

| Comportamiento real       | Etiqueta visual          |
| ------------------------- | ------------------------ |
| Módulo                    | Atención                 |
| Lista de `ServiceRequest` | Solicitudes de atención  |
| `Pet`                     | Paciente                 |
| `Service` de la solicitud | Servicio solicitado      |
| `Treatment`               | Plantilla de tratamiento |
| `PetTreatment`            | Tratamiento asignado     |
| `planned_sessions`        | Sesiones requeridas      |
| Resolver `ServiceRequest` | Resolver solicitud       |
| Resultado ya resuelto     | Tratamiento asignado     |

"Resolver solicitud" representa exactamente la transición técnica
`pending -> resolved`. "Definir atención" puede aparecer como texto de apoyo,
pero no reemplaza la acción primaria porque no describe por sí misma la
operación persistida.

## 7. Navegación

### 7.1. Cliente

Se conservan las rutas y `PetContextHeader` para las solicitudes bajo una
mascota:

```text
Inicio / Servicios disponibles
Inicio / Servicios disponibles / [Servicio]
Inicio / Mis mascotas / [Mascota] / Solicitudes de atención
Inicio / Mis mascotas / [Mascota] / Solicitudes de atención / Solicitud #[id]
```

El tratamiento resultante se abre en el contexto existente de la mascota.

### 7.2. Admin

La jerarquía de navegación es siempre:

```text
Inicio / Solicitudes de atención / Solicitud #[id]
```

El detalle incluye una acción clara "Volver a solicitudes" hacia el listado
existente. El bloque relacionado de paciente ofrece el sistema normal de
navegación:

```text
Resumen | Historia clínica | Tratamientos
```

No se representa `service-requests` como pestaña activa dentro de Pacientes:
esa ruta contextual administrativa no existe y no se crea en este alcance.

## 8. Mapa de componentes reutilizables

| Pantalla o necesidad   | Reutilizar directamente                                                                           | Componer o variante visual                                            | No utilizar                                                                              |
| ---------------------- | ------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| Servicios disponibles  | `PageHeader`, `Card`, `Button`, `EmptyState`                                                      | Cards de servicio y selector de mascota como interacción focal única. | Patrones de ecommerce, precio, carrito o compra.                                         |
| Detalle de servicio    | `PageHeader`, `Card`, `Button`, componentes de detalle existentes                                 | Bloque de procedimientos y CTA de atención.                           | Tratamientos asignados o sesiones.                                                       |
| Selección de mascota   | `Avatar`, `Button`, `Card`, datos actuales de mascotas                                            | Superficie contextual para elegir mascota antes de navegar.           | Persistencia nueva, wizard con rutas nuevas.                                             |
| Crear solicitud        | `PetContextHeader`, `Card`, `Button`, `InputError`, controles de formulario                       | Resumen de mascota/tipo de atención y mensaje de alcance.             | Datos clínicos no recibidos, calendario o disponibilidad.                                |
| Solicitudes cliente    | `PetContextHeader`, `Card`, `Badge`, `Button`, `EmptyState`                                       | Badge específico de solicitud y card de resultado resuelto.           | `TreatmentStatusBadge` para estados de solicitud.                                        |
| Detalle cliente        | `PetContextHeader`, `Card`, `Badge`, `Button`, `PetTreatmentCard` si el contrato de datos alcanza | Bloque de estado y resumen de tratamiento resultante.                 | Acciones de resolución, cancelación o sesiones.                                          |
| Cola admin             | `PageHeader`, `ResponsiveDataList`, `Button`, `Badge`, `EmptyState`, filtros actuales             | Fila/card operativa y badge específico de solicitud.                  | `PetContextHeader` como contenedor de lista.                                             |
| Detalle admin          | `Card`, `Badge`, `Button`, `Avatar`, `ConfirmActionDialog`, inputs existentes                     | Contexto compacto de paciente y superficie focal de resolución.       | `PetContextHeader` con `service-requests` activo; historia clínica o sesiones embebidas. |
| Tratamiento resultante | `PetTreatmentCard`, `TreatmentStatusBadge`, `TreatmentProgress`                                   | Resumen enlazado desde solicitud.                                     | Repetir formulario de resolución tras haber resuelto.                                    |

`ConfirmActionDialog` debe reemplazar visualmente las confirmaciones nativas
actuales en una futura implementación, sin modificar las acciones ni sus
endpoints.

## 9. Propuesta de composición por pantalla

### 9.1. `/services`

```text
PageHeader
  Pregunta guía
  Explicación breve de evaluación

Listado de tipos de atención
  Nombre y descripción de Service
  Ver detalles
  Solicitar atención

Estado vacío de servicios
```

Al pulsar "Solicitar atención", la composición debe pedir mascota una única vez
para el servicio elegido y luego navegar al formulario existente con
`?service={id}`.

### 9.2. `/services/{service}`

```text
Breadcrumbs
Service: nombre y descripción
Procedimientos disponibles, si existen
Solicitar atención
```

### 9.3. `/pets/{pet}/service-requests/create`

```text
PetContextHeader
Solicitud de atención
  Contexto de mascota
  Tipo de atención
  Contanos qué necesitás
  Mensaje: se evaluará la solicitud; no es un turno ni tratamiento
  Cancelar | Enviar solicitud
```

### 9.4. `/pets/{pet}/service-requests`

```text
PetContextHeader
Solicitudes de atención
  Nueva solicitud
Listado
  Tipo de atención
  Estado
  Fecha
  Ver solicitud
  Ver tratamiento definido, si existe
Estado vacío
```

### 9.5. `/pets/{pet}/service-requests/{serviceRequest}`

```text
PetContextHeader
Solicitud de atención
  Tipo de atención
  Estado y fecha
  Tu mensaje
  Explicación correspondiente al estado
  Tratamiento definido y enlace, si existe
```

### 9.6. `/admin/service-requests`

```text
PageHeader
  Solicitudes de atención
  Descripción operativa
Filtros y búsqueda existentes
Cola responsive
  Estado | fecha | paciente | responsable | servicio | abrir
Paginación existente
Estado vacío o sin resultados
```

### 9.7. `/admin/service-requests/{serviceRequest}`

```text
Breadcrumbs: Atención / Solicitudes de atención / Solicitud #[id]
Volver a solicitudes

Solicitud
  Estado, fecha, servicio solicitado, nota del cliente

Paciente relacionado
  Identidad compacta, responsable y accesos longitudinales

Resolución o estado actual
  Formulario existente si pending y resoluble
  Bloqueado, sin plantillas, resuelta o cancelada según corresponda
```

## 10. Brief Stitch - Cliente

### Servicios -> selección de mascota -> crear solicitud -> seguimiento -> tratamiento resultante

| Pantalla               | Objetivo                                                | Datos reales                                                      | CTA principal                            | Acciones secundarias                                         | Estados y responsive                                                                         |
| ---------------------- | ------------------------------------------------------- | ----------------------------------------------------------------- | ---------------------------------------- | ------------------------------------------------------------ | -------------------------------------------------------------------------------------------- |
| Servicios disponibles  | Orientar sobre tipos de atención.                       | Nombre y descripción de servicios activos; mascotas propias.      | Solicitar atención.                      | Ver detalles; registrar mascota cuando no existen.           | Sin servicios; sin mascotas. Cards apiladas en móvil, grilla moderada en desktop.            |
| Detalle de servicio    | Entender el tipo de atención antes de solicitar.        | Nombre, descripción y procedimientos activos.                     | Solicitar atención.                      | Volver a servicios.                                          | Sin procedimientos no debe inventar explicación. Contenido de lectura vertical en móvil.     |
| Selección de mascota   | Elegir para quién se consulta.                          | Nombre de mascotas propias y, si ya está disponible, avatar/foto. | Continuar a solicitud.                   | Cancelar o elegir otro servicio.                             | Debe ser una decisión corta y focal, no repetida por card.                                   |
| Crear solicitud        | Enviar motivo para mascota y tipo de atención.          | Mascota, servicios activos, servicio preseleccionado, nota.       | Enviar solicitud.                        | Cancelar; selector de tipo solo al acceder sin preselección. | Errores de validación existentes. Formulario de una columna en móvil.                        |
| Solicitudes de mascota | Seguir solicitudes de una mascota.                      | Servicio, fecha, estado, tratamiento resultante opcional.         | Ver solicitud.                           | Nueva solicitud; ver tratamiento definido.                   | Sin solicitudes. Cards compactas en móvil y grilla/lista en desktop según densidad aprobada. |
| Detalle de solicitud   | Entender que ocurrió y continuar si existe tratamiento. | Servicio, estado, fecha, nota, tratamiento resultante.            | Ver tratamiento definido, cuando existe. | Volver a solicitudes.                                        | En revisión, atención definida, solicitud cancelada, sin nota. Sin acciones clínicas.        |

Restricciones para Stitch:

- No mostrar precio, duración, agenda, profesional, confirmación ni turno.
- No mostrar diagnóstico, tratamiento elegido o sesiones antes de existir un
  `PetTreatment` resultante.
- No inventar descripciones médicas adicionales.
- La sesión programada nunca se representa como turno.

## 11. Brief Stitch - Admin

### Cola -> detalle -> resolución -> tratamiento resultante

| Pantalla               | Objetivo                                 | Datos reales                                                                                              | CTA principal                          | Acciones secundarias                                                          | Estados y responsive                                                                                 |
| ---------------------- | ---------------------------------------- | --------------------------------------------------------------------------------------------------------- | -------------------------------------- | ----------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| Cola de solicitudes    | Identificar rápidamente qué abrir.       | Estado, fecha, paciente, responsable, servicio; filtros y paginación.                                     | Abrir solicitud.                       | Filtros, limpiar, acceder a paciente/responsable/servicio.                    | Vacía, sin resultados, pending/resolved/cancelled. Tabla en desktop, cards operativas en móvil.      |
| Detalle pendiente      | Comprender y resolver.                   | Solicitud, nota, servicio, paciente, responsable, plantillas compatibles y campos actuales de resolución. | Resolver solicitud.                    | Volver a solicitudes, ver paciente, historia clínica, tratamientos, cancelar. | Servicio inactivo, sin plantilla compatible, errores de validación. Una columna priorizada en móvil. |
| Detalle resuelto       | Entender trazabilidad.                   | Solicitud y tratamiento asignado: nombre y sesiones requeridas.                                           | Ver tratamiento asignado.              | Volver a solicitudes; accesos al paciente.                                    | No mostrar formulario de resolución.                                                                 |
| Detalle cancelado      | Consultar historial.                     | Solicitud, nota y estado cancelado.                                                                       | Volver a solicitudes.                  | Accesos al paciente.                                                          | No sugerir reapertura ni nueva regla.                                                                |
| Tratamiento resultante | Continuar gestión operacional existente. | Datos propios de `PetTreatment` y sesiones.                                                               | Usar acción existente del tratamiento. | Navegación de paciente.                                                       | Fuera del rediseño interno de Atención, salvo enlace y resumen de origen.                            |

Restricciones para Stitch:

- No incluir historia clínica completa ni sesiones dentro de la solicitud.
- No mostrar asignación de profesional, prioridad, motivo de cancelación o
  disponibilidad.
- No transformar la resolución en una cita, aprobación de turno ni calendario.
- No mostrar una pestaña Solicitudes bajo Pacientes para admin.

## 12. Diferencias visuales desktop/mobile

| Área                 | Desktop                                                                                         | Mobile                                                                                                             |
| -------------------- | ----------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------ |
| Servicios            | Grilla moderada de tipos de atención, lectura y CTA claros.                                     | Una columna; CTA de ancho completo cuando corresponda.                                                             |
| Selección de mascota | Superficie focal centrada o lateral, sin selectores repetidos por card.                         | Hoja, diálogo o bloque vertical; objetivos táctiles de al menos 44 px.                                             |
| Crear solicitud      | Contexto y formulario pueden convivir en dos regiones si no compiten.                           | Contexto arriba, formulario de una columna, acción primaria visible al final.                                      |
| Solicitudes cliente  | Grilla/lista con estado y acción de lectura rápida.                                             | Cards de una columna con estado visible y CTA completo.                                                            |
| Cola admin           | Tabla responsive desde breakpoint de escritorio.                                                | Cards operativas: estado, paciente, fecha, responsable, servicio y abrir.                                          |
| Detalle admin        | Solicitud y contexto de paciente pueden ser dos regiones; resolución mantiene prioridad visual. | Secuencia única: solicitud, paciente, resolución. No ocultar la acción principal al final de una pantalla extensa. |

En todos los formatos:

- Mantener breadcrumbs y orden de foco lógico.
- Evitar overflow horizontal de página.
- Usar estado con texto y señal visual, no color aislado.
- Truncar o permitir wrap seguro para nombre de paciente, responsable y
  servicio.

## 13. Mejoras futuras fuera de alcance

Estas posibilidades se documentan pero no se diseñan ni implementan en
UX-ATTENTION-02:

- Solicitud de atención sin `service_id` obligatorio.
- Categorías adicionales o clasificación profesional posterior.
- Turnos, agenda, reserva, disponibilidad y confirmación.
- Profesional responsable, prioridad o SLA.
- Motivo de cancelación.
- Prevención o advertencia de solicitudes duplicadas.
- Listado global cliente de solicitudes.
- Contexto clínico resumido dentro del detalle de solicitud, si requiere datos
  nuevos o queries nuevas.
- Cambios de etiquetas persistidas, estados, validaciones o reglas de negocio.

## 14. Fuentes

- `features/UX-ATTENTION-01.md`
- `features/UX-03-patient-and-pet-context.md`
- `features/UX-07-service-requests.md`
- `features/08-treatments-and-sessions.md`
- `routes/web.php`
- `app/Http/Controllers/ServiceRequestController.php`
- `app/Http/Controllers/Admin/ServiceRequestController.php`
- `resources/js/pages/services/`
- `resources/js/pages/pets/service-requests/`
- `resources/js/pages/admin/service-requests/`
