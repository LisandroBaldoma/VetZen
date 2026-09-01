Crea la especificación funcional y UX del rediseño del panel de VetZen.

Lee `AGENTS.md`, la documentación del proyecto y, principalmente, `docs/ux/current-panel-audit.md`. Usa el código actual para comprobar detalles cuando sea necesario.

No implementes componentes, estilos, rutas ni lógica. El entregable debe ser únicamente:

`docs/ux/panel-redesign-spec.md`

## Decisiones confirmadas

* Se mantienen inicialmente los roles `admin` y `client`.
* En el panel profesional se usará “Pacientes” para las mascotas.
* En el panel cliente se usará “Mis mascotas”.
* `Treatment` se mostrará como “Plantilla de tratamiento”.
* `PetTreatment` se mostrará como “Tratamiento del paciente”.
* `ServiceRequest` se mostrará como “Solicitud de atención”.
* La asignación directa de un tratamiento seguirá disponible para admin.
* El cliente podrá consultar toda la historia clínica de sus mascotas.
* Una sesión completada podrá ofrecer registrar una evolución clínica, sin hacerlo obligatorio.
* Agenda, profesionales, gestión de permisos y configuración general no deben mostrarse hasta estar implementados.
* Toda la interfaz debe quedar en español.
* No cambies todavía nombres técnicos, tablas o modelos.

## Arquitectura que debe especificarse

### Administrador

* Inicio
* Pacientes

  * Clientes
  * Pacientes
  * Historia clínica dentro de cada paciente
* Atención

  * Solicitudes de atención
  * Tratamientos de pacientes
  * Sesiones, solo si una vista global aporta valor real
* Catálogo clínico

  * Servicios clínicos
  * Procedimientos clínicos
  * Plantillas de tratamiento
* Cuenta

  * Perfil
  * Seguridad
  * Apariencia

### Cliente

* Inicio
* Mis mascotas
* Servicios disponibles
* Mis solicitudes
* Mis tratamientos
* Cuenta

  * Mis datos
  * Seguridad
  * Apariencia

Evalúa esta arquitectura y realiza ajustes justificados, sin agregar módulos inexistentes.

## Contenido obligatorio

1. Objetivos y principios del rediseño.
2. Arquitectura definitiva por rol.
3. Mapa de navegación y diagramas Mermaid.
4. Flujo completo desde registro/login hasta dashboard.
5. Flujo cliente: mascota → servicio → solicitud → tratamiento → sesiones.
6. Flujo admin: solicitud → evaluación → plantilla → tratamiento → sesiones.
7. Flujo alternativo de asignación directa.
8. Vocabulario definitivo y reemplazo de nombres actuales.
9. Especificación pantalla por pantalla.
10. Wireframes de baja fidelidad en texto o Mermaid.
11. Jerarquía de acciones: primaria, secundaria, contextual y sensible.
12. Breadcrumbs, navegación de regreso y conservación del contexto.
13. Estados vacío, carga, error, éxito, sin resultados y acceso denegado.
14. Búsqueda, filtros, paginación y comportamiento responsive.
15. Formularios, validaciones, confirmaciones y mensajes.
16. Accesibilidad básica.
17. Componentes compartidos recomendados.
18. Estrategia de implementación incremental.

Para cada pantalla incluye una tabla con:

| Pantalla | Rol | Propósito | Información | Acción primaria | Acciones secundarias | Filtros | Estados | Navegación siguiente |

Incluye como mínimo wireframes para:

* Dashboard admin.
* Dashboard cliente.
* Menú lateral desktop y navegación móvil.
* Listado y ficha de paciente.
* Historia clínica.
* Catálogo de servicios.
* Solicitudes de atención.
* Plantillas de tratamiento.
* Tratamiento del paciente.
* Gestión de sesiones.
* Configuración de cuenta.

## Reglas de experiencia

* Cada pantalla debe tener un solo propósito principal.
* Los nombres de registros deben ser enlaces cuando conducen al detalle.
* Las cantidades no deben funcionar como enlace principal.
* Los cambios de estado deben usar el mismo control que muestra el estado cuando sea comprensible y accesible.
* Las acciones sensibles deben confirmar consecuencias.
* Los íconos sin texto solo se permiten cuando sean inequívocos y tengan nombre accesible.
* Las acciones poco frecuentes deben agruparse en un menú contextual.
* Las páginas internas deben conservar el contexto del paciente, servicio o tratamiento.
* En móvil, las tablas densas deben transformarse o priorizar columnas; no basta con recortarlas.
* Las solicitudes resueltas deben enlazar directamente al tratamiento creado.
* Los servicios disponibles deben permitir iniciar una solicitud seleccionando una mascota.
* Si faltan procedimientos o plantillas, el estado vacío debe explicar y ofrecer el siguiente paso.
* No inventes datos que el backend todavía no puede proporcionar; marca cualquier dependencia.

## Plan de implementación

Divide el rediseño en tareas pequeñas y ordenadas. Separa:

1. Fundamentos y componentes compartidos.
2. Navegación y vocabulario.
3. Dashboards.
4. Pacientes e historia clínica.
5. Catálogo clínico.
6. Solicitudes.
7. Tratamientos y sesiones.
8. Cuenta, autenticación y landing.
9. Responsive y accesibilidad.
10. Pruebas y validación final.

Para cada tarea indica alcance, archivos probables, dependencias y criterios de aceptación. No escribas código.

Al finalizar informa:

* Ruta del documento creado.
* Principales decisiones tomadas.
* Dependencias de backend detectadas.
* Preguntas que todavía requieran decisión.
* Archivos creados o modificados.

No hagas commit.
