---
id: client.consultar-tratamientos-y-sesiones
title: Consultar tratamientos y sesiones de una mascota
roles: [client]
module: treatments
route_names: [pets.treatments.index, pets.treatments.show]
keywords: [mi tratamiento, ver sesiones, progreso, tratamiento mascota]
status: available
grounding: not_allowed_for_role
feature_ids: [08, UX-08]
source_refs: [routes/web.php, docs/ui-audit/06-user-flows.md, features/08-treatments-and-sessions.md]
reviewed_at: 2026-10-06
---

# Consultar tratamientos y sesiones de una mascota

## Cuándo usarlo

Usalo para ver los tratamientos ya asignados a una de tus mascotas, su progreso

## Cómo hacerlo

1. Abrí **Mis mascotas** y elegí la mascota.
2. Entrá en **Tratamientos**.
3. Seleccioná el tratamiento que querés consultar.
4. Revisá sus condiciones, progreso, procedimientos y sesiones.

## Qué sucede después

Podés volver al listado de tratamientos de la mascota o consultar otra sección

## Importante

Los tratamientos los asigna la veterinaria. Como cliente podés consultarlos,
pero no modificar su estado, sesiones, fechas, precios ni notas. No existe una
pantalla global de tratamientos: se accede desde cada mascota.

## Relacionado

- [Consultar solicitudes de atención](consultar-solicitudes-de-atencion.md)
- [Qué es un tratamiento del paciente](../concepts/tratamiento-del-paciente.md)
- [Qué es una sesión del tratamiento](../concepts/sesion-del-tratamiento.md)
