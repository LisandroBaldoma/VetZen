# Fichas Stitch: Catálogo clínico

## Servicios cliente

- **Objetivo:** conocer servicios activos e iniciar una solicitud para una mascota propia.
- **Usuario:** client.
- **Información:** nombre, descripción, procedimientos activos, mascotas propias.
- **Acciones:** seleccionar mascota, solicitar atención, crear mascota si no existe; abrir detalle.
- **Componentes:** PageHeader, ServiceCard/ServiceDetails, procedure list, pet selector, CTA/disabled state.
- **Estados:** sin mascotas, servicio sin procedimiento, selector pendiente. El índice repite selector/CTA por servicio.

## Servicios admin: listado, detalle y formulario

- **Objetivo:** administrar el área terapéutica general.
- **Usuario:** admin.
- **Información:** nombre, descripción, activo, procedimientos y plantillas relacionados.
- **Acciones:** crear, editar, activar/desactivar, abrir procedimientos/plantillas.
- **Componentes:** PageHeader, FilterBar, ResponsiveDataList, CatalogStatusBadge/Action, service form, Pagination.
- **Estados:** resultado vacío, filtro, procesando, confirmación de estado.

## Procedimientos: catálogo global y contextual

- **Objetivo:** mantener técnicas de un servicio.
- **Usuario:** admin.
- **Información:** nombre, servicio, descripción, duración sugerida, activo.
- **Acciones:** filtrar global; crear/editar/ver/cambiar estado contextual.
- **Componentes:** DataList, FilterBar global, EntityHeader contextual, procedure form, status badge.
- **Estados:** vacío, duración no definida, inactivo, responsive table/cards salvo vistas contextuales.

## Plantillas de tratamiento: global y contextual

- **Objetivo:** configurar tratamientos reutilizables sin precio.
- **Usuario:** admin.
- **Información:** nombre, servicio, descripción, procedimientos, sesiones estimadas, activo.
- **Acciones:** filtrar global; crear/editar/cambiar estado contextual.
- **Formulario:** nombre, descripción, sesiones estimadas, multiselección de procedimientos activos del servicio, activo.
- **Estados:** vacío; bloqueo si el servicio no tiene procedimientos activos; fila actual usa scroll horizontal en móvil.
- **Componentes:** FilterBar/DataList/Pagination, treatment template form, selection list, EmptyState, status action.
