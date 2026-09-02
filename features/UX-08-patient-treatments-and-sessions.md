# UX-08 - Tratamientos de pacientes y sesiones

> Estado: implementada y verificada automáticamente. Permanece pendiente la
> revisión visual manual responsive y de teclado.

## 1. Objetivo

Presentar la asignación y el seguimiento operativo de tratamientos dentro del
contexto del paciente, diferenciando claramente una plantilla de catálogo de un
PetTreatment y manteniendo las sesiones legibles y seguras para ambos roles.

## 2. Dependencias

- UX-01 y UX-03 para navegación y contexto persistente.
- UX-05 para el acceso contextual opcional a una evolución clínica.
- UX-06 y UX-07 para plantillas y solicitudes resueltas.
- F08 como contrato funcional de asignaciones, estados, precios y sesiones.

## 3. Alcance

- Listado admin de tratamientos dentro del paciente.
- Asignación directa desde una plantilla disponible.
- Detalle admin con condiciones, progreso, procedimientos históricos y sesiones.
- Edición de condiciones y cambios de estado permitidos.
- Edición de fecha, precio, estado y notas de sesión.
- Consulta cliente de tratamientos y sesiones propias en modo lectura.
- Acceso contextual al alta de una evolución clínica, sin asociación persistente.

No incluye protocolos, procedimiento por sesión, turnos, pagos, facturación ni
creación automática de ClinicalRecord.

## 4. Contrato funcional

1. Solo se asignan plantillas activas de Services activos con procedimientos válidos.
2. La asignación y generación inicial de sesiones es atómica.
3. Aumentar `planned_sessions` crea sesiones consecutivas con precio vigente.
4. Si precio y cantidad cambian juntos, solo las sesiones nuevas usan el precio nuevo.
5. Reducir cantidad elimina únicamente las últimas sesiones pendientes.
6. Una sesión cancelada se conserva y genera reemplazo cuando corresponde.
7. Sesiones completadas o canceladas no cambian de estado, pero sus metadatos
   pueden corregirse mientras el tratamiento padre esté pendiente o en curso.
8. Un tratamiento suspendido no admite sesiones; puede reanudarse manualmente.
9. PetTreatment completado o cancelado es final y no se reabre.
10. El cliente tiene acceso exclusivamente de lectura a recursos propios.

## 5. Experiencia

- `PetContextHeader` conserva paciente o mascota y navegación relacionada.
- Listados muestran estado, fecha de inicio y progreso sin exponer tokens técnicos.
- El detalle separa resumen, condiciones, procedimientos y sesiones.
- Las sesiones usan tabla en escritorio y tarjetas editables o de lectura en móvil.
- Acciones terminales explican consecuencias y solicitan confirmación.
- Cuando una acción está bloqueada por estado, la interfaz explica el motivo.
- “Registrar evolución” abre Historia clínica con tipo `evolution` preseleccionado;
  no vincula ni crea automáticamente datos clínicos.

## 6. Seguridad y datos

- Policies y bindings protegen PetTreatment y TreatmentSession en backend.
- El acceso cliente resuelve `User → Client → Pet → PetTreatment → TreatmentSession`.
- IDs de ownership y autoría no se aceptan por mass assignment.
- Las props cliente son de solo lectura y omiten claves internas innecesarias.

## 7. Criterios de aceptación

- [x] Admin asigna y administra tratamientos en contexto del paciente.
- [x] Las sesiones respetan precio histórico, reemplazos y estados finales.
- [x] El progreso se deriva de sesiones completadas.
- [x] Cliente consulta tratamientos y sesiones exclusivamente propios.
- [x] La continuidad clínica es un enlace explícito, no una escritura automática.
- [x] Vistas admin y cliente se adaptan a móvil.
- [x] Pruebas HTTP cubren reglas, autorización y ownership horizontal.
- [ ] Revisión visual manual responsive y de teclado completada.

## 8. Decisiones pendientes

No existen decisiones pendientes dentro de UX-08.
