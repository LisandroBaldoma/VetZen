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

* Sistema de autenticación compatible con Laravel.

### **Autorización**

* Spatie Laravel Permission.

### **Procesamiento en segundo plano**

* Sistema de Jobs/Queues de Laravel.

### **Notificaciones**

* Email.  
* Notificaciones Push.

### **Inteligencia artificial**

* Modelo de lenguaje.  
* Embeddings.  
* Búsqueda semántica.  
* Arquitectura RAG.  
* Herramientas controladas para acceder a información dinámica del sistema.

La elección concreta de proveedores y servicios deberá definirse antes de implementar las funcionalidades correspondientes.

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

El sistema deberá contemplar una agenda basada en disponibilidad y servicios.

Los turnos solicitados por clientes requerirán aprobación profesional antes de quedar confirmados.

Los clientes podrán modificar y cancelar sus propios turnos, respetando los límites establecidos por la veterinaria.

Los límites de anticipación y cancelación deberán ser configurables.

La implementación deberá garantizar que no se produzcan inconsistencias en la disponibilidad ante solicitudes concurrentes.

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
* Notificaciones Push.

Las notificaciones deberán poder procesarse de forma asíncrona cuando corresponda.

Los recordatorios estarán habilitados para los eventos definidos por el producto.

La implementación deberá contemplar mecanismos adecuados para evitar notificaciones duplicadas cuando un mismo evento sea procesado más de una vez.

El proveedor concreto de Push Notifications queda pendiente de definición.

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

---

# **14\. Inteligencia artificial**

El asistente virtual será un componente controlado de VetZen y no deberá tener acceso directo e irrestricto a la base de datos.

Se utilizarán dos fuentes principales de información.

## **14.1 Base de conocimiento**

Contendrá información autorizada por la veterinaria.

Esta información podrá utilizar una arquitectura RAG para recuperar contenido relevante antes de generar una respuesta.

El sistema deberá contemplar:

* Ingesta de información.  
* Generación de embeddings.  
* Almacenamiento de representaciones vectoriales.  
* Recuperación de información relevante.  
* Incorporación del contexto recuperado al modelo de lenguaje.

## **14.2 Información dinámica**

Los datos actualizados de VetZen, como turnos, tratamientos y pacientes, deberán obtenerse mediante mecanismos controlados.

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

El asistente podrá:

* Responder consultas sobre información autorizada de la veterinaria.  
* Consultar información seleccionada de las mascotas del cliente.  
* Consultar información actualizada de tratamientos.  
* Consultar información de turnos.  
* Ayudar a identificar opciones de turnos.  
* Ayudar durante el proceso de solicitud de un turno.

El asistente no deberá:

* Realizar diagnósticos autónomos.  
* Sustituir la evaluación profesional.  
* Acceder a información no autorizada.  
* Modificar información clínica sin autorización.  
* Inventar información cuando no exista contexto suficiente.

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

* ¿Se utilizará Laravel con Inertia \+ Vue como arquitectura principal?  
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

* ¿Qué mecanismo de autenticación se utilizará?  
* ¿Se requerirá verificación de email obligatoria?  
* ¿Se utilizará autenticación de dos factores?

## **Roles y permisos**

* ¿Cuáles serán los roles iniciales?  
* ¿Qué permisos tendrá cada rol?  
* ¿Los permisos podrán ser modificados por un administrador?  
* ¿Existirá un rol con control total sobre la plataforma?

## **Frontend**

* ¿Se utilizará Vue 3 \+ Inertia?  
* ¿Qué sistema de estilos se utilizará?  
* ¿Se utilizará una librería de componentes?  
* ¿Se requiere una estrategia mobile-first?  
* ¿Qué estrategia se utilizará para estado global?

## **Turnos**

* ¿Cómo se configurarán los horarios de disponibilidad?  
* ¿La duración será definida únicamente por servicio?  
* ¿Se permitirán excepciones de disponibilidad?  
* ¿Cómo se evitarán conflictos de turnos?  
* ¿Qué límites de anticipación y cancelación se utilizarán inicialmente?

## **Notificaciones**

* ¿Se utilizará Firebase Cloud Messaging para Push Notifications?  
* ¿Qué proveedor de email se utilizará?  
* ¿Qué eventos generarán notificaciones?  
* ¿El usuario podrá configurar preferencias de notificación?

## **Inteligencia artificial**

* ¿Qué proveedor de modelo de lenguaje se utilizará?  
* ¿Qué modelo se utilizará inicialmente?  
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
