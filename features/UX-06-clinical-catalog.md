# UX-06 - Catálogo clínico

> Estado: implementada y verificada automáticamente. Permanece pendiente la
> revisión visual manual responsive y de teclado.

## 1. Objetivo

Unificar la administración de servicios, procedimientos y plantillas de
tratamiento como un catálogo clínico coherente, sin cambiar el modelo de dominio
de F07 y F08.

## 2. Dependencias

- UX-01 para navegación, shell, breadcrumbs y vocabulario.
- F07 para Service y Procedure.
- F08 para Treatment y sus asociaciones.

## 3. Alcance

- Listado global de servicios con búsqueda, estado y paginación.
- Listados globales de procedimientos y plantillas con contexto del servicio.
- Administración contextual de procedimientos y plantillas desde un servicio.
- Formularios responsive con estados vacíos y acciones coherentes.
- Activación y desactivación sin eliminación física.

No incluye precios de catálogo, protocolos, asignaciones a pacientes, pagos,
agenda ni recursos nuevos.

## 4. Contrato funcional

1. Service continúa siendo el área terapéutica general.
2. Procedure y Treatment siempre muestran el Service propietario.
3. Una plantilla nueva requiere un Service activo y al menos un Procedure activo.
4. Una plantilla no puede agregar procedimientos inactivos ni de otro Service.
5. Al editar, un Procedure inactivo ya asociado permanece disponible para
   conservar y comprender la configuración histórica; otro inactivo no puede
   agregarse.
6. Treatment conserva descripción obligatoria y el mismo máximo en alta y edición.
7. Los cambios de estado usan endpoints específicos y no permiten mass assignment
   de ownership.

## 5. Experiencia

- La navegación usa “Servicios clínicos”, “Procedimientos clínicos” y “Plantillas
  de tratamiento”.
- Las pantallas globales facilitan búsqueda y descubrimiento; las contextuales
  mantienen visible el Service padre.
- Las tablas se adaptan a tarjetas en pantallas estrechas.
- Los estados se muestran localizados y las acciones sensibles requieren
  confirmación.
- Si un Service no posee procedimientos seleccionables, la creación de plantilla
  explica el bloqueo y enlaza al alta de procedimiento.

## 6. Seguridad y datos

- Todas las rutas requieren autenticación y rol admin.
- Policies y Form Requests aplican autorización y validación backend.
- `service_id` se deriva de la ruta contextual.
- Las props Inertia incluyen solo campos utilizados por cada página.

## 7. Criterios de aceptación

- [x] Servicios, procedimientos y plantillas tienen listados utilizables y responsive.
- [x] Búsqueda, filtros y paginación preservan query string donde corresponde.
- [x] Alta y edición mantienen contexto y validación backend.
- [x] No se agregan procedimientos incompatibles o inactivos nuevos a plantillas.
- [x] No existe eliminación física.
- [x] Pruebas funcionales y TypeScript verifican el contrato automatizable.
- [ ] Revisión visual manual responsive y de teclado completada.

## 8. Decisiones pendientes

No existen decisiones pendientes dentro de UX-06.
