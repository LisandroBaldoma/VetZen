---
id: concepts.turnos-y-agenda
title: Turnos y Agenda
roles: [admin, client]
module: appointments
route_names: [appointments.index, appointments.create, admin.appointments.index, admin.schedule-dates.index]
keywords: [turno, turnos, agenda, reserva, reservas, cita, citas, agendar, reprogramar, horario]
status: available
feature_ids: [10, SIMON-01, SIMON-04]
source_refs: [routes/web.php, features/10-appointments-and-schedule.md, resources/js/lib/navigation.ts]
reviewed_at: 2026-10-19
---

# Turnos y Agenda

Los turnos permiten reservar un horario publicado para una mascota y un servicio.
La Agenda es la herramienta administrativa donde la veterinaria publica fechas,
horarios y capacidad, y consulta los turnos registrados.

## Estados de un turno

- **Pendiente:** un cliente solicitó el turno y la veterinaria debe revisarlo.
- **Aprobado:** el turno fue confirmado por la veterinaria o creado por un administrador.
- **Rechazado, cancelado, atendido o ausente:** son estados finales que se conservan en el historial.

## Importante

Simón puede explicar cómo usar Turnos y Agenda, pero no consulta datos reales.
No puede indicar cuál es tu próximo turno, qué horarios están disponibles ni qué
pacientes tienen turnos en una fecha.

## Relacionado

- [Gestionar mis turnos](../client/gestionar-turnos.md)
- [Gestionar turnos](../admin/gestionar-turnos.md)
- [Usar la Agenda](../admin/gestionar-agenda.md)
