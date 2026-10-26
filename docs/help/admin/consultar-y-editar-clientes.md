---
id: admin.consultar-y-editar-clientes
title: Consultar y editar clientes
roles: [admin]
module: clients
route_names: [admin.clients.index, admin.clients.edit, admin.clients.update, admin.users.role.update]
keywords: [clientes, editar cliente, responsable, datos de contacto, convertir en administrador]
status: available
feature_ids: [04, UX-03]
source_refs: [routes/web.php, resources/js/pages/admin/clients/edit.tsx, docs/ui-audit/02-pages-inventory.md, docs/ui-audit/07-role-matrix.md]
reviewed_at: 2026-10-24
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

Desde la ficha también podés conceder acceso administrativo. Después del
cambio, la cuenta deja de aparecer en Clientes y pasa a Administradores sin
perder sus datos.

## Relacionado

- [Gestionar pacientes](gestionar-pacientes.md)
- [Gestionar administradores](gestionar-administradores.md)
