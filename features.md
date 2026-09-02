# **VetZen — Feature Specification**

## **1\. Propósito**

Este documento define el conjunto de funcionalidades que componen VetZen y establece el alcance funcional inicial de cada una.

Las funcionalidades deberán desarrollarse respetando:

* `spec.md`: definición funcional y reglas del producto.  
* `technical.md`: arquitectura y criterios técnicos.  
* Las decisiones arquitectónicas registradas durante el desarrollo.

Este documento no define todavía detalles de implementación, estructura de archivos, clases, endpoints ni código.

Cada funcionalidad podrá posteriormente convertirse en una especificación individual antes de comenzar su implementación.

---

# **2\. Organización de funcionalidades**

Las funcionalidades de VetZen se agrupan en las siguientes áreas:

1. Autenticación y usuarios.  
2. Clientes.  
3. Mascotas.  
4. Historia clínica.  
5. Servicios.  
6. Tratamientos.  
7. Planes de seguimiento.  
8. Turnos y agenda.  
9. Notificaciones.  
10. Asistente virtual.  
11. Seguridad y permisos.  
12. Auditoría.

---

# **3\. Autenticación y usuarios**

## **Objetivo**

Permitir que los usuarios accedan a VetZen de forma segura y que el sistema determine las funcionalidades disponibles según su perfil y permisos.

## **Alcance**

* Registro de clientes.  
* Inicio de sesión.  
* Recuperación de contraseña.  
* Infraestructura de verificación de identidad, activable cuando exista un
  proveedor de correo configurado.
* Gestión de perfiles.  
* Gestión de profesionales.  
* Roles y permisos.  
* Diferentes niveles de permisos para profesionales.

## **Reglas principales**

* Los usuarios deberán autenticarse para acceder a las funcionalidades protegidas.  
* La verificación de correo no condiciona el acceso en la versión actual.
* Los permisos determinarán qué acciones puede realizar cada usuario.  
* Los profesionales podrán tener diferentes niveles de acceso.  
* Los profesionales autorizados podrán modificar información clínica.  
* Los clientes solo podrán acceder a información correspondiente a sus propias mascotas.

---

# **4\. Clientes**

## **Objetivo**

Permitir gestionar la información de las personas responsables de las mascotas.

## **Alcance**

* Registro de clientes.  
* Administración de información personal.  
* Administración de información de contacto.  
* Asociación de mascotas.  
* Consulta de turnos.  
* Consulta de tratamientos.

## **Reglas principales**

* Cada cliente podrá administrar sus propias mascotas.  
* El cliente no podrá acceder a información perteneciente a otros clientes.  
* El acceso a información clínica deberá respetar las reglas de autorización.

---

# **5\. Mascotas**

## **Objetivo**

Permitir registrar y administrar a las mascotas como pacientes de la veterinaria.

## **Alcance**

* Alta de mascotas.  
* Administración de información general.  
* Asociación con un cliente.  
* Consulta de información clínica autorizada.  
* Consulta de tratamientos.  
* Consulta de turnos.

## **Reglas principales**

* Una mascota deberá estar asociada a un cliente.  
* El cliente podrá consultar las mascotas sobre las cuales tenga autorización.  
* La información clínica deberá estar protegida mediante permisos.

---

# **6\. Historia clínica**

## **Objetivo**

Centralizar la información clínica y la evolución de los pacientes.

## **Alcance**

* Registro de información clínica.  
* Consulta de información clínica.  
* Registro de consultas.  
* Registro de evaluaciones.  
* Registro de evolución.  
* Seguimiento de sesiones.  
* Modificación de registros existentes.

## **Reglas principales**

* Los profesionales autorizados podrán consultar y modificar la historia clínica.  
* Los clientes podrán consultar toda la historia clínica disponible de sus propias mascotas.  
* Las modificaciones clínicas deberán ser auditables.  
* El acceso a la información deberá estar restringido según el usuario.

---

# **7\. Servicios**

## **Objetivo**

Permitir administrar el catálogo terapéutico y su estructura general.

## **Alcance**

* Crear, modificar, activar y desactivar servicios.
* Crear, modificar, activar y desactivar procedimientos de cada servicio.
* Definir una duración orientativa opcional para procedimientos.

## **Reglas principales**

* Service representa un área terapéutica y no tiene precio ni duración.
* Procedure pertenece a Service, puede tener duración orientativa y no tiene
  precio.
* La baja comercial se realiza mediante estado, sin eliminar referencias
  históricas.

---

# **8\. Tratamientos**

## **Objetivo**

Permitir administrar tratamientos reutilizables, asignarlos a mascotas y
gestionar sus sesiones básicas.

## **Alcance**

* Administración de tratamientos de catálogo asociados a un servicio.
* Asociación de uno o varios procedimientos del mismo servicio.
* Definición de cantidad estimada de sesiones.
* Asignación de tratamientos a mascotas con snapshots históricos.
* Generación y administración básica de sesiones.
* Precio y estado independientes por sesión.
* Seguimiento del progreso por sesiones completadas.
* Consulta cliente de asignaciones y sesiones de sus propias mascotas.
* Conservación histórica y reemplazo de sesiones canceladas.
* Solicitud cliente de un servicio activo para una mascota propia mediante
  `ServiceRequest`.
* Resolución profesional de la solicitud mediante un tratamiento compatible.

## **Reglas principales**

* El tratamiento requiere al menos un procedimiento del mismo servicio.
* El precio pertenece a TreatmentSession, no al catálogo.
* El cliente solicita un Service activo para una Pet propia, no un Treatment.
* El administrador determina el Treatment, crea PetTreatment y administra sus
  sesiones.
* El cliente no selecciona tratamientos ni modifica solicitudes resueltas,
  asignaciones o sesiones.
* El cliente solo consulta recursos de sus propias mascotas.
* Cambios posteriores del catálogo no alteran asignaciones históricas.
* `planned_sessions` representa sesiones completadas requeridas. Una sesión
  cancelada no cuenta ni cambia ese total y genera un reemplazo pendiente con
  numeración consecutiva cuando sea necesario.

---

# **9\. Planes de seguimiento**

## **Objetivo**

Extender en una feature futura el seguimiento clínico estructurado más allá de
las sesiones básicas incluidas en F08.

## **Alcance**

* Crear protocolos o planes clínicos personalizados.
* Definir frecuencia y objetivos clínicos.
* Registrar evolución clínica detallada.
* Asociar procedimientos concretos a sesiones cuando se defina.

## **Reglas principales**

* Los planes estarán asociados a tratamientos asignados.
* La información clínica deberá respetar ownership, autorización y auditoría.
* Esta feature futura no redefine las sesiones operativas básicas de F08.

---

# **10\. Turnos y agenda**

## **Objetivo**

Permitir administrar la disponibilidad de la veterinaria y gestionar los turnos de los clientes.

## **Alcance**

* Configuración de horarios.  
* Configuración de disponibilidad.  
* Definición de duración según servicio.  
* Solicitud de turnos.  
* Aprobación de turnos.  
* Modificación de turnos.  
* Cancelación de turnos.  
* Administración profesional de la agenda.  
* Estados de turno.

## **Reglas principales**

* El cliente podrá solicitar un turno.  
* La solicitud deberá ser aprobada por un profesional.  
* El turno no estará confirmado hasta su aprobación.  
* El cliente podrá modificar sus propios turnos.  
* El cliente podrá cancelar sus propios turnos.  
* Las modificaciones y cancelaciones estarán sujetas a límites configurables.  
* El sistema deberá evitar conflictos de disponibilidad.

---

# **11\. Notificaciones**

## **Objetivo**

Informar a clientes y profesionales sobre eventos relevantes de la plataforma.

## **Alcance**

* Notificaciones por email.  
* Notificaciones Push.  
* Confirmaciones.  
* Solicitudes.  
* Cancelaciones.  
* Recordatorios.  
* Eventos relacionados con tratamientos y seguimiento.

## **Reglas principales**

* Los recordatorios estarán habilitados.  
* Las notificaciones deberán generarse a partir de eventos relevantes.  
* Las operaciones que puedan ejecutarse de forma asíncrona deberán procesarse sin bloquear las operaciones principales.  
* El sistema deberá evitar notificaciones duplicadas.

---

# **12\. Asistente virtual**

## **Objetivo**

Proporcionar un asistente virtual capaz de ayudar al cliente utilizando información autorizada de VetZen y datos correspondientes a sus mascotas.

## **Alcance**

El asistente podrá:

* Responder consultas sobre la veterinaria.  
* Consultar información autorizada.  
* Consultar información seleccionada de las mascotas.  
* Consultar tratamientos.  
* Consultar turnos.  
* Ayudar a identificar opciones de turno.  
* Ayudar a solicitar un turno.

## **Fuentes de información**

El asistente utilizará dos tipos principales de contexto:

### **Conocimiento de la veterinaria**

Información autorizada sobre:

* Servicios.  
* Tratamientos.  
* Información institucional.  
* Preguntas frecuentes.  
* Contenido definido por la veterinaria.

Esta información podrá utilizar recuperación semántica y RAG.

### **Información dinámica**

Información actualizada de VetZen, incluyendo:

* Mascotas.  
* Turnos.  
* Tratamientos.  
* Información clínica seleccionada.

Esta información deberá obtenerse respetando los permisos del usuario.

## **Reglas principales**

* El asistente deberá respetar los permisos del usuario.  
* No podrá acceder indiscriminadamente a información clínica.  
* Solo podrá consultar información correspondiente a mascotas autorizadas.  
* No deberá inventar información.  
* No deberá realizar diagnósticos autónomos.  
* No deberá reemplazar la evaluación profesional.  
* No podrá modificar información clínica sin autorización.  
* Podrá ayudar a iniciar procesos permitidos, como la solicitud de turnos.  
* Las acciones realizadas por el asistente deberán respetar las mismas reglas de negocio que las realizadas directamente por el usuario.

---

# **13\. Seguridad y permisos**

## **Objetivo**

Garantizar que cada usuario pueda acceder únicamente a las funcionalidades e información que le corresponden.

## **Alcance**

* Roles.  
* Permisos.  
* Autorización.  
* Protección de información clínica.  
* Protección de información de clientes.  
* Protección de información de mascotas.  
* Control de acceso del asistente.

## **Reglas principales**

* La seguridad deberá aplicarse en backend.  
* Las restricciones de interfaz no serán consideradas mecanismos de seguridad.  
* Los clientes no podrán acceder a información de otros clientes.  
* El asistente deberá respetar las mismas restricciones.  
* Los profesionales tendrán acceso según sus permisos.

---

# **14\. Auditoría**

## **Objetivo**

Mantener trazabilidad sobre las acciones relevantes realizadas dentro de VetZen.

## **Alcance**

* Registro de acciones importantes.  
* Identificación del usuario.  
* Registro temporal.  
* Registro de modificaciones sobre información sensible.

## **Reglas principales**

* Las modificaciones sobre información clínica deberán poder rastrearse.  
* Las acciones relevantes realizadas por usuarios deberán poder identificarse.  
* Las acciones realizadas mediante funcionalidades automatizadas o asistentes deberán respetar las reglas de auditoría definidas.

---

# **15\. Prioridad inicial**

Las funcionalidades deberán implementarse progresivamente.

## **Fase 1 — Base del sistema**

* Autenticación.  
* Usuarios.  
* Roles y permisos.  
* Clientes.  
* Mascotas.

## **Fase 2 — Gestión clínica**

* Historia clínica.  
* Servicios.  
* Tratamientos.  
* Planes de seguimiento.

## **Fase 3 — Agenda**

* Disponibilidad.  
* Turnos.  
* Aprobación.  
* Modificación y cancelación.  
* Recordatorios.

## **Fase 4 — Comunicación**

* Email.  
* Push Notifications.

## **Fase 5 — Asistente virtual**

* Base de conocimiento.  
* Recuperación de información.  
* Contexto.  
* Acceso controlado a información dinámica.  
* Consulta de turnos.  
* Asistencia para solicitar turnos.

## **Fase 6 — Validación**

* Pruebas funcionales.  
* Pruebas de autorización.  
* Pruebas de reglas de negocio.  
* Pruebas del asistente.  
* Validación integral del sistema.

---

# **16\. Especificación individual de features**

Antes de implementar una funcionalidad importante, se deberá crear una especificación específica.

Cada feature deberá definir como mínimo:

* Objetivo.  
* Problema que resuelve.  
* Usuario involucrado.  
* Alcance.  
* Reglas de negocio.  
* Flujos principales.  
* Casos alternativos.  
* Permisos requeridos.  
* Validaciones.  
* Estados involucrados.  
* Dependencias.  
* Criterios de aceptación.  
* Requisitos de testing.  
* Consideraciones técnicas específicas.

La especificación de la feature no deberá contradecir `spec.md` ni `technical.md`.

Si aparece una decisión no definida en estos documentos, deberá registrarse como decisión pendiente antes de implementar.

---

# **17\. Flujo de desarrollo con IA**

El desarrollo de cada feature deberá seguir el siguiente flujo:

spec.md  
   ↓  
technical.md  
   ↓  
Feature specification  
   ↓  
Revisión de dependencias  
   ↓  
Implementación  
   ↓  
Tests  
   ↓  
Validación  
   ↓  
Revisión

El agente de IA deberá leer la documentación correspondiente antes de implementar una feature.

El agente deberá:

* Comprender el contexto del producto.  
* Respetar las decisiones técnicas.  
* Respetar las reglas de negocio.  
* Revisar funcionalidades relacionadas.  
* Evitar asumir comportamientos no definidos.  
* Informar ambigüedades.  
* Implementar pruebas.  
* Verificar que los cambios no rompan funcionalidades existentes.

---

# **18\. Estado del documento**

Este documento representa el mapa inicial de funcionalidades de VetZen.

No reemplaza la especificación de producto ni las especificaciones individuales de cada feature.

Las funcionalidades deberán detallarse progresivamente antes de su implementación.

Las decisiones técnicas globales deberán mantenerse en `technical.md`.

Las reglas de producto deberán mantenerse en `spec.md`.

Las reglas específicas de una funcionalidad deberán mantenerse en su correspondiente especificación de feature.
