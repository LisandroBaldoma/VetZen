# UX-10 - Migración de navegación global

> Estado: implementada y verificada automáticamente. Permanece pendiente la
> revisión manual visual, responsive y de teclado en navegador.

## 1. Objetivo

Consolidar la navegación global autenticada de VetZen sin modificar rutas,
datos, roles, permisos, ownership ni comportamiento de dominio.

UX-10 conserva UX-09 como el plan histórico de foundations y AppShell. Esta
feature documenta la composición posterior del shell que integra la dirección
visual disponible en `resources/views/home_stitch.blade.php`.

## 2. Alcance

- `AppTopbar` es el único encabezado global autenticado.
- `AppSidebar` contiene los destinos de navegación definidos por rol y la marca
  cuando está expandido.
- `AppBottomNav` ofrece destinos móviles por rol; la acción "Más" controla el
  mismo sidebar móvil.
- La navegación conserva `getNavigationGroups()`, Wayfinder y la clasificación
  existente de rutas activas.
- El logo se sirve desde `public/logo.png` mediante una URL generada por Laravel
  para que funcione cuando la aplicación se aloja en un subdirectorio.

## 3. Comportamiento del shell

### 3.1 Topbar

- Incluye el trigger del sidebar, breadcrumbs y el menú de cuenta.
- Muestra el logo compacto cuando el sidebar desktop está colapsado y en móvil.
- No antepone el nombre de la aplicación a los breadcrumbs. La jerarquía se
  compone exclusivamente con los items que declara cada página, comenzando por
  `Inicio` cuando corresponda.

### 3.2 Sidebar

- En desktop expandido muestra el bloque de marca con logo, nombre y subtítulo.
- En desktop colapsado oculta ese bloque y conserva los destinos accesibles.
- En móvil se abre como drawer y muestra el bloque de marca completo.
- No duplica el menú de usuario ni el logo iconográfico anterior.
- El cierre visible nativo del drawer permanece disponible.

### 3.3 Navegación móvil

- `AppBottomNav` no crea una segunda fuente de destinos: usa la navegación por
  rol existente.
- "Más" abre y cierra el drawer del mismo `SidebarProvider` que controla el
  trigger de `AppTopbar`.

## 4. Asset de marca

- `public/logo.png` es el único archivo de imagen utilizado por `AppLogo`.
- `HandleInertiaRequests` comparte `logoUrl` con `asset('logo.png')`.
- `AppLogo` recibe `name` y `logoUrl` desde las props compartidas de Inertia.
- `name` proviene de `config('app.name')`; se usa como texto de marca y atributo
  alternativo, no como breadcrumb.

## 5. Fuera de alcance

- Rutas, controladores de dominio, Policies, Form Requests, modelos,
  migraciones, roles, permisos y ownership.
- Nuevos destinos, notificaciones, búsqueda, filtros, datos de dashboard o
  módulos futuros.
- Cambios a login, registro, autenticación o settings fuera del menú de cuenta
  existente.
- La maqueta de trabajo `resources/views/home_logo_stitch.blade.php`; no forma
  parte de esta feature ni del código de producto.

## 6. Criterios de aceptación

- [x] Los destinos por rol y sus rutas Wayfinder se conservan.
- [x] El trigger superior y "Más" controlan el mismo sidebar.
- [x] El sidebar expandido muestra una única marca completa.
- [x] El topbar no muestra la marca completa mientras el sidebar desktop está
  expandido.
- [x] Los breadcrumbs no incluyen el prefijo fijo `VetZen`.
- [x] El logo resuelve mediante `asset('logo.png')` y funciona con
  `APP_URL=http://localhost/vet_zen/public`.
- [x] Formato, lint, tipos, build, Pint y la suite PHP pasan.
- [ ] Revisión manual en 320, 375, 390, 768 y 1280 px para admin y client.
- [ ] Revisión manual de foco, Escape, retorno de foco, drawer, breadcrumbs
  largos y zoom al 200 %.

## 7. Verificación automática

Se ejecutaron correctamente:

```text
npm run format:check
npm run lint:check
npm run types:check
npm run build
vendor/bin/pint --dirty --test
php artisan test
```

La suite PHP completó 150 tests y 1236 assertions.

## 8. Decisiones pendientes

No existen decisiones pendientes para la implementación actual. La validación
manual indicada en los criterios de aceptación debe completarse antes de
considerar la experiencia visual definitivamente cerrada.
