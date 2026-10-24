# SIMON-03 - Generación grounded mediante proveedor LLM

> Estado: implementada y verificada automáticamente. Esta feature no incorpora UI de chat, streaming,
> embeddings, vector store, memoria, analytics, tool calling, acciones ni acceso
> dinámico a datos clínicos o de negocio.

## Objetivo

Exponer un endpoint autenticado que responda preguntas de uso de VetZen mediante
contenido recuperado por SIMON-02 y generación de texto mediante un proveedor
LLM.

## Alcance

* `POST /simon/messages` para usuarios con rol `admin` o `client`;
* validación de pregunta y módulo opcional de contexto;
* filtrado de Knowledge Base por rol antes de llamar al proveedor;
* contrato de generación independiente del proveedor;
* adapter Groq configurable por entorno para desarrollo y pruebas;
* respuesta JSON con respuesta y fuentes de KB generadas por Laravel;
* rate limit por usuario e IP;
* respuesta local sin llamada al proveedor cuando no hay evidencia.

## Reglas de seguridad

1. Laravel determina el rol efectivo; el payload no puede elegirlo.
2. El endpoint no acepta IDs de clientes, mascotas, tratamientos ni historia
   clínica.
3. El módulo es opcional y se valida contra una lista cerrada.
4. El proveedor recibe solo la pregunta, rol, módulo validado y chunks
   autorizados.
5. El modelo no recibe acceso a base de datos, herramientas ni URLs ejecutables.
6. Si no hay evidencia, Simón usa una respuesta fija de falta de información.
7. Las fuentes se adjuntan desde Laravel, no desde texto generado por el modelo.

## Configuración

La generación usa `SIMON_LLM_PROVIDER`, `SIMON_LLM_MODEL`,
`SIMON_LLM_CONNECT_TIMEOUT` y `SIMON_LLM_TIMEOUT`. Groq usa `GROQ_API_KEY` y
`GROQ_BASE_URL` para desarrollo y pruebas. El modelo no tiene valor por defecto.
OpenAI sigue siendo una alternativa compatible futura, pero no tiene adapter ni
configuración activa. El proveedor de producción permanece pendiente.

## Criterios de aceptación

* [x] El endpoint requiere autenticación y un rol `admin` o `client`.
* [x] Laravel deriva el rol y filtra evidencia antes de llamar al proveedor.
* [x] No se llama al proveedor cuando la búsqueda no encuentra evidencia.
* [x] La respuesta incluye fuentes generadas por Laravel, no por el modelo.
* [x] Un proveedor no configurado o fallido responde con un error seguro.
* [x] La pregunta y el módulo se validan y el endpoint aplica rate limiting.
