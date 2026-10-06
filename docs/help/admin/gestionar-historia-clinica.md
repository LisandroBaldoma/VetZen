---
id: admin.gestionar-historia-clinica
title: Gestionar la historia clínica de un paciente
roles: [admin]
module: medical-records
route_names: [admin.pets.medical-records.index, admin.pets.medical-records.create, admin.pets.medical-records.store, admin.pets.medical-records.show, admin.pets.medical-records.edit, admin.pets.medical-records.update]
keywords: [historia clínica, nuevo registro, editar registro, evolución]
status: available
feature_ids: [06, UX-05]
source_refs: [routes/web.php, resources/js/pages/admin/pets/medical-records/create.tsx, features/06-MedicalRecords.md]
reviewed_at: 2026-10-06
---

# Gestionar la historia clínica de un paciente

## Cuándo usarlo

Usalo para consultar, crear o editar registros clínicos de un paciente.

## Cómo hacerlo

1. Abrí **Pacientes** y seleccioná el paciente.
2. Entrá en **Historia clínica**.
3. Para registrar información, elegí la acción para crear un nuevo registro.
4. Seleccioná el tipo de registro, completá título, contenido y fecha.
5. Guardá el registro.
6. Para modificar uno existente, abrí su detalle y elegí la acción de edición.

## Qué sucede después

El registro queda asociado al paciente y la creación o actualización conserva
la autoría en el historial de auditoría clínico.

## Importante

La historia clínica es información sensible. Esta guía explica el uso del
sistema, no cómo evaluar un caso clínico. No existe eliminación de registros.

## Relacionado

- [Gestionar pacientes](gestionar-pacientes.md)
- [Gestionar sesiones](gestionar-sesiones.md)
