# Tablas, cards y estados

## Tablas y listas

| Área | Vista / columnas o contenido | Filtros, acciones y respuesta |
|---|---|---|
| Clientes admin | nombre, email/contacto, mascotas/responsable y editar | sin filtros/paginación; tabla escritorio y cards móvil; vacío. |
| Pacientes admin | foto, nombre, especie/raza, responsable, acciones | sin filtros/paginación; tabla escritorio/cards móvil; detalle/editar. |
| Servicios | nombre, descripción, estado, procedimientos/plantillas, acciones | búsqueda + activo/inactivo, paginación; tabla/cards; editar, navegar, cambiar estado. |
| Procedimientos global | nombre, servicio, duración, estado, acciones | búsqueda + servicio + estado, paginación; tabla/cards. |
| Procedimientos de servicio | procedimiento, duración, estado, acciones | lista contextual, sin filtro/paginación visible; editar/detalle/estado. |
| Plantillas global | nombre, servicio, sesiones estimadas, procedimientos, estado | búsqueda + servicio + estado, paginación; tabla/cards. |
| Plantillas de servicio | nombre, sesiones/procedimientos, estado, acciones | lista contextual en tabla scroll horizontal, sin cards móvil: excepción. |
| Solicitudes admin | mascota, responsable, servicio, fecha, estado, acciones | filtros y paginación; tabla/cards; abrir/resolver/cancelar. |
| Historia y sesiones | timelines, no tablas | historia: tipo/título/fecha; sesiones: número/fecha/precio/estado/notas. |

No se expone ordenamiento en ninguna lista. La arquitectura reutilizable debe aceptar columnas, renderer mobile, filtros y row actions específicos; no asumir que toda colección es paginada ni tabular.

## Cards

| Familia | Aparición | Información / acciones |
|---|---|---|
| Pet card | índice client, dashboard | foto, nombre, especie/raza; abrir mascota. |
| Patient row card | listas admin mobile | identidad, responsable, acciones overflow. |
| Service card/detail | catálogo client | nombre, descripción, procedimientos, selector de mascota, solicitar atención. |
| Request card | dashboard/listas client | mascota, servicio, estado, fecha/notas; abrir solicitud/tratamiento resuelto. |
| Treatment card | dashboard/listas client y admin | nombre/servicio, estado, sesiones/progreso y CTA. |
| Timeline item | historia clínica y sesiones | secuencia temporal con tipo/estado, contenido, metadatos y acciones autorizadas. |
| Empty/action card | dashboards, catálogos, tratamientos | ausencia de datos, explicación y siguiente acción. |

`PetCard`, `RequestCard`, `TreatmentCard` y timeline no deben forzarse en una `EntityCard`: comparten superficie pero tienen jerarquías diferentes. Sí conviene una base visual Card, composición de metadatos, icono/estado y CTA.

## Estados y representación actual

| Entidad | Estados | Significado y UI actual |
|---|---|---|
| Servicio/procedimiento/plantilla | activo/inactivo (`is_active`) | disponibilidad comercial; texto/badge y acción de activar/desactivar. |
| Solicitud | pending/resolved/cancelled | espera evaluación, ya resuelta con tratamiento, cancelada; badges locales con color y texto. |
| Tratamiento asignado | pending/in_progress/completed/suspended/cancelled | pendiente, en curso, terminado, pausado, cancelado; badges locales y progreso. |
| Sesión | pending/completed/cancelled | por realizar, realizada, cancelada; timeline y selector admin. |
| Registro clínico | consultation/evaluation/evolution/session/other | clase del registro; etiqueta/tipo en timeline. |
| Controles | processing/disabled/errors | botones deshabilitados durante envío y `InputError`; no hay loading skeletons de dominio. |

Los mapas de estado se duplican por página. Unificar presentación por **dominio** y mantener tokens semánticos, no un único diccionario universal.
