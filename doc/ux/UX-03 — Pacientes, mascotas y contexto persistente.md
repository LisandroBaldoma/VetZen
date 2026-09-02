Implementa la tercera etapa del rediseño de VetZen: `UX-03 — Pacientes, mascotas y contexto persistente`.

Lee completamente:

* `AGENTS.md`
* `features.md`
* `docs/ux/current-panel-audit.md`
* `docs/ux/panel-redesign-spec.md`
* La documentación e implementación de UX-01 y UX-02
* Feature 04 Clientes
* Feature 05 Mascotas
* Rutas, Policies, controladores, componentes y pruebas relacionados

## 1. Documentación

Antes de implementar, crea:

`features/UX-03-patient-and-pet-context.md`

Adapta la ubicación al patrón real del proyecto si fuera necesario.

Documenta objetivo, situación actual, alcance, fuera de alcance, diseño por rol, componentes, responsive, seguridad, pruebas y criterios de aceptación.

## 2. Objetivo

Crear una experiencia consistente para localizar y consultar pacientes o mascotas, manteniendo visible su contexto al navegar hacia historia clínica, solicitudes y tratamientos.

No rediseñes todavía el contenido interno de esos módulos.

## 3. Administrador

### Listado de pacientes

Rediseña `/admin/pets` para mostrar:

* Nombre del paciente como enlace principal.
* Foto o avatar.
* Especie.
* Raza, cuando exista.
* Responsable o cliente.
* Acciones secundarias agrupadas.
* Acción primaria “Nuevo paciente”.

Comportamiento:

* Tabla accesible en escritorio.
* Tarjetas adaptadas en móvil.
* No uses cantidades o datos secundarios como enlace.
* No agregues búsqueda, filtros o paginación si el backend todavía no los soporta.
* Mantén las operaciones actuales.

### Ficha del paciente

Rediseña `/admin/pets/{pet}` como ficha contextual.

Debe mostrar una cabecera reutilizable con:

* Foto o avatar.
* Nombre.
* Especie y raza.
* Sexo y edad o fecha de nacimiento si están disponibles.
* Responsable enlazado cuando exista destino útil.
* Acción “Editar paciente”.

Incluye navegación contextual mediante enlaces Inertia:

* Resumen.
* Historia clínica.
* Solicitudes de atención.
* Tratamientos.

Usa las rutas existentes. Si alguna ruta contextual no existe para admin, no inventes una página sin backend; documenta la dependencia.

## 4. Cliente

### Mis mascotas

Rediseña `/pets` con:

* Tarjetas de mascotas.
* Foto o avatar.
* Nombre como enlace principal.
* Especie y raza.
* Acción primaria “Registrar mascota”.
* Estado vacío con explicación y siguiente paso.

### Ficha de mascota

Rediseña `/pets/{pet}` manteniendo lenguaje de cliente:

* “Mascota” y “Mis mascotas”, no “Paciente”.
* Foto, nombre y datos básicos.
* Acción “Editar mascota”.
* Navegación contextual:

  * Resumen.
  * Historia clínica.
  * Solicitudes de atención.
  * Tratamientos.

## 5. Contexto persistente

Crea o adapta un componente compartido, por ejemplo `PatientHeader` o `PetContextHeader`.

Debe soportar variantes admin y cliente sin duplicar toda la estructura.

El contexto debe mantenerse en:

* Ficha principal.
* Listado y detalle de historia clínica.
* Listado y detalle de solicitudes.
* Listado y detalle de tratamientos.

En esta feature solo agrega cabecera, breadcrumbs y navegación contextual a esas páginas. No rediseñes sus tablas, formularios o reglas de negocio.

Las pestañas o secciones deben ser enlaces reales con URL, no estado local.

## 6. Componentes y navegación

Reutiliza:

* `PageHeader`.
* Breadcrumbs.
* Estados vacíos.
* Componentes UI existentes.
* Wayfinder e Inertia.

Revisa si `PetSummary` puede evolucionar o reutilizarse. No crees componentes duplicados sin justificarlo.

Breadcrumbs esperados:

* `Inicio / Pacientes`.
* `Pacientes / Nombre`.
* `Pacientes / Nombre / Historia clínica`.
* `Mis mascotas / Nombre`.
* `Mis mascotas / Nombre / Tratamientos`.

El estado activo debe funcionar en rutas anidadas.

## 7. Seguridad

* Conserva middleware, Policies y ownership.
* Cliente solo puede recibir mascotas propias.
* Cliente no puede consultar páginas contextuales de mascotas ajenas.
* Admin conserva las capacidades actuales.
* No dependas de ocultar enlaces como mecanismo de autorización.
* Verifica que los recursos anidados pertenezcan a la mascota de la URL.

## 8. Responsive y accesibilidad

Valida 320, 375, 390, 768 y 1280 px.

Requisitos:

* Sin overflow horizontal.
* Tabla desktop y tarjetas móvil para pacientes admin.
* Acciones adaptadas o agrupadas en móvil.
* Una única `h1` por pantalla.
* Fotos con texto alternativo adecuado.
* Navegación contextual usable con teclado.
* Estado activo no dependiente únicamente del color.
* Controles táctiles de al menos 44 × 44 px.
* Orden de foco lógico.

## 9. Estados

Incluye:

* Admin sin pacientes.
* Cliente sin mascotas.
* Mascota sin foto.
* Datos opcionales ausentes.
* Error o acceso denegado sin filtrar información.
* Navegación contextual hacia secciones vacías existentes.

## 10. Fuera de alcance

* Búsqueda, filtros o paginación nuevos.
* Acceso completo a historia clínica.
* Rediseño interno de historia clínica.
* Rediseño de solicitudes.
* Rediseño de tratamientos o sesiones.
* Endpoints globales.
* Eliminación o archivado de mascotas.
* Cambios de base de datos.
* Agenda, profesionales o permisos.
* Landing, autenticación o verificación de correo.

## 11. Pruebas

Agrega o actualiza pruebas Pest para comprobar:

* Admin accede al listado y ficha de pacientes.
* Cliente accede a sus mascotas y fichas.
* Cliente no accede a mascotas ajenas.
* Recursos anidados no pueden consultarse cambiando el identificador de mascota.
* Las props incluyen únicamente los datos necesarios.
* Los enlaces contextuales respetan rol y rutas existentes.
* Los estados vacíos funcionan.
* No se debilitan las pruebas previas de autorización.

Ejecuta pruebas específicas, formato, TypeScript, lint y build según los comandos del proyecto.

No modifiques funcionalidades ajenas y no hagas commit.

Al finalizar informa:

1. Documento de feature creado.
2. Componentes creados o reutilizados.
3. Páginas modificadas.
4. Decisiones de responsive.
5. Pruebas ejecutadas y resultados.
6. Dependencias o limitaciones detectadas.
