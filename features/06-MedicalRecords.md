# Feature 06 — Historia clínica

## 1. Objetivo

Centralizar la información clínica y la evolución de cada paciente veterinario.
La historia clínica pertenece a una mascota y se compone de registros clínicos
cronológicos. La información es sensible y su consulta o modificación debe
autorizarse siempre en backend.

Esta primera versión habilita lectura para el cliente propietario y creación,
lectura y edición para el administrador. No introduce todavía roles
profesionales ni permisos clínicos granulares.

## 2. Problema que resuelve

Hoy VetZen identifica clientes y mascotas, pero no conserva el historial de
consultas, evaluaciones ni evolución de un paciente. Esta feature incorpora la
base clínica sin romper el aislamiento ya implementado entre clientes ni asumir
tratamientos, turnos o seguimiento que aún no existen.

## 3. Usuarios involucrados

### Cliente

Puede consultar, en modo solo lectura, los registros clínicos disponibles de
sus propias mascotas. No puede crear, editar, eliminar ni reasignar registros
clínicos.

### Administrador

En esta versión es el único perfil de escritura clínica. Puede consultar la
historia clínica de cualquier mascota y crear o editar registros clínicos.

Los futuros profesionales autorizados no forman parte de esta iteración. La
separación entre capacidad general y acceso al recurso debe permitir agregarlos
sin reemplazar el ownership existente.

## 4. Dependencias

* Feature 03: autenticación y usuario autenticado.
* Feature 04: `User → Client`, roles `client` y `admin`, Spatie Laravel
  Permission y Policies.
* Feature 05: `Client → Pets`, `Pet → Client`, `PetPolicy`, áreas `/pets` y
  `/admin/pets`, y las reglas de ownership ya comprobadas por pruebas HTTP.
* Stack Laravel + React + Inertia y convenciones de Form Requests, Policies,
  Wayfinder y pruebas PHPUnit del proyecto.

No existen actualmente tablas, modelos, rutas ni pantallas de historia clínica;
esta feature los incorporará según las decisiones aprobadas en el apartado 19.

## 5. Modelo de dominio

La cadena de ownership se mantiene sin duplicar datos del cliente:

```text
User
  └── hasOne Client
        └── hasMany Pets
              └── hasMany Clinical Records
```

El modelo clínico de esta versión es `ClinicalRecord`, con las relaciones
`Pet hasMany ClinicalRecord` y `ClinicalRecord belongsTo Pet`. No se crean
tablas separadas por tipo clínico.

Un registro clínico debe pertenecer a una única `Pet`. La historia clínica es
el conjunto cronológico de registros de esa mascota, no una fila única que se
sobrescribe. Los datos de mascota, responsable y contacto se resuelven mediante
relaciones; no deben copiarse como `pet_name`, `owner_name` o `client_email`.

`ClinicalRecord` tiene los siguientes campos conceptuales:

* `id`
* `pet_id`
* `created_by`
* `updated_by`
* `type`
* `title`
* `content`
* `occurred_at`
* `is_visible_to_client`
* `created_at`
* `updated_at`

`created_by` y `updated_by` deben referenciar usuarios válidos y son
determinados por el backend desde el usuario autenticado. No se duplica
`client_id`, el `user_id` propietario, el nombre de la mascota ni ningún dato
derivable de relaciones existentes.

## 6. Alcance

La primera implementación deberá incluir:

* Consultar el historial clínico cronológico de una mascota autorizada.
* Consultar un registro clínico individual autorizado.
* Crear registros clínicos para una mascota por parte de un `admin`.
* Editar registros clínicos existentes por parte de un `admin`.
* Registrar consultas, evaluaciones, evolución e información relevante de
  sesiones mediante el modelo genérico y los tipos aprobados.
* Asociar los registros a su mascota y auditar su creación y modificación.
* Aplicar autorización, validación y protección contra mass assignment en
  backend.
* Ofrecer UI React/Inertia contextualizada por mascota, con lectura para
  clientes y controles de escritura exclusivamente administrativos.

Las operaciones de esta etapa son únicamente **create, read y update**.

## 7. Fuera de alcance

No se implementan:

* Tratamientos, planes de seguimiento ni su dominio de sesiones.
* Turnos, agenda, notificaciones o solicitudes.
* Roles profesionales, permisos clínicos granulares o panel de permisos.
* Eliminación física, Soft Deletes, anulación o corrección formal de registros.
* Paquetes externos de auditoría, incluidos Spatie Activitylog y OwenIt.
* Event sourcing o un sistema global de auditoría para todo VetZen.
* Asistente IA, RAG, embeddings, almacenamiento vectorial o acceso automático
  a información clínica.
* Diagnóstico automático, prescripciones o campos clínicos no aprobados.

## 8. Reglas de negocio

1. Todo registro clínico pertenece a una mascota existente.
2. La historia clínica de una mascota es el historial de sus múltiples
   registros, ordenado por `occurred_at DESC` y, como desempate,
   `created_at DESC`.
3. Un `client` solo puede leer registros con `is_visible_to_client = true` de
   mascotas cuyo `Pet → Client → User` corresponde al usuario autenticado.
4. Un `client` nunca puede crear, editar, eliminar ni reasignar registros
   clínicos, aunque envíe solicitudes HTTP manuales.
5. Un `admin` puede crear, consultar y editar registros de cualquier mascota.
6. El acceso debe decidirse en backend mediante autenticación, Policy y las
   relaciones de dominio; los IDs del navegador no determinan autorización.
7. Todo registro debe identificar al usuario que lo creó y al último usuario
   que lo actualizó, y su creación y edición deben quedar en la auditoría
   clínica específica de esta feature.
8. No se agrega `delete` en esta versión debido a la sensibilidad y necesidad
   de trazabilidad de la información clínica.
9. `occurred_at` representa cuándo ocurrió clínicamente el evento, admite
   cargas tardías y no puede ser una fecha futura.
10. El administrador debe elegir explícitamente `is_visible_to_client` al crear
    o editar; la visibilidad no se infiere automáticamente desde `type`.

## 9. Autorización

Spatie se utiliza para las capacidades generales y las Policies para comprobar
el recurso concreto. La primera versión tiene el siguiente contrato cerrado:

| Acción | `client` propietario | Otro `client` | `admin` |
| --- | --- | --- | --- |
| Ver historial de una Pet | Permitido solo para registros visibles | Denegado | Permitido |
| Ver Clinical Record | Permitido solo si es visible | Denegado | Permitido |
| Crear Clinical Record | Denegado | Denegado | Permitido |
| Editar Clinical Record | Denegado | Denegado | Permitido |
| Eliminar Clinical Record | No incluido | No incluido | No incluido |

La futura `ClinicalRecordPolicy` debe evaluar el ownership atravesando
`ClinicalRecord → Pet → Client → User`. Para crear, el backend debe resolver la
mascota desde la ruta autorizada, no adoptar un `pet_id` de un payload como
fuente de acceso. El autor también debe obtenerse del usuario autenticado.

No se crearán en esta etapa permisos separados para consultas, evaluaciones,
evoluciones o sesiones. Si se introducen permisos `clinical-records.*`, deberán
ser pocos, reproducibles y complementarios a la Policy, nunca un sustituto de
la verificación de ownership.

## 10. Protección de información clínica

Antes de responder o modificar información clínica, Laravel debe seguir esta
secuencia conceptual:

```text
usuario autenticado → autorización → Pet autorizada → datos clínicos
```

Para un cliente, la autorización se deriva de `User → Client → Pet`. Para un
administrador, se comprueba su capacidad administrativa y la Policy. Deben
rechazarse con `403` las consultas o mutaciones contra recursos ajenos, aun si
se manipulan URLs, route model binding, `pet_id`, `client_id`, `created_by`,
`updated_by`, `user_id`, roles o permisos.

La interfaz no debe mostrar controles de edición a clientes, pero esa ausencia
no es una barrera de seguridad. La información clínica no queda disponible de
forma automática para futuros asistentes: requerirá tanto autorización como
selección explícita de contenido por la veterinaria.

## 11. Flujos principales

### Cliente: lectura de historia propia

1. Abre una mascota desde “Mis mascotas”.
2. Solicita su historia clínica o un registro individual.
3. El backend resuelve la mascota y la Policy verifica ownership.
4. Recibe el historial cronológico y los datos clínicos autorizados en modo
   lectura.

### Administrador: consulta y alta

1. Abre una mascota desde `/admin/pets/{pet}` o su ruta clínica contextual.
2. El backend verifica que tiene rol `admin` y autoriza la mascota.
3. Consulta el historial o registra una nueva entrada con el tipo, campos y
   visibilidad aprobados.
4. Laravel valida los datos, asocia la entrada a la mascota, establece
   `created_by` y `updated_by` con el administrador autenticado y registra la
   creación en la auditoría clínica.

### Administrador: edición

1. Abre un registro clínico perteneciente a una mascota.
2. La Policy autoriza la edición.
3. Laravel actualiza únicamente los atributos permitidos, sin cambiar la
   mascota ni los autores por datos enviados desde el navegador.
4. Laravel establece `updated_by` con el administrador autenticado y registra
   la edición, con valores anteriores y nuevos, en la auditoría clínica.

## 12. Casos alternativos y seguridad

* Usuarios no autenticados siguen el flujo de login y verificación existente.
* Una mascota o registro inexistente responde `404` según la convención actual.
* Cliente A que intenta consultar Pet B o un registro de Pet B recibe `403`,
  sin exponer información clínica.
* Un cliente que envía `POST`, `PATCH` o métodos manipulados contra endpoints
  clínicos recibe `403`, incluso sobre una mascota propia.
* El backend debe ignorar o rechazar atributos no asignables como `pet_id`,
  `client_id`, `created_by`, `updated_by`, `user_id`, `role` y `permissions`.
* No debe serializarse por defecto información clínica de otras mascotas al
  renderizar pantallas de clientes o mascotas.

## 13. Validaciones y mass assignment

Se usarán Form Requests o la convención equivalente del proyecto. Antes de
guardar se debe validar la existencia y autorización de la mascota, el tipo de
registro, campos requeridos, formatos, fechas, longitudes y consistencia de los
datos clínicos aprobados.

`type`, `title`, `content` y `occurred_at` son requeridos.
`is_visible_to_client` debe ser booleano y debe ser elegido explícitamente por
el administrador. `title` debe ser un string de hasta 255 caracteres y
`content` debe ser texto. `occurred_at` no puede ser futuro.

`type` es controlado por la aplicación, sin ENUM de base de datos, y admite
inicialmente estos valores:

* `consultation`
* `evaluation`
* `evolution`
* `session`
* `other`

El modelo deberá declarar explícitamente sus atributos asignables. La mascota,
el cliente propietario y los autores se resolverán en backend desde el contexto
autorizado, no mediante mass assignment. No se admiten por ahora campos
especializados como diagnóstico, síntomas, medicación, signos vitales o
prescripción.

## 14. Frontend

### Cliente

Desde el detalle de una mascota propia deberá acceder a una sección de Historia
clínica. Verá únicamente los registros con `is_visible_to_client = true`, en
orden cronológico, con fecha, tipo, título y contenido, además del detalle
cuando corresponda. No tendrá controles de alta, edición ni eliminación.

### Administrador

La gestión clínica parte de la mascota, preferentemente bajo una ruta
equivalente a `/admin/pets/{pet}/medical-records`. Debe permitir listar,
consultar, crear y editar registros sin crear un CRUD clínico desconectado del
paciente.

La implementación reutilizará React, Inertia, los componentes de formularios,
errores y confirmaciones existentes. No agregará API REST paralela, router
frontend, Redux ni una librería UI nueva.

## 15. Auditoría

Esta feature implementará una estrategia propia y específica para Historia
Clínica mediante el modelo conceptual `ClinicalRecordAudit`, con estos campos:

* `id`
* `clinical_record_id`
* `user_id`
* `action`
* `old_values`
* `new_values`
* `created_at`

Se registrarán al menos la creación y la edición de registros clínicos. Cada
entrada debe permitir conocer quién realizó el cambio, cuándo lo realizó, qué
acción ejecutó y los valores anterior y nuevo cuando corresponda.

Esta decisión no introduce Spatie Activitylog, OwenIt, event sourcing ni otro
paquete externo, y tampoco define un sistema global de auditoría para VetZen.
Su alcance se limita a los recursos clínicos de esta feature.

## 16. Testing

Las pruebas HTTP de la futura implementación deberán cubrir, como mínimo:

* Cliente A puede consultar los registros visibles de Pet A y no puede
  consultar sus registros no visibles.
* Cliente A no puede crear ni editar registros de Pet A.
* Con Client A → Pet A → Record A y Client B → Pet B → Record B, Client A no
  puede consultar ni editar Record B ni el historial de Pet B.
* Admin puede consultar el historial de cualquier mascota, crear un registro y
  editar un registro existente.
* Requests manipuladas no cambian `pet_id`, `client_id`, autores ni permisos.
* Se validan los tipos aprobados, los campos requeridos, sus formatos y límites,
  el booleano de visibilidad y la prohibición de fechas clínicas futuras.
* La cronología usa `occurred_at DESC` y `created_at DESC`, incluidas las cargas
  tardías.
* Creaciones y ediciones generan la auditoría clínica con usuario, acción y
  valores correspondientes.
* Se mantiene la regresión de autenticación, clientes y mascotas.

## 17. Criterios de aceptación

La implementación estará completa cuando:

* [ ] La historia clínica sea un historial de registros asociado a `Pet`.
* [ ] Cliente pueda consultar solo los registros visibles de sus propias
  mascotas.
* [ ] Cliente no pueda crear ni editar registros, tampoco mediante requests
  directas.
* [ ] Admin pueda crear, consultar y editar registros de cualquier mascota.
* [ ] La autorización opere en backend y cubra URLs, bindings y payloads
  manipulados.
* [ ] Los registros guarden `created_by` y `updated_by` desde el usuario
  autenticado, y sus creaciones y ediciones queden auditadas.
* [ ] La UI clínica esté contextualizada por mascota y respete los roles.
* [ ] No exista eliminación de registros en esta versión.
* [ ] Validaciones, pruebas de autorización y controles de calidad del proyecto
  pasen satisfactoriamente.

## 18. Dependencias futuras

Tratamientos, sesiones, planes de seguimiento, turnos y profesionales podrán
relacionarse con la mascota y, si se define, con registros clínicos concretos.
No se crean ahora tablas, claves foráneas ni campos anticipados para esos
módulos.

El asistente virtual solo podrá usar información clínica seleccionada y
autorizada en una feature futura, aplicando como mínimo las mismas reglas de
ownership de esta especificación.

## 19. Decisiones resueltas

* **Modelo clínico:** modelo genérico `ClinicalRecord`, relacionado con `Pet`,
  sin tablas separadas por tipo en esta versión.
* **Tipos:** campo `type` controlado por aplicación, sin ENUM de base de datos,
  con `consultation`, `evaluation`, `evolution`, `session` y `other` como
  valores iniciales.
* **Campos clínicos:** quedan aprobados los campos conceptuales y reglas de
  validación definidos en los apartados 5 y 13, sin campos clínicos
  especializados adicionales.
* **Autoría:** `created_by` y `updated_by` referencian usuarios válidos y son
  determinados exclusivamente por el backend desde el usuario autenticado.
* **Auditoría clínica:** tabla/modelo conceptual propio
  `ClinicalRecordAudit`, limitado a recursos clínicos y con registro mínimo de
  creación y edición.
* **Cronología:** `occurred_at DESC`, con `created_at DESC` como desempate; se
  permiten cargas tardías y se prohíben fechas clínicas futuras.
* **Visibilidad:** `is_visible_to_client` debe ser elegido explícitamente por el
  administrador; el cliente solo accede a registros visibles de sus propias
  mascotas.
* **Eliminación:** no se implementan `delete`, Soft Deletes, invalidación,
  anulación ni correcciones versionadas adicionales en esta versión.

No quedan decisiones pendientes dentro del alcance de Feature 06 definido en
este documento. Las decisiones globales de otras features o de la arquitectura
general permanecen fuera de este alcance.
