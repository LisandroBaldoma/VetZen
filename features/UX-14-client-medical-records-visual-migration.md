# UX-14 - Migración visual de historia clínica

> Estado: implementada. Verificación automatizada completada; revisión visual
> manual responsive pendiente.

## Objetivo

Migrar las referencias de Stitch para los índices de historia clínica a
componentes React reutilizables, preservando el acceso cliente de solo lectura y
las capacidades administrativas existentes.

## Alcance

- Crear encabezado, cronología y estado vacío reutilizables para los índices
  cliente y administrativo de historia clínica.
- Mostrar fecha, tipo localizado y título mediante enlaces Wayfinder reales.
- Mantener la cabecera contextual, breadcrumbs y navegación existentes.
- Mostrar en admin las acciones reales de crear y editar, junto con sus
  metadatos históricos.

## Decisiones

- El contenido completo no aparece en la cronología: se consulta únicamente en
  el detalle autorizado.
- Cliente no ve acciones de crear o editar registros.
- Admin conserva “Nuevo registro”, “Ver registro” y “Editar registro”.
- La referencia no aporta un estado vacío específico; se adapta el mensaje
  existente sin CTA.
- No se duplican shell, breadcrumbs ni `PetContextHeader` de Stitch.

## Seguridad y datos

- No cambian backend, rutas, Policies ni ownership.
- Las props del índice permanecen limitadas a `id`, `type`, `title` y
  `occurred_at`.

## Verificación

- Pruebas focalizadas de historia clínica, formato, lint, tipos y build.
- Revisión visual manual pendiente en 320, 375, 390, 768 y 1280 px.
