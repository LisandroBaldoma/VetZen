# UX-09: Clínica Cálida Foundations and AppShell

## Estado

Plan aprobado para iniciar por etapas. La implementación se realiza únicamente
en la rama `Feature/UX-DASHBOARD-redesign`. Cada etapa termina con
verificaciones y un commit manual del usuario antes de iniciar la siguiente.

## Objetivo

Construir la base visual Mobile First de VetZen sin reiniciar el frontend ni
alterar rutas, datos, ownership, autorización o comportamiento de dominio.

La feature conserva la arquitectura actual:

```text
UI Primitives
  ↓
Shared Patterns
  ↓
Domain Components
  ↓
Pages
```

UX-09 cubre únicamente UI Primitives y el AppShell, que es el primer Shared
Pattern. Las páginas de dominio se migran en features posteriores.

## Decisiones vigentes

- Identificador: `UX-09`.
- Nombre de especificación futuro: `UX-09-clinica-calida-foundations-and-app-shell.md`.
- Referencia mobile inicial: 390 px.
- Estrategia: mobile, tablet, desktop; no desktop-first.
- Fuente global: Plus Jakarta Sans.
- Fondo dominante: `#FDFAF3`, blanco y neutros cálidos.
- Marca primaria: `#478F49`.
- Marca secundaria: `#80AB4B`.
- Marca de apoyo: `#B2BA44`.
- Énfasis semántico: terracota `#DA5F42`, naranja `#EA9640`, ocre `#E8AD46`.
- Los colores de marca no se aplican como decoración repetitiva ni como fondo de
  todas las cards.
- Se preservan los tokens Tailwind actuales como aliases durante la migración.
- Se preservan APIs y comportamiento de primitives existentes siempre que sea
  posible.

## Referencias visuales Stitch

Las referencias Stitch aprobadas son una fuente de dirección visual junto con:

1. `features/`, que define comportamiento y alcance funcional;
2. `docs/ui-audit/`, que describe arquitectura y estado actual;
3. el código actual, que confirma la implementación real.

Stitch permite extraer jerarquía, densidad, spacing, proporciones, superficies,
borders, radios, tipografía, tratamiento de acciones, navegación, formularios,
primitives y patrones compartidos; no se copia una pantalla mediante JSX o
clases Tailwind específicas.

Stitch no define campos, rutas, estados de negocio, permisos, roles, datos,
acciones ni módulos. Cuando una referencia utiliza contenido ficticio, prevalece
el contrato funcional y el código de VetZen.

### Disponibilidad obligatoria

Antes de iniciar una etapa que requiera decisiones visuales, se debe indicar y
verificar el directorio exacto de las referencias Stitch aplicables. Si no están
disponibles, se detiene la interpretación visual y no se inventan decisiones de
jerarquía, spacing, superficies, tipografía o responsive.

La referencia aprobada para UX-09.1 está disponible en
`resources/views/home_stitch.blade.php`. Confirma un canvas crema, superficies
blancas, bordes cálidos sutiles, texto verde-carbón, radio moderado, densidad
compacta, acciones verdes y objetivos táctiles de 44 px. Estas decisiones se
extraen como tokens y primitives, no como clases copiadas por página.

La barra de navegación inferior, el acceso a notificaciones, los nombres de
secciones y los datos de demostración de esa referencia no se adoptan: la barra
inferior contradice la decisión explícita de UX-01, y las demás piezas no tienen
soporte funcional actual. La navegación, roles, rutas y acciones siguen estando
definidos por `features/` y el código de VetZen.

## Límites funcionales

No se modifica en UX-09:

- backend Laravel, rutas, controladores, Policies, Form Requests, modelos o base de datos;
- navegación funcional, roles, permisos, ownership o enlaces Wayfinder;
- páginas de dominio, tablas, cards de dominio, filtros o paginación;
- flujos de solicitudes, tratamientos, sesiones o historia clínica;
- landing, autenticación o settings;
- confirmaciones de dominio, estados de dominio o copy completo de la aplicación;
- módulos futuros: agenda, turnos, notificaciones, IA, profesionales o permisos.

## Etapas y checkpoints

### UX-09.1 Foundations

**Propósito:** definir el sistema de tokens Clínica Cálida y cargar Plus Jakarta
Sans sin modificar composición de páginas o APIs de componentes.

**Archivos previstos:**

- `resources/css/app.css`
- `vite.config.ts`

**Implementación:**

1. Sustituir Instrument Sans por Plus Jakarta Sans mediante el plugin de fuentes
   existente de Vite.
2. Definir valores de marca como tokens base.
3. Definir aliases semánticos en tema claro y oscuro para:
   - canvas, superficies base, elevadas, sutiles y hundidas;
   - texto fuerte, estándar, secundario, tenue e inverso;
   - bordes sutiles, estándar y enfáticos;
   - acción primaria, hover, active y foreground;
   - foco, disabled, destructive, success, warning e info;
   - superficies clínicas, operativas y sidebar.
4. Mapear los tokens necesarios con `@theme` de Tailwind v4.
5. Mantener `background`, `foreground`, `card`, `primary`, `secondary`,
   `accent`, `border`, `input`, `ring`, `sidebar` y colores semánticos actuales
   como aliases compatibles.
6. Formalizar escalas base de tipografía, radio, control y espacio sin cambiar
   todavía clases de páginas.

**Criterios de aceptación:**

- Plus Jakarta Sans se carga correctamente en build.
- Claro y oscuro definen todos los aliases semánticos necesarios.
- Las utilities existentes siguen resolviendo los tokens actuales.
- No se modifica JSX, backend o flujo funcional.

**Verificación:**

```text
npm run format:check
npm run lint:check
npm run types:check
npm run build
```

**Commit manual requerido al cerrar:**

```text
feat(ui): add clinica calida design tokens
```

### UX-09.2 Core Primitives

**Propósito:** aplicar los tokens semánticos a primitives existentes, sin añadir
componentes de dominio ni cambiar sus APIs públicas.

**Archivos previstos:**

- `resources/js/components/ui/button.tsx`
- `resources/js/components/ui/input.tsx`
- `resources/js/components/ui/select.tsx`
- `resources/js/components/ui/checkbox.tsx`
- `resources/js/components/ui/card.tsx`
- `resources/js/components/ui/badge.tsx`
- `resources/js/components/ui/alert.tsx`
- `resources/js/components/ui/dialog.tsx`
- `resources/js/components/ui/sheet.tsx`
- `resources/js/components/ui/label.tsx` si requiere el nuevo sistema de texto.

**Implementación:**

1. Reasignar superficies, bordes, foco, hover, active, disabled e invalid a
   tokens semánticos.
2. Mantener los variants existentes de `Button` y `Badge`.
3. Garantizar targets de 44 px para icon actions en móvil sin forzar controles
   desktop innecesariamente grandes.
4. Localizar labels propios de primitives, por ejemplo controles de cierre de
   Dialog y Sheet.
5. No convertir `Badge` en un sistema genérico de estados clínicos.

**Commit manual requerido al cerrar:**

```text
feat(ui): refine core primitives for clinica calida
```

### UX-09.3 AppShell Mobile First

**Propósito:** refinar el shell autenticado existente sin modificar su contrato
de navegación por rol.

**Archivos previstos:**

- `resources/js/components/ui/sidebar.tsx`
- `resources/js/components/app-shell.tsx`
- `resources/js/components/app-content.tsx`
- `resources/js/components/app-sidebar.tsx`
- `resources/js/components/app-sidebar-header.tsx`
- `resources/js/components/nav-main.tsx`
- `resources/js/components/nav-user.tsx`
- `resources/js/components/app-logo.tsx`
- `resources/js/components/breadcrumbs.tsx`
- `resources/js/layouts/app/app-sidebar-layout.tsx`

**Implementación:**

1. Aplicar tokens al canvas, sidebar, header, navegación activa, hover y foco.
2. Mantener Wayfinder, clasificación de rutas, cookie de estado, drawer y cierre
   al navegar.
3. Consolidar el header móvil: trigger, contexto y breadcrumbs truncados sin
   duplicar el `h1` de las páginas.
4. Verificar target táctil, safe areas, foco, Escape, cierre visible y retorno de
   foco del drawer.
5. Mantener workspaces operativos, clínicos y de lectura como responsabilidad de
   las páginas.

**Commit manual requerido al cerrar:**

```text
feat(ui): redesign mobile-first app shell
```

### UX-09.4 QA and hardening

**Propósito:** resolver exclusivamente regresiones detectadas durante la
verificación de foundations, primitives y shell.

**Incluye:** correcciones de contraste, focus, responsive, temas, overflow,
tipografía o accesibilidad dentro de archivos ya afectados por UX-09.

**No incluye:** migración de páginas o patrones de dominio nuevos.

**Commit manual requerido al cerrar:**

```text
test(ui): verify clinica calida foundations
```

## Reglas Mobile First

| Rango | Reglas |
|---|---|
| 320-767 px | Una columna; drawer para navegación; targets de 44 px; acciones apiladas o wrap; scroll natural; sin hover obligatorio. |
| 768-1023 px | Sidebar persistente; contenido `min-w-0`; controles aún aptos para touch. |
| 1024-1279 px | Mayor densidad solo donde el layout ya la permite; sidebar colapsable con tooltip y foco. |
| 1280 px+ | Workspaces con ancho máximo; contenido clínico no se estira sin límite. |

## Riesgos y mitigaciones

| Riesgo | Mitigación |
|---|---|
| Regresión de tema oscuro | Definir y revisar cada token en `:root` y `.dark`. |
| Rotura de utilities existentes | Mantener tokens actuales como aliases compatibles. |
| Contraste insuficiente | Revisar texto, foco, acciones y estados en claro/oscuro antes del commit. |
| Drawer inaccesible | Probar trigger, Escape, focus trap, foco de retorno y control de cierre visible. |
| Scope creep | No tocar páginas, backend ni patrones de dominio durante UX-09. |
| Diseño inventado | Usar solo paleta, fuente y dirección aprobadas; no inferir detalles de referencias no presentes en el repositorio. |

## QA por etapa

Después de cada etapa se ejecuta:

```text
npm run format:check
npm run lint:check
npm run types:check
npm run build
```

Para UX-09.3 y UX-09.4 se revisa manualmente admin y client en 320, 375, 390,
768 y 1280 px, en temas claro, oscuro y sistema. También se prueba teclado,
zoom 200 %, foco, drawer, breadcrumbs largos y ausencia de overflow horizontal.

## Definition of Done de UX-09

- Plus Jakarta Sans y tokens Clínica Cálida están implementados en claro y oscuro.
- Primitives base y AppShell usan tokens semánticos sin cambiar contratos.
- Navegación por rol y Wayfinder se conservan.
- El drawer móvil es usable con touch y teclado.
- No hay cambios de backend ni de comportamiento de dominio.
- Las verificaciones técnicas y manuales están ejecutadas y documentadas.
- Cada etapa fue confirmada mediante el commit manual correspondiente.

## Siguiente feature

Después de UX-09, proponer `UX-10-shared-responsive-patterns.md` para
`EmptyState`, `FormSection`, `FormActions`, `ConfirmActionDialog`, `FilterBar`,
`DataTablePagination` y `ResponsiveDataList`. No se inicia hasta cerrar y
confirmar UX-09.
