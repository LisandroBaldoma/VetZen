---
id: client.gestionar-turnos
title: Gestionar mis turnos
roles: [client]
module: appointments
route_names: [appointments.index, appointments.create, appointments.store, appointments.show, appointments.cancellation, appointments.reschedule.edit, appointments.reschedule]
keywords: [pedir turno, solicitar turno, reservar turno, mis turnos, cancelar turno, reprogramar turno, horarios disponibles]
status: available
feature_ids: [10]
source_refs: [routes/web.php, resources/js/pages/appointments/index.tsx, resources/js/pages/appointments/create.tsx, resources/js/pages/appointments/show.tsx]
reviewed_at: 2026-10-19
---

# Gestionar mis turnos

## Solicitar un turno

1. Abrí **Turnos** desde el menú de Atención.
2. Seleccioná **Solicitar turno**.
3. Elegí una de tus mascotas y un servicio.
4. Elegí uno de los horarios publicados disponibles.

La solicitud queda en estado **Pendiente** hasta que la veterinaria la revise.

## Consultar, cancelar o reprogramar

En **Mis turnos** podés ver los próximos turnos y el historial de tus mascotas.
Abrí un turno para consultar su detalle. Cuando el plazo lo permite, desde el
detalle podés cancelarlo o elegir una nueva fecha y horario.

## Importante

Solo podés solicitar y consultar turnos de tus propias mascotas. La agenda
mostrada al reservar se vuelve a validar al confirmar la solicitud.

Simón no puede consultar tus turnos reales ni elegir un horario por vos.

## Relacionado

- [Turnos y Agenda](../concepts/turnos-y-agenda.md)
- [Solicitar atención para una mascota](solicitar-atencion.md)
