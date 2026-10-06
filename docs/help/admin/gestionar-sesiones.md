---
id: admin.gestionar-sesiones
title: Gestionar sesiones de un tratamiento
roles: [admin]
module: treatments
route_names: [admin.pets.treatments.show, admin.treatment-sessions.update]
keywords: [sesión, completar sesión, programar sesión, cancelar sesión]
status: available
feature_ids: [08, UX-08]
source_refs: [routes/web.php, features/08-treatments-and-sessions.md, features/UX-08-patient-treatments-and-sessions.md]
reviewed_at: 2026-10-06
---

# Gestionar sesiones de un tratamiento

## Cuándo usarlo

Usalo para actualizar la fecha, el precio, las notas o el estado de una sesión

## Cómo hacerlo

1. Abrí el paciente y entrá en **Tratamientos**.
2. Seleccioná el tratamiento correspondiente.
3. Buscá la sesión que querés administrar.
4. Actualizá los datos permitidos, incluida la fecha programada si corresponde.
5. Para completar una sesión, seleccioná el estado **Completada** y guardá.

## Qué sucede después

El progreso del tratamiento se calcula a partir de las sesiones completadas. Al
completar la cantidad requerida, el tratamiento pasa a **Completado**.

## Importante

Una sesión completada o cancelada no puede volver a cambiar de estado. Cancelar
una sesión conserva el historial y puede generar una sesión pendiente de
reemplazo si todavía faltan sesiones para completar el tratamiento. Una sesión
no equivale a un turno.

## Relacionado

- [Asignar y gestionar tratamientos](asignar-y-gestionar-tratamientos.md)
- [Qué es una sesión del tratamiento](../concepts/sesion-del-tratamiento.md)
