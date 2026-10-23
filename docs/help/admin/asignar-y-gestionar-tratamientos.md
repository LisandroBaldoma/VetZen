---
id: admin.asignar-y-gestionar-tratamientos
title: Asignar y gestionar tratamientos de un paciente
roles: [admin]
module: treatments
route_names: [admin.pet-treatments.index, admin.pets.treatments.index, admin.pets.treatments.create, admin.pets.treatments.store, admin.pets.treatments.show, admin.pets.treatments.update, admin.pets.treatments.status.update]
keywords: [tratamientos de pacientes, asignar tratamiento, iniciar tratamiento, tratamiento paciente, condiciones]
status: available
feature_ids: [08, UX-08]
source_refs: [routes/web.php, resources/js/pages/admin/pets/treatments/create.tsx, features/08-treatments-and-sessions.md]
reviewed_at: 2026-10-06
---

# Asignar y gestionar tratamientos de un paciente

## Cuándo usarlo

Usalo para iniciar un tratamiento directamente para un paciente o administrar
uno que ya fue asignado.

## Cómo hacerlo

1. Para consultar todos los tratamientos asignados, abrí **Tratamientos de
   pacientes** desde el menú de Atención.
2. Podés buscar por tratamiento, paciente o responsable, y filtrar por servicio
   o estado.
3. Abrí un tratamiento para revisar su detalle y progreso.
4. Para iniciar uno nuevo, abrí **Pacientes**, seleccioná el paciente y entrá en
   **Tratamientos**.
5. Elegí **Iniciar tratamiento**.
6. Seleccioná una plantilla disponible.
7. Definí sesiones planificadas, precio por sesión, fecha de inicio y notas.
8. Confirmá el inicio del tratamiento.
9. Desde el detalle del tratamiento, revisá condiciones, procedimientos,
   progreso y sesiones.

## Qué sucede después

VetZen conserva una copia histórica de la plantilla y crea sesiones pendientes.
El tratamiento puede iniciarse directamente o como resultado de una solicitud
resuelta.

## Importante

Solo se muestran plantillas activas de servicios activos con procedimientos
válidos. Un tratamiento de paciente no es la misma entidad que una plantilla del
catálogo. Los cambios posteriores del catálogo no modifican tratamientos ya
asignados.

## Relacionado

- [Gestionar sesiones](gestionar-sesiones.md)
- [Qué es una plantilla de tratamiento](../concepts/plantilla-de-tratamiento.md)
