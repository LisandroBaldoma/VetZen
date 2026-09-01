# UX-02 - Dashboards por rol

> Estado: especificada, pendiente de implementación.

## 1. Objetivo

Transformar `/dashboard` en un Inicio útil y diferente para los roles actuales `admin` y `client`, reutilizando los fundamentos de UX-01 y mostrando únicamente información accionable que el backend ya puede obtener de forma autorizada.

El dashboard administrativo debe priorizar solicitudes de atención y accesos frecuentes. El dashboard cliente debe reemplazar el formulario de perfil por resúmenes de sus mascotas, solicitudes pendientes y tratamientos activos.

## 2. Fuentes y dependencias

Esta feature se apoya en:

- `AGENTS.md`.
- `spec.md`, `technical.md` y `features.md`.
- `doc/ux/current-panel-audit.md`.
- `doc/ux/panel-redesign-spec.md`.
- `features/UX-01-panel-foundations-navigation.md` y su implementación.
- Feature 05 para mascotas y ownership `User → Client → Pet`.
- Feature 08 para solicitudes, tratamientos asignados, sesiones y progreso.
- React, Inertia, Wayfinder, Tailwind CSS y PHPUnit existentes.

La ruta real de la documentación UX es `doc/ux`, no `docs/ux`.

## 3. Situación actual

### 3.1 Selección por rol

`DashboardController` ya aplica la decisión de UX-01:

```text
admin  → admin/dashboard
client → client/dashboard
otro   → 403
```

El rol selecciona la experiencia. Los permisos continúan controlando capacidades internas y las Policies protegen recursos concretos.

### 3.2 Administrador

`resources/js/pages/admin/dashboard.tsx` solo muestra `PageHeader`. No recibe datos operativos ni ofrece accesos rápidos.

### 3.3 Cliente

`resources/js/pages/client/dashboard.tsx` todavía contiene el formulario de perfil completo. Duplica Cuenta y no resume mascotas, solicitudes ni tratamientos.

### 3.4 Infraestructura disponible

UX-01 ya aporta:

- navegación por rol;
- `PageHeader`;
- breadcrumbs de Inicio;
- shell responsive;
- vocabulario base en español;
- selección segura del dashboard por rol.

No existe un componente compartido `EmptyState`; UX-02 puede mantener estados vacíos locales y extraer uno solo si aparece duplicación concreta suficiente.

## 4. Actores

### 4.1 Administrador

Consulta una síntesis global de solicitudes y navega hacia tareas administrativas existentes.

### 4.2 Cliente

Consulta exclusivamente información derivada de su propio `Client` y de sus mascotas. No puede acceder a recursos de otro cliente aunque conozca IDs o URLs.

## 5. Alcance

UX-02 incluye:

1. Datos operativos mínimos para Inicio admin.
2. Datos propios resumidos para Inicio cliente.
3. Eliminación del formulario de perfil del dashboard cliente.
4. Accesos rápidos hacia rutas existentes.
5. Enlaces contextuales hacia paciente, mascota, solicitud y tratamiento.
6. Estados vacíos específicos por bloque.
7. Presentación de fechas en español.
8. Presentación comprensible del progreso de tratamientos.
9. Diseño responsive y accesible.
10. Consultas acotadas, campos explícitos, eager loading y límites.
11. Pruebas HTTP/Inertia de rol, props, ownership y estados vacíos.

## 6. Fuera de alcance

UX-02 no incluye:

- agenda, turnos o calendario;
- próximas sesiones globales;
- estadísticas históricas, tendencias o gráficos;
- notificaciones;
- historia clínica;
- rediseño interno de solicitudes, tratamientos o sesiones;
- landing o autenticación;
- verificación de correo;
- páginas globales nuevas de solicitudes o tratamientos;
- rutas backend nuevas;
- cambios de base de datos, modelos, migraciones o Policies;
- filtros, búsqueda o paginación de dashboard;
- edición de perfil desde Inicio;
- precios, facturación o pagos;
- manejo parcial de errores mediante excepciones silenciadas.

## 7. Reglas de negocio y seguridad

1. El rol determina el componente Inertia del dashboard.
2. Un usuario sin rol aprobado recibe `403`.
3. Las consultas admin no se reutilizan para construir props cliente.
4. El cliente se resuelve desde `$request->user()->client`.
5. El ownership cliente se aplica en SQL antes de ordenar o limitar resultados.
6. Nunca se confía en `client_id`, `pet_id` ni otro identificador enviado por frontend para establecer ownership.
7. No se serializan modelos completos cuando una transformación explícita puede limitar campos.
8. Las consultas cargan relaciones necesarias de forma anticipada para evitar N+1.
9. Un cliente sin `Client` asociado o sin recursos recibe colecciones vacías, no un error de renderizado.
10. Los nombres enlazan al recurso autorizado mediante Wayfinder.
11. El dashboard no sustituye Policies ni middleware de las rutas de destino.
12. No se exponen notas, precios, datos clínicos, rutas internas de archivos ni campos administrativos innecesarios.

## 8. Dashboard administrativo

### 8.1 Propósito

Permitir que admin identifique solicitudes que requieren atención y acceda rápidamente a pacientes, solicitudes y catálogo clínico.

### 8.2 Información

Debe mostrar:

- cantidad total de solicitudes `pending`;
- hasta cinco solicitudes, priorizando pendientes y completando con solicitudes recientes;
- paciente;
- servicio solicitado;
- fecha de creación;
- estado localizado;
- enlace al detalle de solicitud;
- enlace al paciente.

No muestra notas del cliente en el resumen, tratamientos resultantes, sesiones, precios ni datos del responsable.

### 8.3 Accesos rápidos

| Acción                 | Ruta existente            |
| ---------------------- | ------------------------- |
| Nuevo paciente         | `/admin/pets/create`      |
| Ver pacientes          | `/admin/pets`             |
| Ver solicitudes        | `/admin/service-requests` |
| Abrir catálogo clínico | `/admin/services`         |

“Abrir catálogo clínico” usa Servicios clínicos como entrada existente al catálogo. No se crea una landing adicional.

### 8.4 Estado vacío

Si no hay solicitudes para mostrar:

- el conteo indica `0`;
- el bloque informa “Todavía no hay solicitudes de atención”;
- mantiene visibles los accesos rápidos;
- no muestra un gráfico, ilustración decorativa ni error.

### 8.5 Wireframe

```text
┌────────────────────────────────────────────────────────┐
│ Inicio                                                 │
│ Lo importante para continuar la atención               │
├──────────────────────┬─────────────────────────────────┤
│ Solicitudes          │ Accesos rápidos                 │
│ 4 pendientes         │ Nuevo paciente                  │
│                      │ Ver pacientes                   │
│                      │ Ver solicitudes                 │
│                      │ Abrir catálogo clínico          │
├──────────────────────┴─────────────────────────────────┤
│ Solicitudes de atención                                │
│ Mora → · Fisioterapia · 01 sep 2026 · Pendiente  [Ver]│
└────────────────────────────────────────────────────────┘
```

## 9. Dashboard cliente

### 9.1 Propósito

Dar al cliente una vista resumida de su relación actual con VetZen sin duplicar la edición de Cuenta.

### 9.2 Mascotas

Debe mostrar hasta seis mascotas propias, ordenadas por nombre e ID, con:

- nombre;
- especie;
- enlace a la ficha de la mascota.

El bloque ofrece:

- “Registrar mascota” hacia `/pets/create`;
- “Ver mis mascotas” hacia `/pets` cuando corresponda.

No serializa `client_id`, notas, peso, fecha de nacimiento, ruta interna de foto ni otros datos no utilizados.

### 9.3 Solicitudes pendientes

Debe mostrar hasta cinco solicitudes `pending` de mascotas propias, ordenadas de más reciente a más antigua, con:

- mascota;
- servicio;
- fecha;
- estado “Pendiente”;
- enlace a la solicitud anidada bajo la mascota;
- enlace a la mascota.

No se crea una página global “Mis solicitudes”.

### 9.4 Tratamientos activos

Debe mostrar hasta cinco tratamientos propios en estado `pending`, `in_progress` o `suspended`, con:

- mascota;
- nombre snapshot del tratamiento;
- estado localizado;
- sesiones completadas;
- sesiones previstas;
- progreso textual `N de M sesiones completadas`;
- barra de progreso accesible;
- enlace al tratamiento anidado bajo la mascota.

El progreso se calcula exclusivamente como:

```text
sesiones completed / planned_sessions
```

Las sesiones canceladas no cuentan. No se cargan todas las sesiones: se usa un conteo condicionado.

No se crea una página global “Mis tratamientos”.

### 9.5 Accesos rápidos

| Acción             | Ruta existente |
| ------------------ | -------------- |
| Registrar mascota  | `/pets/create` |
| Explorar servicios | `/services`    |

### 9.6 Estados vacíos

#### Sin mascotas

- Mensaje: “Todavía no registraste mascotas.”
- Acción primaria: “Registrar mascota”.
- Los bloques de solicitudes y tratamientos explican que estarán disponibles después de registrar una mascota, sin aparentar un error.

#### Con mascotas y sin solicitudes pendientes

- Mensaje: “No tenés solicitudes de atención pendientes.”
- Acción: “Explorar servicios”.

#### Sin tratamientos activos

- Mensaje: “No tenés tratamientos activos.”
- No ofrece seleccionar una plantilla ni iniciar un tratamiento.

#### Usuario client sin Client asociado

- Renderiza los tres bloques vacíos.
- No intenta acceder a una relación nula.
- No recibe datos globales como fallback.

### 9.7 Wireframe

```text
┌────────────────────────────────────────────────────────┐
│ Inicio                              [Explorar servicios]│
│ Seguimiento de tus mascotas                           │
├────────────────────────────────────────────────────────┤
│ Mis mascotas                         [Registrar mascota]│
│ [Mora · Canina →] [Simón · Felina →]                  │
├──────────────────────────┬─────────────────────────────┤
│ Solicitudes pendientes   │ Tratamientos activos       │
│ Mora · Fisioterapia →    │ Mora · En curso →          │
│ 01 sep 2026 · Pendiente  │ 2 de 6 completadas         │
└──────────────────────────┴─────────────────────────────┘
```

## 10. Contrato de props admin

```ts
type AdminDashboardProps = {
    pendingRequestsCount: number;
    requests: Array<{
        id: number;
        status: 'pending' | 'resolved' | 'cancelled';
        createdAt: string;
        pet: {
            id: number;
            name: string;
        };
        service: {
            id: number;
            name: string;
        };
    }>;
};
```

No debe recibir props específicas cliente como `client`, `pets`, `pendingRequests` o `activeTreatments`.

## 11. Contrato de props cliente

```ts
type ClientDashboardProps = {
    pets: Array<{
        id: number;
        name: string;
        species: string;
    }>;
    pendingRequests: Array<{
        id: number;
        status: 'pending';
        createdAt: string;
        pet: {
            id: number;
            name: string;
        };
        service: {
            id: number;
            name: string;
        };
    }>;
    activeTreatments: Array<{
        id: number;
        treatmentName: string;
        status: 'pending' | 'in_progress' | 'suspended';
        plannedSessions: number;
        completedSessions: number;
        pet: {
            id: number;
            name: string;
        };
    }>;
};
```

La prop completa `client` deja de enviarse porque el formulario de perfil sale de Inicio y los datos globales de autenticación ya existen.

No debe recibir props específicas admin como `pendingRequestsCount` o `requests`.

## 12. Consultas requeridas

### 12.1 Admin: conteo

Consulta global con `where('status', 'pending')->count()`. Usa el índice existente sobre estado y fecha.

### 12.2 Admin: solicitudes resumidas

Selecciona solamente:

```text
id, pet_id, service_id, status, created_at
```

Precarga:

```text
pet: id, name
service: id, name
```

Ordena primero las solicitudes `pending`; dentro de cada grupo ordena por fecha descendente e ID descendente. Limita a 5, completando con solicitudes `resolved` o `cancelled` recientes cuando haya menos de cinco pendientes.

### 12.3 Cliente: mascotas

Parte de `$client->pets()` y selecciona:

```text
id, client_id, name, species
```

Ordena por nombre e ID y limita a 6. `client_id` puede ser necesario para Eloquent, pero no se incluye en la transformación final.

### 12.4 Cliente: solicitudes

Aplica ownership en SQL mediante la relación con Pet antes de ordenar y limitar. Selecciona campos mínimos, filtra `pending`, precarga mascota y servicio, ordena por fecha e ID descendentes y limita a 5.

### 12.5 Cliente: tratamientos

Aplica ownership en SQL mediante Pet. Selecciona:

```text
id, pet_id, treatment_name, planned_sessions, status
```

Filtra `pending`, `in_progress` y `suspended`, precarga la mascota y obtiene `completed_sessions_count` con `withCount` condicionado a sesiones `completed`. Ordena por fecha de inicio e ID descendentes y limita a 5.

### 12.6 Transformación

Cada colección se transforma a arrays explícitos con nombres de props en camelCase. Las fechas de solicitudes se envían como ISO-8601. No se serializan modelos ni relaciones completas.

## 13. Enlaces Wayfinder

### 13.1 Admin

- `@/routes/admin/pets`: `create`, `index`, `show`.
- `@/routes/admin/service-requests`: `index`, `show`.
- `@/routes/admin/services`: `index`.

### 13.2 Cliente

- `@/routes/pets`: `create`, `index`, `show`.
- `@/routes/services`: `index`.
- `@/routes/pets/service-requests`: `show` con Pet y ServiceRequest.
- `@/routes/pets/treatments`: `show` con Pet y PetTreatment.

No se construyen URLs manualmente.

## 14. Fechas y estados visibles

### 14.1 Fechas

Las fechas de solicitudes se formatean con `Intl.DateTimeFormat('es-AR')`, mostrando día, mes abreviado y año. No se muestra hora.

### 14.2 Solicitudes

| Estado técnico | Etiqueta  |
| -------------- | --------- |
| `pending`      | Pendiente |
| `resolved`     | Resuelta  |
| `cancelled`    | Cancelada |

### 14.3 Tratamientos

| Estado técnico | Etiqueta   |
| -------------- | ---------- |
| `pending`      | Pendiente  |
| `in_progress`  | En curso   |
| `suspended`    | Suspendido |

Los estados terminales no forman parte del bloque de tratamientos activos.

## 15. Diseño responsive

### 15.1 Desktop

- `PageHeader` conserva título, descripción y acción principal.
- Resumen de solicitudes y accesos rápidos admin pueden ocupar dos columnas.
- Bloques cliente pueden ocupar dos columnas debajo de mascotas.
- Las tarjetas no imitan tablas densas.

### 15.2 Móvil

- Todos los bloques se apilan en una columna.
- Los enlaces conservan nombre, estado y foco visible.
- Fechas y estados pueden pasar a una segunda línea.
- Ningún identificador o texto obliga a overflow horizontal.
- Las acciones principales mantienen objetivos táctiles de al menos 44 por 44 px.

### 15.3 Validación

La implementación se revisa a 320, 375, 390, 768 y 1280 px, en tema claro y oscuro.

## 16. Accesibilidad

1. `PageHeader` conserva la única `h1`.
2. Cada bloque usa `section` con encabezado asociado.
3. Los nombres enlazados describen el recurso de destino.
4. La barra de progreso incluye nombre accesible y valores comprensibles.
5. El progreso no depende solo de longitud o color.
6. Los estados incluyen texto, no solo color.
7. Los estados vacíos se anuncian como contenido normal, no únicamente por toast.
8. Los iconos decorativos usan `aria-hidden`.
9. Los enlaces y botones tienen foco visible.
10. El orden de tabulación sigue el orden visual.

## 17. Error parcial

Las consultas se resuelven en una única respuesta Inertia síncrona. UX-02 no captura ni oculta excepciones por bloque porque eso podría presentar datos incompletos como válidos y dificultar observabilidad.

Si una consulta falla, se aplica el manejo general de errores de Laravel. Un estado parcial solo se incorporará en una feature posterior si se adoptan props diferidas o fuentes independientes con una estrategia explícita de reintento.

Los estados vacíos por ausencia de datos no son errores parciales.

## 18. Archivos previstos

| Archivo                                        | Cambio                                                  |
| ---------------------------------------------- | ------------------------------------------------------- |
| `features/UX-02-role-based-dashboards.md`      | Contrato de la feature                                  |
| `app/Http/Controllers/DashboardController.php` | Consultas, ownership y props mínimas por rol            |
| `resources/js/pages/admin/dashboard.tsx`       | Resumen administrativo y accesos rápidos                |
| `resources/js/pages/client/dashboard.tsx`      | Resumen cliente sin formulario de perfil                |
| `resources/js/types/dashboard.ts`              | Tipos específicos si evitan duplicación o tipos amplios |
| `resources/js/types/index.ts`                  | Exportar tipos nuevos si se crea el archivo anterior    |
| `tests/Feature/DashboardTest.php`              | Rol, props, límites, estados y ownership                |

No se prevén cambios en rutas, Policies, modelos, migraciones ni componentes de dominio.

## 19. Plan de implementación

### Paso 1. Aplicar decisiones cerradas

- Priorizar pendientes y completar el listado admin con solicitudes recientes.
- Considerar activos los tratamientos `pending`, `in_progress` y `suspended`.

### Paso 2. Backend admin

- Incorporar conteo y listado mínimo.
- Transformar campos explícitamente.
- Verificar orden y límite.

### Paso 3. Backend cliente

- Resolver `Client` autenticado.
- Aplicar ownership antes de límites.
- Incorporar mascotas, solicitudes y tratamientos.
- Transformar props y manejar cliente sin datos asociados.

### Paso 4. Frontend admin

- Reutilizar `PageHeader` y breadcrumbs.
- Añadir conteo, listado, estado vacío y accesos rápidos.
- Mantener todo el contenido en español.

### Paso 5. Frontend cliente

- Eliminar formulario de perfil e imports asociados.
- Añadir mascotas, solicitudes, tratamientos y estados vacíos.
- Mostrar progreso textual y visual.

### Paso 6. Pruebas

- Ampliar `DashboardTest` con datos propios y ajenos.
- Probar props mínimas, filtros, límites y estados vacíos.
- Mantener pruebas HTTP reales.

### Paso 7. Verificación

- Ejecutar PHPUnit específico y completo.
- Ejecutar Pint, Prettier, TypeScript, ESLint y build.
- Revisar responsive, teclado, foco y temas manualmente.

## 20. Pruebas requeridas

El proyecto usa PHPUnit, no Pest. UX-02 sigue la convención establecida y no agrega un segundo framework.

### 20.1 Selección y acceso

- Guest es redirigido a login.
- Admin recibe `admin/dashboard`.
- Client recibe `client/dashboard`.
- Permisos no cambian la experiencia seleccionada por rol.
- Usuario sin rol aprobado recibe `403`.

### 20.2 Admin

- Recibe el conteo exacto de solicitudes pendientes.
- Recibe como máximo cinco solicitudes, con pendientes primero y recientes después.
- Recibe paciente, servicio, fecha y estado.
- No recibe props específicas cliente.
- No recibe notas ni campos innecesarios.
- El estado sin solicitudes devuelve conteo `0` y lista vacía.

### 20.3 Cliente

Con Client A y Client B:

- Client A recibe solo mascotas de Client A.
- Client A recibe solo solicitudes pendientes de sus mascotas.
- Client A recibe solo tratamientos propios `pending`, `in_progress` o `suspended`.
- Recursos más recientes de Client B no desplazan recursos de Client A antes del límite.
- Recibe como máximo seis mascotas, cinco solicitudes y cinco tratamientos.
- El progreso cuenta solo sesiones completadas.
- No recibe `client_id`, notas, precios, sesiones completas ni props admin.
- Cliente sin mascotas recibe colecciones vacías.
- Cliente con mascotas sin solicitudes recibe solicitudes vacías.
- Cliente sin tratamientos activos recibe tratamientos vacíos.
- Cliente con rol pero sin `Client` asociado recibe colecciones vacías.

### 20.4 Regresión

- Las rutas de detalle continúan aplicando Policies y bindings anidados.
- Las pruebas de autorización horizontal de mascotas, solicitudes y tratamientos siguen pasando.
- No se debilita middleware administrativo.

## 21. Criterios de aceptación

- [ ] `/dashboard` sigue seleccionando experiencia por rol.
- [ ] Admin ve conteo y hasta cinco solicitudes con pendientes primero y recientes después.
- [ ] Admin ve paciente, servicio, fecha y estado con enlaces contextuales.
- [ ] Admin dispone de los cuatro accesos rápidos requeridos.
- [ ] Cliente ya no ve ni recibe el formulario o la prop completa de perfil.
- [ ] Cliente ve hasta seis mascotas propias.
- [ ] Cliente ve hasta cinco solicitudes pendientes propias.
- [ ] Cliente ve hasta cinco tratamientos propios `pending`, `in_progress` o `suspended`.
- [ ] El progreso usa sesiones completadas sobre sesiones previstas.
- [ ] No existen listados globales nuevos de solicitudes o tratamientos.
- [ ] Las consultas aplican ownership antes de ordenar y limitar.
- [ ] Las props son explícitas y no exponen campos innecesarios.
- [ ] Los estados vacíos definidos se renderizan sin errores.
- [ ] Todo el dashboard visible está en español.
- [ ] Los nombres relevantes enlazan mediante Wayfinder.
- [ ] La interfaz no presenta agenda, gráficos, notificaciones ni historia clínica.
- [ ] No existen cambios de base de datos, rutas, Policies o modelos.
- [ ] La interfaz funciona sin overflow en los anchos definidos.
- [ ] Las pruebas de rol, datos, límites y ownership pasan.
- [ ] Formato, tipos, lint, build y suite relevante pasan.

## 22. Decisiones resueltas

### DECISIÓN RESUELTA - Contenido de solicitudes admin

La frase “hasta cinco solicitudes pendientes o recientes” admite dos contratos:

1. Mostrar solamente solicitudes `pending`, hasta cinco.
2. Mostrar primero todas las `pending` disponibles hasta completar cinco y, si faltan, completar con solicitudes `resolved` o `cancelled` recientes.

Se adopta la alternativa 2: las solicitudes `pending` aparecen primero y, si son menos de cinco, el bloque se completa con solicitudes `resolved` o `cancelled` recientes. El conteo destacado continúa representando exclusivamente solicitudes pendientes.

### DECISIÓN RESUELTA - Definición de tratamiento activo

`PetTreatment` admite:

```text
pending, in_progress, completed, suspended, cancelled
```

Debe elegirse si el resumen cliente incluye:

1. `pending` e `in_progress` solamente.
2. `pending`, `in_progress` y `suspended`, entendiendo “activo” como no terminal.

Se adopta la alternativa 2: el resumen incluye `pending`, `in_progress` y `suspended`. “Activo” significa no terminal en UX-02; `completed` y `cancelled` quedan excluidos.

## 23. Contradicciones y aclaraciones detectadas

1. El prompt referencia `docs/ux`, pero el repositorio usa `doc/ux`.
2. El prompt solicita pruebas Pest, pero el proyecto utiliza PHPUnit. Se conserva PHPUnit.
3. UX-01 figura documentalmente como pendiente de implementación, aunque su infraestructura y pruebas ya están presentes en el código. UX-02 depende del estado real verificado.
4. “Abrir catálogo clínico” no tiene una landing propia; se usa la ruta existente de Servicios clínicos.
5. El error parcial no se implementa con consultas síncronas en una única respuesta; no se silencian fallos backend.

## 24. Definición de terminado

UX-02 estará terminada cuando ambos dashboards implementen sus contratos con ownership y props mínimas, los estados vacíos sean utilizables, la interfaz sea responsive y accesible, y todas las verificaciones automatizadas y manuales requeridas hayan sido completadas.
