---
id: admin.gestionar-administradores
title: Gestionar administradores
roles: [admin]
module: clients
route_names: [admin.clients.edit, admin.administrators.index, admin.users.role.update]
keywords: [administradores, convertir en administrador, quitar acceso administrativo, cambiar rol, promover cliente]
status: available
feature_ids: [04]
source_refs: [routes/web.php, app/Actions/ChangeUserRole.php, resources/js/pages/admin/administrators/index.tsx, resources/js/pages/admin/clients/edit.tsx]
reviewed_at: 2026-10-24
---

# Gestionar administradores

## Cuándo usarlo

Usalo para conceder acceso administrativo a un cliente registrado o para
devolver al rol cliente a un administrador que conserve su perfil de cliente.

## Cómo conceder acceso administrativo

1. Abrí **Clientes** desde la navegación administrativa.
2. Seleccioná el cliente.
3. En **Acceso administrativo**, elegí **Convertir en administrador**.
4. Revisá el alcance del acceso y confirmá **Conceder acceso**.

## Cómo quitar acceso administrativo

1. Abrí **Administradores** desde la sección **Administración**.
2. Buscá al administrador.
3. Elegí **Quitar acceso** y confirmá la acción.

## Qué sucede después

Al conceder acceso, la cuenta pasa de Clientes a Administradores. Al quitarlo,
vuelve a aparecer en Clientes. En ambos casos se conservan el perfil, las
mascotas y los demás datos relacionados con la cuenta.

## Importante

No podés modificar tu propio rol. Tampoco podés quitar el acceso a un
administrador interno que no tenga perfil de cliente. Cada cambio queda
registrado para auditoría.

## Relacionado

- [Consultar y editar clientes](consultar-y-editar-clientes.md)
