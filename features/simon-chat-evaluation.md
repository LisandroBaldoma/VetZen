# Interfaz de evaluación de Simón

> Estado: en implementación. Esta feature habilita una interfaz temporal para
> evaluar el asistente de producto existente. No redefine SIMON-01, SIMON-02 ni
> SIMON-03.

## Objetivo

Permitir que usuarios `admin` y `client` usen Simón desde VetZen para evaluar
las respuestas grounded sobre el uso actual del producto.

## Alcance

* ruta autenticada y autorizada para mostrar la página de Simón;
* acceso visible en el sidebar para `admin` y `client`;
* conversación temporal durante la visita a la página;
* envío de preguntas al endpoint existente `POST /simon/messages`;
* visualización de respuesta, fuentes de Knowledge Base y estados de carga o
  error;
* guía de ayuda que explique cómo usar Simón.

## Reglas

1. La página no recibe ni determina el rol efectivo. Laravel lo deriva del
   usuario autenticado en cada mensaje.
2. La ruta de la página y el endpoint solo están disponibles para `admin` y
   `client`.
3. La interfaz envía únicamente `question`; no envía `module`, IDs, datos de
   clientes, mascotas, tratamientos ni información clínica.
4. El historial existe solo en el estado de React y se pierde al recargar,
   cerrar o abandonar la página.
5. Las fuentes se muestran desde el payload generado por Laravel, no desde el
   texto del modelo.
6. La interfaz debe informar con mensajes seguros errores de validación,
   disponibilidad, rate limit y red, sin exponer detalles del proveedor.
7. Las trazas técnicas se habilitan por entorno mediante
   `SIMON_TRACE_LOGGING`. Registran correlación, rol, conteos, duración y tipo
   de error, pero nunca la pregunta, respuesta, chunks, API keys ni payloads del
   proveedor.

## Fuera de alcance

Esta feature no incorpora:

* persistencia, memoria conversacional, historial entre sesiones o analytics;
* streaming, Markdown enriquecido, adjuntos o feedback;
* embeddings, vector store o retrieval semántico/híbrido;
* tool calling, navegación ejecutable ni acciones;
* consultas de datos dinámicos, clínicos o de negocio;
* cambios al prompt, recuperación textual, ownership o autorización de
  SIMON-03.

## Experiencia

La página usa el layout autenticado actual de VetZen. Presenta un estado inicial
que delimita que Simón responde preguntas sobre el uso del producto y no brinda
orientación veterinaria. Cada pregunta se envía de forma individual, muestra un
estado de espera y agrega la respuesta recibida a la conversación temporal.

En móvil, Simón sigue disponible desde el menú "Más" para no desplazar las
acciones principales de la navegación inferior.

## Criterios de aceptación

* [ ] Un `admin` y un `client` autenticados pueden abrir la página de Simón.
* [ ] Un invitado es redirigido al inicio de sesión y un usuario sin rol recibe
  una respuesta prohibida.
* [ ] La navegación muestra Simón para ambos roles y refleja la sección activa.
* [ ] La página puede enviar una pregunta válida al endpoint existente sin
  enviar rol, módulo ni datos de recursos.
* [ ] La página muestra respuesta y fuentes recibidas desde Laravel.
* [ ] La página comunica correctamente carga, validación y errores seguros.
* [ ] El historial no se persiste al recargar la página.
* [ ] La guía de ayuda describe la interfaz implementada y es indexable para
  ambos roles.

## Testing

Las pruebas feature cubren acceso de invitado, acceso de `admin`, acceso de
`client` y rechazo de un usuario sin rol. Las pruebas existentes de
`SimonMessageTest` continúan verificando autorización, validación y aislamiento
del endpoint de mensajes.
