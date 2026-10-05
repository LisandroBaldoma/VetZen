# UX-CLIENT-DASHBOARD-02 - Inicio centrado en la mascota

> Estado: aprobada para implementación.

## Objetivo

Rediseñar el Inicio del cliente como un portal personal de seguimiento, con la
siguiente jerarquía:

1. Mascota seleccionada.
2. Próxima sesión.
3. Tratamientos activos y progreso.
4. Solicitudes pendientes.
5. Acciones secundarias para mascotas y servicios.

El diseño de `resources/views/home_client_stitch.blade.php` es solo una
referencia de jerarquía, composición y densidad. La implementación usa el
design system, layout, componentes, tokens y rutas existentes de VetZen.

## Información Disponible

### Mascota

El modelo `Pet` ya dispone de nombre, especie, raza, sexo, fecha de nacimiento,
peso y foto opcional. La foto se sirve mediante la ruta protegida `pets.photo`.

### Tratamientos

`PetTreatment` ya dispone de nombre snapshot, estado y cantidad de sesiones
planificadas. El progreso se deriva de sesiones `completed`.

### Sesiones

`TreatmentSession` ya dispone de `session_number`, `scheduled_at` nullable y
estado. No existen profesional, sede, sala ni estado de confirmación clínica.

### Solicitudes

Las solicitudes pendientes ya exponen mascota, servicio, fecha y estado.

## Referencia Stitch

### Adoptar

- Mascota protagonista.
- Selector compacto cuando hay varias mascotas.
- Próxima sesión como información de atención prioritaria.
- Cards compactas de tratamiento con progreso.
- Solicitudes después de la información asistencial.

### Descartar

- Header, navegación inferior, colores, fuentes, Tailwind y JavaScript de
  Stitch.
- Datos demostrativos de Rocky y Mia.
- Clínica abierta, profesional, sede, sala, WhatsApp y estado "Al día".
- Estado "Confirmada por clínica" y detalle aislado de sesión, que no existen
  en VetZen.

## Componentes

### Reutilizar

- `Card`, `Badge`, `Button`, `Avatar` y `EmptyState`.
- `TreatmentProgress` y `TreatmentStatusBadge`.
- `PetTreatmentCard`, extendido solo con detalle contextual opcional de próxima
  sesión.
- `DashboardHero` como encabezado limpio con `showSummary={false}`.
- `DashboardActivityList`, con una variante compacta para solicitudes.

### No Usar Como Bloque Principal

- `QuickAccessGrid`: sus cards son demasiado grandes para la mascota
  seleccionada.
- `PetContextHeader`: incluye navegación y edición propias de la ficha de
  mascota.

### Componentes Nuevos

No se crea un componente global en la primera implementación. La card de
mascota seleccionada y la próxima sesión se componen dentro de
`ClientDashboard` con primitives existentes. Solo se extraerán si una segunda
pantalla necesita la misma composición.

## Payload Cliente Propuesto

La selección debe ser contextual a una mascota autorizada.

```ts
type DashboardSelectedPet = {
    id: number;
    name: string;
    species: string;
    breed: string | null;
    sex: string;
    birthDate: string | null;
    weight: string | null;
    hasPhoto: boolean;
};

type DashboardNextSession = {
    treatmentId: number;
    treatmentName: string;
    sessionNumber: number;
    plannedSessions: number;
    scheduledAt: string;
    status: 'pending';
};
```

El dashboard también recibe tratamientos activos y solicitudes pendientes de la
mascota seleccionada. No expone paths de archivos, precios, notas, datos
clínicos, IDs de ownership ni entidades nuevas.

## Selección De Mascota

La selección usa una visita Inertia con query string:

```text
/dashboard?pet=<id>
```

El backend resuelve la mascota exclusivamente desde `User -> Client -> Pet`.
Sin query, se selecciona la primera mascota por nombre e ID. El selector de
Inicio mantiene el límite actual de seis mascotas; `Ver todas las mascotas`
permanece como acceso al listado completo.

## Estructura De La Página

```text
Inicio                                             [Explorar servicios]

Selector de mascota, si hay más de una

Mascota seleccionada                               [Ver ficha]
Foto, nombre, especie, raza, sexo, edad/peso disponibles

Próxima sesión
Fecha y hora destacadas, tratamiento y sesión N de M [Ver tratamiento]

Tratamientos activos
Nombre, estado, progreso y próxima sesión disponible [Ver tratamiento]

Solicitudes pendientes
Mascota, servicio, estado, fecha y acceso contextual [Ver solicitud]

Acciones secundarias
[Ver todas las mascotas] [Registrar mascota]
```

Si no hay próxima sesión, no se muestra una card vacía prominente. Si no hay
solicitudes pendientes, la sección no se renderiza. Los estados vacíos de
mascotas y tratamientos permanecen compactos y accionables.

## DECISIÓN RESUELTA - Próxima Sesión

```text
La próxima sesión es la TreatmentSession pending con scheduled_at no nulo y
mayor o igual al instante actual, perteneciente a un PetTreatment pending o
in_progress de la mascota seleccionada y autorizada para el cliente. Se ordena
por scheduled_at ascendente y session_number ascendente. Se excluyen sesiones
sin fecha, completadas, canceladas, anteriores al instante actual y las de
tratamientos suspendidos. La fecha y hora se presenta usando la timezone
configurada por Laravel.
```

## Plan De Implementación

1. Resolver y documentar la decisión pendiente sobre próxima sesión.
2. Extender `DashboardController` para el rol `client` con mascota seleccionada
   y payload mínimo, evitando N+1.
3. Actualizar `ClientDashboardProps` y tipos relacionados.
4. Adaptar `DashboardActivityList` a una variante compacta para solicitudes.
5. Extender `PetTreatmentCard` con detalle contextual opcional de próxima
   sesión.
6. Reordenar `ClientDashboard`: selector, mascota, próxima sesión,
   tratamientos, solicitudes y acciones secundarias.
7. Agregar pruebas HTTP para selección autorizada, ownership horizontal,
   próxima sesión, ausencia de sesión y props mínimas.
8. Verificar responsive, teclado, tema oscuro, formato, tipos, lint, build y
   pruebas focalizadas.
