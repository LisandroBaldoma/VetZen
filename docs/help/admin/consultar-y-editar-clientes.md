---
id: admin.consultar-y-editar-clientes
title: Consultar y editar clientes
roles: [admin]
module: clients
route_names: [admin.clients.index, admin.clients.edit, admin.clients.update]
keywords: [clientes, editar cliente, responsable, datos de contacto]
status: available
feature_ids: [04, UX-03]
source_refs: [routes/web.php, docs/ui-audit/02-pages-inventory.md, docs/ui-audit/07-role-matrix.md]
reviewed_at: 2026-10-06
---

# Consultar y editar clientes

## Cuándo usarlo

Usalo para buscar un cliente existente y actualizar sus datos de contacto.

## Cómo hacerlo

1. Abrí **Clientes** desde la navegación administrativa.
2. Buscá y seleccioná el cliente que querés revisar.
3. Actualizá los datos disponibles del cliente.
4. Guardá los cambios.

## Qué sucede después

Los cambios quedan disponibles en la ficha del cliente y en el contexto de sus
pacientes.

## Importante

El panel administrativo no tiene una acción para crear clientes. El registro
público crea una cuenta y su perfil de cliente asociado.

## Relacionado

- [Gestionar pacientes](gestionar-pacientes.md)
