# Feature 10 — Turnos y agenda

> Estado: especificada para implementación por etapas. La implementación no debe
> comenzar en una parte afectada por una decisión marcada como pendiente.

## 1. Objetivo

Permitir que clientes soliciten turnos en una agenda única publicada por la
veterinaria y que administradores la administren sin exceder la capacidad
compartida.

## 2. Alcance cerrado

- Agenda única, sin profesionales asignados ni agendas individuales.
- Fechas concretas publicadas con bloques de trabajo, inicios reservables y
  servicios habilitados por fecha.
- Capacidad compartida de uno a cinco pacientes durante todo el intervalo.
- Duración predeterminada de turno por Service, snapshot histórico por turno.
- Estados: `pending`, `approved`, `rejected`, `cancelled`, `attended`, `absent`.
- Solicitud cliente, creación administrativa, aprobación, rechazo, cancelación,
  reprogramación, agenda diaria/semanal e historial.
- Vínculos opcionales con ServiceRequest y TreatmentSession compatibles.
- Tareas administrativas para registrar atención clínica pendiente.

No incluye agendas por profesional, recurrencias, slots automáticos, equipos,
estaciones, rotación de procedimientos, pagos, facturación, Push Notifications o
acciones de Simón sobre datos dinámicos.

## 3. Reglas de reserva

1. El cliente reserva solo para una Pet propia; el admin puede reservar para una
   Pet existente.
2. El Service debe estar activo y habilitado para la fecha publicada.
3. El inicio debe estar publicado explícitamente y el intervalo completo debe
   caber en un bloque de trabajo de esa fecha.
4. `pending` y `approved` ocupan capacidad; sus intervalos usan semántica
   `[inicio, fin)`, por lo que dos turnos contiguos son válidos.
5. Una Pet no puede tener turnos `pending` o `approved` solapados.
6. El cliente reserva con al menos 120 minutos; cancela o reprograma con al
   menos 1440 minutos respecto del inicio original. La comparación exacta es
   inclusiva: en el límite la acción está permitida.
7. No se reservan fechas pasadas en la zona horaria de la veterinaria.
8. Crear, reprogramar y editar agenda se valida en backend dentro de transacción.

## 4. Estados y transiciones

| Estado | Significado | Reserva capacidad |
| --- | --- | --- |
| `pending` | Solicitud cliente pendiente de revisión | Sí |
| `approved` | Turno aprobado o creado por admin | Sí |
| `rejected` | Solicitud no aceptada | No |
| `cancelled` | Turno cancelado, conservado como historial | No |
| `attended` | Atención registrada manualmente por admin | No |
| `absent` | Inasistencia registrada manualmente por admin | No |

Transiciones cerradas: `pending → approved`, `pending → rejected`,
`pending → cancelled`, `approved → cancelled`, `approved → attended` y
`approved → absent`. Ningún estado terminal se reabre o reprograma. La
reprogramación conserva el mismo turno e historial; el estado resultante de una
reprogramación administrativa conserva el estado `approved`.

## 5. Relaciones clínicas

ServiceRequest se conserva como solicitud de atención sin fecha y no reserva
capacidad. Appointment puede conservar una referencia opcional a ServiceRequest,
sin resolverla ni crear tratamientos automáticamente.

Appointment puede conservar una referencia opcional a TreatmentSession solo si
la sesión pertenece a la misma Pet y a un tratamiento del mismo Service. Cancelar,
rechazar o marcar ausente un turno no modifica la sesión. Marcar atendido tampoco
la completa automáticamente.

Una sesión no puede tener simultáneamente más de un turno `pending` o `approved`.
Las sesiones completadas no pueden volver a reservarse. El bloqueo ante un turno
atendido cuya sesión permanezca pendiente se define como decisión pendiente para
evitar atención clínica duplicada.

## 6. Configuración e historial

Los valores iniciales configurables son capacidad, anticipación de reserva,
cancelación, reprogramación, recordatorio y zona horaria de la veterinaria. Cada
turno conserva snapshots de servicio, duración, inicio, fin, actores y cambios.

Editar agenda no modifica reservas existentes. No se permite retirar bloques,
capacidad, inicios o servicios si invalida turnos `pending` o `approved`; estos
deben cancelarse o reprogramarse antes.

## 7. Seguridad

Client se autoriza recorriendo `User → Client → Pet → Appointment`. Admin usa el
rol actual y Policies. El navegador no determina ownership, estado, duración,
capacidad, autores, sesión vinculada ni disponibilidad.

## 8. Concurrencia

La fecha publicada es el recurso de serialización. Todas las reservas y cambios
de agenda bloquean esa fecha dentro de una transacción antes de volver a evaluar
servicio, inicio, bloque, solapamientos y capacidad. Las operaciones entre fechas
las bloquean por ID ascendente. La capacidad se calcula por intervalos, no por un
conteo simple. Las pruebas de carrera se ejecutarán contra el motor productivo.

## 9. Notificaciones y tareas

La primera versión crea notificaciones internas y envía email solo cuando el
correo saliente esté configurado. Los eventos son solicitud, aprobación, rechazo,
cancelación, reprogramación/cambio administrativo y recordatorio de turno
aprobado. Los recordatorios se programan inicialmente 24 horas antes, se
invalidan ante cambios y se registran idempotentemente. Un error de email no
revierte una reserva.

Un turno `attended` sin registro clínico correspondiente crea una tarea
persistente de dashboard: registrar sesión cuando existe vínculo, o asignar
tratamiento y registrar sesión cuando no existe tratamiento.

## 10. Decisiones pendientes

1. Permiso y reglas para editar duración individual de un turno.
3. Cardinalidad definitiva ServiceRequest--Appointment y prevención de vínculos
   activos duplicados.
4. Regla exacta para una sesión pendiente vinculada a un turno atendido.
5. Destinatarios administrativos y proveedor de email.
6. Mecanismo para identificar qué ClinicalRecord resuelve una tarea de registro
   clínico, ya que F06 no vincula registros clínicos con sesiones o turnos.

## 11. Criterios de aceptación por dominio

- Ninguna reserva supera la capacidad en ningún tramo de su intervalo.
- Un fallo de reprogramación conserva el intervalo original.
- Un cliente no puede actuar sobre turnos de otra mascota.
- Cambios de catálogo y agenda no reescriben ni invalidan reservas históricas.
- Turno, solicitud de atención y sesión de tratamiento conservan sus ciclos de
  vida independientes.
- Las notificaciones no duplican envíos ni hacen fallar reservas.

## 12. Estado de implementación

### Etapa 1 — Publicación administrativa de agenda

Implementada: fechas concretas, alta múltiple con configuración común, edición
individual, bloques de trabajo no superpuestos, inicios reservables explícitos,
servicios activos habilitados por fecha y capacidad de uno a cinco pacientes.
También se incorporó la duración predeterminada de turno de Service. Los servicios
existentes sin duración deben configurarla antes de poder habilitarse en agenda.

### Etapa 2 — Disponibilidad y solicitud de cliente

Implementada: el cliente selecciona una de sus mascotas y un servicio activo
para consultar fechas e inicios publicados. La solicitud crea un turno
`pending` con snapshots de servicio y duración, y persiste los instantes en UTC.
La reserva se ejecuta en una transacción MySQL bloqueando la fecha de agenda;
valida anticipación mínima de dos horas, bloques, inicio publicado, capacidad
compartida por intervalo y ausencia de solapamiento para la misma mascota.

La disponibilidad mostrada es informativa y se revalida al confirmar la
solicitud. Esta etapa no incorpora todavía el listado de próximos/historial ni
las transiciones administrativas de estado.

### Etapa 3 — Consulta de turnos del cliente

Implementada: el cliente consulta sus próximos turnos y su historial, y puede
abrir el detalle de un turno propio. La lectura se autoriza recorriendo
`User → Client → Pet → Appointment`; no se exponen turnos de otras mascotas.
Las transiciones, cancelación y reprogramación no forman parte de esta etapa.

### Etapa 4a — Cola administrativa y transiciones

Implementada: el administrador consulta la cola de turnos `pending`, abre el
detalle y puede ejecutar únicamente las transiciones cerradas aplicables. Cada
cambio bloquea el turno y conserva un historial inmutable con actor, estados y
motivo. La agenda diaria/semanal y la creación administrativa de turnos quedan
para una etapa posterior.

### Etapa 4b — Agenda administrativa semanal

Implementada: el administrador consulta una semana de agenda con sus fechas
publicadas, capacidad y todos los turnos por día. Cada tarjeta abre el detalle
administrativo. La vista no crea, reprograma ni altera reservas.

### Etapa 4c — Creación administrativa

Implementada: el administrador crea un turno `approved` para cualquier paciente
existente usando inicios, bloques, servicios y capacidad publicados. Reutiliza la
validación transaccional de reservas, sin la anticipación mínima exclusiva de
solicitudes de clientes.

### Etapa 5a — Cancelación de cliente

Implementada: el cliente puede cancelar un turno propio `pending` o `approved`
cuando faltan al menos 24 horas, inclusive, para el inicio original. El límite se
revalida dentro de la transacción y la cancelación queda registrada en historial.

### Etapa 5b — Reprogramación administrativa

Implementada: un administrador puede reprogramar un turno `approved` sin crear
otro registro. La operación conserva el estado `approved`, bloquea las fechas
involucradas por ID ascendente, vuelve a validar disponibilidad y persiste el
intervalo anterior y nuevo junto al actor.
