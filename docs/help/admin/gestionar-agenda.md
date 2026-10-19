---
id: admin.gestionar-agenda
title: Usar la Agenda
roles: [admin]
module: appointments
route_names: [admin.schedule-dates.index, admin.schedule-dates.create, admin.schedule-dates.edit, admin.schedule-templates.index]
keywords: [agenda, publicar fecha, horarios, bloques de trabajo, capacidad, plantilla, agenda mensual, agenda semanal, agenda diaria]
status: available
feature_ids: [10]
source_refs: [routes/web.php, resources/js/pages/admin/schedule-dates/index.tsx, resources/js/pages/admin/schedule-dates/create.tsx, resources/js/pages/admin/schedule-templates/index.tsx]
reviewed_at: 2026-10-19
---

# Usar la Agenda

## Publicar fechas y horarios

Abrí **Agenda** y seleccioná **Publicar fecha**. Podés usar una plantilla o una
configuración puntual para definir capacidad, bloques de trabajo, intervalo y
servicios habilitados. También podés publicar fechas manuales o un rango de días.

Los inicios reservables se generan desde los bloques y el intervalo. Antes de
publicar una configuración puntual, podés ajustarlos si necesitás una excepción.

## Consultar la operación diaria

La Agenda abre en vista mensual. Usá los controles **Mes**, **Semana** y **Día**
para cambiar la vista y las flechas para navegar períodos.

Cada fecha publicada muestra capacidad, carga y turnos. Abrí un día para ver sus
bloques, inicios publicados y turnos. Abrí un turno para revisar su detalle o
editá una fecha desde la acción correspondiente.

## Importante

La Agenda ayuda a analizar la ocupación, pero la disponibilidad se valida al
crear o reprogramar cada turno. Simón no puede informar la carga real de una
fecha ni modificar la Agenda.

## Relacionado

- [Gestionar turnos](gestionar-turnos.md)
- [Turnos y Agenda](../concepts/turnos-y-agenda.md)
