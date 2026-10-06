# SIMON-01 - Base de conocimiento del asistente de producto

> Estado: en implementación documental. Esta feature no incorpora código de IA,
> endpoints, dependencias, persistencia, embeddings ni interfaz de chat.

## 1. Objetivo

Crear la base de conocimiento curada que permitirá a Simón explicar cómo usar
las funcionalidades reales de VetZen. Simón es un asistente de producto: da
instrucciones breves, accionables y adaptadas al rol para navegar y operar el
sistema.

SIMON-01 prepara únicamente documentación de ayuda. La recuperación, la
generación con modelos de lenguaje y toda consulta de información dinámica se
implementarán en features posteriores.

## 2. Alcance

La feature crea documentación de ayuda en `docs/help/` para los módulos
implementados:

* creación de cuenta y acceso a la cuenta;
* mascotas y pacientes;
* solicitudes de atención;
* historia clínica;
* catálogo clínico;
* tratamientos y sesiones.

Cada guía debe describir solo acciones que estén disponibles en rutas,
controladores, Policies, pantallas y pruebas existentes. Las guías reflejan el
vocabulario visible actual: "Mascota" para clientes, "Paciente" para admin,
"Solicitud de atención", "Servicio clínico", "Plantilla de tratamiento",
"Tratamiento del paciente" y "Sesión".

## 3. Fuera de alcance

SIMON-01 no implementa:

* chat, UI, endpoint o streaming;
* llamadas a un proveedor LLM, credenciales, modelos o parámetros de proveedor;
* parsing, chunking, almacenamiento, búsqueda textual, embeddings, vector store
  o retrieval;
* acceso del asistente a clientes, mascotas, tratamientos, sesiones o historia
  clínica;
* tool calling, acciones, navegación ejecutable, memoria o analytics;
* turnos, agenda, disponibilidad, reprogramación, notificaciones, profesionales
  adicionales, roles adicionales o permisos administrativos.

## 4. Roles y alcance funcional real

Los únicos roles implementados son `client` y `admin`.

### Cliente

Puede crear y administrar sus propias mascotas, solicitar atención para una
mascota propia, y consultar en modo lectura solicitudes, tratamientos, sesiones

No puede administrar catálogo, resolver solicitudes, asignar tratamientos,
editar sesiones ni escribir historia clínica.

### Administrador

Puede consultar y editar clientes existentes, administrar pacientes, historia
clínica, solicitudes, catálogo, tratamientos asignados y sesiones. No existe una
ruta administrativa para crear clientes ni para crear solicitudes de atención.

## 5. Reglas de conocimiento

1. El código implementado prevalece cuando contradice documentación histórica o
   de planificación.
2. La KB no describe turnos, diagnósticos, medicamentos, reprogramaciones,
   notificaciones ni acciones inexistentes.
3. Una guía no puede presentar una acción administrativa como disponible para
   `client`.
4. La KB no contiene datos reales de usuarios, mascotas ni información clínica.
5. Cada guía incluye referencias a las fuentes verificadas y una fecha de
   revisión.
6. Si una futura respuesta no cuenta con una guía aplicable, Simón debe indicar
   que no tiene información suficiente, sin completar pasos por inferencia.
7. Las consultas clínicas deben recibir una aclaración de alcance: Simón ayuda a
   usar VetZen y no brinda orientación veterinaria.

## 6. Formato de las guías

Las guías usan frontmatter YAML con los campos:

* `id`: identificador estable para retrieval y pruebas futuras;
* `title`: título visible;
* `roles`: roles que pueden recuperar la guía;
* `module`: módulo funcional normalizado;
* `route_names`: rutas Laravel relacionadas, no URLs estáticas;
* `keywords`: sinónimos de intención para búsqueda textual futura;
* `status`: disponibilidad del comportamiento documentado;
* `feature_ids`: features que originan el comportamiento;
* `source_refs`: fuentes verificadas;
* `reviewed_at`: fecha de última revisión funcional.

No se usa `version`: Git conserva el historial. No se persisten URLs ni
permisos en la KB, porque las rutas con parámetros se generan posteriormente y
la autorización siempre sigue perteneciendo a Laravel.

## 7. Arquitectura futura y límites

La evolución prevista es:

```text
SIMON-01: Knowledge Base curada
SIMON-02: Chunking, almacenamiento y búsqueda textual
SIMON-03: Generación grounded mediante un proveedor LLM
SIMON-04: Embeddings y búsqueda semántica/híbrida si la evaluación lo justifica
```

La generación se integra mediante una abstracción de proveedor LLM, aislada de
dominio y autorización. Groq es el proveedor inicial para desarrollo y pruebas;
OpenAI sigue siendo una alternativa futura. El proveedor de producción se
definirá según calidad, costo, latencia, límites, privacidad y necesidades reales
de VetZen. API key, modelo, parámetros y límites dependen del entorno.

El modelo no tendrá acceso directo a la base de datos. Todo dato dinámico futuro
deberá obtenerse desde operaciones Laravel explícitas y autorizadas. La
información clínica no forma parte del contexto de Simón hasta que una feature
posterior defina y apruebe ese acceso específicamente.

## 8. Seguridad futura

Las siguientes etapas deben respetar estas reglas:

* Laravel determina autenticación, rol, autorización y ownership.
* El frontend solo comunica contexto de página; no prueba permisos.
* El rol se deriva del usuario autenticado, no del payload del navegador.
* El contexto de página debe validarse contra una lista de módulos y rutas
  permitidas.
* El filtro por rol ocurre antes del retrieval.
* El contexto enviado al proveedor se limita a pregunta, rol, contexto funcional
  validado y documentación recuperada.
* No se envían PII, IDs, notas, contenido clínico, precios ni datos de terceros
  para responder preguntas de uso.
* Las instrucciones presentes en documentos o preguntas no pueden reemplazar
  reglas de sistema, filtros ni Policies.

## 9. Criterios de aceptación

* [x] Existe `docs/help/README.md` con formato, fuentes y gobernanza de la KB.
* [x] Existen guías de cliente únicamente para acciones disponibles para
  `client`.
* [x] Existen guías de administración únicamente para acciones disponibles para
  `admin`.
* [x] Existen conceptos que distinguen servicio, solicitud, plantilla,
  tratamiento asignado y sesión.
* [x] Ninguna guía afirma que VetZen tiene turnos, agenda, reprogramación,
  notificaciones, roles profesionales o capacidades clínicas de IA.
* [x] Las guías contienen rutas, fuentes y terminología verificadas.
* [x] La documentación global registra una arquitectura LLM agnóstica y el roadmap
  SIMON-01 a SIMON-04.

## 10. Testing y validación documental

SIMON-01 no agrega tests de aplicación. La revisión documental debe comprobar:

1. que cada `route_name` exista en `routes/web.php`;
2. que el rol declarado coincida con middleware, Policies y UI disponible;
3. que los pasos no mencionen acciones fuera de alcance;
4. que cada fuente referenciada exista;
5. que las preguntas de referencia por rol tengan una guía aplicable;
6. que preguntas clínicas o sobre turnos tengan una respuesta de alcance, no una
   guía que afirme una función inexistente.

## 11. Dependencias

SIMON-01 depende de Features 04 a 08 y de UX-00 a UX-08 solo como fuentes de
comportamiento y terminología. No modifica su dominio, autorización ni
implementación.
