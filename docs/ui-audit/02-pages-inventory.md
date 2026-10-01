# Inventario de pantallas

Cada página protegida recibe además `auth.user`, roles y estado del sidebar desde `HandleInertiaRequests`. Las tablas indican la página React y su contrato principal; los layouts son `AppLayout` salvo que se indique otro.

## Pública y autenticación

| Pantalla / archivo | Ruta y responsable | Usuario / objetivo / datos y anatomía |
|---|---|---|
| Bienvenida `welcome.tsx` | `GET /`, Inertia route | Pública. Landing starter-kit: enlaces Login/Register/Dashboard y contenido Laravel; no pertenece al flujo clínico. |
| Iniciar sesión `auth/login.tsx` | Fortify `GET/POST /login` | Pública. `status`, `canResetPassword`; email, contraseña, recordar, passkey, recuperar/registrar. `AuthLayout > logo > heading > form > enlaces`. |
| Registro `auth/register.tsx` | Fortify `GET/POST /register` | Pública. `passwordRules`; nombre, email, teléfono, contraseña/confirmación. Crea User + Client. |
| Recuperar/restablecer `auth/forgot-password.tsx`, `reset-password.tsx` | Fortify | Pública. Estado o token/email/reglas; solicitud de enlace y nueva contraseña. |
| Confirmación/2FA/verificación `auth/confirm-password.tsx`, `two-factor-challenge.tsx`, `verify-email.tsx` | Fortify | Autenticado o transición auth. Confirmar password/passkey, OTP/código recuperación, reenvío de correo. La verificación está inactiva por decisión. |

## Dashboards

| Pantalla / archivo | Ruta / controlador | Datos, objetivo y bloques |
|---|---|---|
| Inicio admin `admin/dashboard.tsx` | `GET /dashboard`, `DashboardController` | Admin. `pendingRequestsCount`, solicitudes prioritarias con mascota/servicio y enlaces. `PageHeader + CTA Nuevo paciente + contador + lista de solicitudes + enlaces rápidos + vacío`. |
| Inicio cliente `client/dashboard.tsx` | `GET /dashboard`, `DashboardController` | Client. `pets`, `pendingRequests`, `activeTreatments`: nombre/foto/especie, servicio/estado, progreso. `PageHeader + CTA + Pet cards + request cards + treatment cards/progress + vacíos`. |
| Placeholder `dashboard.tsx` | Sin respuesta Inertia actual | Archivo starter-kit no seleccionado por el controlador de dashboard; excluir como pantalla de producto. |

## Cliente: mascotas y contexto clínico

| Pantalla / archivo | Ruta / responsable | Datos, objetivo y anatomía |
|---|---|---|
| Mis mascotas `pets/index.tsx` | `GET /pets`, `PetController@index` | `pets[]`: foto, nombre, especie, raza, sexo. Descubrir/abrir/registrar. `PageHeader + grid PetCard local + empty CTA`. |
| Crear/editar mascota `pets/create.tsx`, `pets/edit.tsx` | `GET/POST /pets`; `GET/PATCH /pets/{pet}` | Datos de mascota opcional. Alta/edición propia y foto. `Heading + Form + PetFormFields + submit`; edición agrega eliminación de foto. |
| Detalle mascota `pets/show.tsx` | `GET /pets/{pet}` | `pet: PetContext`: identidad, foto, datos clínicos básicos. Hub. `PetContextHeader + PetSummary + enlaces Historia/Solicitudes/Tratamientos/Editar`. |
| Historia lista/detalle `pets/medical-records/index.tsx`, `show.tsx` | GET anidado, `ClinicalRecordController` | `pet`, `records[]` o `record`: tipo, título, contenido, fecha, auditoría. Lectura. `PetContextHeader + timeline ClinicalRecordSummary/detail + empty`. |
| Solicitudes lista/crear/detalle `pets/service-requests/*` | GET/POST anidado, `ServiceRequestController` | `pet`, requests; creación recibe `services[]`, `selectedServiceId`; detalle: servicio, notas, estado, resolución/tratamiento. `PetContextHeader + cards/form/status + error summary`. |
| Tratamientos lista/detalle `pets/treatments/*` | GET anidado, `PetTreatmentController` | `pet`, resúmenes o tratamiento completo: snapshot de plantilla/procedimientos, sesiones, precio, fechas, notas/progreso. Solo lectura. `PetContextHeader + cards/timeline/progress + empty`. |

## Cliente: servicios y cuenta

| Pantalla / archivo | Ruta / responsable | Datos, objetivo y anatomía |
|---|---|---|
| Servicios índice/detalle `services/index.tsx`, `show.tsx` | `GET /services[/{service}]`, `ServiceController` | Servicios/procedimientos activos y mascotas propias. Explorar catálogo e iniciar solicitud. `PageHeader + service cards/detail + procedures + selector de mascota + CTA`; CTA deshabilitado sin mascota. |
| Perfil de cuenta `settings/profile.tsx` | `GET/PATCH /settings/profile`, `ProfileController` | User autenticado, `mustVerifyEmail`, `status`; nombre/email y borrado de cuenta. `SettingsLayout + profile form + delete dialog`. |
| Perfil cliente `settings/client-profile.tsx` | `GET/PATCH /clients/{client}`, `ClientProfileController` | `client`: teléfono, dirección, ciudad/provincia/CP, documento, nacimiento. `SettingsLayout + ClientProfileFields`. |
| Seguridad/apariencia `settings/security.tsx`, `appearance.tsx` | seguridad controller / Inertia | Password rules, 2FA, passkeys; o preferencia claro/oscuro/sistema. `SettingsLayout + secciones o tabs`. |

## Administración: clientes, pacientes e historia

| Pantalla / archivo | Ruta / responsable | Datos, objetivo y anatomía |
|---|---|---|
| Clientes lista/editar `admin/clients/index.tsx`, `edit.tsx` | `Admin\ClientController` | Clientes, user y contacto. Admin consulta/edita, no crea. `PageHeader + desktop table/mobile cards + empty`; edición `Heading + ClientProfileFields`. |
| Pacientes lista/crear/editar/detalle `admin/pets/*` | `Admin\PetController` | Lista: mascota/responsable; forms reciben `clients[]`; detalle `PetContext`. `PageHeader + responsive table/cards + row actions`, formulario compartido, o hub clínico. |
| Historia clínica lista/crear/editar/detalle `admin/pets/medical-records/*` | `ClinicalRecordManagementController` | `pet`, registros/registro, `types`, prefill de evolución. Admin registra/consulta/edita. `PetContextHeader + timeline + CTA + ClinicalRecordFormFields/detail`. |
| Tratamientos paciente lista/asignar/detalle `admin/pets/treatments/*` | `Admin\PetTreatmentController` | `pet`, templates, asignaciones/sesiones. Asigna condiciones iniciales, actualiza estado/sesiones. `PetContextHeader + cards + assign form + detail/timeline + session forms`. |

## Administración: catálogo y solicitudes

| Pantalla / archivo | Ruta / responsable | Datos, objetivo y anatomía |
|---|---|---|
| Servicios lista/crear/editar/detalle `admin/services/*` | `Admin\ServiceController` | Paginador de servicio, filtros `search/status`; detalle con procedimientos/plantillas. `PageHeader/Heading + FilterBar local + responsive table/cards + CatalogStatusForm`. |
| Procedimientos global `admin/procedures/index.tsx` | `ProcedureCatalogController` | Paginador; filtros búsqueda/servicio/estado. `PageHeader + filters + responsive table/cards + estado/editar`. |
| Procedimientos contextual `admin/services/procedures/*` | `ProcedureController` | Servicio y sus procedimientos/procedimiento. CRUD dentro de servicio. `Heading + table/form/detail + status`. |
| Plantillas global `admin/treatments/index.tsx` | `TreatmentCatalogController` | Paginador; búsqueda/servicio/estado. `PageHeader + filters + responsive table/cards`. |
| Plantillas contextual `admin/services/treatments/*` | `TreatmentController` | Servicio, plantillas y procedimientos activos. CRUD; selección múltiple de procedimientos y sesiones estimadas. `Heading + table/form + empty bloqueado`. |
| Solicitudes admin lista/detalle `admin/service-requests/*` | `Admin\ServiceRequestController` | Paginador, filtros de estado, solicitud con mascota/cliente/servicio/notas; detalle recibe tratamientos compatibles. `PageHeader + filters + responsive table/cards`; detalle `context + resolution/cancellation form`. |

## Convención de datos y estados

Colecciones de catálogo/listas admin usan `data`, links y metadatos de paginación. Filtros se conservan por query string. No hay ordenamiento expuesto. Los estados son strings validados, no enums PHP: catálogo `is_active`; solicitud `pending/resolved/cancelled`; tratamiento `pending/in_progress/completed/suspended/cancelled`; sesión `pending/completed/cancelled`; registro clínico `consultation/evaluation/evolution/session/other`.
