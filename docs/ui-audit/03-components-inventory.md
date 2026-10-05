# Inventario de componentes

## A. Primitives UI (shadcn/Radix)

`alert`, `avatar`, `badge`, `breadcrumb`, `button`, `card`, `checkbox`, `collapsible`, `dialog`, `dropdown-menu`, `input`, `input-otp`, `label`, `navigation-menu`, `select`, `separator`, `sheet`, `sidebar`, `skeleton`, `sonner`, `spinner`, `toggle`, `toggle-group`, `tooltip`, `placeholder-pattern`, `icon` en `resources/js/components/ui/`. Son los controles globales a preservar como base del futuro sistema.

## B. Estructurales

| Componente | Responsabilidad |
|---|---|
| `AppLayout`, `app-sidebar-layout`, `AppShell`, `AppContent` | composición del shell autenticado y ancho del contenido |
| `AppSidebar`, `AppSidebarHeader`, `NavMain`, `NavUser`, `NavFooter` | navegación por rol, breadcrumb, perfil y drawer móvil |
| `AuthLayout`, `auth-simple-layout` | marco centrado de autenticación; existen variantes `auth-card` y `auth-split` no seleccionadas |
| `SettingsLayout` | tabs/enlaces de Cuenta, Perfil, Seguridad y Apariencia |
| `PageHeader`, `Heading`, `Breadcrumbs` | jerarquía de página; coexistencia no uniforme |

## C. Funcionales compartidos

| Componente | Uso y datos |
|---|---|
| `PetContextHeader`, `PetSummary`, `PetFormFields` | cabecera/identidad, resumen y formulario de mascota; cliente y admin |
| `ClinicalRecordSummary`, `ClinicalRecordDetail`, `ClinicalRecordFormFields` | timeline, lectura y edición de historia clínica |
| `ServiceFormFields`, `ServiceDetails`, `ProcedureFormFields` | catálogo clínico |
| `ClientProfileFields` | contacto/residencia de cliente y perfil |
| `CatalogStatusForm`, `CatalogIconLink` | activar/desactivar catálogo y acciones iconográficas |
| `InputError`, `AlertError`, `TextLink` | error/form feedback y enlaces de texto |
| `PasswordInput`, passkeys/2FA, `DeleteUser`, `AppearanceTabs` | cuenta y seguridad |

## D. Específicos/locales de página

Tarjetas de mascota, solicitud, tratamiento, filas responsive, barras de progreso, filtros, paginadores, badges de solicitud/tratamiento/sesión y formularios de asignación/resolución/sesión se declaran dentro de páginas. Esto explica las variaciones visuales actuales.

## Matriz de reutilización

| Patrón detectado | Dónde se repite | Clasificación / propuesta |
|---|---|---|
| Header de listado con título, descripción y CTA | mascotas, pacientes, clientes, catálogos, solicitudes, dashboards | Global: consolidar sobre `PageHeader`; mantener copy/CTA de dominio. |
| Filtros búsqueda + selects + mantener query | servicios, procedimientos, plantillas, solicitudes | Global: `FilterBar` composicional; los filtros y opciones siguen siendo de dominio. |
| Paginación Laravel | cuatro catálogos y solicitudes | Global: `DataTablePagination` que recibe links/meta. |
| Tabla desktop + cards móvil | clientes, pacientes, servicios, procedimientos, plantillas, solicitudes | Global: `ResponsiveDataList`; columnas/contenido/acciones siguen específicos. |
| Estado activo/inactivo | servicio, procedimiento, plantilla | Global: `CatalogStatusBadge` y `CatalogStatusAction`; `CatalogStatusForm` ya es evidencia. |
| Estados request/treatment/session | dashboard, índices, detalles y timelines | Dominio terapéutico: `RequestStatusBadge`, `TreatmentStatusBadge`, `SessionStatusBadge`; no un único badge semánticamente ambiguo. |
| Vacío con explicación/CTA | dashboard, mascotas, historia, catálogos, tratamientos | Global: `EmptyState`; icono, copy y CTA de dominio. |
| Contexto de paciente | detalle, historia, solicitudes, tratamientos admin/client | Dominio paciente: conservar/evolucionar `PetContextHeader`. |
| Form field + label + error | todos los formularios | Global: `FormField`, `FormSection`, `FormActions`; tipos y reglas siguen de dominio. |
| Confirmación destructiva | cancelaciones y cambios de estado | Global: `ConfirmActionDialog`; no reemplaza el formulario/acción del dominio. |

No se recomienda un `EntityCard` universal ni una mega-tabla/mega-formulario: las entidades difieren en jerarquía, densidad, acciones y contexto.
