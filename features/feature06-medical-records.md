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
esta feature los incorporará después de resolver las decisiones indicadas en el
apartado 19.

## 5. Modelo de dominio

La cadena de ownership se mantiene sin duplicar datos del cliente:

```text
User
  └── hasOne Client
        └── hasMany Pets
              └── hasMany Clinical Records
```

Un registro clínico debe pertenecer a una única `Pet`. La historia clínica es
el conjunto cronológico de registros de esa mascota, no una fila única que se
sobrescribe. Los datos de mascota, responsable y contacto se resuelven mediante
relaciones; no deben copiarse como `pet_name`, `owner_name` o `client_email`.

Todo registro creado o modificado debe poder asociarse conceptualmente al
usuario responsable. Los nombres definitivos de columnas y relaciones quedan
sujetos a la decisión de autoría y auditoría.

## 6. Alcance

La primera implementación deberá incluir:

* Consultar el historial clínico cronológico de una mascota autorizada.
* Consultar un registro clínico individual autorizado.
* Crear registros clínicos para una mascota por parte de un `admin`.
* Editar registros clínicos existentes por parte de un `admin`.
* Registrar consultas, evaluaciones, evolución e información relevante de
  sesiones mediante la estructura de tipos que se apruebe.
* Asociar los registros a su mascota y conservar trazabilidad conceptual de
  creación y modificación.
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
* Auditoría mediante una librería o proveedor específico hasta definir su
  estrategia global.
* Asistente IA, RAG, embeddings, almacenamiento vectorial o acceso automático
  a información clínica.
* Diagnóstico automático, prescripciones o campos clínicos no aprobados.

## 8. Reglas de negocio

1. Todo registro clínico pertenece a una mascota existente.
2. La historia clínica de una mascota es el historial de sus múltiples
   registros, ordenado según la regla cronológica que se defina.
3. Un `client` solo puede leer la historia y los registros de mascotas cuyo
   `Pet → Client → User` corresponde al usuario autenticado.
4. Un `client` nunca puede crear, editar, eliminar ni reasignar registros
   clínicos, aunque envíe solicitudes HTTP manuales.
5. Un `admin` puede crear, consultar y editar registros de cualquier mascota.
6. El acceso debe decidirse en backend mediante autenticación, Policy y las
   relaciones de dominio; los IDs del navegador no determinan autorización.
7. Todo registro debe conservar la posibilidad de identificar autor, momento y
   recurso afectado para una futura auditoría clínica.
8. No se agrega `delete` en esta versión debido a la sensibilidad y necesidad
   de trazabilidad de la información clínica.

## 9. Autorización

Spatie se utiliza para las capacidades generales y las Policies para comprobar
el recurso concreto. La primera versión tiene el siguiente contrato cerrado:

| Acción | `client` propietario | Otro `client` | `admin` |
| --- | --- | --- | --- |
| Ver historial de una Pet | Permitido | Denegado | Permitido |
| Ver Clinical Record | Permitido | Denegado | Permitido |
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
3. Consulta el historial o registra una nueva entrada con el tipo y campos
   aprobados.
4. Laravel valida los datos, asocia la entrada a la mascota y registra al
   administrador autenticado como autor según la estrategia definida.

### Administrador: edición

1. Abre un registro clínico perteneciente a una mascota.
2. La Policy autoriza la edición.
3. Laravel actualiza únicamente los atributos permitidos, sin cambiar la
   mascota ni los autores por datos enviados desde el navegador.
4. La modificación queda preparada para la trazabilidad clínica.

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

Las reglas concretas no se pueden fijar hasta definir la estructura y los
campos clínicos. El modelo deberá declarar explícitamente sus atributos
asignables. La mascota, el cliente propietario y los autores se resolverán en
backend desde el contexto autorizado, no mediante mass assignment.

## 14. Frontend

### Cliente

Desde el detalle de una mascota propia deberá acceder a una sección de Historia
clínica. Verá listado cronológico, fecha, tipo y contenido autorizado de cada
registro, además del detalle cuando corresponda. No tendrá controles de alta,
edición ni eliminación.

### Administrador

La gestión clínica parte de la mascota, preferentemente bajo una ruta
equivalente a `/admin/pets/{pet}/medical-records`. Debe permitir listar,
consultar, crear y editar registros sin crear un CRUD clínico desconectado del
paciente.

La implementación reutilizará React, Inertia, los componentes de formularios,
errores y confirmaciones existentes. No agregará API REST paralela, router
frontend, Redux ni una librería UI nueva.

## 15. Auditoría

La auditoría completa no se implementa ni se vincula a una librería en esta
especificación. Como requisito mínimo, el diseño debe permitir identificar:

* Usuario responsable.
* Acción de creación o modificación.
* Momento de la acción.
* Registro y mascota afectados.

La solución concreta (Activitylog, OwenIt, tabla propia, eventos u otra) debe
elegirse de forma global y quedar registrada antes de implementar la auditoría.

## 16. Testing

Las pruebas HTTP de la futura implementación deberán cubrir, como mínimo:

* Cliente A puede consultar la historia y un registro de Pet A.
* Cliente A no puede crear ni editar registros de Pet A.
* Con Client A → Pet A → Record A y Client B → Pet B → Record B, Client A no
  puede consultar ni editar Record B ni el historial de Pet B.
* Admin puede consultar el historial de cualquier mascota, crear un registro y
  editar un registro existente.
* Requests manipuladas no cambian `pet_id`, `client_id`, autores ni permisos.
* Se validan los tipos, campos y fechas que se aprueben para el modelo clínico.
* Se mantiene la regresión de autenticación, clientes y mascotas.

## 17. Criterios de aceptación

La implementación estará completa cuando:

* [ ] La historia clínica sea un historial de registros asociado a `Pet`.
* [ ] Cliente pueda consultar solo los registros de sus propias mascotas.
* [ ] Cliente no pueda crear ni editar registros, tampoco mediante requests
  directas.
* [ ] Admin pueda crear, consultar y editar registros de cualquier mascota.
* [ ] La autorización opere en backend y cubra URLs, bindings y payloads
  manipulados.
* [ ] Los registros guarden o permitan guardar la autoría y la trazabilidad
  mínima requerida.
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

## 19. Decisiones pendientes

### DECISIÓN PENDIENTE — Estructura del modelo clínico

Debe confirmarse la entidad o entidades persistentes que representarán los
eventos del historial. El requisito cerrado es un historial con entradas
independientes; no está aprobada todavía la tabla exacta ni sus columnas.

### DECISIÓN PENDIENTE — Tipos de registro clínico

La documentación menciona consultas, evaluaciones, evolución y sesiones, pero
no determina su representación. Alternativas a evaluar:

1. **Modelo genérico:** `ClinicalRecord` con un campo `type`.
2. **Modelos separados:** `Consultation`, `Evaluation`, `Evolution` y `Session`.
3. **Modelo base y especializaciones:** entrada común más datos específicos por
   tipo.

Recomendación técnica inicial: comenzar con un modelo genérico tipado solo si
los campos comunes son suficientes y los tipos están cerrados. Si cada tipo
requiere datos obligatorios muy distintos, evaluar especializaciones. Esta no
es una decisión tomada.

### DECISIÓN PENDIENTE — Campos clínicos

Antes de crear migraciones deben definirse campos, obligatoriedad, formatos y
límites. No son requisitos confirmados diagnóstico, síntomas, medicación,
temperatura, frecuencia cardíaca, prescripción ni diagnóstico diferencial.

Como referencia no definitiva, cualquier registro probablemente necesitará un
tipo, contenido, fecha clínica y autor; esto debe validarse con el producto
antes de codificarlo.

### DECISIÓN PENDIENTE — Autoría de registros

Debe definirse la representación exacta de creación y modificación, por
ejemplo `created_by` y `updated_by` o una estrategia equivalente acorde a las
convenciones Laravel del proyecto. El requisito ya definido es poder
identificar al administrador autenticado que realizó cada acción.

### DECISIÓN PENDIENTE — Estrategia de auditoría clínica

Debe definirse la estrategia global de auditoría sin seleccionar de forma
anticipada Spatie Activitylog, OwenIt, tabla personalizada o event sourcing.
La elección deberá cubrir usuario, acción, momento y recurso afectado.

### DECISIÓN PENDIENTE — Fecha clínica y orden cronológico

Debe decidirse si el historial se ordena por fecha del evento clínico, fecha de
creación o ambas, y cómo se comportan los registros cargados de forma tardía.

### DECISIÓN PENDIENTE — Exposición de contenido al cliente

El producto indica que el cliente consulta la historia clínica disponible de
sus mascotas. Debe confirmarse si todos los tipos y campos aprobados son
visibles por defecto o si existirá una marca explícita de visibilidad. Hasta
esa decisión, la implementación no debe ocultar ni inferir selectivamente
contenido clínico por iniciativa propia.
