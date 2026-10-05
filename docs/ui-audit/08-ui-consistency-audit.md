# Auditoría de consistencia y diferencias

## Hallazgos UX/UI

1. **Sistemas visuales coexistentes.** Landing, auth, ajustes y formularios heredados conservan starter-kit/inglés; dashboards, contexto clínico y catálogos recientes usan una línea más editorial, cálida y localizada. El futuro rediseño debe migrar composición y contenido, no solo color.
2. **Localización incompleta.** `welcome`, auth, perfil/seguridad, formulario de mascota/cliente y detalle de procedimiento contienen copy en inglés. Esto contradice UX-00, que exige interfaz visible en español.
3. **Jerarquía inconsistente.** `PageHeader` expone `h1`; muchas vistas profundas usan `Heading` (`h2`) y no presentan h1 visible. Breadcrumbs son sólidos en contexto de mascota, incompletos en formularios y procedimientos/plantillas anidados.
4. **Listas responsive no uniformes.** Varios listados cambian tabla por cards móviles; plantillas contextual mantiene scroll horizontal. Sin verificación de navegador no se certifica overflow/foco.
5. **Feedback/destructivas inconsistente.** Se usan `window.confirm()` para estado/cancelaciones; foto se borra sin confirmación. Existen Dialog y Sonner primitives pero no un patrón adoptado. Botones processing y puntos suspensivos varían.
6. **Estados duplicados.** badges y barras de progreso de request/tratamiento/sesión se definen por página. La barra del dashboard client tiene semántica ARIA; las demás no son equivalentes.
7. **Carga/errores globales.** hay `Skeleton`, `Sonner`, `Alert` e `InputError`; listas de dominio no usan skeleton/deferred props ni se encontró una página Inertia 403/404/error.
8. **Uso de color.** `app.css` ya declara fondo cálido, verdes y tonos operativos, pero muchas superficies, estados y acciones dependen visualmente del verde. El futuro sistema debe usar el fondo/neutros como 60% dominante y reservar marca/semántica para intención. No se aplicó cambio alguno en esta auditoría.

## Diferencias documentación vs implementación

| Fuente | Diferencia observada |
|---|---|
| `technical.md` | sigue preguntando Vue 3/stack por definir, pero el repositorio usa React + Inertia. Decisión técnica desactualizada. |
| `spec.md` / `features.md` | agenda, disponibilidad, notificaciones, IA, múltiples profesionales y permisos avanzados son alcance producto pero no están implementados. |
| UX-00 / UX-01 | exige español y retiro de contenido starter-kit; persiste en auth/welcome y componentes/layouts alternos. |
| UX-08 | especifica sesiones como tabla desktop/cards móvil; la implementación usa timeline en desktop y móvil. |
| UX-03/05/06 | algunas respuestas Inertia comparten modelos Eloquent crudos o campos más amplios que las props mínimas documentadas. |
| UX-01..08 | revisiones manuales responsive, teclado, contraste y accesibilidad siguen marcadas pendientes; no se ejecutaron aquí. |

## Riesgos de rediseño

- No rediseñar funciones ausentes como turnos/IA, ni inventar navegación para ellas.
- Preservar permiso y ownership backend; una acción oculta no sustituye Policy/middleware.
- Mantener la visibilidad histórica como dato de registro, no como restricción de lectura del cliente.
- Tratamientos de cliente son estrictamente read-only; solicitudes no permiten elegir tratamiento.
- No asumir que el detalle de sesión se puede simplificar: conserva precio, moneda, fecha, notas y estado histórico.
