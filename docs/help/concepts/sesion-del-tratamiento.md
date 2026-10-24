---
id: concepts.sesion-del-tratamiento
title: Qué es una sesión del tratamiento
roles: [client, admin]
module: treatments
route_names: [pets.treatments.show, admin.pets.treatments.show, admin.treatment-sessions.update]
keywords: [sesión, completar sesión, sesión pendiente, sesión cancelada]
status: available
feature_ids: [08, UX-08]
source_refs: [features/08-treatments-and-sessions.md, features/UX-00-panel-redesign-spec.md]
reviewed_at: 2026-10-06
---

# Qué es una sesión del tratamiento

Una sesión pertenece a un tratamiento del paciente. Tiene fecha programada,
precio, notas y estado propios.

## Importante

- No equivale a un turno ni reserva una cita.
- Puede estar **Pendiente**, **Completada** o **Cancelada**.
- Una sesión completada cuenta para el progreso del tratamiento.
- Una sesión cancelada se conserva como historial y puede requerir un reemplazo.
- El cliente solo consulta sesiones de sus propias mascotas; el admin las
  administra.

## Relacionado

- [Gestionar sesiones](../admin/gestionar-sesiones.md)
- [Qué es un tratamiento del paciente](tratamiento-del-paciente.md)
