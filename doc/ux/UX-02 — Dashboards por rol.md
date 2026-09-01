Implementa la segunda etapa del rediseño de VetZen: `UX-02 — Dashboards por rol`.

Primero lee completamente:

* `AGENTS.md`
* `features.md`
* `docs/ux/current-panel-audit.md`
* `docs/ux/panel-redesign-spec.md`
* La documentación e implementación de UX-01
* Los controladores, Policies, modelos, rutas, páginas y pruebas relacionados

## Primera fase: documentación

Crea la feature siguiendo el formato de las features existentes. Usa `features/UX-02-role-based-dashboards.md` o la ubicación equivalente definida por el proyecto.

Debe documentar objetivo, situación actual, alcance, fuera de alcance, datos requeridos, diseño por rol, estados, seguridad, responsive, pruebas y criterios de aceptación.

## Segunda fase: implementación

Transforma `/dashboard` en un inicio útil y diferente para cada rol.

### Administrador

Mostrar:

* Cantidad de solicitudes pendientes.
* Hasta cinco solicitudes pendientes o recientes.
* Paciente, servicio, fecha y estado.
* Enlaces hacia el detalle de la solicitud y el paciente.
* Accesos rápidos:

  * Nuevo paciente.
  * Ver pacientes.
  * Ver solicitudes.
  * Abrir catálogo clínico.

No mostrar agenda, sesiones próximas, estadísticas históricas ni gráficos.

### Cliente

Eliminar el formulario de perfil del dashboard y mostrar:

* Sus mascotas.
* Solicitudes pendientes identificadas por mascota.
* Tratamientos activos con progreso.
* Accesos para registrar mascota y explorar servicios.
* Enlaces hacia la mascota, solicitud o tratamiento contextual correspondiente.

No crear páginas globales de solicitudes o tratamientos.

## Backend y seguridad

* El rol determina qué dashboard se renderiza.
* Los permisos continúan controlando acciones internas.
* Reutiliza las rutas actuales.
* Las consultas cliente deben respetar `User → Client → Pet`.
* Nunca serialices mascotas, solicitudes o tratamientos de otro cliente.
* Carga solamente campos y relaciones necesarios.
* Evita consultas N+1.
* Mantén los límites de resultados razonables.
* Un usuario sin datos asociados debe recibir un estado vacío, no un error.

## Interfaz

* Reutiliza `PageHeader`, breadcrumbs, navegación y estados creados en UX-01.
* Todo el contenido visible debe estar en español.
* Nombres de pacientes, mascotas y solicitudes deben enlazar al destino correspondiente.
* Los tratamientos deben mostrar progreso en formato comprensible.
* Implementa estados:

  * Admin sin solicitudes.
  * Cliente sin mascotas.
  * Cliente con mascotas pero sin solicitudes.
  * Cliente sin tratamientos activos.
  * Error parcial cuando sea aplicable.
* Diseño responsive sin overflow en 320, 375, 390, 768 y 1280 px.
* Mantén accesibilidad de encabezados, enlaces, estados y foco.

## Fuera de alcance

* Agenda y turnos.
* Próximas sesiones globales.
* Gráficos.
* Notificaciones.
* Historia clínica.
* Rediseño de solicitudes o tratamientos.
* Landing y autenticación.
* Verificación de correo.
* Nuevas páginas globales.
* Cambios de base de datos.

## Pruebas

Agrega o actualiza pruebas Pest para comprobar:

* Admin recibe el dashboard administrativo.
* Cliente recibe el dashboard cliente.
* Admin y cliente reciben únicamente las props necesarias.
* Cliente solo recibe mascotas propias.
* Cliente solo recibe solicitudes de sus mascotas.
* Cliente solo recibe tratamientos propios y activos.
* Los estados vacíos funcionan.
* Usuarios no autenticados siguen siendo redirigidos.
* No se debilitan Policies ni ownership.

Ejecuta pruebas específicas, formato, análisis de tipos, lint y build usando los comandos definidos por el proyecto.

No cambies funcionalidades ajenas y no hagas commit.

Al finalizar informa:

1. Documento creado.
2. Decisiones de implementación.
3. Archivos modificados.
4. Consultas agregadas.
5. Pruebas ejecutadas y resultados.
6. Limitaciones o tareas pendientes.
