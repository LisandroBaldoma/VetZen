# Feature 05 — Pets / Mascotas

## 1. Objetivo

Permitir registrar y administrar mascotas como pacientes de la veterinaria. Toda mascota deberá asociarse a un cliente y su acceso se protegerá en backend según el usuario autenticado.

La feature continúa sobre autenticación, `User → Client`, roles `client` y `admin`, Spatie Laravel Permission y la autorización de Feature 04.

## 2. Problema que resuelve

Los clientes necesitan mantener los datos generales de sus mascotas sin poder acceder, crear para otros ni modificar pacientes ajenos mediante URLs, IDs, parámetros o requests manipulados. Los administradores necesitan gestionar las mascotas de cualquier cliente.

## 3. Usuarios involucrados

### Cliente

Puede listar, consultar, registrar y editar exclusivamente las mascotas de su propio `Client`.

### Administrador

Puede listar todas las mascotas, consultar cualquiera, crear una mascota para un cliente y editar cualquier mascota. Esta versión no define roles profesionales.

## 4. Dependencias

* Feature 01: autenticación y usuario autenticado.
* Feature 04: `User → Client`, roles `client` y `admin`, Spatie, Policies y áreas diferenciadas.
* Stack Laravel, React e Inertia existente.

Historia clínica, tratamientos, turnos y seguimiento no son dependencias de implementación de esta feature, aunque dependerán después de la mascota.

## 5. Modelo de dominio

```text
User
  └── hasOne Client
        └── hasMany Pets

Pet
  └── belongsTo Client
```

* Toda `Pet` pertenece a un único `Client` existente.
* Un `Client` puede tener cero o más mascotas.
* La mascota no duplica datos del responsable; estos siguen perteneciendo a `User`/`Client`.
* El ownership se resuelve por `Pet → Client → User`, no por una relación directa con el usuario autenticado.

Actualmente existen `Pet::client()`, `Client::pets()` y `pets.client_id`. La tabla solo contiene esa relación y timestamps.

## 6. Alcance

La primera implementación deberá incluir:

* Crear mascotas.
* Listar mascotas.
* Consultar detalle e información general.
* Editar información general.
* Asociar cada mascota a un cliente.
* Aplicar autorización backend por rol y ownership.
* Mostrar estados vacíos, errores y confirmaciones con React/Inertia.

Las operaciones de esta etapa son solamente **create, read y update**.

## 7. Fuera de alcance

No se implementa historia clínica, tratamientos, seguimiento, turnos, agenda, notificaciones, asistente IA, RAG, profesionales, matriz avanzada de roles, tablas futuras vacías, archivos, fotos, eliminación, archivado ni Soft Deletes.

## 8. Reglas de negocio

1. Toda mascota debe asociarse a un cliente existente.
2. Un `client` solo puede listar, consultar, crear y editar las mascotas de su propio `Client`.
3. Un `client` no puede crear para otro cliente, reasignar una mascota ni modificar `client_id`.
4. Un `admin` puede administrar mascotas de cualquier cliente.
5. La interfaz no sustituye las rutas, Policies y relaciones de dominio de Laravel como protección de recursos.
6. Esta feature no crea ni expone información clínica.
7. Los datos generales deberán validarse en backend una vez definidos sus campos.

## 9. Autorización

Esta versión utiliza los roles actuales de Spatie como capacidades generales, sin crear permisos individuales `pets.*`. La autorización sobre recursos concretos corresponde a una `PetPolicy` y a las relaciones de dominio.

| Acción | `client` propietario | Otro `client` | `admin` |
| --- | --- | --- | --- |
| `viewAny` | Solo su colección | Denegado | Todas |
| `view` | Permitido | Denegado | Permitido |
| `create` | Solo para sí mismo | Denegado | Para cualquier cliente |
| `update` | Permitido | Denegado | Permitido |

La `PetPolicy` deberá definir `viewAny`, `view`, `create` y `update`; no se define `delete`.

Para alta de cliente, Laravel debe resolver el dueño desde el usuario autenticado: `request user → client → new pet`.

Nunca se elegirá ownership con `client_id` enviado por el navegador. Para alta administrativa, el cliente objetivo se resolverá y autorizará en backend.

## 10. Flujos principales

### Cliente: listado y detalle

1. Abre “Mis mascotas”.
2. El backend obtiene su `Client` desde el usuario autenticado.
3. Devuelve únicamente las mascotas de ese cliente.
4. Al abrir una mascota, la Policy confirma ownership antes de responder.

### Cliente: alta y edición

1. Completa los campos generales aprobados.
2. Laravel valida los datos.
3. En alta, Laravel asocia la mascota al `Client` autenticado.
4. En edición, la Policy autoriza y solo se actualizan atributos permitidos.
5. La asociación con el cliente no cambia.

### Administrador

1. Accede al área administrativa habilitada.
2. Laravel verifica rol y Policy antes de listar, consultar, crear o editar.
3. Al crear, selecciona un cliente existente en el flujo que se defina.

## 11. Casos alternativos y seguridad

* Sin autenticación, las rutas aplican el login existente.
* Una mascota inexistente responde `404` según la convención actual.
* Cliente A que solicita Pet B recibe `403`, sin información de Pet B.
* Requests con `client_id`, `user_id`, `role` o `permissions` no pueden cambiar ownership ni autorización; se ignorarán o rechazarán mediante el patrón de Form Requests que se defina al implementar.
* Las pruebas usarán rutas HTTP reales; ocultar enlaces no es suficiente.

## 12. Campos, validaciones y mass assignment

La información general inicial de una mascota estará compuesta por:

```text
name
species
breed
sex
birth_date
weight
color
notes
photo
```

Los campos obligatorios son `name`, `species` y `sex`. Los demás campos son
opcionales.

Todo dato ingresado se validará en backend mediante Form Requests o el patrón
vigente. Las reglas deben cubrir requeridos, formatos, tipos y límites
razonables para cada campo.

`photo` almacenará únicamente una referencia o ruta del archivo, nunca el
contenido binario ni Base64. La carga utilizará el sistema de almacenamiento de
Laravel y el disco configurado por el entorno, sin acoplar el modelo `Pet` a un
proveedor particular. El archivo debe validarse como imagen permitida y con un
límite razonable de tamaño.

La foto aplica las mismas reglas de ownership que la mascota: un cliente solo
puede cargar, reemplazar, consultar o eliminar la foto de una mascota propia;
un administrador puede hacerlo para cualquier mascota autorizada. Reemplazarla
debe eliminar la referencia anterior del almacenamiento para no dejar archivos
innecesarios. Eliminar una foto no elimina ni archiva la mascota.

El modelo declarará explícitamente atributos asignables. Un formulario de cliente nunca podrá modificar `client_id`, `user_id`, `roles` ni `permissions`.

## 13. Frontend

### Cliente

“Mis mascotas” deberá ofrecer listado propio, estado vacío, alta, detalle y edición. Debe reutilizar componentes, errores, carga y confirmaciones actuales de React/Inertia.

### Administrador

Deberá ofrecer listado, detalle, alta asociada a un cliente y edición. No se agregará router frontend, API REST paralela, Redux ni librería UI adicional.

## 14. Testing

Las pruebas HTTP deberán comprobar:

* Cliente A puede listar, consultar, editar Pet A y crear mascotas para sí.
* La mascota creada queda asociada a Client A.
* Con Client A → Pet A y Client B → Pet B, Cliente A no puede consultar o editar Pet B, reasignar Pet A ni crear una mascota para Client B.
* Admin puede listar, consultar, crear para un cliente y editar cualquier mascota.
* Client no puede acceder a rutas administrativas de mascotas.
* Se validan `name`, `species` y `sex` como obligatorios, y las reglas de los
  demás campos como opcionales.
* Se prueba carga, reemplazo, consulta y eliminación de foto con las mismas
  reglas de autorización de la mascota.
* Se mantienen pruebas de regresión de Feature 04.

## 15. Criterios de aceptación

* [ ] `Client` conserva `hasMany Pets` y `Pet` conserva `belongsTo Client`.
* [ ] Toda mascota creada se vincula a un cliente existente.
* [ ] Un cliente solo administra sus propias mascotas.
* [ ] Un admin puede administrar mascotas de cualquier cliente.
* [ ] Ownership y rutas se protegen en backend.
* [ ] Requests manipuladas no cambian `client_id`, `user_id`, roles ni permisos.
* [ ] `name`, `species` y `sex` son obligatorios; los otros campos son
  opcionales y se validan en Laravel.
* [ ] La foto se almacena como ruta mediante Laravel Storage y está protegida
  por ownership; su reemplazo no deja archivos anteriores innecesarios.
* [ ] Cliente y admin cuentan con las pantallas especificadas.
* [ ] No se implementa funcionalidad fuera de alcance.

## 16. Dependencias futuras

La mascota será el paciente de futuras relaciones con historia clínica, tratamientos, turnos y planes de seguimiento. Esta feature no crea esas tablas ni relaciones anticipadamente.

## 17. Decisiones pendientes

### DECISIÓN PENDIENTE — Eliminación / archivado

Debe definirse si una mascota podrá eliminarse, archivarse/desactivarse o usar Soft Deletes. Hasta entonces, la feature se limita a create, read y update.

### DECISIÓN TÉCNICA PENDIENTE — Área administrativa

La documentación no define si la gestión administrativa será:

1. Global en `/admin/pets`, consistente con el listado global actual de `/admin/clients`.
2. Contextual desde `/admin/clients/{client}`.

Ambas alternativas son válidas. Se deberá elegir al implementar la UI; ninguna modifica el modelo de ownership ni los controles backend.
