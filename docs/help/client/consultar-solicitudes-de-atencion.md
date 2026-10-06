---
id: client.consultar-solicitudes
title: Consultar solicitudes de atención
roles: [client]
module: service-requests
route_names: [pets.show, pets.service-requests.index, pets.service-requests.show]
keywords: [ver solicitudes, estado solicitud, solicitud pendiente, solicitud resuelta]
status: available
feature_ids: [08, UX-07]
source_refs: [routes/web.php, docs/ui-audit/06-user-flows.md, features/08-treatments-and-sessions.md]
reviewed_at: 2026-10-06
---

# Consultar solicitudes de atención

## Cuándo usarlo

Usalo para revisar las solicitudes creadas para una mascota y conocer su estado.

## Cómo hacerlo

1. Abrí **Mis mascotas** y elegí la mascota correspondiente.
2. Entrá en **Solicitudes de atención**.
3. Seleccioná una solicitud para ver su detalle.
4. Revisá el servicio solicitado, la nota y el estado.

## Qué sucede después

Una solicitud puede mostrarse como **Pendiente**, **Resuelta** o **Cancelada**.
Cuando está resuelta y tiene un tratamiento asociado, podés abrirlo desde el

## Importante

No existe una pantalla global de solicitudes. Las solicitudes se consultan desde
cada mascota. Como cliente no podés resolver, cancelar ni modificar una solicitud
existente.

## Relacionado

- [Solicitar atención](solicitar-atencion.md)
- [Consultar tratamientos y sesiones](consultar-tratamientos-y-sesiones.md)
