# Mapa de navegación

## Shell

`AppLayout` usa `AppSidebarLayout`: sidebar persistente en escritorio y Sheet/drawer en móvil, header con breadcrumbs y menú de usuario. Las páginas de ajustes añaden `SettingsLayout`; `auth/*` usa `AuthLayout`; `welcome` no tiene layout.

## Rutas realmente implementadas

```text
Pública
└── / → Welcome

Autenticación (Fortify)
├── /login, /register, /forgot-password, /reset-password/{token}
├── /confirm-password, /two-factor-challenge, /verify-email
└── POST de login, logout, registro, recuperación, 2FA y passkeys

Panel autenticado
├── /dashboard → redirige visualmente a dashboard de admin o client
├── /services → /services/{service}
├── /pets → crear, detalle, editar y foto
│   ├── historia clínica → listado, detalle
│   ├── solicitudes → listado, crear, detalle
│   └── tratamientos → listado, detalle
├── /settings → perfil, seguridad, apariencia
└── /clients/{client} → perfil propio del cliente autorizado

Administración (middleware role:admin)
├── /admin/clients → editar cliente
├── /admin/pets → crear, detalle, editar
│   ├── historia clínica → listado, crear, detalle, editar
│   └── tratamientos → listado, asignar, detalle, editar estado/sesiones
├── /admin/service-requests → detalle, resolver o cancelar
├── /admin/services → crear, detalle, editar, activar/desactivar
│   ├── procedimientos → listado, crear, detalle, editar, activar/desactivar
│   └── tratamientos → listado, crear, editar, activar/desactivar
├── /admin/procedures → catálogo transversal
└── /admin/treatments → catálogo transversal
```

## Navegación visible

| Rol | Sidebar | Navegación contextual |
|---|---|---|
| Admin | Inicio, Clientes, Pacientes, Solicitudes de atención, Servicios clínicos, Procedimientos clínicos, Plantillas de tratamiento | detalle de paciente hacia historia/tratamientos; servicio hacia procedimientos/plantillas; solicitud hacia paciente y resolución |
| Client | Inicio, Mis mascotas, Servicios disponibles | detalle de mascota hacia historia, solicitudes y tratamientos; servicio hacia selector de mascota y solicitud |

El menú de usuario contiene Cuenta y cerrar sesión. El logo vuelve a `/dashboard`. Los breadcrumbs están en el header del sidebar; su cobertura es desigual en formularios y catálogos anidados.

## Enlaces y handoffs relevantes

- Servicio cliente → elegir mascota → crear solicitud con servicio preseleccionado.
- Solicitud resuelta de cliente → tratamiento asignado resultante.
- Dashboard admin → solicitud, paciente o catálogo; dashboard client → mascota, solicitud, tratamiento o servicios.
- Fila/card de paciente → detalle; desde su contexto se abren historia clínica y tratamientos.
- Servicio admin → procedimientos y plantillas; actualmente el nombre en la lista global dirige a procedimientos, no siempre al detalle de servicio.

## Ausencias de navegación

No hay sidebar, rutas ni pantallas para turnos, disponibilidad, notificaciones, asistente ni gestión de profesionales/permisos. No deben incluirse en el rediseño de las vistas actuales como si estuvieran implementados.
