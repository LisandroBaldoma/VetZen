# UX-11 - Migración visual del dashboard administrativo

> Estado: implementada y verificada automáticamente. Las métricas sin definición
> de negocio permanecen temporales.

## Objetivo

Extraer los bloques visuales del dashboard administrativo desde
`resources/views/home_logo_stitch.blade.php` conservando el contrato funcional
del dashboard administrativo.

## Alcance de esta fase

- Crear componentes React reutilizables para el resumen KPI, solicitudes
  prioritarias y accesos rápidos.
- Componer el dashboard administrativo con esos tres bloques.
- Conectar solicitudes pendientes, especie, notas y acciones existentes mediante
  props Inertia y Wayfinder.
- Mantener hardcodeadas únicamente las métricas "Terapias hoy" y "Adherencia
  médica", que no tienen definición de negocio aprobada.

## Fuera de alcance

- Crear métricas para terapias del día o adherencia médica.
- Mostrar raza, edad, imágenes u otros datos no incluidos en el contrato del
  dashboard.
- Cambiar rutas, Policies, permisos, ownership o modelos.

## Adaptación posterior

Una etapa posterior definirá las métricas temporales y, si corresponde, datos
adicionales de paciente. Los componentes conservan una API de props simple para
esa migración.

## Criterios de aceptación

- [x] `DashboardHero`, `PriorityRequests` y `QuickAccessGrid` existen como
  componentes reutilizables.
- [x] `DashboardHero` muestra el contador real y conserva los dos KPIs sin
  definición como fixtures.
- [x] `PriorityRequests` muestra únicamente solicitudes pendientes reales.
- [x] Las acciones de ficha de paciente y detalle de solicitud usan Wayfinder.
- [x] `QuickAccessGrid` usa destinos reales aprobados.
- [x] No hay cambios de rutas, autorización, ownership ni datos clínicos.
- [x] Pruebas, formato, lint, tipos y build pasan.

## Decisiones pendientes

Las métricas de terapias del día y adherencia médica requieren una definición de
negocio antes de reemplazar sus fixtures. Imágenes, raza y edad de pacientes
también permanecen fuera del contrato actual.
