# UX-15 — Turnos y agenda

> Estado: Etapa 1 implementada para administración de fechas publicadas. Los
> flujos de reserva y agenda operativa permanecen pendientes.

## 1. Navegación

Admin incorpora **Agenda** como destino principal. Client incorpora **Turnos**
dentro de Atención, manteniendo separado **Servicios disponibles** y la
solicitud de atención sin fecha.

## 2. Cliente

Flujo: elegir mascota propia, elegir servicio, consultar fechas compatibles,
elegir un inicio publicado, revisar duración e intervalo y solicitar. El detalle
expone estado, servicio, mascota, inicio/fin e historial relevante. Próximos e
historial permiten cancelar o reprogramar cuando las reglas lo permiten.

La interfaz debe explicar que una solicitud de atención sin fecha no reserva un
turno. Si el cupo desaparece entre selección y confirmación, mantiene la
selección visible, informa el error y no muestra éxito optimista.

## 3. Administrador

La agenda diaria y semanal son las vistas iniciales. Cada bloque muestra carriles
de capacidad para uno a cinco pacientes simultáneos, con tarjetas de intervalo
para mascota, servicio y estado. No se incorpora vista mensual en la primera
etapa.

Admin dispone de cola de solicitudes pendientes, creación de turno, detalle,
aprobación, rechazo, cancelación con motivo, reprogramación, asistencia/ausencia,
vínculos clínicos y publicación de fechas. La configuración permite alta masiva
con edición individual por fecha.

## 4. Responsive y accesibilidad

En escritorio se usa grilla temporal. En móvil se reemplaza por selector de fecha
y lista cronológica por bloque/carril; no se comprime una agenda semanal ilegible.
Todos los diálogos gestionan foco, Escape y retorno de foco. Estados, cupos y
errores incluyen texto; controles mantienen foco visible y objetivos táctiles de
al menos 44 px.

Se reutilizan AppLayout, PageHeader, Card, Badge, Button, Form, diálogos Radix,
toasts y Wayfinder. No se agrega una librería de calendario inicialmente.
