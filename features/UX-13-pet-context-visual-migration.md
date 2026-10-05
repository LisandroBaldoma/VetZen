# UX-13 - Migración visual del contexto de paciente

> Estado: implementada. Verificación automatizada completada; revisión visual
> manual responsive pendiente.

## Objetivo

Adaptar la referencia `pets_context_header.blade.php` al contexto compartido de
mascotas y pacientes con información real, sin alterar el shell, rutas,

## Alcance

- Actualizar `PetContextHeader` y `PetSummary` para las rutas existentes de
  admin y cliente.
- Presentar foto autorizada o avatar, identidad, especie, raza, sexo,
  responsable administrativo y edición mediante enlaces Wayfinder existentes.
- Usar navegación horizontal por secciones con estado activo accesible.
- Presentar datos generales y notas reales en tarjetas responsive.

## Decisiones

- Se conserva el shell global y sus breadcrumbs; no se replica el enlace
  interno de retorno de la referencia.
- La variante admin no muestra solicitudes porque no existe una ruta contextual
  bajo `/admin/pets/{pet}`.
- No se incorporan edad calculada, menú de opciones, ni etiquetas de bloqueo
  estáticas de la referencia.
- La variante cliente conserva sus solicitudes de atención y no expone el
  responsable.

## Seguridad y datos

- No hay cambios de backend, Policies, ownership ni rutas.
- Las fotos se obtienen exclusivamente mediante la ruta existente autorizada.
- Las props actuales de `PetContext` contienen los datos de presentación
  requeridos.

## Verificación

- Pruebas focalizadas de mascotas, formato, lint, tipos y build.
- Revisión visual manual pendiente en 320, 375, 390, 768 y 1280 px.
