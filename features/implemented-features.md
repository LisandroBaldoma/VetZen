# VetZen — Estado de implementación

Este documento es una referencia de continuidad. Describe el código disponible
sin reemplazar `spec.md`, `technical.md` ni las especificaciones de cada
feature.

## Feature 03 — Autenticación y usuarios

Está disponible la base de autenticación del starter de Laravel: registro,
inicio y cierre de sesión, recuperación de contraseña, verificación de email,
perfil y opciones de seguridad (incluyendo las capacidades de dos factores y
passkeys ya configuradas en el proyecto).

Las rutas protegidas requieren autenticación y verificación de email según la
convención actual.

## Feature 04 — Clientes, roles y autorización

* Cada usuario registrado recibe un `Client` asociado y el rol `client` en
  backend.
* Se usan los roles `client` y `admin` de Spatie Laravel Permission. Existe una
  factory para crear administradores durante desarrollo y pruebas.
* El cliente puede consultar y actualizar solamente su propio perfil, tanto
  desde Settings como desde su dashboard.
* El administrador dispone de la gestión global de clientes en `/admin/clients`.
* `ClientPolicy`, Form Requests y las relaciones de dominio protegen el acceso
  horizontal; ocultar enlaces no es el control de seguridad.
* No están implementados aún turnos, tratamientos, historia clínica ni una
  matriz de roles profesionales.

## Feature 05 — Mascotas

* `Client` tiene muchas `Pet`; cada mascota pertenece obligatoriamente a un
  cliente.
* Se implementó create, read y update de mascotas; no hay eliminación,
  archivado ni Soft Deletes de la mascota.
* Los campos son `name`, `species`, `breed`, `sex`, `birth_date`, `weight`,
  `color`, `notes` y `photo`. Son obligatorios `name`, `species` y `sex`.
* El cliente gestiona solo sus mascotas desde `/pets`; el administrador gestiona
  todas desde `/admin/pets`. Ambas áreas se protegen en backend.
* `PetPolicy` valida ownership. Las solicitudes manipuladas no pueden cambiar
  el `client_id` de una mascota.
* La foto es opcional y se guarda como una ruta en el disco Laravel configurado
  por entorno. Su consulta, reemplazo y eliminación están autorizados por la
  misma Policy; al reemplazarla se elimina el archivo anterior.
* Historia clínica, tratamientos, turnos, profesionales y transferencia de
  titularidad permanecen fuera de alcance.

## Calidad verificada al finalizar Feature 05

Pasaron ESLint, Prettier, TypeScript, Pint, PHPStan, el build de Vite y la suite
Laravel completa (54 pruebas).

## Próximo criterio de trabajo

Antes de implementar una nueva feature, leer este documento junto con
`spec.md`, `technical.md` y la especificación de la feature. Cualquier cambio
de alcance o decisión de negocio debe registrarse primero en su especificación.
