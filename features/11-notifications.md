# Feature 11 - Notificaciones

## Objetivo

Informar acciones relevantes mediante notificaciones internas y email sin exponer
precios, notas ni información clínica.

## Alcance inicial

- Inbox autenticado con lectura y marcado individual o total como leído.
- Turnos: solicitudes, confirmaciones, cancelaciones, reprogramaciones y recordatorios.
- Tratamientos: asignación, cambio de estado del tratamiento y cambio de estado de una sesión.
- Email mediante el mailer configurado; Mailtrap Email Sandbox es el entorno inicial.

## Reglas

- Cada usuario solo consulta o modifica sus propias notificaciones.
- Los eventos de tratamiento notifican al cliente propietario de la mascota.
- No se incluyen precios, notas, diagnósticos ni datos clínicos.
- El email no bloquea la operación principal y las notificaciones se emiten después
  de confirmar la transacción.
- Push, preferencias por usuario y alertas clínicas automáticas quedan fuera de alcance.
