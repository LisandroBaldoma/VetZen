# SIMON-04 - Evaluación, grounding y observabilidad del MVP

> Estado: en implementación. Esta feature mide y corrige el comportamiento del
> MVP de Simón sin incorporar nuevas capacidades de producto.

## Objetivo

Establecer un baseline reproducible para evaluar retrieval, grounding,
aislamiento por rol, terminología, uso de contexto, tokens y latencia de Simón.

## Alcance

* dataset versionado de diez preguntas de evaluación;
* documentación de ejecución manual y resultados baseline;
* corrección de guías cuya diferencia con la UI esté demostrada;
* recuperación estructurada de conocimiento `available`, `unavailable` y
  `not_allowed_for_role`;
* métricas de retrieval y usage del proveedor en trazas existentes;
* pruebas de invariantes de retrieval, roles, grounding y provider fake.

## Reglas

1. El contrato `AssistantResponseGenerator` continúa siendo la única frontera de
   generación. Las pruebas no realizan llamadas reales al proveedor.
2. Groq es el proveedor de desarrollo/pruebas y `openai/gpt-oss-20b` es el
   modelo baseline de esta evaluación. La lógica de dominio permanece agnóstica
   al proveedor.
3. La KB sigue siendo la fuente de verdad de capacidades disponibles y no
   disponibles. No se codifican preguntas, frases o módulos inexistentes en PHP.
4. `available` representa una guía de una funcionalidad implementada.
   `unavailable` representa una funcionalidad verificada como no disponible.
5. Una evidencia `unavailable` debe producir una respuesta grounded que indique
    que la funcionalidad no está disponible. La ausencia de evidencia conserva la
    respuesta local de información insuficiente.
6. Una evidencia `not_allowed_for_role` debe indicar que la funcionalidad existe,
   pero el rol recuperado no puede ejecutar la acción, y orientar solo hacia una
   alternativa permitida por la misma evidencia.
7. El filtro por rol ocurre antes del ranking, tanto para evidencia disponible
    como no disponible.
   Una evidencia `not_allowed_for_role` solo se recupera cuando está autorizada
   para el rol consultante y explica su propia restricción; nunca habilita ni
   expone instrucciones operativas de otro rol.
8. Las trazas no registran preguntas, respuestas, prompts, chunks, PII, datos
   clínicos, payloads completos ni secretos.

## Hallazgos baseline

* C2 y A4 recuperan documentos relevantes, pero el ranking original prioriza
  chunks genéricos o relacionados por encima de `Cómo hacerlo`.
* Las guías de solicitar atención y completar sesión tenían drift respecto de la
  interfaz implementada.
* `en curso` es un estado de tratamiento, no de solicitud de atención. Las
  solicitudes solo pueden estar pendientes, resueltas o canceladas.
* Turnos y Agenda son funcionalidades documentadas de uso; Simón no consulta su
  disponibilidad ni datos dinámicos.

## Fuera de alcance

SIMON-04 no implementa embeddings, vector database, retrieval semántico o
híbrido, reranking con IA, memoria, persistencia de chat, streaming, tool
calling, acciones, datos dinámicos, información clínica, agenda, turnos,
notificaciones ni rediseño de UI.

## Criterios de aceptación

* [ ] Las preguntas de evaluación incluyen guía disponible de Turnos y Agenda y
  mantienen fuera de alcance las consultas de datos dinámicos.
* [ ] C2 y A4 recuperan primero evidencia procedimental relevante sin depender
  de un módulo enviado por la UI.
* [ ] C5 recupera `NOT_ALLOWED_FOR_ROLE`, explica que un cliente no puede asignar
  tratamientos y lo orienta a consultarlos en modo lectura.
* [ ] A1 no presenta `en curso` como estado de una solicitud de atención.
* [ ] A5 recupera evidencia procedimental de Turnos y Agenda sin inventar datos
  dinámicos.
* [ ] Las trazas registran chunks, documentos únicos, caracteres, palabras,
  latencia y usage del proveedor cuando está disponible.
* [ ] La suite usa mocks/fakes del contrato o `Http::fake()`; nunca Groq real.
* [ ] La evaluación manual puede repetirse sin registrar contenido sensible.
