---
id: admin.gestionar-pacientes
title: Gestionar pacientes
roles: [admin]
module: pets
route_names: [admin.pets.index, admin.pets.create, admin.pets.store, admin.pets.show, admin.pets.edit, admin.pets.update]
keywords: [paciente, crear paciente, editar paciente, mascota]
status: available
feature_ids: [05, UX-03]
source_refs: [routes/web.php, features/05-Pets.md, docs/ui-audit/02-pages-inventory.md]
reviewed_at: 2026-10-06
---

# Gestionar pacientes

## Cuándo usarlo

Usalo para registrar, consultar o editar pacientes y acceder a su contexto
clínico.

## Cómo hacerlo

1. Abrí **Pacientes** desde la navegación administrativa.
2. Para crear uno, elegí la acción de crear paciente y seleccioná el cliente
   responsable.
3. Completá los datos generales y guardá.
4. Para revisar uno existente, abrí su ficha.
5. Desde su ficha, entrá a **Historia clínica** o **Tratamientos** según la
   tarea que necesites realizar.

## Qué sucede después

El paciente queda asociado al cliente seleccionado y su ficha concentra las
secciones clínicas y de tratamiento.

## Importante

En el área administrativa VetZen usa el término **Paciente**. No se puede
eliminar ni reasignar un paciente desde este flujo.

## Relacionado

- [Gestionar historia clínica](gestionar-historia-clinica.md)
- [Asignar y gestionar tratamientos](asignar-y-gestionar-tratamientos.md)
