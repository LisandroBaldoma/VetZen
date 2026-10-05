# Flujos reales

## Cliente

### Mascota y seguimiento
```text
Inicio → Mis mascotas → detalle de mascota
  → Historia clínica → registro de solo lectura
  → Solicitudes de atención → detalle → tratamiento resuelto (si existe)
  → Tratamientos → detalle → sesiones y progreso
  → Editar mascota
```

### Solicitar atención
```text
Inicio o Servicios disponibles → servicio
→ seleccionar mascota propia → Crear solicitud (servicio preseleccionado)
→ confirmar notas opcionales → Solicitudes de la mascota
→ pendiente / resuelta / cancelada
```
El cliente no ve ni elige plantilla, precio, sesión o asignación. Si no tiene mascotas, el CTA lleva a crear una.

### Perfil
```text
Menú de usuario → Cuenta → Perfil / Perfil de cliente / Seguridad / Apariencia
```

## Admin

### Paciente e historia clínica
```text
Inicio o Pacientes → listado → detalle de paciente
→ Historia clínica → crear / detalle / editar
→ Tratamientos → asignar / detalle / actualizar sesión o estado
```

### Catálogo terapéutico
```text
Servicios clínicos → crear/editar/detalle
→ Procedimientos del servicio → crear/editar/detalle/estado
→ Plantillas del servicio → crear/editar/estado
```
También puede empezar desde los catálogos globales de Procedimientos o Plantillas. La ruta de retorno/jerarquía no es consistente en todos los formularios.

### Resolver solicitud
```text
Inicio o Solicitudes de atención → listado filtrable → detalle
→ revisar mascota, responsable, servicio y notas
→ seleccionar plantilla compatible + condiciones → resolver
→ PetTreatment y sesiones creadas → estado resuelto
```

## Fricciones observadas

- El detalle de servicio y el de procedimiento existen, pero no siempre son destino primario desde la lista global.
- El selector de mascota se repite dentro de cada card del listado de servicios, lo que densifica el escaneo.
- Formularios de catálogo/mascota no siguen una pauta estable de breadcrumbs, volver/cancelar y `h1`.
- Cambios sensibles usan `window.confirm()` y la eliminación de foto no confirma.
- Las sesiones se muestran como timeline en todos los tamaños, mientras UX-08 describe tabla desktop/cards móvil: diferencia documentada, no cambio propuesto.
