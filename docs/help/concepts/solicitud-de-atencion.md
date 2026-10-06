---
id: concepts.solicitud-de-atencion
title: Qué es una solicitud de atención
roles: [client, admin]
module: service-requests
route_names: [pets.service-requests.create, pets.service-requests.show, admin.service-requests.show]
keywords: [solicitud de atención, pendiente, resuelta, cancelada]
status: available
feature_ids: [08, UX-07]
source_refs: [features/08-treatments-and-sessions.md, features/UX-00-panel-redesign-spec.md]
reviewed_at: 2026-10-06
---

# Qué es una solicitud de atención

Una solicitud de atención expresa que un cliente quiere una evaluación
profesional para un servicio disponible y una mascota propia.

## Importante

- No es un turno, una reserva ni una consulta clínica.
- No permite al cliente elegir un tratamiento.
- Puede estar **Pendiente**, **Resuelta** o **Cancelada**.
- Al resolverla, un admin puede crear un tratamiento compatible para el paciente.

## Relacionado

- [Solicitar atención](../client/solicitar-atencion.md)
- [Revisar y resolver solicitudes](../admin/revisar-y-resolver-solicitudes.md)
