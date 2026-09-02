# UX-04 - Verificación obligatoria de correo

> Estado: implementada y verificada automáticamente.

## 1. Objetivo

Hacer efectiva la verificación obligatoria de correo para que ninguna cuenta
autenticada con correo pendiente pueda acceder al panel de VetZen.

## 2. Fuentes y dependencias

- `AGENTS.md`, `spec.md`, `technical.md` y `features.md`.
- `doc/ux/current-panel-audit.md` y `doc/ux/panel-redesign-spec.md`.
- Fortify y las rutas de autenticación existentes.
- UX-01 para la definición del panel autenticado.

## 3. Situación actual

Fortify tiene habilitada la verificación de correo y ya proporciona la pantalla,
el reenvío, la URL firmada y el evento de verificación. Las rutas principales
usan middleware `verified`, pero `User` no implementa `MustVerifyEmail`; por eso
el middleware no constituye actualmente una barrera efectiva.

Además, las rutas de Cuenta bajo `settings` y `clients/{client}` solo exigen
autenticación y permiten entrar a una parte del panel sin verificar el correo.

## 4. Alcance

1. Incorporar `MustVerifyEmail` al contrato de `User`.
2. Exigir `verified` en todas las rutas del panel, incluida Cuenta.
3. Conservar la pantalla de aviso, el reenvío de la notificación, la URL firmada
   y el cierre de sesión existentes.
4. Cubrir mediante HTTP el bloqueo y el acceso correcto tras verificar.
5. Verificar que el flujo Fortify existente continúa funcionando.

## 5. Fuera de alcance

- Rediseñar o traducir las pantallas de autenticación.
- Configurar un proveedor real de correo o probar entrega externa.
- Cambiar registro, login, recuperación, 2FA o passkeys.
- Cambiar roles, permisos, ownership o reglas de dominio.
- Agregar una verificación administrativa o una vía alternativa de aprobación.

## 6. Reglas

1. Toda cuenta `admin` o `client` debe verificar su correo antes de entrar al
   panel.
2. Una cuenta autenticada no verificada que solicite una ruta del panel debe ser
   redirigida a `verification.notice`.
3. La restricción se aplica en backend mediante middleware; la visibilidad
   frontend no reemplaza la barrera.
4. Una cuenta verificada conserva el acceso que le otorguen su rol, permisos,
   Policies y ownership. Verificar el correo no concede capacidades nuevas.
5. Mientras esté pendiente, la cuenta puede consultar el aviso de verificación,
   reenviar el correo y cerrar sesión.
6. La URL de verificación debe continuar siendo temporal, firmada y vinculada al
   identificador y hash de correo correctos.
7. Un hash o identificador inválido no verifica la cuenta.
8. Verificar una cuenta ya verificada no vuelve a emitir el evento `Verified`.

## 7. Rutas

Las rutas públicas y las rutas Fortify necesarias para completar el flujo
mantienen sus middlewares actuales. Todas las rutas de aplicación en
`routes/web.php` y de Cuenta en `routes/settings.php` deben exigir conjuntamente
`auth` y `verified`.

No se crean endpoints nuevos.

## 8. Experiencia

Después del registro o al intentar entrar al panel con una cuenta pendiente, el
usuario permanece en la pantalla de verificación. Puede solicitar otro enlace o
cerrar sesión. Después de abrir un enlace válido, Fortify marca el correo como
verificado y redirige a Inicio.

La traducción y mejora responsive de esta pantalla pertenecen a la tarea A2 del
rediseño de autenticación y no se incluyen en UX-04.

## 9. Seguridad

`email_verified_at` continúa siendo determinado por el backend. No se acepta en
payloads de usuario ni se convierte en un campo asignable. La verificación no
omite Policies, roles ni la cadena de ownership de los recursos solicitados.

## 10. Pruebas

El proyecto usa PHPUnit. Deben cubrirse mediante rutas HTTP reales:

- una cuenta no verificada es redirigida desde Inicio al aviso;
- una cuenta no verificada es redirigida desde Cuenta al aviso;
- una cuenta verificada accede a Inicio y Cuenta;
- la pantalla de aviso se renderiza para una cuenta pendiente;
- un enlace temporal válido verifica y redirige a Inicio;
- hashes e identificadores inválidos no verifican;
- una cuenta verificada no repite la verificación;
- el reenvío notifica a cuentas pendientes y no a cuentas verificadas.

No se prueba la entrega real de correo.

## 11. Archivos previstos

- `features/UX-04-mandatory-email-verification.md`.
- `app/Models/User.php`.
- `routes/settings.php`.
- `tests/Feature/Auth/EmailVerificationTest.php`.
- Pruebas Fortify relacionadas solo si la cobertura existente lo requiere.

## 12. Criterios de aceptación

- [x] `User` implementa `MustVerifyEmail`.
- [x] Una cuenta no verificada no accede a Inicio.
- [x] Una cuenta no verificada no accede a Cuenta ni a otras rutas del panel.
- [x] Una cuenta verificada accede normalmente según su autorización.
- [x] Aviso, reenvío, URL firmada y logout continúan disponibles.
- [x] No se agregan rutas ni campos de base de datos.
- [x] No se modifica autorización de recursos ni ownership.
- [x] Las pruebas de autenticación afectadas pasan.
- [x] Formato y suite completa pasan.

## 13. Decisiones pendientes

No existen decisiones pendientes para este alcance. La obligatoriedad de la
verificación fue cerrada en `doc/ux/panel-redesign-spec.md`.

## 14. Definición de terminado

UX-04 queda terminada cuando el contrato de `User` hace efectivo el middleware,
todas las superficies del panel exigen correo verificado, Fortify conserva su
flujo actual y las pruebas HTTP y de calidad pasan.
