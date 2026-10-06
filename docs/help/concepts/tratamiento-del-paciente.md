---
id: concepts.tratamiento-del-paciente
title: Qué es un tratamiento del paciente
roles: [client, admin]
module: treatments
route_names: [pets.treatments.show, admin.pets.treatments.show]
keywords: [tratamiento paciente, mi tratamiento, progreso, sesiones]
status: available
feature_ids: [08, UX-08]
source_refs: [features/08-treatments-and-sessions.md, features/UX-00-panel-redesign-spec.md]
reviewed_at: 2026-10-06
---

# Qué es un tratamiento del paciente

Un tratamiento del paciente es la asignación concreta de una plantilla a un
paciente. Conserva las condiciones acordadas, los procedimientos históricos y
sus sesiones.

## Importante

- Puede crearse al resolver una solicitud o iniciarse directamente por un admin.
- El cliente lo consulta como **Mi tratamiento** en modo lectura.
- Su progreso depende de las sesiones completadas.
- Los cambios posteriores del catálogo no modifican las condiciones históricas
  del tratamiento asignado.

## Relacionado

- [Consultar tratamientos y sesiones](../client/consultar-tratamientos-y-sesiones.md)
- [Asignar y gestionar tratamientos](../admin/asignar-y-gestionar-tratamientos.md)
