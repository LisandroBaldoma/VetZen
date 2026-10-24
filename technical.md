# **VetZen — Technical Specification**

## **1\. Propósito**

Este documento define los lineamientos técnicos y arquitectónicos generales para el desarrollo de **VetZen**.

Su objetivo es establecer criterios comunes para la construcción del sistema y servir como contexto técnico para desarrolladores y agentes de IA.

Este documento complementa `spec.md`.

* `spec.md` define **qué debe hacer el producto**.  
* `technical.md` define **cómo se construirá técnicamente el producto**.  
* Las especificaciones de cada feature definirán posteriormente el comportamiento concreto de cada funcionalidad.

Cuando exista una contradicción entre una especificación de producto y una decisión técnica, deberá identificarse la contradicción antes de implementar.

Cuando una decisión técnica no esté definida, no deberá asumirse arbitrariamente: deberá registrarse como decisión pendiente.

---

# **2\. Principios generales**

El desarrollo de VetZen deberá seguir los siguientes principios:

* Mantener una arquitectura clara y organizada.  
* Priorizar código mantenible y fácil de extender.  
* Separar responsabilidades.  
* Evitar duplicación innecesaria.  
* Priorizar soluciones simples antes que soluciones excesivamente complejas.  
* Mantener las reglas de negocio fuera de las capas de presentación cuando corresponda.  
* Aplicar autorización en backend y no únicamente en la interfaz.  
* Proteger especialmente la información clínica.  
* Crear pruebas para las reglas de negocio importantes.  
* Evitar incorporar dependencias innecesarias.  
* No introducir funcionalidades que no estén definidas en `spec.md` o en la especificación de una feature.

---

# **3\. Stack tecnológico**

El stack tecnológico definitivo deberá establecerse antes de comenzar la implementación.

Actualmente se contempla:

### **Backend**

* PHP  
* Laravel

### **Frontend**

* React  
* Inertia

### **Base de datos**

* Base de datos relacional.

### **Autenticación**

* Laravel Fortify para autenticación local mediante sesión.
* Laravel Socialite con Google OAuth como método adicional de registro e inicio de sesión.
* La identidad externa se almacenará mediante el identificador estable de Google, sin persistir tokens OAuth mientras no se consuman APIs de Google.

### **Autorización**

* Spatie Laravel Permission.

### **Procesamiento en segundo plano**

* Sistema de Jobs/Queues de Laravel.

### **Notificaciones**

* Email.  
* Notificaciones internas.

### **Inteligencia artificial**

* Arquitectura agnóstica de proveedor de modelo de lenguaje.
* Groq como proveedor inicial para desarrollo y pruebas con
  `openai/gpt-oss-20b` como baseline de SIMON-04.
* OpenAI como alternativa futura compatible.
* Embeddings.  
* Búsqueda semántica.  
* Arquitectura RAG.  
* Herramientas controladas para acceder a información dinámica del sistema.

La integración LLM se realiza mediante una capa de aplicación del asistente, sin
acoplar la lógica de dominio, autorización o acceso a datos a Groq, OpenAI u
otro proveedor. Groq es el proveedor inicial para desarrollo y pruebas. El
proveedor de producción se decidirá posteriormente según calidad, costo,
latencia, límites, privacidad y necesidades reales de VetZen. La API key, el
modelo, los parámetros, los límites y los timeouts son valores configurables por
entorno. `openai/gpt-oss-20b` es el baseline de desarrollo/pruebas de
SIMON-04. El modelo de producción permanece pendiente de decisión.

---

# **4\. Arquitectura general**

VetZen deberá utilizar una arquitectura que permita separar claramente:

* Presentación.  
* Lógica de negocio.  
* Persistencia.  
* Autorización.  
* Servicios externos.  
* Procesamiento asíncrono.  
* Integraciones de inteligencia artificial.

La arquitectura deberá permitir que las funcionalidades puedan evolucionar sin generar dependencias innecesarias entre módulos.

Las decisiones concretas sobre estructura interna deberán mantenerse consistentes durante todo el desarrollo.

---

# **5\. Backend**

El backend será responsable de:

* Aplicar las reglas de negocio.  
* Validar información.  
* Controlar autorización.  
* Gestionar información clínica.  
* Gestionar clientes y pacientes.  
* Gestionar tratamientos.  
* Gestionar turnos.  
* Gestionar notificaciones.  
* Gestionar integraciones externas.  
* Controlar el acceso del asistente virtual a la información.

Las operaciones sensibles deberán validarse en el backend independientemente de las restricciones existentes en la interfaz.

---

# **6\. Frontend**

El frontend deberá proporcionar las interfaces necesarias para los perfiles definidos en `spec.md`.

Deberá contemplar:

* Interfaz para profesionales.  
* Interfaz para clientes.  
* Formularios.  
* Validación y presentación de errores.  
* Estados de carga.  
* Estados vacíos.  
* Confirmaciones de operaciones relevantes.  
* Diseño responsive.

La interfaz no deberá ser considerada una capa de seguridad. Las restricciones de acceso deberán validarse también en el backend.

---

# **7\. Autenticación y autorización**

VetZen utilizará autenticación de usuarios y autorización mediante roles y permisos.

Existirán múltiples profesionales con diferentes niveles de permisos.

El sistema deberá permitir controlar:

* Acceso a funcionalidades.  
* Acceso a información clínica.  
* Acceso a información de clientes.  
* Acceso a información de pacientes.  
* Administración de servicios.  
* Administración de tratamientos.  
* Administración de turnos.  
* Administración de usuarios.

Los profesionales autorizados podrán consultar y modificar información clínica.

Los clientes solamente podrán acceder a información correspondiente a sus propias mascotas y a la información que el producto determine como accesible para ellos.

---

# **8\. Protección de información clínica**

La información clínica deberá considerarse información sensible dentro del sistema.

El acceso deberá estar controlado mediante autorización.

Las operaciones de consulta y modificación deberán respetar los permisos correspondientes.

Las modificaciones relevantes sobre información clínica deberán poder ser auditadas.

El asistente virtual no tendrá acceso indiscriminado a la información clínica.

---

# **9\. Reglas de acceso de clientes**

El sistema deberá garantizar que un cliente solamente pueda acceder a información correspondiente a sus propias mascotas.

Esto deberá aplicarse independientemente de la interfaz utilizada para acceder al sistema.

La misma regla deberá aplicarse al asistente virtual.

El hecho de que una información exista en la base de datos no implica que el usuario pueda consultarla.

---

# **10\. Gestión de turnos**

La agenda única se implementará con fechas publicadas, bloques de trabajo,
inicios explícitamente habilitados, servicios habilitados por fecha y capacidad
compartida configurable de uno a cinco pacientes.

La fuente de verdad de disponibilidad será el backend. Las reservas y cambios de
agenda usarán transacciones y bloqueos de filas sobre la fecha de agenda afectada.
La ocupación incluye estados `pending` y `approved`, y se calcula sobre todo el
intervalo `[starts_at, ends_at)`, no mediante un conteo simple de turnos
solapados. Las operaciones entre dos fechas bloquean las fechas en orden estable
para evitar interbloqueos.

Los instantes se persistirán en UTC y se validarán y presentarán en la zona
horaria explícita de la veterinaria, `America/Argentina/Buenos_Aires`. El motor
objetivo es MySQL. Las pruebas de concurrencia deberán ejecutarse contra MySQL;
SQLite no valida semánticas de bloqueo equivalentes.

La reprogramación debe ser atómica: reserva el intervalo destino y libera el
origen en la misma transacción, o conserva el turno original. Las modificaciones
de agenda no pueden invalidar turnos pendientes o aprobados existentes.

---

# **11\. Tratamientos y seguimiento**

La implementación de servicios y procedimientos corresponde a Feature 07. Los
tratamientos de catálogo, asignaciones y sesiones corresponden a Feature 08.

El dominio terapéutico se compone de servicios, procedimientos, tratamientos
de catálogo, tratamientos asignados a mascotas y sesiones.

`Service` representa un área general y no contiene precio ni duración.
`Procedure` pertenece a un servicio, puede contener una duración orientativa y
no contiene precio. `Treatment` pertenece a un servicio, agrupa procedimientos
de ese mismo servicio y define una cantidad estimada de sesiones.

`ServiceRequest` representa únicamente la intención de un cliente de solicitar
atención para un `Service` activo y una `Pet` propia. Su ownership se deriva por
`User → Client → Pet → ServiceRequest`; no duplica `client_id` ni `user_id` y no
acepta relaciones de ownership desde el payload. Sus estados son `pending`,
`resolved` y `cancelled`.

`PetTreatment` representa la asignación concreta a una mascota y conserva
snapshots históricos de las condiciones acordadas. `TreatmentSession`
pertenece a esa asignación y conserva precio, moneda y estado propios. Los
importes deberán persistirse como decimales, nunca float.

La creación y ajuste de sesiones deberá ejecutarse transaccionalmente,
manteniendo numeración única y sin modificar precios históricos. El progreso se
calculará exclusivamente como sesiones completadas sobre `planned_sessions`.

Una sesión cancelada se conserva como historial, no altera
`planned_sessions` y no cuenta para el progreso. Salvo cuando el tratamiento
esté suspendido o cancelado, la cancelación deberá garantizar que sesiones
completadas más pendientes alcancen el total requerido, generando
transaccionalmente un reemplazo `pending` con el siguiente número consecutivo y
el precio predeterminado vigente. Los números cancelados no se reutilizan.

Los clientes podrán crear y consultar solicitudes de servicios únicamente para
sus propias mascotas. No podrán seleccionar `Treatment`, resolver solicitudes,
crear `PetTreatment` ni modificar asignaciones o sesiones. El administrador
representa durante F08 al profesional autorizado que determina el tratamiento.

La resolución deberá ejecutarse transaccionalmente: validar que el `Treatment`
activo pertenece al mismo `Service` solicitado, crear `PetTreatment` y sus
sesiones con las reglas vigentes, marcar `ServiceRequest` como `resolved` y
conservar una referencia nullable al `PetTreatment` resultante. La autorización
de lectura cliente recorrerá tanto `User → Client → Pet → ServiceRequest` como
`User → Client → Pet → PetTreatment → TreatmentSession`.

No se implementan protocolos complejos, facturación, pagos ni procedimientos
diferentes por número de sesión.

---

# **12\. Notificaciones**

VetZen utilizará inicialmente:

* Email.  
* Notificaciones internas.

Las notificaciones deberán poder procesarse de forma asíncrona cuando corresponda.

Los recordatorios estarán habilitados para los eventos definidos por el producto.

La implementación deberá contemplar mecanismos adecuados para evitar notificaciones duplicadas cuando un mismo evento sea procesado más de una vez.

Push Notifications queda fuera de la primera versión de turnos. La implementación
de recordatorios deberá registrar entregas idempotentes, invalidar entregas
obsoletas y no bloquear una reserva si falla el email.

---

# **13\. Auditoría**

VetZen deberá mantener trazabilidad de las acciones relevantes realizadas dentro del sistema.

La auditoría deberá permitir identificar como mínimo:

* Usuario.  
* Acción.  
* Momento.  
* Información afectada.

Se deberá prestar especial atención a las modificaciones realizadas sobre información clínica.

La estrategia concreta de auditoría queda pendiente de definición.

Como decisión acotada a la gestión de privilegios, los cambios entre los roles
`client` y `admin` se registrarán en una tabla propia. El cambio de rol y su
registro de auditoría se ejecutarán en una misma transacción. Esta decisión no
define todavía una estrategia global de auditoría para otros recursos.

---

# **14\. Inteligencia artificial**

El asistente virtual Simón será un componente controlado de VetZen y no deberá

La implementación evoluciona en este orden:

1. SIMON-01: Knowledge Base curada de uso del producto.
2. SIMON-02: chunking, almacenamiento y búsqueda textual.
3. SIMON-03: generación grounded con un proveedor LLM y contexto recuperado.
4. SIMON-04: evaluación, grounding y observabilidad del MVP.
5. Embeddings y búsqueda semántica/híbrida si la evaluación los justifica.

SIMON-01 no implementa integración de IA ni persistencia de conocimiento fuera
de los documentos versionados.

Se utilizarán dos fuentes principales de información.

## **14.1 Base de conocimiento**

Contendrá información autorizada y curada sobre el uso del producto. Su contenido
se mantiene separado de especificaciones técnicas, documentos de planificación y

Esta información podrá utilizar una arquitectura RAG para recuperar contenido
relevante antes de generar una respuesta. El retrieval deberá filtrar el
contenido por rol antes de enviarlo al proveedor.

El grounding de cada documento distingue entre `allowed`, `unavailable` y
`not_allowed_for_role`. Esta última clasificación representa una funcionalidad
existente cuya acción no está permitida para el rol que puede recuperar la guía;
la respuesta debe rechazar la acción y orientar hacia una alternativa permitida.

El sistema deberá contemplar:

* Ingesta de información.  
* Generación de embeddings.  
* Almacenamiento de representaciones vectoriales.  
* Recuperación de información relevante.  
* Incorporación del contexto recuperado al modelo de lenguaje.

## **14.2 Información dinámica**

Los datos actualizados de VetZen, como turnos, tratamientos y pacientes, podrán
obtenerse mediante mecanismos controlados en una feature posterior.

El modelo no deberá consultar directamente la base de datos.

El acceso deberá realizarse mediante operaciones explícitamente autorizadas.

---

# **15\. Seguridad del asistente virtual**

El asistente deberá operar dentro del contexto del usuario autenticado.

Antes de proporcionar información relacionada con una mascota deberá verificarse que el usuario tenga autorización para acceder a ella.

El contexto enviado al modelo deberá limitarse a la información necesaria para resolver la consulta.

El asistente no deberá utilizar información de otros clientes o pacientes.

El sistema deberá evitar que una instrucción proporcionada por el usuario permita saltarse las restricciones de autorización.

---

# **16\. Capacidades del asistente**

El asistente podrá, según el alcance de cada feature:

* Responder consultas sobre información autorizada de la veterinaria.  
* Explicar el uso de funcionalidades implementadas a partir de la Knowledge Base.
* Consultar información seleccionada de las mascotas del cliente cuando se
  habilite explícitamente.
* Consultar información actualizada de tratamientos cuando se habilite
  explícitamente.
* Consultar información de turnos o ayudar a solicitarlos cuando el módulo exista
  y se habilite explícitamente.

El asistente no deberá:

* Realizar diagnósticos autónomos.  
* Sustituir la evaluación profesional.  
* Acceder a información no autorizada.  
* Modificar información clínica sin autorización.  
* Inventar información cuando no exista contexto suficiente.
* Proporcionar diagnósticos, recomendaciones veterinarias o prescripciones.

Las reglas concretas de derivación a profesionales deberán definirse posteriormente en función del contenido y contexto autorizado para el asistente.

---

# **17\. Jobs y procesamiento asíncrono**

Las operaciones que puedan ejecutarse de manera independiente del ciclo de una solicitud deberán poder utilizar procesamiento asíncrono.

Se contempla su utilización especialmente para:

* Notificaciones.  
* Recordatorios.  
* Procesamiento relacionado con la base de conocimiento.  
* Procesamiento de embeddings.  
* Otras operaciones potencialmente costosas.

Las reglas específicas de reintentos, fallos e idempotencia deberán definirse según cada feature.

---

# **18\. Validación y manejo de errores**

Toda información proveniente del usuario deberá ser validada.

El backend deberá ser responsable de validar:

* Datos requeridos.  
* Formatos.  
* Reglas de negocio.  
* Autorización.  
* Consistencia de la información.

Los errores deberán comunicarse de forma consistente al frontend.

Las reglas específicas de validación se definirán dentro de cada feature.

---

# **19\. Testing**

El proyecto deberá incorporar pruebas automatizadas.

Se deberá priorizar la cobertura de:

* Autenticación.  
* Autorización.  
* Acceso a información clínica.  
* Relación entre clientes y mascotas.  
* Solicitud y aprobación de turnos.  
* Límites de modificación y cancelación.  
* Tratamientos.  
* Seguimiento.  
* Notificaciones.  
* Acceso del asistente a información protegida.

El tipo de prueba requerido para cada comportamiento deberá definirse en la especificación de cada feature.

---

# **20\. Dependencias externas**

VetZen podrá depender de servicios externos para:

* Inteligencia artificial.  
* Embeddings.  
* Almacenamiento vectorial.  
* Email.  
* Push Notifications.
* Google OAuth para autenticación.

Los proveedores concretos deberán definirse antes de implementar cada integración.

Las integraciones deberán estar aisladas de la lógica de negocio para permitir su reemplazo cuando sea necesario.

---

# **21\. Configuración**

Los valores dependientes del entorno no deberán estar definidos directamente en el código.

Esto incluye, entre otros:

* Credenciales.  
* Claves de servicios externos.  
* Configuración de IA.  
* Configuración de notificaciones.  
* Configuración de base de datos.  
* Parámetros operativos.

Los valores configurables por la veterinaria deberán diferenciarse de los valores propios del entorno técnico.

---

# **22\. Observabilidad**

El sistema deberá permitir identificar errores y problemas relevantes durante su funcionamiento.

Como mínimo deberá contemplarse:

* Registro de errores.  
* Registro de operaciones relevantes.  
* Identificación de fallos en procesos asíncronos.  
* Seguimiento de integraciones externas.

La estrategia concreta de logging y monitoreo queda pendiente de definición.

---

# **23\. Documentación y trabajo con agentes de IA**

Los agentes de IA utilizados para desarrollar VetZen deberán considerar como contexto principal:

1. `spec.md`  
2. `technical.md`  
3. La especificación de la feature que se esté implementando.  
4. Las decisiones arquitectónicas registradas.

Antes de implementar una funcionalidad, el agente deberá:

* Revisar las especificaciones disponibles.  
* Identificar dependencias con funcionalidades existentes.  
* Respetar las decisiones técnicas establecidas.  
* Detectar contradicciones.  
* Identificar información faltante.  
* Evitar inventar reglas de negocio.  
* Evitar introducir dependencias innecesarias.  
* Implementar pruebas correspondientes.  
* Informar decisiones que no estén definidas.

Cuando una decisión necesaria no esté especificada, el agente deberá detenerse en ese punto de decisión y solicitar una definición, salvo que exista una convención técnica previamente establecida que permita resolverla.

---

# **24\. Decisiones técnicas pendientes**

Las siguientes decisiones deben definirse antes de completar las especificaciones técnicas de las features.

## **Arquitectura**

* Laravel con Inertia \+ React es la arquitectura actual.
* ¿La aplicación será monolítica o se separará frontend y backend?  
* ¿Se utilizará API además de Inertia?  
* ¿Se requiere una API pública o solamente una API interna?

## **Base de datos**

* ¿Se utilizará MySQL o MariaDB?  
* ¿Se utilizarán Soft Deletes?  
* ¿Qué estrategia se utilizará para auditoría?  
* ¿Qué estrategia se utilizará para almacenar documentos e imágenes?  
* ¿Se requiere almacenamiento externo?

## **Autenticación**

* Laravel Fortify continuará gestionando correo, contraseña, passkeys y segundo factor.
* Laravel Socialite gestionará Google OAuth mediante el flujo web stateful y validación de estado; no se utilizará `stateless()`.
* Google coexistirá con el registro local. Los usuarios nuevos completarán teléfono y contraseña, y las cuentas existentes requerirán vinculación autenticada explícita.
* Las cuentas con segundo factor activo deberán completar el desafío de Fortify después de autenticarse con Google.
* La verificación de email no será obligatoria en la etapa actual. Su
  infraestructura permanece inactiva hasta disponer de correo saliente y una
  decisión explícita de reactivación.
* La autenticación de dos factores está disponible y se aplica también a Google cuando el usuario la tiene activa.

## **Roles y permisos**

* ¿Cuáles serán los roles iniciales?  
* ¿Qué permisos tendrá cada rol?  
* ¿Los permisos podrán ser modificados por un administrador?  
* ¿Existirá un rol con control total sobre la plataforma?

## **Frontend**

* React \+ Inertia es el frontend actual.
* ¿Qué sistema de estilos se utilizará?  
* ¿Se utilizará una librería de componentes?  
* ¿Se requiere una estrategia mobile-first?  
* ¿Qué estrategia se utilizará para estado global?

## **Turnos**

* La agenda usa fechas concretas, bloques, inicios explícitos, servicios por fecha
  y capacidad compartida; no hay disponibilidad implícita.
* La duración predeterminada del turno pertenece a Service y se snapshottea al
  reservar; no se deriva de procedimientos ni tratamientos.
* Las excepciones se representan publicando o editando fechas concretas; no se
  agrega un sistema separado de recurrencias.
* Los conflictos se evitan con transacciones, bloqueo de la fecha de agenda y
  cálculo de capacidad por intervalos.
* Valores iniciales: reserva cliente 2 horas; cancelación y reprogramación cliente
  24 horas; recordatorio 24 horas.
* Resuelto para Feature 10: ServiceRequest y TreatmentSession pueden conservar
  historial de turnos, pero cada una admite como máximo un turno `pending` o
  `approved`; una atención de sesión pendiente de documentación bloquea una nueva
  reserva. La tarea clínica se resuelve con un ClinicalRecord asociado
  explícitamente a la sesión vinculada, con sesión completada y turno atendido.
* Pendiente: matriz completa de transiciones y permisos para editar duración
  individual.

## **Notificaciones**

* ¿Qué proveedor de email se utilizará?  
* ¿El usuario podrá configurar preferencias de notificación?

## **Inteligencia artificial**

* Proveedor de modelo de lenguaje inicial para desarrollo y pruebas: Groq, con
  `openai/gpt-oss-20b` como baseline de SIMON-04. OpenAI permanece como
  alternativa futura. El proveedor de producción, límites, región, retención y
  parámetros siguen pendientes de definición.
* ¿Qué proveedor de embeddings se utilizará?  
* ¿Qué solución de almacenamiento vectorial se utilizará?  
* ¿La búsqueda vectorial estará dentro de la misma base de datos o será independiente?  
* ¿Cómo se administrará y actualizará la base de conocimiento?  
* ¿Quién podrá agregar o modificar contenido de la base de conocimiento?  
* ¿Qué información clínica podrá exponerse al asistente?  
* ¿Qué herramientas podrá utilizar el asistente?  
* ¿Qué acciones podrá ejecutar directamente?  
* ¿Cómo se registrarán las interacciones del asistente?  
* ¿Cómo se controlará el costo y volumen de uso de IA?

## **Testing**

* ¿Qué framework de testing se utilizará?  
* ¿Qué nivel mínimo de cobertura se espera?  
* ¿Se utilizarán pruebas E2E?  
* ¿Qué funcionalidades serán consideradas críticas y requerirán mayor cobertura?

## **Infraestructura**

* ¿Dónde se desplegará VetZen?  
* ¿Se utilizará Docker?  
* ¿Qué servidor/proveedor se utilizará?  
* ¿Se utilizará Redis?  
* ¿Dónde se almacenarán archivos?  
* ¿Cómo se ejecutarán los workers?  
* ¿Cómo se ejecutarán las tareas programadas?

## **CI/CD**

* ¿Se utilizará GitHub Actions u otra solución?  
* ¿Qué verificaciones deberán ejecutarse antes de integrar cambios?  
* ¿Se ejecutarán automáticamente tests, análisis estático y linting?  
* ¿Habrá diferentes entornos para desarrollo, staging y producción?

## **Observabilidad**

* ¿Qué sistema de logging se utilizará?  
* ¿Se utilizará algún sistema de monitoreo de errores?  
* ¿Se requiere monitoreo específico de Jobs y servicios de IA?

---

# **25\. Estado del documento**

Este documento representa la **especificación técnica inicial de VetZen**.

Las decisiones marcadas como pendientes deberán resolverse antes de generar las especificaciones técnicas definitivas de las features que dependan de ellas.

El documento deberá evolucionar junto con el proyecto. Las decisiones técnicas importantes que modifiquen la arquitectura deberán registrarse y justificarse para mantener trazabilidad.

Las features no deberán utilizar decisiones técnicas contradictorias con este documento sin actualizar previamente la especificación correspondiente.
