# Análisis del módulo Simón

## Propósito actual

Simón es el asistente de uso del producto VetZen. Responde cómo utilizar
funcionalidades implementadas a partir de documentación curada, autorizada y
filtrada por rol.

No consulta datos dinámicos, no ejecuta acciones, no conserva conversaciones y
no brinda diagnósticos, recomendaciones veterinarias ni indicaciones de
medicamentos.

## Base de conocimiento

Las guías fuente viven en `docs/help/` y se organizan por rol y tema:

```text
docs/help/
├── admin/
├── client/
├── concepts/
└── getting-started/
```

Cada archivo Markdown declara frontmatter con identificador, roles, módulo,
rutas relacionadas, keywords, estado, fuentes y fecha de revisión.

El comando `php artisan simon:index-knowledge-base` valida estas guías y
sincroniza su índice persistido.

## Tablas

### `assistant_knowledge_documents`

Representa una guía de la base de conocimiento. Persiste, entre otros:

* `document_id` estable;
* ruta del archivo fuente;
* título, módulo y estado;
* roles autorizados;
* rutas, keywords, features y fuentes de referencia;
* contenido completo y hash de contenido.

### `assistant_knowledge_chunks`

Representa los fragmentos recuperables de cada guía. Persiste:

* documento padre;
* posición y encabezado;
* contenido del fragmento;
* `search_text` normalizado para búsqueda textual.

Cada chunk pertenece a un documento y se elimina en cascada al eliminarlo.

No existen tablas para historial de chat, mensajes, memoria, feedback,
embeddings, vectores ni datos clínicos.

## Flujo de una pregunta

```text
Usuario autenticado
  ↓
GET /simon
  ↓
Página Inertia con conversación temporal
  ↓
POST /simon/messages { question }
  ↓
auth + throttle:simon + StoreSimonMessageRequest
  ↓
SimonMessageController
  ↓
SimonProductAssistant
  ↓
AssistantKnowledgeBaseSearch
  ↓
¿Hay chunks autorizados y relevantes?
  ├─ No: respuesta local de falta de evidencia
  └─ Sí: AssistantResponseGenerator
            ↓
          GroqAssistantResponseGenerator
            ↓
          respuesta grounded
  ↓
JSON { answer, sources }
  ↓
Página actualiza la conversación temporal
```

## Recuperación textual

`AssistantKnowledgeBaseSearch`:

1. deriva términos normalizados de la pregunta;
2. descarta stop words y términos de menos de tres caracteres;
3. filtra documentos `available` por el rol efectivo antes de puntuar;
4. puntúa coincidencias de términos sobre `search_text`;
5. aplica un boost si recibe un módulo válido;
6. devuelve como máximo `SIMON_RETRIEVAL_LIMIT`, actualmente cinco chunks.

La interfaz actual no envía módulo, por lo que la búsqueda cubre toda la KB
autorizada del rol. Si no hay evidencia, Simón responde localmente y no invoca
al proveedor LLM.

## Autorización y límites de datos

La interfaz no determina ni envía el rol. Laravel deriva `admin` o `client` del
usuario autenticado en cada solicitud.

La página `/simon` y el endpoint de mensajes solo están disponibles para esos
roles. La UI envía únicamente la pregunta; no incluye IDs, clientes, mascotas,
tratamientos, información clínica ni otros datos dinámicos.

Antes de enviar una solicitud a Groq, Laravel limita el contexto a:

* rol efectivo;
* pregunta;
* módulo validado, si existe;
* chunks de documentación recuperados y autorizados;
* instrucciones de seguridad de Simón.

Laravel genera las fuentes de respuesta. No confía en citas, roles o permisos
que pudiera producir el modelo.

## Integración Groq

La aplicación depende del contrato:

```text
App\Contracts\AssistantResponseGenerator
```

El composition root actual lo vincula con:

```text
App\Services\GroqAssistantResponseGenerator
```

Groq se llama mediante su endpoint compatible con chat completions. La llamada
incluye mensajes `developer` y `user`, el modelo configurado y timeouts de
entorno.

Configuración requerida:

```env
SIMON_LLM_PROVIDER=groq
SIMON_LLM_MODEL=un-modelo-chat-activo-de-groq
GROQ_API_KEY=...
GROQ_BASE_URL=https://api.groq.com/openai/v1
```

La API key no se expone al frontend. Si el proveedor, key o modelo no están
configurados, o Groq falla, Laravel devuelve un error seguro `503`.

## Interfaz

La página `resources/js/pages/simon/index.tsx` ofrece una conversación de
evaluación:

* acceso desde el sidebar para `admin` y `client`;
* mensajes mantenidos solo en estado React;
* estado de carga, validación, rate limit, red y proveedor no disponible;
* respuesta y fuentes de KB;
* sin streaming, adjuntos, Markdown enriquecido, memoria ni persistencia.

Al recargar o abandonar la página, los mensajes dejan de mostrarse.

## Observabilidad

`SIMON_TRACE_LOGGING` habilita trazas estructuradas. En desarrollo se habilita
desde `.env.example`; en producción debe definirse explícitamente según la
política operativa.

Eventos actuales:

```text
simon.message.received
simon.retrieval.completed
simon.response.local_no_evidence
simon.provider.requested
simon.provider.request_started
simon.provider.completed
simon.provider.failed
simon.provider.not_configured
simon.message.completed
simon.message.failed
```

Las trazas incluyen correlación, usuario interno, rol, módulo, longitudes,
conteos, documentos fuente, modelo, duración y tipo de error. No registran la
pregunta, respuesta, chunks, payload hacia Groq ni secretos.

## Verificación operativa

Para que una pregunta alcance Groq se deben cumplir estos pasos:

1. La migración de Knowledge Base debe estar aplicada.
2. La KB debe estar indexada con `php artisan simon:index-knowledge-base`.
3. La pregunta debe producir chunks relevantes para el rol autenticado.
4. `GROQ_API_KEY` y `SIMON_LLM_MODEL` deben estar configurados.
5. Los logs deben mostrar `chunks_count > 0`, seguido de
   `simon.provider.request_started` y `simon.provider.completed`.

Una secuencia que termina en `simon.response.local_no_evidence` indica que no
hubo evidencia recuperada y que Groq no fue llamado.

## Evolución recomendada

1. Evaluar preguntas reales por rol y módulo.
2. Medir respuestas sin evidencia, documentos recuperados, fallos y latencia.
3. Mejorar guías y keywords cuando existan preguntas válidas sin recuperación.
4. Crear un set de evaluación repetible para `admin` y `client`.
5. Evaluar si la recuperación textual resulta insuficiente antes de incorporar
   embeddings o búsqueda híbrida.
6. Definir por separado cualquier memoria conversacional, datos dinámicos,
   tool calling o acciones.

Las etapas futuras no deben habilitar acceso clínico ni datos de clientes sin
definir previamente ownership, autorización, auditoría, propósito, retención y
pruebas de aislamiento.
