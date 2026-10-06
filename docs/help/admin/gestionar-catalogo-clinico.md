---
id: admin.gestionar-catalogo-clinico
title: Gestionar el catálogo clínico
roles: [admin]
module: services
route_names: [admin.services.index, admin.services.store, admin.services.update, admin.services.status.update, admin.services.procedures.index, admin.services.procedures.store, admin.services.procedures.update, admin.services.procedures.status.update, admin.services.treatments.index, admin.services.treatments.store, admin.services.treatments.update, admin.services.treatments.status.update]
keywords: [servicio clínico, procedimiento, plantilla de tratamiento, catálogo]
status: available
feature_ids: [07, 08, UX-06]
source_refs: [routes/web.php, resources/js/pages/admin/services/index.tsx, features/07-services.md]
reviewed_at: 2026-10-06
---

# Gestionar el catálogo clínico

## Cuándo usarlo

Usalo para administrar servicios clínicos, procedimientos y plantillas de
tratamiento reutilizables.

## Cómo hacerlo

1. Abrí **Servicios clínicos**, **Procedimientos clínicos** o **Plantillas de
   tratamiento** según el recurso que necesites administrar.
2. Para un servicio, crealo o abrí uno existente para editarlo y cambiar su
   estado.
3. Desde un servicio, administrá sus procedimientos.
4. Desde un servicio, administrá sus plantillas y asociá procedimientos del
   mismo servicio.
5. Activá o desactivá recursos cuando sea necesario.

## Qué sucede después

Los recursos activos quedan disponibles para los flujos que los requieran. Las
referencias históricas se conservan aunque un recurso se desactive.

## Importante

Un servicio no tiene precio ni duración. Un procedimiento puede tener duración
orientativa, pero no precio. Las plantillas se reutilizan para asignar
tratamientos a pacientes. No existe eliminación física de estos recursos.

## Relacionado

- [Revisar y resolver solicitudes](revisar-y-resolver-solicitudes.md)
- [Qué es un servicio clínico](../concepts/servicio-clinico.md)
