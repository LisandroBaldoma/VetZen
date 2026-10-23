---
id: getting-started.crear-cuenta
title: Crear una cuenta
roles: [client]
module: account
route_names: [register, google.redirect, google.registration.create]
keywords: [registro, registrarme, crear cuenta, alta, google]
status: available
feature_ids: [04]
source_refs: [resources/js/pages/auth/register.tsx, resources/js/pages/auth/complete-google-registration.tsx, app/Actions/Fortify/CreateNewUser.php, app/Http/Controllers/Auth/GoogleAuthenticationController.php]
reviewed_at: 2026-10-23
---

# Crear una cuenta

## Cuándo usarlo

Usalo para registrarte como cliente de VetZen y administrar tus propias mascotas.

## Cómo hacerlo

### Con correo y contraseña

1. Abrí la pantalla de registro.
2. Completá tu nombre, email, teléfono y contraseña.
3. Repetí la contraseña en el campo de confirmación.
4. Seleccioná **Crear cuenta**.

### Con Google

1. Abrí la pantalla de registro y seleccioná **Registrarme con Google**.
2. Elegí una cuenta personal de Google o Google Workspace.
3. Completá tu teléfono y definí una contraseña para VetZen.
4. Seleccioná **Crear cuenta**.

## Qué sucede después

VetZen crea tu cuenta y tu perfil de cliente. Después podés ingresar a **Mis
mascotas** para registrar una mascota.

## Importante

Si ya existe una cuenta de VetZen con el mismo correo, iniciá sesión con tu
método habitual y vinculá Google desde **Seguridad**. VetZen no vincula cuentas
automáticamente por coincidencia de correo.

El registro no crea una mascota automáticamente.

## Relacionado

- [Gestionar mascotas](../client/gestionar-mascotas.md)
