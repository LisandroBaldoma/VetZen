# Arquitectura UI propuesta para el rediseño futuro

Esta propuesta se fundamenta solo en repeticiones existentes. No es una instrucción de implementación ni agrega comportamiento.

```text
UI primitives
├── Button, IconButton, Input, Textarea, Select, Checkbox, Label
├── Card, Badge, Avatar, Tooltip, Dialog, Sheet, Dropdown, Tabs
├── Table, Pagination, Skeleton, Alert, Toast
└── tokens semánticos: surface, text, border, brand, success, warning, danger

Shared patterns
├── AppShell, Sidebar, UserMenu, Breadcrumbs
├── PageHeader, EntityHeader, ContentWorkspace
├── FilterBar, ResponsiveDataList, DataTablePagination, RowActions
├── EmptyState, ConfirmActionDialog, StatusBadge (por dominio)
├── FormField, FormSection, FormActions, ValidationSummary, HelpText
└── MetaList, ProgressIndicator, Timeline

Domain components
├── Patient: PetContextHeader, PetSummary, PetIdentity
├── Clinical: ClinicalRecordTimelineItem, ClinicalRecordDetail
├── Catalog: ServiceDetails, ProcedureSummary, CatalogStatusAction
├── Requests: ServiceRequestSummary, RequestResolutionForm
└── Treatments: TreatmentSummary, SessionTimelineItem, TreatmentProgress

Pages
└── Composición de patrones + componentes de dominio + copy/acciones propias
```

## Límites de responsabilidad

| Nivel | Debe resolver | No debe resolver |
|---|---|---|
| Primitive | accesibilidad, estado y apariencia de un control | reglas de negocio, permisos o navegación de dominio |
| Shared pattern | estructura repetida y responsive consistente | campos/columnas/estados que no comparten semántica |
| Domain | vocabulario, formato y jerarquía clínica/terapéutica | layout completo de cada página |
| Page | tarea, orden de bloques, copy y handoff | volver a estilizar o validar primitives |

## Sistema visual Clínica Cálida: insumo, no implementación

Usar `#FDFAF3` y neutros cálidos como lienzo dominante; `#478F49`, `#80AB4B`, `#B2BA44` para identidad/éxito y `#DA5F42`, `#EA9640`, `#E8AD46` para énfasis/advertencia según significado. Antes de codificar se deben definir tokens de contraste, estado, foco y superficies. No convertir los seis colores de marca en decoraciones de igual peso ni usar verde como fondo de cada bloque.
