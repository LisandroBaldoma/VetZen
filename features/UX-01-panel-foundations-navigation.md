# UX-01 - Fundamentos y navegación del panel

> Estado: implementada y verificada automáticamente. Permanece pendiente la
> validación manual responsive, visual y de accesibilidad de la sección 21.

## 1. Identificador

Esta feature utiliza el identificador `UX-01` porque el número funcional F09 está reservado en `features.md` para Planes de seguimiento. El rediseño UX mantiene una secuencia propia para no alterar ni ocupar la numeración del roadmap de producto.

## 2. Objetivo

Establecer la base visual, semántica y responsive del panel autenticado de VetZen antes de rediseñar pantallas de dominio.

La feature reorganiza la navegación existente según los roles `admin` y `client`, fija el vocabulario visible, hace predecible el estado activo en rutas anidadas e incorpora un encabezado de página y breadcrumbs reutilizables para las vistas principales.

No modifica reglas de negocio, autorización, datos, rutas backend ni contenido funcional de dashboards.

## 3. Fuentes y dependencias

Esta especificación se apoya en:

- `AGENTS.md`.
- `spec.md`, `technical.md` y `features.md`.
- `doc/ux/current-panel-audit.md`.
- `doc/ux/panel-redesign-spec.md`.
- Features 04 a 08 para roles, ownership y recursos ya implementados.
- La estructura React/Inertia, Wayfinder, Tailwind CSS y componentes UI existentes.

La ruta real de la documentación UX es `doc/ux`, no `docs/ux`.

## 4. Situación actual

### 4.1 Navegación

`resources/js/components/app-sidebar.tsx` construye un único array plano por rol. El menú:

- mezcla inglés y español;
- muestra el grupo genérico “Platform”;
- no separa Pacientes, Atención y Catálogo clínico;
- incluye enlaces del starter kit a repositorio y documentación;
- usa “Mascotas”, “Tratamientos” y “Solicitudes” en admin sin el vocabulario definitivo;
- no presenta una arquitectura clara de Cuenta.

### 4.2 Estado activo

`NavMain` usa `isCurrentUrl`, que compara la ruta exacta. Una entrada puede dejar de verse activa al navegar a alta, edición, detalle o recursos anidados.

`useCurrentUrl` ya ofrece comparación por prefijo mediante `isCurrentOrParentUrl`, pero un prefijo simple no resuelve todos los casos. Por ejemplo, `/admin/services/{service}/procedures` pertenece conceptualmente a Procedimientos clínicos y no debe activar simultáneamente Servicios clínicos.

### 4.3 Cuenta

El menú de usuario muestra “Settings” y “Log out”. El layout de settings muestra “Account”, “Client profile”, “Security” y “Appearance”.

Los datos del cliente se reparten entre:

- `/settings/profile`: identidad de `User`;
- `/clients/{client}`: datos personales de `Client`;
- `/settings/security`: seguridad;
- `/settings/appearance`: apariencia.

Esta feature debe ordenar y traducir esa navegación sin mover endpoints ni aparentar un guardado único entre recursos distintos.

### 4.4 Encabezados y breadcrumbs

`Heading` genera siempre un `h2`. Muchas vistas principales no tienen un `h1` visible. Los breadcrumbs ya existen en el shell, pero se declaran solo en algunas páginas y no siguen todavía una jerarquía uniforme.

## 5. Actores y reglas de acceso

### 5.1 Administrador

El rol `admin` recibe la navegación profesional. Dentro de ella, las capacidades continúan controladas por permisos, middleware y Policies existentes.

### 5.2 Cliente

El rol `client` recibe la navegación cliente y solo accede a recursos propios conforme a las Policies y relaciones de ownership vigentes.

### 5.3 Regla de selección

El rol determina la experiencia de navegación:

```text
admin  → panel profesional
client → panel cliente
```

Los permisos no eligen entre los dos paneles; limitan acciones dentro del panel correspondiente. El frontend refleja esta regla, pero no reemplaza la autorización backend.

Usuarios autenticados sin ninguno de los roles aprobados no deben recibir por descarte una experiencia cliente. Ese estado debe manejarse de forma explícita y segura según la política de acceso existente.

## 6. Alcance

La implementación de UX-01 incluye:

1. Navegación exacta por rol.
2. Grupos y rótulos del sidebar.
3. Vocabulario visible definitivo dentro del alcance afectado.
4. Menú de cuenta y navegación interna de Cuenta.
5. Sidebar colapsable en desktop y drawer en móvil.
6. Estado activo único y correcto para rutas principales y anidadas.
7. Componente base `PageHeader`.
8. Componente base de breadcrumbs y contrato para declararlos.
9. Aplicación inicial de `PageHeader` solo a vistas principales.
10. Traducción de textos del shell y navegación.
11. Accesibilidad y responsive de los componentes anteriores.
12. Pruebas y verificaciones correspondientes.

## 7. Fuera de alcance

UX-01 no incluye:

- datos, métricas ni consultas nuevas de dashboards;
- rediseño de tablas, tarjetas o formularios de dominio;
- búsqueda, filtros, paginación o endpoints nuevos;
- cambios de historia clínica;
- activación o rediseño de la verificación de correo;
- rediseño funcional de solicitudes, tratamientos o sesiones;
- landing, login, registro u otras pantallas de autenticación;
- cambios de base de datos, modelos o migraciones;
- nuevas rutas backend;
- listados globales de solicitudes o tratamientos;
- Agenda, profesionales, gestión de permisos o configuración general;
- eliminación de cuenta;
- cambio de roles, permisos, Policies u ownership.

## 8. Navegación definitiva por rol

### 8.1 Administrador

| Grupo            | Entrada                   | Destino actual            | Icono conceptual      |
| ---------------- | ------------------------- | ------------------------- | --------------------- |
| General          | Inicio                    | `/dashboard`              | Cuadrícula o inicio   |
| Pacientes        | Clientes                  | `/admin/clients`          | Personas              |
| Pacientes        | Pacientes                 | `/admin/pets`             | Huella                |
| Atención         | Solicitudes de atención   | `/admin/service-requests` | Portapapeles con alta |
| Catálogo clínico | Servicios clínicos        | `/admin/services`         | Estetoscopio          |
| Catálogo clínico | Procedimientos clínicos   | `/admin/procedures`       | Lista clínica         |
| Catálogo clínico | Plantillas de tratamiento | `/admin/treatments`       | Tratamiento o jeringa |

No se muestran:

- Tratamientos de pacientes como entrada global;
- Sesiones como entrada global;
- Agenda;
- Profesionales;
- Usuarios o permisos;
- Configuración general.

Historia clínica, solicitudes y tratamientos concretos permanecen contextualizados dentro del paciente o del flujo que los originó.

### 8.2 Cliente

| Grupo        | Entrada               | Destino actual | Icono conceptual    |
| ------------ | --------------------- | -------------- | ------------------- |
| General      | Inicio                | `/dashboard`   | Cuadrícula o inicio |
| Mis mascotas | Mis mascotas          | `/pets`        | Huella              |
| Atención     | Servicios disponibles | `/services`    | Estetoscopio        |

No se muestran entradas globales “Mis solicitudes” ni “Mis tratamientos”. Esos recursos se alcanzan desde Inicio mediante resúmenes futuros y, en detalle, desde cada mascota.

### 8.3 Cuenta

Cuenta no forma parte de los grupos de navegación principal. Se abre desde el usuario autenticado en el pie del sidebar.

#### Administrador

| Entrada visible | Destino                    |
| --------------- | -------------------------- |
| Cuenta          | `/settings/profile`        |
| Perfil          | `/settings/profile`        |
| Seguridad       | `/settings/security`       |
| Apariencia      | `/settings/appearance`     |
| Cerrar sesión   | acción de logout existente |

#### Cliente

| Entrada visible             | Destino                    |
| --------------------------- | -------------------------- |
| Cuenta                      | `/settings/profile`        |
| Mis datos: datos de acceso  | `/settings/profile`        |
| Mis datos: datos personales | `/clients/{client}`        |
| Seguridad                   | `/settings/security`       |
| Apariencia                  | `/settings/appearance`     |
| Cerrar sesión               | acción de logout existente |

“Mis datos” es el concepto visible que agrupa identidad y datos personales. Como UX-01 no crea ni mueve endpoints, el layout debe dejar claro que son dos secciones guardadas por separado. No debe presentar un único botón que sugiera persistencia atómica.

La eliminación de cuenta permanece oculta.

## 9. Vocabulario visible

| Nombre actual             | Nombre definitivo                          |
| ------------------------- | ------------------------------------------ |
| Dashboard                 | Inicio                                     |
| Platform                  | Se elimina; se usan grupos específicos     |
| Clients                   | Clientes                                   |
| Mascotas en admin         | Pacientes                                  |
| My pets                   | Mis mascotas                               |
| Services en cliente       | Servicios disponibles                      |
| Servicios en admin        | Servicios clínicos                         |
| Procedimientos            | Procedimientos clínicos                    |
| Tratamientos del catálogo | Plantillas de tratamiento                  |
| Solicitudes               | Solicitudes de atención                    |
| Settings                  | Cuenta                                     |
| Account                   | Perfil para admin / Mis datos para cliente |
| Client profile            | Datos personales                           |
| Security                  | Seguridad                                  |
| Appearance                | Apariencia                                 |
| Log out                   | Cerrar sesión                              |
| Repository                | Se elimina                                 |
| Documentation             | Se elimina                                 |

Esta feature traduce el shell, la navegación y los encabezados principales afectados. La traducción exhaustiva de formularios y detalles internos se realiza en las features de cada dominio.

## 10. Grupos y estructura del sidebar

### 10.1 Contrato de grupo

La navegación deja de ser un array plano y pasa a representar grupos con:

- rótulo visible;
- lista de entradas;
- orden estable;
- reglas de visibilidad por rol;
- regla explícita de estado activo por entrada.

No se crea una matriz de permisos en React. La información compartida de autenticación ya disponible determina el rol y las capacidades que corresponda reflejar.

### 10.2 Orden visual

Administrador:

```text
VetZen

GENERAL
  Inicio

PACIENTES
  Clientes
  Pacientes

ATENCIÓN
  Solicitudes de atención

CATÁLOGO CLÍNICO
  Servicios clínicos
  Procedimientos clínicos
  Plantillas de tratamiento

Usuario autenticado
```

Cliente:

```text
VetZen

GENERAL
  Inicio

MIS MASCOTAS
  Mis mascotas

ATENCIÓN
  Servicios disponibles

Usuario autenticado
```

Los rótulos de grupo se ocultan visualmente al colapsar el sidebar, pero sus entradas conservan tooltip y nombre accesible.

## 11. Estado activo

### 11.1 Principios

- Solo una entrada principal se marca activa.
- El estado activo no depende únicamente de igualdad exacta ni de un prefijo ambiguo.
- Query strings y fragmentos no afectan la selección.
- El indicador activo se conserva en altas, detalles, ediciones y recursos anidados.
- El estilo activo debe percibirse en tema claro y oscuro y no depender solo del color.

### 11.2 Familias de rutas admin

| Entrada activa            | Rutas incluidas                                                                                                     |
| ------------------------- | ------------------------------------------------------------------------------------------------------------------- |
| Inicio                    | `/dashboard` cuando el usuario es `admin`                                                                           |
| Clientes                  | `/admin/clients` y descendientes                                                                                    |
| Pacientes                 | `/admin/pets` y descendientes, incluidas historia clínica y tratamientos contextualizados                           |
| Solicitudes de atención   | `/admin/service-requests` y descendientes                                                                           |
| Servicios clínicos        | listado, alta, detalle y edición directa de `/admin/services`; excluye descendientes de procedimientos y plantillas |
| Procedimientos clínicos   | `/admin/procedures` y `/admin/services/{service}/procedures` con sus descendientes                                  |
| Plantillas de tratamiento | `/admin/treatments` y `/admin/services/{service}/treatments` con sus descendientes                                  |

La clasificación debe evaluar primero las familias más específicas de procedimientos y plantillas, y luego Servicios clínicos. Así se evita activar dos entradas por el prefijo `/admin/services`.

### 11.3 Familias de rutas cliente

| Entrada activa        | Rutas incluidas                                                                               |
| --------------------- | --------------------------------------------------------------------------------------------- |
| Inicio                | `/dashboard` cuando el usuario es `client`                                                    |
| Mis mascotas          | `/pets` y descendientes, incluidas historia clínica, solicitudes y tratamientos de la mascota |
| Servicios disponibles | `/services` y descendientes                                                                   |

### 11.4 Cuenta

Cuenta no marca una entrada del sidebar principal. Dentro de su layout:

- Perfil o datos de acceso se activa solo en `/settings/profile`;
- Datos personales se activa en `/clients/{client}` para el cliente autenticado;
- Seguridad se activa en `/settings/security`;
- Apariencia se activa en `/settings/appearance`.

El enlace activo expone `aria-current="page"` cuando representa la página actual.

## 12. Navegación responsive

### 12.1 Desktop

- El sidebar permanece visible a partir del breakpoint utilizado por el componente actual.
- Puede colapsarse a iconos sin perder acceso a ninguna entrada.
- El estado colapsado conserva logotipo identificable, tooltips, estado activo y menú de usuario.
- El contenido principal mantiene `min-width: 0` y no genera overflow horizontal por el shell.
- El estado abierto o cerrado puede conservar la preferencia existente sin introducir almacenamiento nuevo.

### 12.2 Móvil

- El sidebar se presenta como drawer mediante el componente existente.
- Un botón con nombre accesible “Abrir menú” controla su apertura.
- El drawer muestra los mismos grupos, orden y destinos que desktop.
- Seleccionar un destino cierra el drawer.
- Cerrar con Escape o con el control visible devuelve foco al disparador.
- El contenido detrás del drawer no recibe foco mientras está abierto.
- El menú de cuenta se abre sin quedar fuera del viewport.
- No se incorpora barra inferior de navegación en UX-01.

### 12.3 Anchos de validación

La implementación se verifica al menos a 320, 375, 390, 768 y 1280 px.

## 13. Componente `PageHeader`

### 13.1 Propósito

`PageHeader` establece una cabecera semántica y consistente para páginas principales. Reemplaza el uso de `Heading` como título principal, pero no elimina `Heading` cuando siga siendo útil para subsecciones.

### 13.2 Contrato mínimo

Debe aceptar:

- `title`: requerido y renderizado como `h1`;
- `description`: opcional;
- `actions`: opcional, para la acción primaria y, como máximo, acciones secundarias justificadas;
- clases o composición mínima consistente con las convenciones actuales, solo si se necesita.

Los breadcrumbs no se duplican dentro de `PageHeader`: permanecen en `AppSidebarHeader`, por encima del contenido. El encabezado puede convivir con un enlace contextual de regreso en futuras features, pero UX-01 no diseña todavía cabeceras de paciente, servicio o tratamiento.

### 13.3 Comportamiento

- Desktop: título y descripción a la izquierda; acciones alineadas a la derecha.
- Móvil: contenido y acciones se apilan; la acción primaria ocupa el ancho necesario, no obliga a overflow.
- Existe una sola `h1` visible por página.
- La descripción no repite el título ni depende de texto genérico.
- Los botones conservan el componente y variantes existentes.

### 13.4 Vistas principales incluidas

UX-01 aplica `PageHeader` únicamente a:

Administrador:

- `resources/js/pages/admin/dashboard.tsx`;
- `resources/js/pages/admin/clients/index.tsx`;
- `resources/js/pages/admin/pets/index.tsx`;
- `resources/js/pages/admin/service-requests/index.tsx`;
- `resources/js/pages/admin/services/index.tsx`;
- `resources/js/pages/admin/procedures/index.tsx`;
- `resources/js/pages/admin/treatments/index.tsx`.

Cliente:

- `resources/js/pages/client/dashboard.tsx`;
- `resources/js/pages/pets/index.tsx`;
- `resources/js/pages/services/index.tsx`.

Cuenta:

- la cabecera general renderizada por `resources/js/layouts/settings/layout.tsx`.

No se aplica todavía a altas, ediciones, detalles, historia clínica, solicitudes contextualizadas, tratamientos ni sesiones. Esas pantallas requieren contexto adicional y se abordan en features posteriores.

Los títulos de dashboard cambian a “Inicio”, pero su contenido y props permanecen sin cambios en UX-01.

## 14. Breadcrumbs base

### 14.1 Contrato

Los breadcrumbs continúan recibiéndose mediante `AppLayout` y se renderizan una sola vez en `AppSidebarHeader`.

Cada item contiene:

- título visible en español;
- destino generado por Wayfinder para items navegables;
- posición dentro de la jerarquía.

El último item representa la página actual, no es enlace y expone semántica de página actual. Los anteriores son enlaces Inertia.

### 14.2 Vistas principales

| Pantalla             | Breadcrumb                           |
| -------------------- | ------------------------------------ |
| Inicio               | `Inicio`                             |
| Clientes             | `Inicio / Clientes`                  |
| Pacientes admin      | `Inicio / Pacientes`                 |
| Solicitudes admin    | `Inicio / Solicitudes de atención`   |
| Servicios admin      | `Inicio / Servicios clínicos`        |
| Procedimientos admin | `Inicio / Procedimientos clínicos`   |
| Plantillas admin     | `Inicio / Plantillas de tratamiento` |
| Mis mascotas         | `Inicio / Mis mascotas`              |
| Servicios cliente    | `Inicio / Servicios disponibles`     |
| Cuenta               | `Inicio / Cuenta`                    |

UX-01 no completa todavía breadcrumbs de rutas profundas. Define el contrato para que las features posteriores incorporen paciente, servicio, solicitud o tratamiento sin volver a modificar el shell.

### 14.3 Responsive

- En desktop se muestran los items que quepan sin desplazar acciones o provocar overflow.
- En móvil puede mostrarse solo el padre inmediato y la página actual, o una forma abreviada accesible.
- Los títulos truncados conservan el nombre completo mediante texto accesible o tooltip cuando corresponda.
- El componente usa una etiqueta de navegación accesible equivalente a “Migas de pan”.

## 15. Componentes y archivos afectados

### 15.1 Componentes principales

| Archivo probable                                 | Cambio esperado                                                                |
| ------------------------------------------------ | ------------------------------------------------------------------------------ |
| `resources/js/components/app-sidebar.tsx`        | Definir navegación exacta y grupos por rol; retirar footer starter             |
| `resources/js/components/nav-main.tsx`           | Renderizar grupos y estado activo explícito                                    |
| `resources/js/components/nav-footer.tsx`         | Dejar de usarlo para Repository/Documentation; eliminar solo si queda sin usos |
| `resources/js/components/nav-user.tsx`           | Mantener disparador accesible y comportamiento responsive                      |
| `resources/js/components/user-menu-content.tsx`  | Traducir Cuenta y Cerrar sesión                                                |
| `resources/js/layouts/settings/layout.tsx`       | Navegación de Cuenta por rol y copy en español                                 |
| `resources/js/hooks/use-current-url.ts`          | Extender matching solo si el contrato activo no puede resolverse localmente    |
| `resources/js/types/navigation.ts`               | Representar grupos y reglas activas si resulta necesario                       |
| `resources/js/components/page-header.tsx`        | Nuevo componente base                                                          |
| `resources/js/components/heading.tsx`            | Conservar para subtítulos; evitar su uso como `h1`                             |
| `resources/js/components/breadcrumbs.tsx`        | Semántica, labels y responsive base                                            |
| `resources/js/components/app-sidebar-header.tsx` | Integración responsive de breadcrumbs y trigger                                |
| `app/Http/Controllers/DashboardController.php`   | Alinear selección de experiencia por rol sin agregar props ni datos            |

### 15.2 Vistas principales

Solo se modifican las vistas enumeradas en 13.4 para adoptar `PageHeader`, títulos definitivos y breadcrumbs base. No se altera la estructura de tablas, formularios, cards ni props.

### 15.3 Rutas y backend

No se modifican `routes/web.php`, `routes/settings.php`, Form Requests, Policies, modelos ni migraciones. La única modificación backend admisible es alinear `DashboardController` para seleccionar la experiencia por rol, sin agregar consultas, props ni contenido de dashboard. Wayfinder continúa generando los destinos tipados desde las rutas existentes.

## 16. Accesibilidad

La implementación debe cumplir:

1. Una sola `h1` visible en cada vista principal incluida.
2. Rótulos de grupo comprensibles y no interactivos.
3. Enlaces con texto accesible aun cuando el sidebar esté colapsado.
4. Tooltips para iconos colapsados, sin usarlos como único nombre accesible.
5. `aria-current="page"` en el destino actual.
6. Botón del menú móvil con nombre y estado expandido comunicables.
7. Navegación completa por teclado.
8. Focus visible en tema claro y oscuro.
9. Apertura, cierre y restitución de foco correctos en drawer y menú de cuenta.
10. Breadcrumbs dentro de una región `nav` con nombre accesible.
11. Último breadcrumb marcado como página actual.
12. Ningún estado activo comunicado solo por color.
13. Objetivos táctiles de al menos 44 por 44 px en móvil.
14. Respeto por zoom de 200 % sin pérdida de navegación.

Objetivo recomendado: WCAG 2.2 nivel AA.

## 17. Estados y comportamiento

Esta feature no incorpora estados de datos. Los estados propios del shell son:

| Estado            | Comportamiento                                             |
| ----------------- | ---------------------------------------------------------- |
| Sidebar expandido | Muestra icono, texto y grupos                              |
| Sidebar colapsado | Muestra iconos, tooltips y activo; oculta rótulos visuales |
| Drawer cerrado    | Contenido principal operable                               |
| Drawer abierto    | Foco contenido y fondo no operable                         |
| Ruta activa       | Una entrada marcada y anunciada                            |
| Cuenta abierta    | Menú dentro del viewport, navegación por teclado           |
| Sin rol aprobado  | No adopta automáticamente navegación cliente               |

El prefetch de enlaces puede conservarse donde ya se usa, siempre que no altere autorización ni cargue información sensible antes de una navegación autorizada.

## 18. Riesgos y mitigaciones

| Riesgo                                      | Mitigación                                                                |
| ------------------------------------------- | ------------------------------------------------------------------------- |
| Activar Servicios y Procedimientos a la vez | Evaluar primero familias anidadas específicas                             |
| Usar permisos para escoger panel            | Elegir navegación por rol y capacidades por permiso                       |
| Duplicar `h1` entre layout y página         | `PageHeader` es el único título principal; `Heading` queda para secciones |
| Romper rutas tipadas                        | Usar funciones Wayfinder; no hardcodear destinos en implementación        |
| Mezclar Cuenta y configuración general      | Limitar Cuenta al usuario autenticado                                     |
| Aparentar guardado único de User y Client   | Mostrar secciones y acciones separadas                                    |
| Overflow por nombres largos                 | Permitir truncado accesible y apilado responsive                          |
| Regresión de sidebar móvil                  | Probar cierre, Escape, focus y anchos mínimos                             |
| Crear abstracción excesiva                  | Extender tipos y helpers solo lo necesario para las rutas actuales        |
| Ocultar capacidad sin seguridad backend     | Mantener middleware, Policies y pruebas HTTP existentes                   |

## 19. Plan de implementación

### Paso 1. Contrato de navegación

- Representar grupos por rol con la mínima extensión necesaria de tipos.
- Definir familias activas explícitas.
- Mantener destinos Wayfinder.
- Alinear `DashboardController` para que el rol seleccione la experiencia, sin incorporar datos nuevos.

Resultado: estructura estable sin cambios visuales de dominio.

### Paso 2. Sidebar y menú de cuenta

- Reorganizar grupos y orden.
- Aplicar vocabulario definitivo.
- Retirar enlaces del starter kit.
- Traducir y ordenar Cuenta.

Resultado: desktop y móvil ofrecen los mismos destinos autorizados.

### Paso 3. Estado activo

- Implementar matching semántico para rutas exactas y anidadas.
- Evitar doble activación en rutas bajo `/admin/services`.
- Añadir `aria-current`.

Resultado: una sola entrada permanece activa en cada ruta cubierta.

### Paso 4. `PageHeader`

- Crear componente con título, descripción y acciones.
- Mantener `Heading` para subsecciones.
- Aplicarlo únicamente a las vistas principales de 13.4.

Resultado: cada vista principal tiene una `h1` y acciones responsive sin modificar su contenido.

### Paso 5. Breadcrumbs base

- Uniformar títulos y jerarquía de vistas principales.
- Mejorar semántica y comportamiento móvil.
- Conservar integración única en el shell.

Resultado: navegación de regreso consistente y preparada para contextos profundos futuros.

### Paso 6. Verificación

- Ejecutar formato, ESLint, TypeScript y build frontend.
- Ejecutar las pruebas de autenticación/autorización afectadas si algún cambio toca selección de experiencia.
- Realizar matriz manual desktop/móvil, teclado y foco.

Resultado: la base queda lista para las siguientes features UX.

## 20. Pruebas y validación

### 20.1 Automatizadas

La implementación debe verificar, con la infraestructura existente y sin introducir una librería de tests frontend solo para esta feature:

- TypeScript acepta los grupos y reglas de estado activo.
- ESLint y Prettier pasan.
- Vite build completa.
- Las pruebas HTTP existentes confirman que `client` no accede a rutas `/admin`.
- Las pruebas de dashboard cubren que el rol selecciona la experiencia correcta cuando se implemente la alineación necesaria.

Si se incorpora una función pura para clasificar familias de rutas y existe infraestructura apropiada, debe probar al menos:

- ruta exacta;
- alta, detalle y edición;
- procedimiento contextual bajo servicio;
- plantilla contextual bajo servicio;
- tratamiento contextual bajo paciente;
- query string ignorada;
- ninguna doble activación.

### 20.2 Manuales

Se recorre cada destino como `admin` y `client` a 320, 375, 390, 768 y 1280 px. Se comprueba:

- orden y copy;
- ausencia de módulos fuera de alcance;
- estado activo en rutas anidadas;
- sidebar expandido y colapsado;
- drawer, Escape y cierre al navegar;
- menú de cuenta dentro del viewport;
- navegación por teclado y focus visible;
- una sola `h1` por vista principal;
- breadcrumbs sin overflow;
- temas claro y oscuro.

## 21. Criterios de aceptación

- [x] F09 permanece reservado y la feature usa el identificador UX-01.
- [x] `admin` y `client` reciben la navegación exacta definida para su rol.
- [x] Los permisos controlan capacidades internas y no seleccionan el panel por sí solos.
- [x] El sidebar presenta los grupos y el orden aprobados.
- [x] No se muestran Agenda, profesionales, permisos, configuración general ni listados globales de tratamientos.
- [x] No se muestran “Mis solicitudes” ni “Mis tratamientos” como entradas cliente.
- [x] El shell y Cuenta usan el vocabulario en español definido en esta feature.
- [x] Repository, Documentation y Platform desaparecen de la interfaz.
- [x] Cuenta diferencia datos de acceso y datos personales sin aparentar guardado atómico.
- [x] La eliminación de cuenta permanece oculta.
- [x] Desktop colapsado y móvil conservan todos los destinos y nombres accesibles.
- [x] Solo una entrada está activa en cada ruta principal o anidada cubierta.
- [x] Procedimientos y plantillas contextuales no activan simultáneamente Servicios clínicos.
- [x] `PageHeader` renderiza la única `h1` de cada vista principal incluida.
- [x] `PageHeader` no se aplica todavía a vistas profundas o de dominio fuera de alcance.
- [x] Los breadcrumbs base están en español, usan enlaces Inertia/Wayfinder y marcan la página actual.
- [ ] La navegación funciona con teclado, focus visible, zoom y temas claro/oscuro.
- [x] No se agregan endpoints, queries, datos, filtros, migraciones ni reglas de negocio.
- [x] Formato, lint, tipos, build y pruebas afectadas pasan antes de cerrar la implementación.

## 22. Definición de terminado

UX-01 queda terminada cuando navegación, shell, Cuenta, estado activo, `PageHeader` y breadcrumbs base cumplen los criterios anteriores en desktop y móvil, sin introducir cambios de dominio o backend fuera de alcance y con las verificaciones ejecutadas correctamente.

## 23. Contradicciones y aclaraciones detectadas

1. El prompt de origen referencia `docs/ux`, pero la ruta real del repositorio es `doc/ux`. Esta especificación usa la ruta real.
2. F09 no tiene archivo individual, pero `features.md` reserva conceptualmente la siguiente área funcional para Planes de seguimiento. Por eso se usa `UX-01` y no F09.
3. El sidebar actual decide por rol, mientras `DashboardController` decide por el permiso `clients.viewAny`. La decisión definitiva exige usar rol para seleccionar experiencia y permisos para capacidades internas. UX-01 documenta y debe corregir esa alineación sin cambiar permisos.
4. La arquitectura objetivo denomina “Mis datos” a la cuenta cliente, pero actualmente `User` y `Client` se editan en rutas separadas y una de ellas está fuera de `/settings`. UX-01 conserva ambas rutas, las presenta como secciones diferenciadas y no crea un endpoint combinado.
