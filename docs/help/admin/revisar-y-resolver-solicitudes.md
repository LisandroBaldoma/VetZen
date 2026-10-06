---
id: admin.revisar-y-resolver-solicitudes
title: Revisar y resolver solicitudes de atención
roles: [admin]
module: service-requests
route_names: [admin.service-requests.index, admin.service-requests.show, admin.service-requests.resolve, admin.service-requests.cancel]
keywords: [revisar solicitud, resolver solicitud, cancelar solicitud, iniciar tratamiento]
status: available
feature_ids: [08, UX-07]
source_refs: [routes/web.php, resources/js/pages/admin/service-requests/show.tsx, docs/ui-audit/06-user-flows.md]
reviewed_at: 2026-10-06
---

# Revisar y resolver solicitudes de atención

## Cuándo usarlo

Usalo para atender una solicitud pendiente creada por un cliente para uno de los
servicios disponibles.

## Cómo hacerlo

1. Abrí **Solicitudes de atención**.
2. Seleccioná una solicitud pendiente.
3. Revisá el paciente, responsable, servicio y nota del cliente.
4. En **Iniciar tratamiento**, elegí una plantilla compatible y definí las
   condiciones solicitadas.
5. Confirmá la resolución.

## Qué sucede después

VetZen crea un tratamiento para el paciente y sus sesiones iniciales. La
solicitud pasa a estado **Resuelta** y queda vinculada al tratamiento creado.

## Importante

Solo podés resolver solicitudes pendientes. Si no hay plantillas compatibles o
el servicio está inactivo, la resolución se bloquea. También podés cancelar una
solicitud pendiente; se conserva como historial.

## Relacionado

- [Gestionar catálogo clínico](gestionar-catalogo-clinico.md)
- [Asignar y gestionar tratamientos](asignar-y-gestionar-tratamientos.md)
