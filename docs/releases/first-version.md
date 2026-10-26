# Cierre Funcional De Primera Versión

## Estado

El alcance funcional de la primera versión de VetZen queda cerrado. Este cierre
no constituye un despliegue a producción.

## Entregas Cerradas

1. Bandeja y eventos de notificaciones: inbox autenticado, eventos de turnos y
   tratamientos, y email configurable mediante el mailer de Laravel.
2. Auditoría transversal mínima: asignaciones y cambios de estado de turnos,
   tratamientos y sesiones, con actor, recurso y valores de transición.
3. SIMON-04: dataset de evaluación, retrieval con grounding estructurado,
   aislamiento por rol y trazas sin contenido sensible.

## Verificación Realizada

- `composer test` completó correctamente con 248 pruebas, Pint y PHPStan.
- `npm run build` completó correctamente para las entregas de interfaz.
- Las pruebas de Simón usan fakes o mocks; no se realizaron llamadas reales a
  Groq durante la verificación automática.

## Diferido Hasta Producción

1. Revisión manual de UX: responsive, teclado, foco, contraste y flujos por
   rol con datos representativos.
2. Pruebas de concurrencia reales sobre MySQL para agenda, reprogramaciones,
   solicitudes y sesiones. SQLite no valida semánticas de bloqueo equivalentes.
3. Operación productiva:
   - Elegir proveedor de email transaccional y verificar dominio remitente.
   - Configurar scheduler, workers, reintentos y manejo de jobs fallidos.
   - Definir backups cifrados, retención, RPO/RTO y restauraciones verificadas.
   - Configurar monitoreo de `/up`, errores, colas, scheduler, email y backups.
   - Implementar CI con pruebas, análisis estático, formato, tipos y build.

Mailtrap Email Sandbox sigue siendo solo el entorno inicial de desarrollo. La
decisión de proveedor y configuración de correo saliente productivo se toma al
preparar el despliegue.
