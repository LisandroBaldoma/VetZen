# VetZen — Feature 05: Mascotas

## 1. Propósito

Permitir registrar y administrar las mascotas, que constituyen los pacientes de
la veterinaria, manteniendo una asociación verificable con su cliente
responsable y aplicando la autorización del sistema en cada acceso.

Esta feature establece el registro y la información general del paciente. La
historia clínica, tratamientos, turnos y seguimiento quedan fuera de su
implementación funcional, aunque sus futuras relaciones deberán apoyarse en la
identidad de la mascota creada aquí.

## 2. Problema que resuelve

Un cliente necesita identificar y mantener actualizados los datos generales de
sus mascotas para poder gestionar posteriormente servicios, tratamientos,
turnos e información clínica. Los profesionales autorizados necesitan consultar
la información general del paciente dentro de las capacidades que les otorguen
sus roles y permisos.

La solución debe evitar que un cliente pueda consultar, crear, modificar o
asociar mascotas pertenecientes a otro cliente mediante URLs, IDs o requests
manipulados.

## 3. Usuarios involucrados

### Cliente

Puede administrar únicamente las mascotas asociadas a su propio registro de
cliente y consultar la información que el producto habilite para ellas.

### Administrador / profesional autorizado

Puede administrar mascotas únicamente cuando su rol y permisos se lo permitan.
La definición de roles profesionales y su matriz completa no forma parte de
esta feature; se integrará con la infraestructura de autorización existente.

## 4. Alcance

La implementación deberá incluir:

* Alta de una mascota asociada a un cliente existente.
* Listado de las mascotas del cliente autenticado.
* Consulta del detalle e información general de una mascota.
* Edición de la información general permitida.
* Asociación obligatoria de cada mascota con exactamente un cliente responsable.
* Acceso administrativo/profesional conforme a los roles y permisos disponibles.
* Estados vacíos, errores de validación y confirmación visual de las operaciones.
* Relación de dominio preparada para que futuras features vinculen historia
  clínica, tratamientos y turnos a una mascota.

No incluye:

* Historia clínica, consultas, evaluaciones, diagnósticos o evolución.
* Tratamientos, planes de seguimiento o sesiones.
* Turnos, agenda o solicitudes de turno.
* Carga de archivos, fotos o documentación de la mascota.
* Transferencia de titularidad entre clientes.
* Eliminación de mascotas.
* Nuevos roles profesionales ni administración de permisos.

## 5. Modelo y relaciones

La relación de dominio base es:

```text
User 1 ── 1 Client 1 ── N Pet
```

* Una `Pet` pertenece a un único `Client`.
* Un `Client` puede tener cero o más mascotas.
* La mascota no se asocia directamente a un `User`; la pertenencia se resuelve
  a través de `Pet → Client → User`.
* La asociación `client_id` se decide en el backend. Un cliente autenticado no
  puede elegir arbitrariamente el `client_id` recibido en la request.
* La clave foránea debe preservar la integridad referencial y seguir la
  convención de eliminación ya existente para `Client`.

Actualmente existe el modelo `Pet`, su migración mínima y la relación
`Client::pets()`. La migración actual contiene solo `client_id` y timestamps;
por lo tanto, los atributos de información general requerirán una migración
evolutiva una vez resuelta la decisión del apartado 13.

## 6. Reglas de negocio

1. Toda mascota debe pertenecer a un cliente existente.
2. Un cliente puede crear, listar, consultar y actualizar solamente sus propias
   mascotas.
3. Un cliente no puede reasignar una mascota a otro cliente ni modificar su
   `client_id`, directa o indirectamente.
4. Un cliente no puede usar un identificador, parámetro o payload para acceder
   a la mascota de otro cliente.
5. La información clínica no se crea ni se administra en esta feature. Si una
   futura pantalla la presenta, deberá respetar las autorizaciones de Historia
   clínica y no podrá inferirse de este CRUD.
6. El acceso de administradores y profesionales se determina por permisos de
   capacidad; el acceso de clientes se determina además por la propiedad del
   recurso.
7. La UI mejora la experiencia al ocultar acciones no permitidas, pero Laravel
   debe aplicar siempre la autorización real.
8. Los datos generales de la mascota deben validarse en backend. La validación
   frontend es complementaria y no sustituye la del servidor.

## 7. Autorización

La feature depende de la base de roles y permisos de la Feature 04. Se separan
dos responsabilidades:

| Necesidad | Mecanismo |
| --- | --- |
| Determinar si un rol puede usar una capacidad administrativa | Spatie Laravel Permission |
| Determinar sobre cuál mascota concreta puede actuar un cliente | Policy + relación `Pet → Client → User` |

La `PetPolicy` deberá expresar, como mínimo, el siguiente comportamiento:

| Acción | Cliente propietario | Otro cliente | Admin/profesional autorizado |
| --- | --- | --- | --- |
| `viewAny` | Ve solo su colección propia | No ve colecciones ajenas | Según permiso |
| `view` | Permitido | Denegado | Según permiso |
| `create` | Permitido para su propio `Client` | No puede crear para otro cliente | Según permiso |
| `update` | Permitido | Denegado | Según permiso |

No se define `delete` porque la eliminación no está dentro del alcance.

Como nomenclatura inicial, y solo si no existe una convención distinta, los
permisos administrativos podrán ser:

```text
pets.viewAny
pets.view
pets.create
pets.update
```

Asignar un permiso a un cliente no debe bastar para operar sobre mascotas
ajenas: la Policy debe validar siempre la propiedad del recurso.

## 8. Flujos principales

### 8.1 Alta por cliente

1. El cliente autenticado entra a “Mis mascotas”.
2. Selecciona la acción para registrar una mascota.
3. Completa los datos generales definidos para la feature.
4. El backend valida la request y obtiene el `Client` desde el usuario
   autenticado.
5. El backend crea la mascota asociándola a ese `Client`, sin usar un
   `client_id` controlado por el navegador.
6. El usuario vuelve al listado o detalle con confirmación de éxito.

### 8.2 Consulta y edición propia

1. El cliente abre una mascota desde su listado.
2. La ruta resuelve el modelo y la Policy verifica que pertenece a su cliente.
3. Puede consultar y editar los campos generales permitidos.
4. El backend valida y actualiza únicamente atributos autorizados.
5. El vínculo con el cliente permanece inalterado.

### 8.3 Gestión autorizada de un paciente

1. Un administrador o profesional con el permiso pertinente accede al área
   correspondiente.
2. El backend aplica middleware de autenticación y autorización de capacidad.
3. La Policy autoriza la operación sobre la mascota solicitada.
4. La interfaz muestra únicamente información y acciones dentro de esta
   feature.

## 9. Casos alternativos y errores

* Si el usuario no está autenticado, las rutas protegidas deben redirigir al
  inicio de sesión según la convención actual.
* Si un cliente solicita una mascota ajena, Laravel debe responder `403`; no se
  debe devolver información del paciente.
* Si una mascota no existe, debe aplicarse la respuesta estándar `404` sin
  revelar datos de otros recursos.
* Si faltan datos obligatorios o un valor no cumple sus reglas, la request debe
  volver con errores de validación de Inertia.
* Si se envían `client_id`, `user_id`, `role` o `permissions` manipulados, no
  pueden modificar propiedad ni autorización. Los campos no permitidos deben
  ignorarse o rechazarse siguiendo la convención de Form Requests del proyecto.

## 10. Interfaz y navegación

### Cliente

La navegación debe ofrecer “Mis mascotas” una vez que la funcionalidad esté
implementada. Debe contener:

* Listado de sus mascotas.
* Estado vacío con acción para registrar la primera.
* Acceso al detalle y edición de cada mascota propia.
* Estados de carga, validación y éxito consistentes con los componentes React e
  Inertia ya existentes.

### Administración / profesionales

El área administrativa mostrará opciones de mascotas solamente a usuarios con
las capacidades pertinentes. No debe exponer como accesibles los módulos de
historia clínica, tratamientos o turnos hasta que existan.

Los formularios, tipos y componentes React deben reutilizarse entre alta y
edición cuando corresponda. No se debe introducir un router frontend, API REST,
Redux ni una librería de UI adicional.

## 11. Validaciones y seguridad de datos

* Usar Form Requests o el patrón de validación ya consolidado en el proyecto.
* Declarar explícitamente los atributos editables de `Pet`; `client_id` no debe
  ser editable por mass assignment desde un formulario de cliente.
* Mantener las restricciones de clave foránea e índices necesarios.
* Obtener el cliente propietario a partir de `request()->user()->client` (o la
  relación equivalente), no desde IDs aportados por el frontend.
* Autorizar rutas con Policies y middleware de backend antes de ejecutar la
  operación.
* Evitar serializar información clínica o de futuras relaciones por defecto en
  las respuestas de esta feature.

## 12. Dependencias

* Feature 01: autenticación y usuario autenticado.
* Feature 04: entidad `Client`, relación `User → Client`, Settings y base de
  autorización mediante Spatie.
* Infraestructura Laravel + React + Inertia existente.

Las features de Historia clínica, Tratamientos y Turnos dependen de la mascota,
pero no son dependencias de implementación de esta iteración.

## 13. Decisión requerida: datos generales de la mascota

**Contexto:** La documentación del producto exige “información general”, pero
no define sus atributos, obligatoriedad, catálogos ni reglas de validación. El
esquema actual de `pets` tampoco los contiene.

**Opciones:**

1. Definir ahora un conjunto cerrado de campos y sus validaciones para el MVP.
2. Limitar esta iteración a la asociación `Client → Pet` y diferir el formulario
   de información general hasta contar con esa definición.

**Impacto:** La opción 1 permite entregar el CRUD completo, pero requiere una
decisión funcional explícita para no inventar datos de pacientes. La opción 2
mantiene la integridad del alcance, pero solo entrega la estructura mínima ya
existente.

**Recomendación:** Definir antes de implementar, como mínimo, los campos
generales, cuáles son obligatorios, sus valores admitidos y si alguno debe ser
único por cliente. Hasta entonces, no se deben asumir especie, raza, sexo,
fecha de nacimiento, peso, foto, identificación u otros datos.

## 14. Criterios de aceptación

La feature estará completa cuando:

* [ ] Una mascota puede registrarse con los datos generales previamente
  aprobados y queda asociada a un cliente.
* [ ] Un cliente puede ver el listado, detalle y formulario de edición de sus
  mascotas.
* [ ] Un cliente no puede ver ni actualizar una mascota de otro cliente a
  través de rutas HTTP reales.
* [ ] Un cliente no puede crear una mascota para otro cliente ni reasignar una
  existente mediante el request.
* [ ] Los roles autorizados pueden acceder a las capacidades administrativas
  definidas mediante permisos y Policies.
* [ ] Las rutas están protegidas en backend; ocultar enlaces no es el único
  control de acceso.
* [ ] Las validaciones de los campos definidos se aplican en Laravel y sus
  errores se muestran en Inertia.
* [ ] No se implementan historia clínica, turnos, tratamientos, archivos ni
  eliminación de mascotas.
* [ ] El código reutiliza convenciones y componentes existentes y supera los
  controles de calidad configurados en el repositorio.

## 15. Requisitos de testing

Como mínimo, las pruebas HTTP deben cubrir:

* Creación de una mascota para el cliente autenticado.
* Asociación correcta entre `Pet` y `Client`.
* Listado y consulta de las mascotas propias.
* Actualización de una mascota propia.
* Rechazo (`403`) cuando Cliente A intenta consultar o editar una mascota de
  Cliente B.
* Rechazo o ignorado seguro de `client_id`, `user_id`, `role` y `permissions`
  enviados de forma manipulada.
* Acceso permitido y denegado para los roles/permisos administrativos que se
  definan.
* Validaciones de cada campo obligatorio y sus límites, una vez aprobados en la
  decisión pendiente.
* Regresión de autenticación, perfil de cliente y la autorización de Feature
  04.

## 16. Consideraciones técnicas

* Mantener Laravel, React e Inertia como único flujo de aplicación.
* Centralizar validación y autorización en backend.
* Crear migraciones nuevas solo para ampliar la tabla `pets`; no duplicar la
  tabla ni su columna `client_id` ya existentes.
* Mantener los cambios acotados a esta feature y actualizar los tipos,
  Wayfinder/rutas y navegación generados según las convenciones del proyecto.
* Ejecutar únicamente los comandos configurados en `composer.json` y
  `package.json` al validar la implementación.

## 17. Fuera de alcance y pendientes reales

Quedan pendientes, por definición funcional, la matriz final de permisos para
profesionales y la definición de los atributos generales de la mascota. Ninguno
de los dos debe resolverse con supuestos durante la implementación.
