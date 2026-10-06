---
id: concepts.servicio-clinico
title: Qué es un servicio clínico
roles: [client, admin]
module: services
route_names: [services.index, services.show, admin.services.index, admin.services.show]
keywords: [servicio clínico, servicio disponible, procedimiento]
status: available
feature_ids: [07, UX-06]
source_refs: [features/07-services.md, features/UX-00-panel-redesign-spec.md]
reviewed_at: 2026-10-06
---

# Qué es un servicio clínico

Un servicio clínico representa un área terapéutica general ofrecida por la
veterinaria. Puede incluir procedimientos clínicos.

## Importante

- Un servicio no tiene precio ni duración.
- Los clientes ven únicamente servicios activos como **Servicios disponibles**.
- Un cliente puede solicitar atención para un servicio, pero no asignar un
  tratamiento.
- Los admins administran el catálogo de servicios y sus procedimientos.

## Relacionado

- [Gestionar el catálogo clínico](../admin/gestionar-catalogo-clinico.md)
- [Qué es una solicitud de atención](solicitud-de-atencion.md)
