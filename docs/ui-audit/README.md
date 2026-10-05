# Auditoría UX/UI de VetZen

Fecha: 2026-10-01. Alcance: inspección estática de documentación, rutas Laravel, controladores, Policies, Form Requests, respuestas Inertia, React, layouts y componentes. No se modificó código de producto ni se realizó una validación en navegador.

## Resumen ejecutivo

- Se identificaron **57 módulos de página React**: 1 pública, 7 de autenticación, 3 dashboards (uno es placeholder no enrutable), 4 de configuración, 13 de dominio cliente y 29 de administración.
- Las experiencias alcanzables cubren autenticación, perfiles, clientes, mascotas/pacientes, historia clínica, catálogo de servicios/procedimientos/plantillas, solicitudes de atención, tratamientos asignados y sesiones.
- No existen aún páginas, rutas ni modelos para agenda/turnos, disponibilidad, notificaciones, asistente IA/RAG, profesionales adicionales o administración de permisos. Son alcance de producto futuro, no pantallas omitidas por este inventario.
- El shell operativo ya diferencia correctamente `admin` y `client`; la seguridad se valida en backend mediante middleware, Policies y ownership. La UI no es el control de seguridad.
- Hay repetición real en barras de filtros, paginación, vacíos, badges de estado, acciones de fila, acciones de formulario y tarjetas/resúmenes de tratamientos. Son candidatos a patrones compartidos, no a una abstracción única de dominio.
- La principal deuda visual no es funcional: conviven el starter kit/inglés, formularios heredados y nuevas vistas clínicas/operativas. Además hay breadcrumbs, encabezados `h1`, confirmaciones y respuesta móvil inconsistentes.

## Límites y evidencia

Este material describe lo implementado, no lo que debería existir según el roadmap. Las conclusiones de interfaz se basan en código fuente; el comportamiento visual, foco de teclado, contraste y breakpoints requieren una revisión manual posterior.

Las diferencias con los documentos fuente están registradas en `08-ui-consistency-audit.md`. Las decisiones pendientes no se resuelven aquí.

## Índice

- `01-navigation-map.md`: rutas, shell y mapa navegable.
- `02-pages-inventory.md`: inventario funcional de pantallas, datos y anatomía.
- `03-components-inventory.md`: primitives, layout y componentes funcionales.
- `04-forms-inventory.md`: formularios, endpoints, campos y validación.
- `05-tables-and-cards.md`: inventario de tablas, tarjetas, estados y matriz de reutilización.
- `06-user-flows.md`: flujos reales por rol y fricciones de navegación.
- `07-role-matrix.md`: autorización backend y diferencias de experiencia.
- `08-ui-consistency-audit.md`: hallazgos, discrepancias y estados especiales.
- `09-component-architecture.md`: arquitectura propuesta para el rediseño futuro.
- `stitch/`: fichas de pantalla listas para trasladar a Stitch.
