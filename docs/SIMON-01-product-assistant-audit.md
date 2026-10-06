# SIMON-01 - Auditoria del asistente de producto

Fecha: 2026-10-06

## 1. Estado actual

VetZen cuenta con una base reutilizable para Simón:

- Laravel 13, React 19, Inertia 3, Wayfinder, Spatie Permission y colas `database` configuradas.
- Roles reales: `admin` y `client`; no existe aun el rol `professional`.
- Flujos implementados: registro, perfil, mascotas/pacientes, historia clinica, catalogo clinico, solicitudes de atencion, tratamientos y sesiones.
- Backend con Policies, ownership y bindings anidados para los recursos clinicos.
- Inertia comparte `auth.user` y `auth.roles`; el frontend puede conocer la URL actual y calcular el modulo activo.
- No hay codigo, paquete, proveedor, endpoint, base vectorial ni UI existente para IA/RAG.
- No hay `Http\Resources`; las props Inertia se construyen directamente.

Fuentes relevantes: `routes/web.php:29-130`, `app/Http/Middleware/HandleInertiaRequests.php:36-49`, `resources/js/lib/navigation.ts:21-191`, `docs/ui-audit/README.md:7-16`.

## 2. Gaps de documentacion

La documentacion tecnica y de producto es util como contexto, pero no es una base de conocimiento de usuario directamente utilizable.

- `spec.md` y `features.md` describen agenda, turnos, notificaciones, multiples profesionales e IA dinamica que no estan implementados.
- El codigo no tiene agenda, turnos, profesionales adicionales, gestion de permisos, notificaciones ni IA/RAG.
- `technical.md` aun lista Vue como decision pendiente pese a que el proyecto usa React/Inertia.
- Hay inconsistencia entre el concepto futuro de "profesional" y el rol real `admin`.
- La UI real tiene terminologia solida en `features/UX-00-panel-redesign-spec.md`, pero el flujo de autenticacion aun conserva textos en ingles.
- No existe documentacion procedural validada para usuarios. `docs/ui-audit/06-user-flows.md` es la mejor fuente inicial, pero es una auditoria interna, no ayuda publicada.
- Algunas preguntas ejemplo no corresponden al sistema actual:
  - Un admin no puede registrar un cliente desde el panel: solo lista y edita clientes. El registro publico crea `User + Client`.
  - No existen turnos ni reprogramacion.
  - El cliente no tiene una pantalla global "Mis solicitudes" o "Mis tratamientos"; ambos recursos estan dentro de cada mascota.
  - El cliente no completa sesiones; solo las consulta. El admin las administra dentro del tratamiento del paciente.

## 3. Knowledge Base propuesta

Crear una base de conocimiento de ayuda independiente y curada. No indexar automaticamente `spec.md`, `technical.md`, `features/` ni codigo.

```text
docs/help/
  README.md
  getting-started/
    crear-cuenta.md
    iniciar-sesion-y-cuenta.md
  client/
    gestionar-mascotas.md
    solicitar-atencion.md
    consultar-solicitudes.md
    consultar-tratamientos-y-sesiones.md
    consultar-historia-clinica.md
  admin/
    gestionar-clientes-y-pacientes.md
    gestionar-historia-clinica.md
    gestionar-solicitudes-de-atencion.md
    asignar-y-gestionar-tratamientos.md
    gestionar-sesiones.md
    gestionar-catalogo-clinico.md
  concepts/
    solicitud-de-atencion.md
    tratamiento-del-paciente.md
    sesion-del-tratamiento.md
```

No incluir turnos, agenda, notificaciones, profesionales ni acciones inexistentes. Cada guia debe ser escrita y revisada contra ruta, pantalla y prueba existentes.

Metadata minima util:

```yaml
---
id: client.solicitar-atencion
title: Solicitar atencion para una mascota
roles: [client]
module: service-requests
page_context: [services, pets]
route_names:
  - services.show
  - pets.service-requests.create
status: available
source_refs:
  - routes/web.php
  - docs/ui-audit/06-user-flows.md
reviewed_at: 2026-10-06
---
```

Evitar version manual, URLs hardcodeadas, permisos duplicados y metadata clinica innecesaria. Para navegacion futura, agregar una accion estructurada opcional con `route_name` y parametros permitidos, nunca una URL generada por el modelo.

## 4. Arquitectura RAG recomendada

Para V1, una arquitectura simple y grounded:

```text
Guias curadas y aprobadas
  -> validacion de metadata y enlaces
  -> chunks pequenos por tarea
  -> indice de busqueda con filtros de rol/modulo
  -> pregunta + rol + contexto de pagina
  -> recuperacion de evidencia permitida
  -> generacion con evidencia obligatoria
  -> validacion de salida y respuesta
```

Recomendacion inicial:

- Empezar con busqueda por keywords/full-text mas filtros de metadata.
- Agregar embeddings e hibrido solo cuando la evaluacion demuestre fallos de recuperacion por variaciones de lenguaje.
- No usar una base vectorial dedicada inicialmente.
- No introducir LangChain ni un framework RAG externo.
- Mantener una interfaz Laravel propia y pequena entre recuperacion, proveedor de embeddings y generacion.

RAG no exige una base vectorial especializada. Con un corpus pequeno y curado, la precision depende mas de los filtros, la calidad de las guias y la politica de evidencia que de la sofisticacion del indice.

## 5. Integracion Laravel / React / Inertia

### Laravel

- Autoriza el endpoint de Simón.
- Deriva rol desde el usuario autenticado.
- Valida y normaliza contexto de pagina.
- Filtra la KB antes de recuperar contenido.
- Ejecuta retrieval, llamada al proveedor y validacion grounded.
- Aplica rate limiting, logging seguro y limites de uso.
- No entrega datos clinicos ni personales al modelo en V1.

### React/Inertia

- Integra el disparador persistente dentro de `AppLayout`.
- Obtiene rol desde props compartidas y la ruta desde `usePage().url`.
- Envia un identificador de contexto permitido, no datos de mascota, cliente o historia clinica.
- Renderiza respuestas, fuentes y futuros enlaces estructurados con Wayfinder.

### Infraestructura

- El indice y la KB deben gestionarse desde Laravel.
- La indexacion posterior puede usar la cola `database` ya disponible.
- No requiere streaming, tool calling ni persistencia conversacional en V1.

## 6. Retrieval y grounding

La politica debe ser "sin evidencia, no responder".

- Filtrar primero por `roles`; un cliente nunca recupera guias administrativas.
- Usar `module` y `page_context` como boost, no como restriccion absoluta, para permitir preguntas globales.
- Recuperar pocos chunks: inicialmente `top-k` de 3 a 5.
- Exigir que cada respuesta se base unicamente en chunks recuperados.
- Devolver identificadores de guia o fuente junto con la respuesta para trazabilidad.
- Si no hay evidencia suficiente, responder con una negativa estandar y sugerir solo funciones documentadas relacionadas.
- Detectar consultas clinicas y responder que Simón ayuda a usar VetZen, no brinda orientacion veterinaria.
- Validar que una accion de navegacion referenciada exista en un registro estructurado de rutas permitidas.

No usar reranking inicialmente. Solo justificarlo si los tests de referencia prueban que keyword mas metadata y, si se adopta, embeddings no alcanzan la precision requerida.

## 7. Contexto y roles

Contexto util para V1:

- Rol efectivo: `admin` o `client`.
- Ruta o pagina actual.
- Modulo normalizado: `pets`, `service-requests`, `treatments`, `services`, entre otros.
- Historial conversacional breve.

Contexto que no debe enviarse:

- Nombre, email, telefono, documento o direccion.
- IDs de usuario, cliente, mascota, solicitud o tratamiento.
- Historia clinica, contenido clinico, notas, precios o sesiones reales.
- Roles y permisos no necesarios para la respuesta.

El navegador puede enviar la URL actual, pero Laravel debe validarla contra una lista cerrada de contextos. El rol y cualquier capacidad se derivan siempre del usuario autenticado; no se confia en valores enviados desde React.

## 8. Seguridad y privacidad

Riesgos y mitigaciones:

- Datos clinicos: excluirlos completamente de Simón V1.
- Escalada de rol: filtrar retrieval en backend antes del prompt.
- Prompt injection: tratar la KB como contenido no ejecutable; no seguir instrucciones presentes en documentos; separar instrucciones del sistema, pregunta y evidencia.
- Respuestas inventadas: evidencia obligatoria, rechazo por falta de fuentes y pruebas de regresion.
- Abuso y costo: rate limiter especifico por usuario e IP, limite de tamano de pregunta, limite de turnos y `top-k` acotado.
- Logs: guardar metricas y referencias de documento, no prompts o respuestas completas por defecto; definir retencion antes de persistir conversaciones.
- Proveedor externo: enviar solo pregunta saneada, rol y contexto funcional minimo.

La observacion actual de que `is_visible_to_client` no bloquea efectivamente la lectura de historia clinica refuerza la decision de no exponer informacion clinica a Simón V1.

## 9. Infraestructura

Necesario para V1:

- Directorio versionado de guias de ayuda.
- Parser y validador de frontmatter.
- Persistencia de documentos y chunks.
- Busqueda textual filtrada por metadata.
- Endpoint autenticado y rate-limited.
- Adaptador de proveedor de generacion, una vez aprobado.
- Cola existente para reindexacion cuando haya embeddings.

No necesario inicialmente:

- Pinecone, pgvector, Redis, Elasticsearch, LangChain, streaming, tool calling, memoria persistente, nueva base de datos o proveedor vectorial dedicado.

Hay una discrepancia importante: el repositorio soporta MySQL/MariaDB, pero `.env.example` usa SQLite como valor por defecto. Antes de disenar un indice SQL hay que confirmar la base de datos canonica de produccion.

## 10. Testing

La validacion debe combinar pruebas deterministas y evaluacion de respuestas.

- Tests de KB: metadata valida, documentos con rol conocido, referencias de ruta existentes y ausencia de rutas o acciones prohibidas.
- Tests de retrieval: cada pregunta de referencia recupera el documento esperado.
- Tests de autorizacion: un cliente no recupera documentos `admin`; un usuario no autenticado no accede al endpoint.
- Tests de grounding: sin documentos elegibles o con score insuficiente devuelve la respuesta de falta de evidencia.
- Tests de alcance: consultas clinicas reciben rechazo apropiado; turnos y reprogramaciones no se presentan como funciones disponibles.
- Tests de integracion: fake del proveedor para verificar prompt minimo, contexto filtrado y esquema de respuesta.
- Evaluacion humana versionada: conjunto corto de preguntas por rol, respuesta esperada, documentos admisibles y criterios de precision.

Preguntas minimas:

- Cliente: registrar o editar mascota, solicitar atencion, consultar solicitud, tratamiento, sesiones e historia clinica.
- Admin: gestionar paciente, historia clinica, solicitud, tratamiento, sesiones y catalogo.
- Fuera de alcance: medicacion, diagnostico, turnos, reprogramacion y permisos no implementados.

## 11. Mantenimiento

Regla recomendada para futuras features:

```text
Feature implementada
-> pruebas y verificacion
-> guia de usuario creada o actualizada
-> referencias KB validadas
-> reindexacion
-> actualizacion del set de evaluacion
```

La guia de usuario debe ser un requisito solo cuando la feature exponga o cambie una tarea visible para usuarios. Cambios internos sin impacto de uso no deben generar documentacion de ayuda artificial.

## 12. Decisiones pendientes

Requieren aprobacion humana antes de implementar la fase RAG y generacion:

1. Confirmar la base de datos y version de produccion: MySQL, MariaDB o SQLite.
2. Aprobar el proveedor de generacion y las condiciones de privacidad, retencion, region y limites de gasto.
3. Decidir si V1 usa solo busqueda textual filtrada o si incorpora embeddings desde el inicio.
4. Definir si se guardan conversaciones y feedback; la recomendacion es no persistir conversaciones en V1 y si registrar metricas agregadas y preguntas sin respuesta.
5. Aprobar la politica de observabilidad y retencion para preguntas, errores, feedback y referencias recuperadas.

No hace falta decidir todavia proveedor vectorial, streaming, tool calling, memoria persistente ni base de datos adicional.

## 13. Documentacion a crear o modificar

Cuando se resuelvan las decisiones:

- Crear `features/SIMON-01-product-assistant.md`.
- Crear `docs/help/` y las guias curadas de la KB.
- Actualizar `features.md` para registrar SIMON como alcance V1 de ayuda de producto, no como asistente dinamico o clinico.
- Actualizar `spec.md` para distinguir explicitamente el alcance futuro del asistente de la restriccion de SIMON V1.
- Actualizar `technical.md` con proveedor aprobado, base de datos canonica, estrategia de retrieval, limites, logging y reindexacion.
- Actualizar `AGENTS.md` Definition of Done con la actualizacion de ayuda/KB para features de cara al usuario.
- Agregar un set de evaluacion versionado junto a la feature o dentro de `tests/Fixtures/Simon/`, segun las convenciones que se definan al implementar.

`SIMON-01-product-assistant.md` es consistente con los prefijos funcionales `UX-*` existentes y evita forzar una numeracion historica que ya es inconsistente.

## 14. Plan de implementacion

No corresponde cerrar un plan implementable todavia: la base documental es suficiente para la fase de especificacion y KB, pero faltan decisiones obligatorias sobre base de datos de produccion, proveedor y tratamiento de conversaciones/logs.

Tras resolverlas, el plan debe iniciar con la especificacion SIMON y la KB curada, antes de cualquier integracion de IA, indexacion o UI.

## 15. Recomendacion final

**NEEDS PRODUCT/ARCHITECTURE DECISIONS**

VetZen ya tiene suficiente funcionalidad real para construir una KB confiable de uso del producto, limitada a los modulos actuales. No esta listo para implementar RAG/generacion hasta confirmar la base de datos productiva, el proveedor con sus condiciones de privacidad y la estrategia inicial de retrieval.
