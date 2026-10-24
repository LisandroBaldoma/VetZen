---
id: client.solicitar-atencion
title: Solicitar atención para una mascota
roles: [client]
module: service-requests
route_names: [services.index, services.show, pets.service-requests.create, pets.service-requests.store]
keywords: [solicitar atención, solicito atención, atiendan a mi mascota, nueva solicitud, pedir atención, atención para mascota, servicio]
status: available
feature_ids: [08, UX-07]
source_refs: [routes/web.php, resources/js/pages/services/show.tsx, resources/js/pages/pets/service-requests/create.tsx]
reviewed_at: 2026-10-06
---

# Solicitar atención para una mascota

## Cuándo usarlo

Usalo cuando quieras solicitar una evaluación profesional para un **Servicio

## Cómo hacerlo

1. Abrí **Servicios disponibles**.
2. Elegí el servicio que querés consultar.
3. Seleccioná una de tus mascotas y elegí **Continuar solicitud**.
4. En **Nueva solicitud**, revisá el servicio seleccionado.
5. Agregá una nota opcional si querés brindar más contexto.
6. Seleccioná **Enviar solicitud**.

## Qué sucede después

La solicitud queda asociada a la mascota con estado **Pendiente**. La
veterinaria puede evaluarla, resolverla con un tratamiento o cancelarla.

## Importante

Una solicitud de atención no reserva un turno ni permite elegir un tratamiento.
Solo podés crearla para una mascota propia y para servicios disponibles.

## Relacionado

- [Consultar solicitudes de atención](consultar-solicitudes-de-atencion.md)
- [Qué es una solicitud de atención](../concepts/solicitud-de-atencion.md)
