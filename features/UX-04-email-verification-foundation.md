# UX-04 - Infraestructura de verificación de correo

> Estado: implementada pero fuera del flujo normal por decisión de producto. No
> condiciona el acceso ni envía correos automáticamente en la etapa actual.

## 1. Objetivo

Conservar la base técnica de verificación de correo para una activación futura,
sin utilizarla mientras VetZen trabaja con datos de prueba y no dispone de un
proveedor de correo saliente configurado.

## 2. Decisión vigente

La verificación de correo no es obligatoria en la versión actual. Una cuenta
autenticada con `email_verified_at = null` puede acceder al dashboard y a todas
las rutas permitidas por su rol, permisos, Policies y ownership.

La infraestructura no se elimina: permanecen la columna, la pantalla React, el
callback de Fortify y las pruebas del flujo para facilitar una reactivación
posterior.

## 3. Configuración inactiva

1. `User` no implementa `MustVerifyEmail`, por lo que el registro no dispara la
   notificación automática.
2. Las rutas del panel y Cuenta usan middleware `auth`, sin `verified`.
3. `Features::emailVerification()` permanece activa para conservar las rutas
   Wayfinder, la pantalla y el flujo latente, pero ninguna navegación normal
   dirige al usuario hacia ellos.
4. `email_verified_at` permanece en el esquema y en el modelo.
5. La vista `auth/verify-email`, el callback `Fortify::verifyEmailView` y las
   pruebas específicas no se eliminan.

## 4. Reglas

1. Autenticación, rol, permisos y Policies continúan siendo obligatorios.
2. El estado de verificación no concede ni restringe capacidades.
3. Admin y client sin correo verificado acceden a su dashboard correspondiente.
4. Cuenta y las rutas de dominio no redirigen a `/email/verify`.
5. Registrar una cuenta no envía `VerifyEmail`.
6. Las pruebas del flujo Fortify se conservan para comprobar que la
   infraestructura latente sigue disponible.
7. No se simulan verificaciones ni se marcan emails ficticios como verificados
   para evitar la barrera.

## 5. Reactivación futura

La verificación solo podrá reactivarse mediante una decisión de producto y
después de configurar y probar un proveedor de correo real. La reactivación
requiere conjuntamente:

1. configurar y probar el proveedor de correo;
2. implementar `MustVerifyEmail` en `User`;
3. restaurar middleware `verified` únicamente en las rutas aprobadas;
4. revisar la experiencia posterior a login y registro;
5. ejecutar las pruebas de aviso, reenvío, firma, bloqueo y acceso verificado;
6. probar entrega de correo en el entorno correspondiente.

No debe activarse solo uno de esos puntos porque produciría un flujo parcial o
engañoso.

## 6. Seguridad

Desactivar verificación de correo no debilita las fronteras de ownership. Todas
las rutas protegidas siguen exigiendo sesión autenticada y la autorización
backend correspondiente. El frontend no sustituye roles, Policies ni relaciones
de dominio.

## 7. Pruebas actuales

El proyecto usa PHPUnit. Debe verificarse que:

- guest continúa siendo redirigido a login;
- client no verificado accede al dashboard cliente;
- admin no verificado accede al dashboard admin;
- una cuenta no verificada accede a Cuenta;
- el registro redirige al dashboard sin enviar `VerifyEmail`;
- las pruebas específicas de Fortify continúan validando aviso, enlace firmado
  y reenvío con notificaciones simuladas.

## 8. Fuera de alcance

- Configurar SMTP u otro proveedor de correo.
- Probar entrega externa.
- Eliminar `email_verified_at`.
- Eliminar la pantalla o las pruebas de verificación.
- Cambiar recuperación de contraseña, 2FA o passkeys.
- Sustituir autorización backend por el estado del email.

## 9. Criterios de aceptación

- [x] Un correo no verificado no bloquea dashboard ni Cuenta.
- [x] Admin y client conservan la experiencia determinada por su rol.
- [x] El registro no envía una notificación de verificación.
- [x] El flujo Fortify permanece disponible pero fuera de la navegación normal.
- [x] La infraestructura necesaria para reactivarlo no se elimina.
- [x] No existen migraciones ni cambios de ownership.
- [x] Las pruebas y controles de calidad pasan después del cambio.

## 10. Decisiones pendientes

No hay decisiones pendientes para la etapa actual. La fecha y las rutas que
exigirán verificación en una versión futura deberán definirse cuando exista un
proveedor de correo configurado.
