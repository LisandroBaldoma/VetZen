# Feature 08 — Tratamientos y sesiones

## 1. Objetivo

Implementar tratamientos reutilizables del catálogo y su asignación operativa a mascotas, apoyándose en los servicios y procedimientos definidos por Feature 07:

```text
Service
  ├── Procedure
  └── Treatment ── incluye uno o varios Procedure del mismo Service

Pet
  └── PetTreatment
        └── TreatmentSession
```

Un servicio representa un área terapéutica general. Sus procedimientos representan técnicas disponibles dentro de esa área. Un tratamiento representa una combinación reutilizable de procedimientos y una cantidad estimada de sesiones. Al asignarlo a una mascota se crea un tratamiento individual con condiciones acordadas y sesiones con precio y estado independientes.

Esta feature no implementa protocolos clínicos complejos, planificación de procedimientos por número de sesión, pagos, facturación ni evolución clínica detallada.

## 2. Contexto y problema de negocio

La definición anterior de F07 trataba cada servicio como una prestación con duración, precio y modalidades propias, y postergaba los tratamientos. La validación con el veterinario reemplaza ese modelo:

* un servicio es el área terapéutica general y no tiene precio ni duración;
* un procedimiento pertenece a un servicio y puede tener duración orientativa, pero no precio;
* un tratamiento pertenece a un servicio, combina uno o más procedimientos y propone una cantidad estimada de sesiones;
* el catálogo de tratamientos no contiene un precio general;
* la veterinaria cobra por sesión;
* la asignación a una mascota debe preservar lo acordado aunque posteriormente cambie el catálogo;
* cada sesión conserva su propio precio y estado.

Este modelo representa la práctica real sin anticipar protocolos, facturación ni estructuras clínicas innecesarias.

## 3. Dependencias y compatibilidad

F08 depende de:

* Feature 03: autenticación y verificación de email;
* Feature 04: roles `admin` y `client`, Spatie Laravel Permission, Policies y separación de áreas;
* Feature 05: relaciones `User → Client → Pet`, ownership y administración de mascotas;
* Feature 06: protección de información clínica y convenciones de autorización;
* Feature 07: catálogo de servicios y procedimientos;
* Laravel, React, Inertia, Wayfinder, Tailwind CSS y PHPUnit ya adoptados.

Las relaciones con cliente y mascota se resuelven mediante el modelo existente. No se duplica `client_id` ni `user_id` cuando el ownership puede derivarse desde `Pet`.

## 4. Alcance funcional

### Catálogo de tratamientos

El administrador puede:

* utilizar servicios y procedimientos activos administrados por F07;
* listar, consultar, crear, editar, activar y desactivar tratamientos del catálogo;
* asociar uno o varios procedimientos a un tratamiento;
* consultar activos e inactivos sin perder referencias históricas.

### Tratamientos de mascotas

El administrador puede:

* asignar un tratamiento activo del catálogo a una mascota;
* definir las condiciones acordadas para esa asignación;
* generar sus sesiones previstas;
* consultar y actualizar el estado del tratamiento asignado;
* consultar y actualizar fecha programada, precio, estado y notas de cada sesión;
* consultar el progreso calculado desde las sesiones completadas.

### Cliente

El cliente puede consultar los tratamientos asignados a sus propias mascotas y
sus sesiones, siempre en modo de solo lectura. No puede solicitar tratamientos
ni modificar asignaciones, sesiones, fechas, precios o estados.

Las operaciones son create, read, update y cambio de estado. No se incluye eliminación física, Soft Deletes ni archivo independiente.

## 5. Actores y permisos

### Administrador

El rol `admin` puede administrar tratamientos, asignarlos a cualquier mascota existente y administrar sus sesiones. Servicios y procedimientos pertenecen a F07. Todas las operaciones requieren autorización de backend.

### Cliente

El rol `client`:

* no crea ni modifica servicios, procedimientos o tratamientos del catálogo;
* no asigna tratamientos;
* no crea ni modifica sesiones;
* si se habilita la lectura, solo consulta tratamientos y sesiones de sus propias mascotas;
* nunca accede a información de mascotas ajenas mediante URLs, bindings, payloads o requests directas.

No se crean roles adicionales ni una matriz granular de profesionales.

| Acción | `client` | `admin` |
| --- | --- | --- |
| Administrar Treatment | Denegado | Permitido |
| Asignar Treatment a Pet | Denegado | Permitido |
| Administrar PetTreatment o TreatmentSession | Denegado | Permitido |
| Leer PetTreatment y sesiones propios | Permitido, solo lectura | Permitido |
| Leer recursos de otra Pet | Denegado | Permitido |

## 6. Entidades y relaciones

```text
Service 1 ──── * Procedure
Service 1 ──── * Treatment
Treatment * ──── * Procedure

User 1 ──── 0..1 Client
Client 1 ──── * Pet
Pet 1 ──── * PetTreatment
Treatment 1 ──── * PetTreatment
PetTreatment 1 ──── * TreatmentSession
```

La relación `Treatment ↔ Procedure` es multivalor. Todo procedimiento asociado debe pertenecer al mismo servicio que el tratamiento.

`PetTreatment` es la instancia individual acordada para una mascota; `Treatment` es una plantilla reutilizable. `TreatmentSession` pertenece exclusivamente a `PetTreatment`.

## 7. Service

`Service` representa un área terapéutica general ofrecida por VetZen.

| Campo conceptual | Contrato |
| --- | --- |
| `id` | Identificador y route model binding |
| `name` | Nombre obligatorio y único |
| `description` | Descripción general obligatoria, texto plano |
| `is_active` | Booleano, default `true` |
| timestamps | Timestamps convencionales |

Un servicio no tiene precio, moneda, duración, modalidad, disponibilidad horaria, profesional ni relación directa con mascotas o historia clínica.

Ejemplos: Fisioterapia, Acupuntura, Fitoterapia y Flores de Bach.

## 8. Procedure

`Procedure` representa una técnica o práctica perteneciente a un servicio.

| Campo conceptual | Contrato |
| --- | --- |
| `id` | Identificador |
| `service_id` | Servicio propietario, obligatorio |
| `name` | Nombre obligatorio |
| `description` | Descripción opcional, texto plano |
| `duration_minutes` | Entero positivo nullable; duración orientativa |
| `is_active` | Booleano, default `true` |
| timestamps | Timestamps convencionales |

El nombre es único dentro de su servicio, no necesariamente en todo VetZen. Un procedimiento no tiene precio, moneda, cantidad de sesiones ni información clínica individual.

Desactivarlo impide seleccionarlo en nuevos tratamientos, pero conserva sus referencias históricas.

## 9. Treatment

`Treatment` representa un tratamiento reutilizable del catálogo.

| Campo conceptual | Contrato |
| --- | --- |
| `id` | Identificador |
| `service_id` | Servicio al que pertenece |
| `name` | Nombre obligatorio |
| `description` | Descripción obligatoria, texto plano |
| `estimated_sessions` | Entero mayor que cero |
| `is_active` | Booleano, default `true` |
| timestamps | Timestamps convencionales |

Un tratamiento:

* pertenece a un único servicio;
* incluye al menos un procedimiento;
* solo incluye procedimientos del mismo servicio;
* no tiene precio, moneda, duración total, frecuencia ni objetivos clínicos;
* no define qué procedimiento se aplicará en cada sesión;
* no puede agregar procedimientos inactivos;
* conserva asociaciones históricas si un procedimiento se desactiva después.

El nombre es único dentro del servicio.

## 10. PetTreatment

`PetTreatment` representa la asignación de un tratamiento del catálogo a una mascota.

| Campo conceptual | Contrato |
| --- | --- |
| `id` | Identificador |
| `pet_id` | Mascota autorizada |
| `treatment_id` | Tratamiento seleccionado |
| `planned_sessions` | Sesiones previstas, copiadas inicialmente de `estimated_sessions` |
| `default_session_price` | Precio predeterminado por sesión, decimal no negativo |
| `currency` | Moneda explícita; valor inicial `ARS` |
| `starts_on` | Fecha de inicio |
| `status` | Estado controlado |
| `notes` | Texto opcional |
| timestamps | Timestamps convencionales |

La mascota se resuelve mediante un contexto autorizado y no se reasigna por mass assignment. La asignación conserva las condiciones acordadas aunque cambie posteriormente el catálogo.

Además de mantener `treatment_id`, la asignación conserva como snapshot:

* nombre del tratamiento;
* descripción del tratamiento;
* cantidad de sesiones acordada;
* precio predeterminado por sesión y moneda;
* procedimientos incluidos al asignar.

Los procedimientos congelados se persisten mediante una relación propia entre
`PetTreatment` y `Procedure`, conservando `procedure_id` cuando exista, nombre
y descripción opcional como snapshots. Las consultas históricas usan los
snapshots y no los valores actuales del catálogo.

## 11. TreatmentSession

`TreatmentSession` representa una sesión concreta del tratamiento asignado.

| Campo conceptual | Contrato |
| --- | --- |
| `id` | Identificador |
| `pet_treatment_id` | Tratamiento asignado propietario |
| `session_number` | Entero positivo, único dentro de `PetTreatment` |
| `scheduled_at` | Fecha y hora programada nullable |
| `price` | Decimal no negativo; precio propio de la sesión |
| `currency` | Moneda copiada inicialmente desde `PetTreatment` |
| `status` | Estado controlado |
| `notes` | Texto opcional |
| timestamps | Timestamps convencionales |

Al generarse, cada sesión copia precio y moneda de `PetTreatment`. Su precio puede cambiar individualmente. Cambiar el precio predeterminado no modifica sesiones ya creadas, históricas o completadas.

## 12. Reglas de negocio

1. `Service` es un área terapéutica y no tiene precio ni duración.
2. Todo `Procedure` pertenece a un `Service` y no tiene precio.
3. Todo `Treatment` pertenece a un `Service` y requiere al menos un `Procedure` de ese mismo servicio.
4. `estimated_sessions` y `planned_sessions` son enteros mayores que cero.
5. El catálogo no define precios; el precio se acuerda al asignar el tratamiento y se persiste por sesión.
6. Una asignación nueva solo puede usar un tratamiento activo.
7. Las sesiones pertenecen a `PetTreatment`, nunca directamente a `Treatment`.
8. La generación inicial crea exactamente `planned_sessions`, numeradas desde 1 sin duplicados ni huecos.
9. Cada sesión nueva copia `default_session_price` y `currency`.
10. Cambios del catálogo no reescriben asignaciones ni sesiones existentes.
11. Cambios del precio predeterminado no reescriben sesiones existentes.
12. Una sesión completada cuenta para el progreso; una cancelada no cuenta como completada.
13. `planned_sessions` representa la cantidad de sesiones efectivamente
    completadas que requiere el tratamiento.
14. El progreso se calcula exclusivamente como
    `completed_sessions / planned_sessions`; las canceladas no forman parte del
    numerador ni del denominador.
15. Cancelar una sesión no modifica `planned_sessions` y conserva el registro,
    número, precio y demás datos históricos de la sesión cancelada.
16. Después de una cancelación, el sistema garantiza que
    `completed_sessions + pending_sessions >= planned_sessions`.
17. Si falta una sesión, se genera un reemplazo `pending` con el siguiente
    número consecutivo disponible y el `default_session_price` vigente.
18. Los números cancelados no se reutilizan y nunca existen números duplicados.
19. Si el tratamiento está `suspended` o `cancelled`, no se generan reemplazos
    automáticamente.
20. Activar/desactivar conserva la identidad; no existe delete.
21. Toda autorización y ownership se aplican en Laravel.
22. `pet_id`, `client_id`, `user_id`, roles o permisos del frontend no prueban ownership.
23. `planned_sessions` puede ajustarse transaccionalmente mientras el
    tratamiento esté `pending` o `in_progress`.
24. Al aumentarlo se crean sesiones `pending`, consecutivas y con el precio
    predeterminado vigente.
25. Al reducirlo solo se eliminan las últimas sesiones `pending`; nunca se
    eliminan sesiones `completed` o `cancelled`.
26. `planned_sessions` nunca puede quedar por debajo de las sesiones
    completadas.
27. Un tratamiento `suspended` o `cancelled` no admite cambios de cantidad ni
    sesiones. El tratamiento suspendido puede reanudarse manualmente; el
    cancelado no se reabre en F08.

## 13. Estados y transiciones

### PetTreatment

* `pending`: asignado, todavía no iniciado;
* `in_progress`: tratamiento iniciado;
* `completed`: tratamiento finalizado;
* `suspended`: pausado temporalmente;
* `cancelled`: cancelado.

### TreatmentSession

* `pending`: sesión prevista o programada;
* `completed`: sesión realizada;
* `cancelled`: sesión cancelada.

Los estados son valores controlados por la aplicación. Se aplican estas reglas:

* completar la primera sesión cambia `pending` a `in_progress` si aún quedan
  sesiones requeridas;
* cuando las sesiones completadas alcanzan `planned_sessions`, el tratamiento
  pasa a `completed`;
* `suspended` y `cancelled` solo se establecen manualmente por admin;
* reanudar un tratamiento suspendido lo devuelve a `pending` o `in_progress`
  según la cantidad de sesiones completadas;
* cancelar una sesión no cancela el tratamiento;
* cancelar una sesión genera un reemplazo cuando
  `completed_sessions + pending_sessions < planned_sessions`, excepto si el
  tratamiento está suspendido o cancelado;
* un tratamiento cancelado no admite modificaciones ni nuevas sesiones;
* una reapertura de tratamientos cancelados queda fuera de F08.

La posibilidad de aumentar sesiones de un tratamiento ya `completed` permanece
pendiente por la contradicción indicada en la sección 24.

## 14. Flujos principales

### Administrar tratamientos del catálogo

1. Admin selecciona un servicio administrado por F07.
2. Consulta sus procedimientos activos.
3. Crea un tratamiento, define descripción y sesiones estimadas.
4. Selecciona uno o más procedimientos activos del mismo servicio.
5. El backend valida consistencia y persiste catálogo y asociaciones.

### Asignar un tratamiento a una mascota

1. Admin abre una mascota existente.
2. El backend autoriza la mascota y muestra tratamientos activos.
3. Admin selecciona tratamiento, fecha de inicio, sesiones previstas, precio por sesión, moneda, estado y notas.
4. El backend vuelve a validar tratamiento, servicio y ownership.
5. Crea `PetTreatment`, conserva el acuerdo y genera sus sesiones atómicamente.
6. Cada sesión queda numerada, con precio propio y estado `pending`.

### Administrar una sesión

1. Admin abre el tratamiento asignado desde la mascota.
2. Selecciona una sesión autorizada.
3. Puede cambiar fecha programada, precio, estado y notas.
4. El backend actualiza únicamente esa sesión.
5. El progreso se recalcula desde las sesiones completadas.

### Cancelar y reemplazar una sesión

1. Admin cambia una sesión `pending` a `cancelled`.
2. El backend conserva íntegramente la sesión cancelada.
3. Dentro de la misma transacción calcula sesiones completadas y pendientes.
4. Si su suma es menor que `planned_sessions` y el tratamiento no está
   suspendido ni cancelado, crea una sesión `pending` de reemplazo.
5. El reemplazo usa el siguiente `session_number`, sin reutilizar el cancelado,
   y copia el precio y moneda predeterminados vigentes.

### Cliente: consulta propia

1. Cliente abre una mascota propia.
2. El backend verifica `User → Client → Pet`.
3. Lista tratamientos asignados y sesiones en modo lectura.
4. Una URL de otra mascota se deniega.

## 15. Validaciones

La implementación usa Form Requests y separa validación de autorización.

### Service

* `name`: required, string, máximo 255 y único globalmente;
* `description`: required, string y texto plano;
* `is_active`: boolean.

### Procedure

* servicio: existente y autorizado, preferentemente derivado de la ruta;
* `name`: required, string, máximo 255 y único por servicio;
* `description`: nullable, string y texto plano;
* `duration_minutes`: nullable, integer, mínimo 1;
* `is_active`: boolean.

### Treatment

* servicio: existente y autorizado;
* `name`: required, string, máximo 255 y único por servicio;
* `description`: required, string y texto plano;
* `estimated_sessions`: required, integer, mínimo 1;
* procedimientos: required, array, mínimo 1 y sin duplicados;
* cada procedimiento: existente, activo y del mismo servicio;
* `is_active`: boolean.

### PetTreatment

* mascota: existente y autorizada, derivada del contexto;
* tratamiento: required, existente y activo;
* `planned_sessions`: required, integer, mínimo 1;
* `default_session_price`: required, decimal válido, mínimo 0, nunca float;
* `currency`: required, inicialmente solo `ARS`;
* `starts_on`: required, fecha válida;
* `status`: required y perteneciente a los estados aprobados;
* `notes`: nullable, string.

### TreatmentSession

* tratamiento asignado: existente y autorizado, derivado de la ruta;
* `session_number`: required, integer, mínimo 1 y único por asignación;
* `scheduled_at`: nullable, fecha y hora válida;
* `price`: required, decimal válido, mínimo 0, nunca float;
* `currency`: required, inicialmente solo `ARS`;
* `status`: required y perteneciente a los estados aprobados;
* `notes`: nullable, string.

Los modelos declaran explícitamente atributos asignables. Payloads con ownership, roles, permisos o relaciones no autorizadas se ignoran o rechazan sin alterar recursos.

## 16. Autorización y ownership

El catálogo es global y su administración pertenece a `admin`. Los recursos individuales siguen:

```text
User → Client → Pet → PetTreatment → TreatmentSession
```

Las Policies protegen cada recurso y comprueban la relación completa. Una sesión no se autoriza por conocer su ID. El frontend refleja permisos, pero middleware, Policies, Form Requests y consultas acotadas son la barrera definitiva.

## 17. Persistencia e integridad

La futura implementación mantiene:

* claves foráneas para todas las relaciones aprobadas;
* unicidad de `Service.name`;
* unicidad de `Procedure(service_id, name)`;
* unicidad de `Treatment(service_id, name)`;
* unicidad de la asociación `Treatment ↔ Procedure`;
* unicidad de `TreatmentSession(pet_treatment_id, session_number)`;
* importes decimales, nunca float;
* timestamps convencionales;
* `is_active = true` como default del catálogo;
* ausencia de Soft Deletes y columnas especulativas.

Las sesiones se generan atómicamente con `PetTreatment`. La implementación debe evitar números duplicados ante reintentos.

## 18. Frontend

### Administrador

La UI React/Inertia ofrece:

* tratamientos contextualizados por servicio, usando los procedimientos administrados por F07 con selección multivalor compatible;
* tratamientos asignados contextualizados por mascota;
* detalle de asignación con progreso y sesiones;
* edición individual de sesión;
* estados vacíos, errores, carga y confirmaciones coherentes.

### Cliente

La lectura se accede desde una mascota propia y no desde un panel global. Solo
se muestran asignaciones, snapshots y sesiones autorizadas en modo lectura. No
se muestran controles de solicitud o modificación.

Se reutilizan `AppLayout`, componentes existentes, flash/toasts y Wayfinder. No se agregan API REST paralela, router frontend, estado global ni librería UI.

## 19. Testing requerido

Las futuras pruebas Feature/HTTP cubren como mínimo:

* CRUD sin delete y cambio de estado de Treatment;
* nombres de Treatment únicos por servicio;
* procedimientos vacíos, duplicados, inactivos o de otro servicio rechazados;
* ausencia de precio en Treatment;
* asignación de tratamiento activo a mascota;
* generación atómica de sesiones numeradas con precio y moneda copiados;
* independencia del precio y estado de cada sesión;
* cancelación conserva historial y genera exactamente el reemplazo necesario;
* el reemplazo usa numeración consecutiva y precio predeterminado vigente;
* tratamientos suspendidos o cancelados no generan reemplazos automáticos;
* cambio del precio predeterminado sin modificar sesiones existentes;
* cambios del catálogo sin alterar el acuerdo histórico;
* cálculo de progreso desde sesiones completadas;
* validación de enteros, decimales, fechas, estados y longitudes;
* admin administra cualquier mascota y client no muta recursos de F08;
* Client A lee recursos de Pet A y nunca los de Pet B;
* payloads manipulados no reasignan relaciones, ownership, roles o permisos;
* autenticación y verificación de email;
* ausencia de ruta de eliminación;
* regresión de autenticación, clientes, mascotas e historia clínica.

## 20. Casos límite relevantes

* Un servicio inactivo conserva procedimientos, tratamientos y asignaciones históricas, pero no se usa en nuevas configuraciones.
* Un procedimiento inactivo sigue visible históricamente y no se agrega a nuevos tratamientos.
* Un tratamiento inactivo conserva asignaciones y no puede asignarse nuevamente.
* Cambiar el servicio de un procedimiento o tratamiento no puede dejar asociaciones cruzadas.
* Una asignación confirmada nunca queda sin sesiones.
* Reintentar una creación no duplica sesiones.
* Precio cero es válido; precio negativo o formato no decimal es inválido.
* Una sesión puede existir sin fecha programada.
* Cancelar una sesión no cancela todo el tratamiento.
* Una cancelación no cambia `planned_sessions` ni reutiliza numeración.
* Varias cancelaciones pueden producir más registros que sesiones requeridas.
* Completar una sesión incrementa el progreso una sola vez.
* Modificar el catálogo no cambia precios, estados ni notas de sesiones.
* IDs válidos de otra mascota no conceden acceso.

## 21. Ejemplo completo

### Catálogo

```text
Service
  name: Fisioterapia
  description: Rehabilitación física, movilidad y recuperación funcional.
  is_active: true
```

Procedimientos de Fisioterapia:

1. Lámpara infrarroja — 15 minutos orientativos.
2. Magnetoterapia — 30 minutos orientativos.
3. Electroterapia — 20 minutos orientativos.
4. Masoterapia — 25 minutos orientativos.
5. Ejercicios terapéuticos — 30 minutos orientativos.

```text
Treatment
  service: Fisioterapia
  name: Fisioterapia inicial para dolor lumbar leve
  description: Abordaje inicial orientativo definido por la veterinaria.
  estimated_sessions: 6
  is_active: true
  procedures:
    - Lámpara infrarroja
    - Electroterapia
    - Masoterapia
```

### Asignación a una mascota

```text
Pet: Mora
Treatment: Fisioterapia inicial para dolor lumbar leve
planned_sessions: 6
default_session_price: ARS 18.000,00
starts_on: 2026-09-01
status: pending
notes: Seguimiento inicial acordado con la familia.
```

| N.º | Precio inicial | Estado inicial | Evolución independiente posible |
| --- | ---: | --- | --- |
| 1 | ARS 18.000,00 | `pending` | Completarse a ARS 18.000,00 |
| 2 | ARS 18.000,00 | `pending` | Reprogramarse sin cambiar precio |
| 3 | ARS 18.000,00 | `pending` | Cambiar a ARS 20.000,00 |
| 4 | ARS 18.000,00 | `pending` | Conservar su propio estado |
| 5 | ARS 18.000,00 | `pending` | Conservar su propio precio |
| 6 | ARS 18.000,00 | `pending` | Cancelarse individualmente |

Si la sesión 3 se cancela, se conserva como `cancelled` y se crea la sesión 7
como `pending`, con el precio predeterminado vigente. El tratamiento continúa
requiriendo seis sesiones completadas. Los números 1 a 6 no se renumeran ni se
reutiliza el 3.

Con las sesiones 1 y 2 completadas, el progreso es `2 / 6`. Las sesiones
canceladas no aparecen en el numerador ni denominador. Cambiar luego el precio
predeterminado no modifica las sesiones existentes, aunque se utiliza para
nuevos reemplazos. Cambiar el catálogo tampoco altera la composición acordada.

## 22. Criterios de aceptación

* [ ] Treatment pertenece a Service, requiere procedimientos compatibles y sesiones estimadas positivas.
* [ ] El catálogo se administra sin eliminación física.
* [ ] PetTreatment vincula Pet con Treatment y conserva las condiciones acordadas.
* [ ] Una asignación genera sus TreatmentSession atómicamente.
* [ ] Cada sesión conserva precio, moneda y estado propios.
* [ ] Cambios de catálogo o precio predeterminado no reescriben sesiones.
* [ ] El progreso se calcula desde sesiones completadas.
* [ ] Una sesión cancelada se conserva, no modifica `planned_sessions`, no
  cuenta para el progreso y genera el reemplazo necesario con numeración nueva.
* [ ] Admin administra tratamientos, asignaciones y sesiones.
* [ ] Client no modifica ningún recurso de F08.
* [ ] Client consulta en modo lectura únicamente asignaciones y sesiones de sus
  propias mascotas, con ownership completo.
* [ ] Backend aplica autenticación, autorización, validación e integridad.
* [ ] No existen precio de servicio/procedimiento, protocolos, pagos ni facturación.
* [ ] Pruebas y controles de calidad pasan antes de declarar la implementación completa.

## 23. Fuera de alcance

No se incluyen:

* precio, moneda o duración en Service;
* precio en Procedure o Treatment;
* protocolos clínicos versionados o plantillas por sesión;
* procedimientos planificados o realizados por sesión;
* frecuencia, objetivos o reglas clínicas automatizadas;
* evolución clínica por procedimiento;
* diagnósticos, recetas, estudios o indicaciones clínicas detalladas;
* pagos, facturación, caja, descuentos, promociones o paquetes prepagos;
* inventario de insumos;
* turnos, agenda o disponibilidad horaria;
* profesionales asociados o roles nuevos;
* notificaciones, IA, RAG o embeddings;
* eliminación, Soft Deletes o archivo adicional;
* API o catálogo público.

## 24. Decisiones pendientes

### DECISIÓN PENDIENTE — Aumento de un tratamiento completado

Las decisiones recibidas contienen dos reglas incompatibles: indican que
`planned_sessions` solo puede modificarse mientras el tratamiento no esté
`completed`, pero también que aumentar las sesiones de un tratamiento
completado debe devolverlo a `in_progress`.

Debe elegirse si `completed` es inmutable en F08 o si admin puede aumentarlo y
reabrirlo automáticamente. La implementación de ese caso debe esperar esta
definición.

## 25. Decisiones tomadas

* Service es el área terapéutica general y solo contiene nombre, descripción y estado funcional.
* Procedure pertenece a Service, puede tener duración orientativa y no tiene precio.
* Treatment pertenece a Service, requiere procedimientos compatibles y sesiones estimadas positivas.
* Treatment no define procedimientos distintos por sesión.
* PetTreatment vincula Pet con Treatment y contiene condiciones acordadas.
* TreatmentSession pertenece a PetTreatment y tiene número, precio, moneda y estado propios.
* El precio se cobra y conserva por sesión.
* La moneda inicial se mantiene en `ARS`, compatible con la decisión previa.
* Los recursos se desactivan y conservan; no se eliminan.
* El progreso se deriva de sesiones completadas sobre previstas.
* Los roles siguen limitados a `admin` y `client`, con ownership backend.
* No se introducen protocolos, facturación, pagos ni planificación por sesión.
* Client consulta asignaciones y sesiones propias en modo lectura y no puede
  solicitar tratamientos.
* PetTreatment conserva snapshots de tratamiento y procedimientos mediante una
  relación histórica propia.
* La cantidad prevista puede aumentar o reducirse transaccionalmente en estados
  `pending` e `in_progress`, respetando sesiones históricas.
* Los estados combinan cambios administrativos y automatismos por sesiones
  completadas según la sección 13.
* Las sesiones canceladas se conservan, no cuentan para el progreso y generan
  reemplazos pendientes sin cambiar `planned_sessions`.
* `session_number` identifica el orden de registros generados, no el ordinal de
  una sesión clínica completada; por eso puede superar `planned_sessions`.

## 26. Impacto documental y de implementación

Esta especificación separa de F07 el dominio de tratamientos y sesiones. La implementación actual de estas entidades es infraestructura backend parcial de F08, no una funcionalidad terminada.

* la implementación existente refleja el contrato anterior: Service contiene precio, moneda, duración y modalidades, y aún no existen Procedure, Treatment, PetTreatment ni TreatmentSession.

`spec.md`, `technical.md` y `features.md` fueron armonizados con este contrato.
Antes de implementar el nuevo modelo debe resolverse la decisión pendiente y
planificarse migraciones nuevas sin modificar migraciones
históricas ejecutadas.

Historia Clínica permanece desacoplada. Una sesión completada informa progreso operativo, pero no crea ni modifica automáticamente `ClinicalRecord`. Una integración futura deberá respetar autorización, auditoría y visibilidad de Feature 06.
