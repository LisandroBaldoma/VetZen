---
id: admin.gestionar-turnos
title: Gestionar turnos
roles: [admin]
module: appointments
route_names: [admin.appointments.index, admin.appointments.create, admin.appointments.store, admin.appointments.show, admin.appointments.status.update, admin.appointments.reschedule.edit, admin.appointments.reschedule]
keywords: [crear turno, aprobar turno, rechazar turno, cancelar turno, marcar atendido, marcar ausente, reprogramar turno, turnos pendientes]
status: available
feature_ids: [10]
source_refs: [routes/web.php, resources/js/pages/admin/appointments/index.tsx, resources/js/pages/admin/appointments/create.tsx, resources/js/pages/admin/appointments/show.tsx]
reviewed_at: 2026-10-19
---

# Gestionar turnos

## Revisar solicitudes pendientes

Abrí **Turnos** para revisar las solicitudes pendientes. Desde el detalle podés
aprobarlas, rechazarlas o cancelarlas con un motivo.

Un turno aprobado puede marcarse como **Atendido** o **Ausente**. Los estados
finales se conservan en el historial y no se reabren.

## Crear o reprogramar un turno

Seleccioná **Crear turno**, elegí paciente y servicio, y luego uno de los
horarios publicados. El turno creado por administración queda aprobado.

Para reprogramar un turno aprobado, abrí su detalle y seleccioná
**Reprogramar**. Elegí un nuevo horario disponible; el turno conserva su
historial.

## Importante

La disponibilidad se valida nuevamente al guardar. Simón puede explicar estos
pasos, pero no puede crear, aprobar ni modificar turnos.

## Relacionado

- [Usar la Agenda](gestionar-agenda.md)
- [Turnos y Agenda](../concepts/turnos-y-agenda.md)
