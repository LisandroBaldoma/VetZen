# UX-03 - Pacientes, mascotas y contexto persistente

> Estado: implementada. Verificación automatizada completada; revisión visual manual pendiente.

## 1. Objetivo

Crear una experiencia consistente para localizar pacientes o mascotas y conservar visible su contexto al navegar por su ficha, historia clínica, solicitudes de atención y tratamientos, sin rediseñar el contenido interno ni alterar reglas de negocio.

## 2. Fuentes y dependencias

- `AGENTS.md`, `spec.md`, `technical.md` y `features.md`.
- `doc/ux/current-panel-audit.md` y `doc/ux/panel-redesign-spec.md`.
- `features/04-Clients.md`, `features/04-ClientsContinuacion.md` y `features/05-Pets.md`.
- UX-01 para shell, navegación, `PageHeader` y breadcrumbs.
- UX-02 para Inicio y accesos contextuales.
- Rutas, Policies, controladores y pruebas actuales de mascotas, historias clínicas, solicitudes y tratamientos.

La ruta real de documentación es `doc/ux`, no `docs/ux`. El proyecto usa PHPUnit, no Pest.

## 3. Situación actual

- `/admin/pets` usa una tabla no adaptada a móvil, sin foto, raza ni acciones agrupadas, y conserva textos en inglés.
- `/pets` usa tarjetas básicas sin foto, raza ni siguiente paso en el estado vacío.
- Las fichas admin y cliente reutilizan `PetSummary`, pero separan el contexto de los enlaces de dominio y conservan textos en inglés.
- Las páginas anidadas no muestran una cabecera persistente ni breadcrumbs profundos.
- El cliente dispone de rutas contextuales para historia clínica, solicitudes y tratamientos.
- Admin dispone de rutas contextuales para historia clínica y tratamientos, pero no para solicitudes de un paciente concreto.
- Las rutas anidadas de solicitudes y tratamientos usan scoped bindings; historia clínica verifica explícitamente que el registro pertenezca a la mascota de la URL.

## 4. Alcance

1. Rediseñar los listados `/admin/pets` y `/pets`.
2. Rediseñar las fichas `/admin/pets/{pet}` y `/pets/{pet}`.
3. Crear una cabecera contextual compartida con variante administrativa y cliente.
4. Mantener el contexto en listados y detalles existentes de historia clínica y tratamientos para ambos roles.
5. Mantener el contexto en listados y detalles de solicitudes para cliente.
6. Incorporar breadcrumbs profundos y navegación contextual mediante enlaces Inertia/Wayfinder.
7. Mantener una sola `h1`, estados vacíos, foco visible y adaptación responsive.
8. Limitar las props nuevas o modificadas a los datos de presentación necesarios.

## 5. Fuera de alcance

- Búsqueda, filtros o paginación nuevos.
- Nuevas rutas o páginas administrativas de solicitudes por paciente.
- Rediseño interno de historia clínica, solicitudes, tratamientos, sesiones o formularios.
- Cambios de autorización, ownership, modelos, migraciones o base de datos.
- Eliminación, archivado o Soft Deletes de mascotas.
- Endpoints globales nuevos.
- Agenda, profesionales, permisos, landing, autenticación o verificación de correo.

## 6. Diseño administrativo

### 6.1 Listado de pacientes

En escritorio se muestra una tabla accesible con foto o avatar, nombre como enlace principal, especie, raza opcional, responsable y acciones secundarias agrupadas. En móvil se reemplaza por tarjetas; no se recorta ni se obliga a desplazar una tabla horizontal.

La acción primaria es “Nuevo paciente”. Si no hay pacientes, se explica el estado y se conserva esa acción como siguiente paso.

### 6.2 Ficha del paciente

La cabecera muestra foto o avatar, nombre, especie, raza, sexo, fecha de nacimiento cuando exista, responsable enlazado y “Editar paciente”. La navegación disponible es:

```text
Resumen | Historia clínica | Tratamientos
```

“Solicitudes de atención” no se muestra como pestaña administrativa porque no existe una ruta contextual bajo `/admin/pets/{pet}`. El listado administrativo global continúa disponible desde Atención y esta feature no crea un endpoint nuevo.

## 7. Diseño cliente

### 7.1 Mis mascotas

El listado usa tarjetas con foto o avatar, nombre como enlace principal, especie y raza. La acción primaria es “Registrar mascota”. El estado vacío explica que todavía no hay mascotas y ofrece el mismo siguiente paso.

### 7.2 Ficha de mascota

La cabecera mantiene el vocabulario “Mascota” y “Mis mascotas”, muestra datos básicos y ofrece “Editar mascota”. La navegación contextual es:

```text
Resumen | Historia clínica | Solicitudes de atención | Tratamientos
```

## 8. Componente contextual

Se crea `PetContextHeader` como estructura compartida. Recibe la mascota, variante `admin` o `client`, sección activa y destino de edición.

Responsabilidades:

- renderizar la única `h1` de la pantalla;
- mostrar foto autorizada o avatar estable sin foto;
- localizar etiquetas visibles y omitir datos opcionales ausentes sin dejar huecos engañosos;
- mostrar responsable solo en admin cuando la relación esté disponible;
- generar destinos con Wayfinder;
- marcar el enlace activo con `aria-current="page"` y una señal adicional al color;
- mantener objetivos táctiles de al menos 44 por 44 px.

`PetSummary` evoluciona para representar únicamente la información general de la sección Resumen; no duplica foto, título, navegación ni acción de edición.

## 9. Breadcrumbs

Jerarquías previstas:

```text
Inicio / Pacientes
Inicio / Pacientes / Nombre
Inicio / Pacientes / Nombre / Historia clínica
Inicio / Pacientes / Nombre / Tratamientos
Inicio / Mis mascotas
Inicio / Mis mascotas / Nombre
Inicio / Mis mascotas / Nombre / Historia clínica
Inicio / Mis mascotas / Nombre / Solicitudes de atención
Inicio / Mis mascotas / Nombre / Tratamientos
```

En detalles profundos se añade el título del registro, solicitud o tratamiento cuando esté disponible. Los segmentos previos son enlaces reales y el actual no es navegable.

## 10. Datos y backend

- Los listados mantienen el orden actual por nombre y no incorporan paginación.
- Cliente obtiene la colección desde el `Client` autenticado.
- Admin carga responsable mediante `client.user` sin N+1.
- Las props de listados y fichas principales se transforman explícitamente para no exponer rutas internas de foto, timestamps o claves de ownership innecesarias.
- Se envía un booleano de disponibilidad de foto; la imagen se obtiene desde la ruta autorizada existente.
- Las páginas admin de tratamientos cargan el responsable necesario para la cabecera, sin modificar el tratamiento.

## 11. Seguridad

1. Se conservan middleware, roles, Policies y ownership actuales.
2. Cliente solo lista y consulta mascotas de su propio `Client`.
3. La ruta de foto continúa autorizando la mascota antes de responder.
4. Solicitudes y tratamientos anidados deben pertenecer a la mascota indicada por la URL.
5. Historia clínica mantiene su comprobación explícita de pertenencia.
6. Los enlaces React no reemplazan autorización backend.
7. Un acceso denegado no serializa ni muestra contexto del recurso ajeno.

## 12. Responsive y accesibilidad

- Se valida a 320, 375, 390, 768 y 1280 px.
- La tabla admin se muestra desde desktop; móvil usa tarjetas.
- La navegación contextual puede desplazarse dentro de su propia región sin generar overflow de página.
- Nombre, responsable y acciones permiten wrap o truncado seguro.
- Fotos tienen texto alternativo; avatares sin foto conservan un nombre accesible.
- Existe una sola `h1`; las secciones internas comienzan en `h2`.
- El enlace activo usa texto, `aria-current` y estilo estructural, no solo color.
- Orden de foco y orden visual coinciden.

## 13. Estados

- Admin sin pacientes: explicación y acción “Nuevo paciente”.
- Cliente sin mascotas: explicación y acción “Registrar mascota”.
- Sin foto: avatar con inicial de la mascota.
- Sin raza, nacimiento u otro dato opcional: se omite en cabecera o se informa “No informado” en Resumen.
- Secciones anidadas vacías: conservan su estado actual y la cabecera contextual.
- Acceso ajeno o recurso anidado incorrecto: respuesta backend existente sin filtrar contexto.

## 14. Archivos previstos

- `features/UX-03-patient-and-pet-context.md`.
- `app/Http/Controllers/Pet/PetController.php`.
- `app/Http/Controllers/Admin/PetController.php`.
- `app/Http/Controllers/Admin/PetTreatmentController.php`.
- `resources/js/components/pet-context-header.tsx`.
- `resources/js/components/pet-summary.tsx`.
- Listados, fichas y páginas anidadas de mascotas bajo `resources/js/pages/admin/pets` y `resources/js/pages/pets` incluidas en el alcance.
- `resources/js/pages/admin/service-requests/show.tsx` para conservar el contexto del paciente en el detalle global existente.
- `resources/js/types/auth.ts`.
- `tests/Feature/Pet/PetManagementTest.php` y pruebas anidadas solo cuando falte cobertura observable.

## 15. Pruebas

El proyecto usa PHPUnit. Debe verificarse mediante rutas HTTP reales:

- admin accede al listado y ficha con datos de responsable;
- cliente accede al listado y ficha propios;
- las colecciones cliente no contienen mascotas ajenas;
- cliente no consulta una mascota ajena;
- cambiar el padre de una historia, solicitud o tratamiento anidado es rechazado;
- listados y fichas principales reciben campos necesarios y omiten claves de ownership, ruta interna de foto y timestamps;
- estados vacíos devuelven colecciones vacías;
- las pruebas previas de Policies y ownership continúan pasando.

La navegación, responsive, foco y texto alternativo se verifican además mediante TypeScript, ESLint, build y revisión manual, ya que el proyecto no incorpora pruebas de navegador.

## 16. Criterios de aceptación

- [x] Admin ve tabla desktop y tarjetas móvil con paciente, foto/avatar, especie, raza, responsable y acciones.
- [x] Cliente ve tarjetas de mascotas con estado vacío accionable.
- [x] El nombre es el enlace principal; conteos y datos secundarios no lo reemplazan.
- [x] Las fichas comparten cabecera sin duplicar estructura entre roles.
- [x] La variante admin usa “Paciente” y la cliente “Mascota”.
- [x] Historia, solicitudes y tratamientos incluidos conservan cabecera y breadcrumbs.
- [x] Las secciones contextuales son enlaces con URL y estado activo accesible.
- [x] No se crea una ruta admin de solicitudes por paciente.
- [x] No se rediseña contenido interno de módulos anidados.
- [x] Cliente solo recibe y consulta mascotas propias.
- [x] Recursos anidados incorrectos siguen siendo rechazados.
- [x] No se exponen rutas internas de foto ni claves de ownership en props principales rediseñadas.
- [ ] No hay overflow de página en los anchos definidos.
- [x] Existe una sola `h1` por pantalla incluida.
- [x] Formato, tipos, lint, build y pruebas relevantes pasan.

## 17. Dependencia documentada

### Solicitudes administrativas por paciente

No existe una ruta equivalente a `/admin/pets/{pet}/service-requests`. Agregarla implicaría backend y una página contextual nueva, expresamente fuera de alcance. UX-03 omite esa pestaña en admin y conserva el acceso global existente hasta que una feature posterior defina el endpoint y sus datos.

## 18. Definición de terminado

UX-03 queda terminada cuando listados y fichas son consistentes por rol, el contexto persiste en todas las páginas anidadas incluidas, ownership permanece protegido, las props principales son mínimas y las verificaciones automatizadas pasan sin introducir funcionalidades futuras.
