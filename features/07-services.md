# Feature 07 — Servicios

## 1. Objetivo

Implementar el catálogo de terapias complementarias que ofrece VetZen. Un
servicio define la identidad y la información comercial general de una terapia;
no representa su aplicación clínica a una mascota concreta.

La primera versión permite al administrador listar, consultar, crear, editar,
activar y desactivar servicios. Un cliente autenticado puede listar y consultar
únicamente servicios activos. La autorización debe aplicarse siempre en
backend.

## 2. Problema

VetZen ya gestiona clientes, mascotas e historia clínica, pero no dispone de
una fuente estructurada para describir qué terapias ofrece la veterinaria. El
catálogo proporciona esa identidad estable y la información comercial que el
cliente necesita, además de preparar una referencia para futuros Tratamientos.

Esta feature no resuelve la aplicación individual de una terapia, la
planificación clínica, los turnos ni la agenda.

## 3. Contexto real de VetZen

El catálogo inicial conocido contempla cuatro servicios:

### Acupuntura Veterinaria

Servicio de acupuntura veterinaria. El material actual describe sesiones de
aproximadamente 15–20 minutos. La duración configurada en el catálogo es
orientativa y modificable. Las frecuencias dependen del paciente y no
pertenecen a `Service`.

### Fitoterapia

Servicio de fitoterapia veterinaria. Puede contemplar las modalidades `clinic`
y `online`. El material actual describe consultas de aproximadamente 45
minutos. Estudios, receta, preparado y seguimiento individual pertenecen al
futuro Treatment y/o dominio clínico.

### Flores de Bach / Terapia Floral

Servicio de terapia floral veterinaria. El material actual describe sesiones
de aproximadamente 45 minutos y modalidad `online`. Las esencias indicadas,
recetas y controles individuales no pertenecen a `Service`.

### Fisioterapia Veterinaria

Terapia orientada a la rehabilitación física, movilidad y recuperación
funcional del paciente, adaptada a sus necesidades individuales. Tiene como
referencia una duración habitual de 45 minutos, modalidad inicial `clinic`,
precio configurable sin valor hardcodeado y estado activo.

Esta referencia no incorpora plan de rehabilitación, ejercicios, cantidad o
frecuencia de sesiones, evolución ni resultados clínicos. Esos conceptos
pertenecen al futuro Treatment.

Los valores descriptos sirven para configurar el catálogo inicial. No son
invariantes clínicas ni precios permanentes.

## 4. Usuarios

### Administrador

El rol `admin` puede:

* listar todos los servicios, activos e inactivos;
* consultar cualquier servicio;
* crear servicios;
* editar servicios;
* activar servicios;
* desactivar servicios.

No puede eliminarlos porque delete no forma parte de Feature 07.

### Cliente

El rol `client` puede listar y consultar servicios activos. No puede crear,
editar, activar, desactivar, cambiar precios o modalidades, consultar servicios
inactivos ni acceder a endpoints administrativos, incluso mediante requests
HTTP manuales.

Feature 07 no crea nuevos roles, permisos profesionales ni una matriz granular
de capacidades.

## 5. Dependencias

* Feature 03: autenticación, usuario autenticado y verificación de email.
* Feature 04: roles `admin` y `client`, Spatie Laravel Permission, Policies y
  separación entre las áreas administrativa y de cliente.
* Feature 05: convenciones de CRUD, Form Requests, protección de atributos y
  pantallas React/Inertia.
* Feature 06: separación entre información comercial e información clínica.
* Stack implementado: Laravel 13, React 19, Inertia 3, Wayfinder, Tailwind CSS,
  Spatie Laravel Permission y PHPUnit.

No existe actualmente implementación de Servicios. La implementación debe
seguir las convenciones existentes sin introducir repositorios, services,
DTOs, APIs paralelas u otras abstracciones sin una necesidad concreta.

## 6. Modelo de dominio

`Service` es un recurso global del catálogo. No pertenece a un cliente ni a una
mascota.

```text
Service
├── id
├── name
├── description
├── duration_minutes
├── price
├── currency
├── modalities
├── is_active
├── created_at
└── updated_at
```

| Atributo | Contrato de primera versión |
| --- | --- |
| `id` | Identificador y route model binding |
| `name` | Nombre comercial obligatorio y único |
| `description` | Texto comercial/general obligatorio |
| `duration_minutes` | Entero positivo nullable; duración habitual orientativa |
| `price` | Decimal nullable y no negativo; precio base orientativo |
| `currency` | Moneda explícita; único valor inicial: `ARS` |
| `modalities` | Lista multivalor controlada; admite cero, una o varias |
| `is_active` | Booleano con default `true`; disponibilidad comercial |
| `created_at`, `updated_at` | Timestamps convencionales de Laravel |

La representación prevista para `modalities` es JSON con cast a array, si al
implementar se confirma su compatibilidad con la base de datos actual. No se
crea una tabla `service_modalities`. `price` debe persistirse con un decimal
apropiado, nunca float. `is_active` debe tener default de base de datos `true`
y cast booleano.

No se agregan otros campos salvo un requisito técnico imprescindible de Laravel
previamente justificado. En particular, `Service` no contiene `pet_id`,
`client_id`, `user_id`, profesionales, disponibilidad horaria, frecuencia,
cantidad de sesiones, receta, diagnóstico, indicaciones clínicas, evolución,
seguimiento ni campos de IA.

## 7. Distinción Service vs Treatment

```text
Service                              futuro Treatment
Qué terapia ofrece VetZen            Aplicación a una mascota concreta
Información comercial general        Información individual y clínica
No pertenece a una Pet               Pertenece a una Pet
No registra sesiones realizadas      Podrá organizar sesiones y seguimiento
No contiene receta individual        Podrá vincular indicaciones o recetas
```

La relación futura será:

```text
Pet
 ↓
Treatment
 ↓
Service
```

Feature 07 proporciona únicamente la identidad estable de `Service`. No crea
`Treatment`, `TreatmentSession`, relaciones con estructuras inexistentes ni
una relación directa entre `Service` y `ClinicalRecord`.

## 8. Alcance

Feature 07 incluye:

* Modelo y catálogo de `Service` con los atributos aprobados.
* Listado y detalle administrativo de servicios activos e inactivos.
* Creación, edición, activación y desactivación administrativas.
* Listado y detalle de servicios activos para clientes autenticados.
* Precio base, moneda ARS explícita, duración orientativa y modalidades simples.
* Autenticación, autorización con Spatie y `ServicePolicy`, validación y
  protección contra mass assignment en backend.
* Frontend React/Inertia coherente con las áreas actuales de cliente y admin.
* Factory, pruebas Feature/HTTP y evaluación de un Seeder inicial conforme a
  las convenciones del proyecto.

Las operaciones son create, read, update y cambio de estado. No existe delete.

## 9. Fuera de alcance

No se incluyen:

* `Treatment`, `TreatmentSession` o asignación a mascotas.
* Sesiones, planes, ejercicios, frecuencia, evolución, resultados o seguimiento.
* Recetas, preparados, estudios, diagnósticos o indicaciones clínicas.
* Cambios o relaciones adicionales con Historia Clínica.
* Profesionales asociados, asignación o permisos profesionales.
* Disponibilidad horaria, agenda, horarios, turnos o reservas.
* Precios por modalidad, primera consulta, control, domicilio o adicionales.
* Descuentos, promociones, historial de precios o motor de tarifas.
* Pagos o facturación.
* Delete, Soft Deletes, archivo o eliminación condicional.
* Slug, URLs públicas o SEO.
* Notificaciones, IA, RAG, embeddings o recomendaciones automáticas.
* Asignación automática de servicios a mascotas.

## 10. Reglas de negocio

1. Un servicio describe una terapia ofrecida por VetZen y no una atención de
   un paciente.
2. `name` y `description` son obligatorios.
3. El nombre es único: no pueden coexistir duplicados funcionales.
4. `duration_minutes` es nullable y, cuando existe, representa una duración
   aproximada configurable, no una regla clínica.
5. `price` es nullable, configurable, no negativo y representa un precio base
   orientativo en la moneda explícita `ARS`.
6. Un servicio admite cero, una o varias modalidades de `clinic`, `online` y
   `home_visit`.
7. Las modalidades no tienen precio, horario, profesional, disponibilidad ni
   duración propios en esta versión.
8. `is_active = true` significa que VetZen ofrece el servicio;
   `is_active = false` lo conserva pero lo retira del catálogo del cliente.
9. `is_active` no representa agenda, horarios, turnos libres ni profesionales.
10. Admin administra todos los servicios; client solo lee los activos.
11. Las rutas del cliente y sus props Inertia deben excluir inactivos. Conocer
    su ID o URL no permite consultarlos.
12. La baja se realiza exclusivamente con `is_active = false`; no se elimina ni
    archiva el recurso.
13. Los listados se ordenan alfabéticamente por `name` con desempate estable por
    `id`. No existe `sort_order`.
14. Precios y duraciones del material no se hardcodean como reglas permanentes.
15. Frecuencia, receta, evolución y seguimiento no se guardan en `Service`.
16. Toda autorización se aplica en Laravel; la UI no es seguridad.

## 11. Autorización

Spatie Laravel Permission resuelve las capacidades generales de los roles
existentes y `ServicePolicy` protege cada acción o recurso. No se crea una
matriz granular de permisos para Servicios.

| Acción | `client` | `admin` |
| --- | --- | --- |
| Listar | Solo activos | Todos |
| Consultar activo | Permitido | Permitido |
| Consultar inactivo | Denegado | Permitido |
| Crear | Denegado | Permitido |
| Editar | Denegado | Permitido |
| Cambiar precio/modalidades | Denegado | Permitido |
| Activar/desactivar | Denegado | Permitido |
| Eliminar | No incluido | No incluido |

Las rutas permanecen bajo autenticación y verificación de email. El área
administrativa exige rol o capacidad administrativa y la Policy. Las rutas del
cliente autorizan lectura y aplican explícitamente el estado activo.

El catálogo no tiene ownership horizontal porque es global. Su frontera es la
separación entre lectura de activos y administración. Un client que intenta
usar una ruta administrativa o consultar un inactivo debe recibir una
denegación del backend.

## 12. Información comercial

Un client autenticado puede recibir de un servicio activo:

* `name`;
* `description`;
* `duration_minutes`;
* `price`;
* `currency`;
* `modalities`.

La UI presenta duración y precio como información aproximada/orientativa.
Cuando `duration_minutes` o `price` sean null debe mostrar una ausencia
coherente, sin inventar valores.

No se exponen al cliente servicios inactivos, configuración administrativa,
timestamps salvo necesidad real de UI, información clínica, datos de clientes
o mascotas. El catálogo no se abre a visitantes no autenticados.

`description` contiene texto plano comercial/general. No admite HTML arbitrario
ni se utiliza para diagnósticos, recetas, evolución, resultados, frecuencia o
tratamientos individuales.

## 13. Modalidades

`modalities` es una lista controlada multivalor. Admite una lista vacía o una
combinación sin repetidos de:

* `clinic`: atención en consultorio;
* `online`: atención online;
* `home_visit`: atención a domicilio.

El backend valida la lista y cada elemento y rechaza valores arbitrarios. La
representación simple prevista es JSON con cast de Eloquent a array.

No se crea una entidad separada. Si una futura feature requiere atributos
propios por modalidad, podrá normalizar el modelo entonces.

## 14. Precio

`price` es un precio comercial base y orientativo:

* nullable;
* configurable por admin;
* no negativo cuando existe;
* persistido como decimal apropiado, nunca float.

`currency` mantiene la moneda explícita y utiliza únicamente `ARS`. Los
importes del material existente no se usan como defaults ni se hardcodean.

No se implementan variantes, adicionales, descuentos, promociones, historial
ni motor de tarifas.

## 15. Duración

`duration_minutes` es nullable, entero, positivo cuando existe y configurable
por admin. Representa la duración habitual u orientativa de una sesión.

La UI debe presentarla como aproximada. No se almacenan rangos; para Acupuntura
se configurará un valor orientativo al cargar el catálogo, sin convertir el
rango 15–20 minutos en una regla rígida.

Cantidad de sesiones, frecuencia y controles individuales pertenecen a
Treatment.

## 16. Estado y baja

`is_active` es booleano y tiene default `true`.

```text
is_active = true  → servicio ofrecido comercialmente
is_active = false → servicio conservado pero no disponible para clientes
```

Admin consulta ambos estados y puede cambiarlos. Client solo lista y consulta
activos; el detalle de un inactivo se deniega aunque conozca el identificador.

No existe endpoint DELETE, Soft Deletes, archivo ni eliminación condicional. La
identidad se preserva para futuras relaciones históricas.

## 17. Flujos principales

### Cliente: listar y consultar activos

1. Accede a Servicios desde su área autenticada.
2. Laravel verifica autenticación, verificación y capacidad de lectura.
3. El backend consulta solo activos, ordenados por `name` e `id`.
4. La respuesta expone exclusivamente los campos comerciales aprobados.
5. Al abrir un detalle, la Policy y el estado vuelven a comprobarse.

El flujo termina en la consulta; no permite contratar, reservar, solicitar un
turno ni iniciar un tratamiento.

### Administrador: listar y consultar

1. Accede a Administración → Servicios.
2. Laravel verifica autenticación, rol/capacidad y Policy.
3. Recibe activos e inactivos ordenados establemente por nombre.
4. Puede abrir el detalle de cualquiera.

### Administrador: crear

1. Abre el formulario de alta y Laravel autoriza la acción.
2. Envía únicamente los campos aprobados.
3. El Form Request valida los datos.
4. El backend persiste el servicio con `is_active = true` por defecto, salvo
   elección administrativa explícita válida.
5. Redirige según la convención del área administrativa.

### Administrador: editar y cambiar estado

1. Abre un servicio y Laravel autoriza la actualización.
2. Envía los atributos editables aprobados.
3. El Form Request valida y el backend actualiza solo esos campos.
4. Cambiar `is_active` altera inmediatamente la visibilidad para clientes.

## 18. Frontend

### Cliente

Debe existir `Servicios → Listado → Detalle` para clientes autenticados. El
listado muestra solo activos, ordenados alfabéticamente. El detalle presenta
nombre, descripción, duración orientativa, precio base con moneda y modalidades,
omitiendo coherentemente los valores null.

Contempla estado vacío, carga, errores y diseño responsive. No muestra acciones
administrativas ni contratación, reservas, pagos, turnos o tratamientos.

### Administrador

Debe existir `Administración → Servicios → Listado / Crear / Editar / Detalle`.
El listado diferencia activos e inactivos y permite cambiar el estado. No
existe acción de eliminación.

La implementación reutiliza `AppLayout`, componentes UI, formularios, errores,
flash/toasts, React, Inertia y Wayfinder. No introduce API REST paralela, router
frontend, estado global ni librería UI nueva.

La UI refleja permisos; backend, Form Requests, middleware y Policies tienen la
autoridad definitiva.

## 19. Validación

La implementación usa Form Requests. Validación y autorización son fronteras
separadas.

* `name`: required, string, máximo 255 caracteres y único en `services`; al
  actualizar ignora únicamente el registro actual.
* `description`: required, string y texto plano, con longitud razonable
  compatible con una columna `text` y el patrón del proyecto.
* `duration_minutes`: nullable, integer y mayor que cero.
* `price`: nullable, decimal válido y mayor o igual a cero; nunca float.
* `currency`: required y exclusivamente `ARS`.
* `modalities`: required como array, puede estar vacío y no admite duplicados.
* `modalities.*`: cada elemento pertenece a `clinic`, `online` o `home_visit`.
* `is_active`: boolean; puede omitirse al crear para aplicar default `true`.

La unicidad de `name` debe respaldarse con validación y restricción única de
base de datos. La implementación usa el comportamiento de comparación de la
base aprobada y no crea otra normalización sin necesidad.

El modelo declara explícitamente atributos asignables. Solo se persisten datos
validados y seleccionados. Claves como `pet_id`, `client_id`, `user_id`,
`created_by`, `updated_by`, `role`, `permissions`, profesionales u horarios se
ignoran o rechazan sin alterar el recurso.

React renderiza `name` y `description` como texto, sin interpretar HTML.

## 20. Testing

La implementación prioriza pruebas Feature/HTTP con PHPUnit, factories y datos
propios de cada caso. Las escrituras comprueban respuesta y estado persistido.

### Administrador

* Lista y consulta activos e inactivos.
* Crea un servicio válido y se aplican `ARS` e `is_active = true`.
* Edita todos los atributos aprobados.
* Activa y desactiva servicios.
* No existe ruta ni operación de eliminación.
* El listado se ordena por `name` y por `id` como desempate.

### Cliente

* Lista servicios activos y consulta su detalle.
* No recibe inactivos en listado, detalle ni props serializadas.
* No consulta un inactivo conociendo su ID o URL.
* No crea, edita, activa, desactiva ni cambia precio o modalidades mediante
  requests directas.
* No accede a endpoints administrativos.

### Autenticación y autorización

* Un usuario no autenticado sigue el flujo actual hacia login.
* La matriz de `ServicePolicy` cubre `viewAny`, `view`, `create` y `update` para
  admin y client; el cambio de estado usa `update` o la acción dedicada que
  adopte la implementación.
* Pruebas HTTP demuestran autorización en cada endpoint.

### Validación y seguridad

* Payload vacío falla para campos obligatorios.
* `name` cubre required, longitud y unicidad, incluida actualización propia.
* `description` es obligatoria y se renderiza como texto seguro.
* `duration_minutes` acepta null y positivos; rechaza cero, negativos y no
  enteros.
* `price` acepta null, cero y decimales no negativos; rechaza negativos y
  formatos inválidos sin pérdida por float.
* `currency` solo acepta `ARS`.
* `modalities` acepta cero, una o varias opciones y rechaza duplicados, valores
  arbitrarios o una estructura no array.
* `is_active` acepta booleanos y aplica default `true` al omitirse al crear.
* Payloads con ownership, roles, permisos o campos no aprobados no alteran el
  recurso.

### Catálogo inicial y regresión

* Si se incorpora un Seeder, se verifica que produzca el catálogo inicial sin
  duplicados al ejecutarse nuevamente.
* Se mantienen pruebas de regresión relacionadas con autenticación y roles.

No se prueban Treatment, sesiones, agenda, pagos ni historia clínica en F07.

## 21. Criterios de aceptación

* [ ] Existe `Service` únicamente con el modelo aprobado.
* [ ] Nombre y descripción son obligatorios; nombre es único.
* [ ] Duración nullable, precio base nullable en ARS, modalidades multivalor e
  `is_active` funcionan según el contrato.
* [ ] Admin lista, consulta, crea, edita, activa y desactiva.
* [ ] Client autenticado lista y consulta solo activos y recibe exclusivamente
  los campos comerciales aprobados.
* [ ] Client no muta el catálogo ni accede a endpoints administrativos.
* [ ] Spatie y `ServicePolicy` autorizan en backend.
* [ ] No existe delete, Soft Deletes, archivo, slug ni `sort_order`.
* [ ] Los listados usan orden alfabético estable por `name` e `id`.
* [ ] No se hardcodean precios del material comercial.
* [ ] Si se usa Seeder para el catálogo inicial, sigue las convenciones del
  proyecto y evita duplicados.
* [ ] No se guardan datos clínicos ni relaciones anticipadas en `Service`.
* [ ] Las pantallas React/Inertia siguen las convenciones existentes.
* [ ] Las pruebas y controles de calidad aplicables pasan.
* [ ] No se implementa funcionalidad fuera de Feature 07.

## 22. Dependencias futuras

Feature 08 podrá introducir `Treatment` para relacionar una mascota con la
identidad estable de un servicio y definir inicio, estado, indicaciones,
sesiones, evolución y seguimiento. Esa feature definirá sus propias reglas
clínicas, autorización y visibilidad.

Turnos y agenda podrán consumir `Service` cuando sus requisitos se definan.
Profesionales, disponibilidad horaria y atributos propios por modalidad
requieren dominios concretos; Feature 07 no los anticipa.

Una futura base de conocimiento podrá usar contenido comercial autorizado de
Servicios, pero Feature 07 no implementa IA, RAG ni embeddings.

## 23. Decisiones resueltas

* **Estructura mínima:** `id`, `name`, `description`, `duration_minutes`,
  `price`, `currency`, `modalities`, `is_active` y timestamps.
* **Nombre y descripción:** obligatorios; nombre único con máximo 255 y
  descripción de texto plano comercial/general.
* **Precio:** base nullable, decimal no negativo y configurable; sin variantes
  ni motor de tarifas. Moneda explícita `ARS`.
* **Modalidades:** lista controlada multivalor con `clinic`, `online` y
  `home_visit`; admite cero, una o varias y se prevé JSON + cast.
* **Duración:** `duration_minutes` nullable, entero positivo y orientativo; sin
  rangos ni frecuencia individual.
* **Estado:** `is_active` booleano con default `true`; admin administra ambos
  estados y client accede solo a activos.
* **Baja:** no hay delete, Soft Deletes, archivo ni eliminación condicional. La
  baja comercial es `is_active = false`.
* **Slug:** no se implementa; se utiliza ID y route model binding.
* **Visibilidad:** client autenticado ve de activos `name`, `description`,
  `duration_minutes`, `price`, `currency` y `modalities`. No hay catálogo
  público.
* **Orden:** alfabético por `name`, con `id` como desempate; sin `sort_order`.
* **Disponibilidad y profesionales:** fuera de alcance. `is_active` expresa
  solo disponibilidad comercial.
* **Roles:** únicamente `admin` y `client`, con Spatie y `ServicePolicy`; no se
  crea una matriz granular nueva.
* **Catálogo inicial:** Acupuntura Veterinaria, Fitoterapia, Flores de Bach /
  Terapia Floral y Fisioterapia Veterinaria. Se evaluará un Seeder conforme al
  proyecto, nunca datos hardcodeados en una migration.

No quedan decisiones de producto pendientes dentro del alcance aprobado de
Feature 07. Variantes de precio, normalización de modalidades, catálogo público,
profesionales, agenda o eliminación requerirán una decisión futura y una
actualización previa de la documentación.
