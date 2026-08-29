# **VetZen — Product Specification**

## **1\. Idea principal**

**VetZen** es una plataforma web destinada a digitalizar la gestión de una veterinaria especializada en terapias alternativas y complementarias.

El producto centraliza la gestión de clientes, mascotas, historia clínica, servicios, tratamientos y turnos, permitiendo que los profesionales administren la actividad de la veterinaria y que los clientes gestionen sus mascotas y solicitudes.

La plataforma incorpora además un **asistente virtual contextualizado**, diseñado para responder utilizando información autorizada de la veterinaria y datos reales asociados al cliente y sus mascotas, respetando los permisos de acceso establecidos.

---

## **2\. Usuarios**

VetZen contempla dos perfiles principales.

### **2.1 Profesional**

El profesional utiliza la plataforma para administrar la actividad de la veterinaria.

Puede:

* Administrar clientes.  
* Administrar pacientes.  
* Gestionar información clínica.  
* Administrar servicios.  
* Gestionar tratamientos.  
* Administrar turnos.  
* Configurar disponibilidad.  
* Realizar el seguimiento de los pacientes.  
* Consultar y registrar información de acuerdo con sus permisos.

### **2.2 Cliente**

El cliente utiliza la plataforma para gestionar su relación con la veterinaria.

Puede:

* Registrarse en la plataforma.  
* Gestionar su información personal.  
* Registrar y administrar sus mascotas.  
* Solicitar turnos.  
* Solicitar tratamientos.  
* Consultar información relacionada con sus mascotas.  
* Consultar el seguimiento de sus tratamientos.  
* Utilizar el asistente virtual.

El cliente solo podrá acceder a información correspondiente a sus propias mascotas y a la información que tenga autorización para consultar.

---

# **3\. Gestión de usuarios**

La plataforma deberá permitir:

* Registro e inicio de sesión.  
* Gestión de perfiles.  
* Recuperación de contraseña.  
* Verificación de identidad.  
* Diferenciación entre clientes y profesionales.  
* Administración de roles y permisos mediante Spatie.

El acceso a las diferentes funcionalidades deberá estar determinado por los permisos correspondientes a cada usuario.

---

# **4\. Gestión de clientes**

VetZen permitirá administrar la información de los clientes de la veterinaria.

Deberá permitir:

* Registrar clientes.  
* Administrar información personal y de contacto.  
* Asociar mascotas a cada cliente.  
* Consultar el historial de turnos.  
* Consultar el historial de tratamientos.

---

# **5\. Gestión de mascotas**

Las mascotas representan los pacientes de la veterinaria.

La plataforma deberá permitir:

* Registrar mascotas.  
* Administrar su información general.  
* Asociarlas con su correspondiente cliente.  
* Consultar información clínica autorizada.  
* Consultar su historial de tratamientos.  
* Consultar su historial de turnos.

---

# **6\. Historia clínica**

VetZen permitirá centralizar la información clínica de los pacientes.

Deberá permitir:

* Registrar información clínica.  
* Consultar información clínica.  
* Registrar consultas y evaluaciones.  
* Registrar la evolución del paciente.  
* Realizar seguimiento de las diferentes sesiones.  
* Restringir el acceso a la información de acuerdo con los permisos correspondientes.

La información clínica deberá considerarse información protegida y su acceso deberá estar controlado.

---

# **7\. Servicios**

La veterinaria podrá administrar los servicios que ofrece a sus clientes.

Cada servicio representa un área terapéutica general, con nombre, descripción
y estado activo o inactivo. Un servicio no tiene precio ni duración.

Los servicios contienen procedimientos. Cada procedimiento representa una
técnica perteneciente a un servicio, puede tener una duración orientativa
opcional y no tiene precio.

---

# **8\. Tratamientos**

VetZen permitirá administrar tratamientos reutilizables del catálogo y
asignarlos a pacientes.

Deberá permitir:

* Crear tratamientos asociados a un servicio.
* Asociar uno o varios procedimientos del mismo servicio.
* Definir una cantidad estimada de sesiones.
* Asignar un tratamiento a una mascota.
* Conservar las condiciones históricas acordadas al asignarlo.
* Generar y administrar sesiones con precio y estado independientes.
* Realizar seguimiento del progreso según las sesiones completadas.
* Conservar sesiones canceladas como historial y generar reemplazos pendientes
  cuando sean necesarios para alcanzar las sesiones requeridas.

La veterinaria cobra por sesión. El precio efectivo pertenece a cada sesión y
no al servicio, procedimiento o tratamiento del catálogo. Los clientes pueden
consultar en modo lectura los tratamientos y sesiones de sus propias mascotas,
pero no pueden solicitarlos ni modificarlos en esta versión.

Las sesiones canceladas no cuentan para completar el tratamiento ni modifican
la cantidad requerida. Se conservan históricamente y generan un reemplazo
pendiente con numeración consecutiva cuando sea necesario. El tratamiento se
completa cuando las sesiones completadas alcanzan la cantidad prevista.

---

# **10\. Gestión de turnos**

VetZen permitirá administrar la agenda de la veterinaria y gestionar solicitudes de turnos.

Deberá contemplar:

* Configuración de horarios y disponibilidad.  
* Definición de duración según el servicio.  
* Solicitud de turnos por parte de los clientes.  
* Confirmación de turnos.  
* Modificación de turnos.  
* Cancelación de turnos.  
* Administración de turnos por parte de los profesionales.  
* Diferentes estados para los turnos.  
* Visualización de la agenda.

---

# **11\. Notificaciones**

La plataforma deberá generar notificaciones relacionadas con las principales acciones del sistema.

Se contemplan:

* Solicitudes de turnos.  
* Confirmaciones de turnos.  
* Cancelaciones.  
* Recordatorios.  
* Eventos relacionados con tratamientos y seguimiento.

El procesamiento de determinadas notificaciones podrá realizarse mediante tareas en segundo plano.

---

# **12\. Asistente virtual**

VetZen incorporará un asistente virtual capaz de responder consultas utilizando información contextualizada de la veterinaria.

El asistente tendrá acceso controlado a dos fuentes principales de información.

### **12.1 Conocimiento de la veterinaria**

Información previamente autorizada por la veterinaria, relacionada con:

* Servicios.  
* Tratamientos.  
* Información institucional.  
* Preguntas frecuentes.  
* Contenido autorizado.

Esta información podrá utilizarse mediante una arquitectura de recuperación de información basada en búsqueda semántica y RAG.

### **12.2 Información dinámica**

Información actualizada del sistema relacionada con:

* Turnos.  
* Tratamientos.  
* Pacientes.  
* Información asociada al cliente.

El acceso a esta información deberá realizarse de manera controlada y respetando los permisos del usuario autenticado.

---

# **13\. Reglas del asistente virtual**

El asistente deberá:

* Utilizar información confiable y autorizada.  
* Respetar los permisos del usuario.  
* Acceder únicamente a información correspondiente al cliente.  
* Consultar información actualizada cuando sea necesario.  
* Evitar inventar información.  
* Diferenciar entre información disponible y aquella que no puede consultar.  
* No realizar diagnósticos.  
* No reemplazar la evaluación de un profesional veterinario.  
* No modificar información clínica sin autorización.  
* Derivar determinadas consultas al profesional cuando corresponda.

El asistente no deberá tener acceso indiscriminado a la información de la plataforma.

---

# **14\. Seguridad y permisos**

VetZen deberá proteger especialmente la información relacionada con clientes, mascotas e historias clínicas.

Deberá contemplar:

* Roles y permisos.  
* Control de acceso según usuario.  
* Protección de información clínica.  
* Restricción de acceso a información de otros clientes.  
* Control de acceso específico para el asistente virtual.  
* Validación de las operaciones.  
* Registro de acciones importantes.  
* Administrar todos los permisos mediante un panel

---

# **16\. Límites del producto**

El alcance definido para VetZen no contempla actualmente funcionalidades adicionales que no hayan sido especificadas.

En particular, no se define todavía:

* Sistema de pagos.  
* Facturación.  
* Integración con WhatsApp.  
* Integraciones externas.  
* Gestión de múltiples sucursales.  
* Reportes avanzados.  
* Aplicación móvil.  
* Funcionalidades adicionales de comunicación.

Estas funcionalidades no forman parte del alcance actual y deberán evaluarse por separado si fueran necesarias.

El asistente virtual tampoco deberá utilizarse como sustituto de la atención profesional ni realizar diagnósticos o indicaciones clínicas autónomas.

---

### **17\. Definiciones finales del alcance**

#### **Usuarios y profesionales**

* VetZen permitirá trabajar con **múltiples profesionales**.  
* Los profesionales podrán tener **diferentes niveles de permisos**.  
* Los permisos determinarán qué funcionalidades e información puede administrar cada profesional.  
* Los profesionales autorizados podrán **consultar y modificar toda la información clínica**.  
* La gestión de permisos se realizará mediante el sistema de roles y permisos definido para la plataforma.

#### **Tratamientos**

* Los tratamientos del catálogo agrupan procedimientos de un mismo servicio y
  definen una cantidad estimada de sesiones.
* Un administrador podrá asignar tratamientos a mascotas y administrar sus
  sesiones.
* Los clientes podrán consultar únicamente tratamientos y sesiones de sus
  propias mascotas, en modo lectura.
* Los clientes no podrán solicitar tratamientos ni modificar asignaciones,
  sesiones, precios o estados en esta versión.
* Cada sesión conservará su precio efectivo e historial independientemente de
  cambios posteriores del catálogo.
* Las sesiones canceladas no cuentan para el progreso, no reutilizan su número
  y generan reemplazos cuando falten sesiones pendientes para alcanzar el total
  requerido.

#### **Turnos**

* Los clientes podrán solicitar turnos.  
* Las solicitudes de turno deberán ser **aprobadas por un profesional** antes de considerarse confirmadas.  
* Los clientes podrán modificar sus propios turnos.  
* Los clientes podrán cancelar sus propios turnos.  
* Existirán **límites de anticipación y cancelación** para las acciones relacionadas con los turnos.  
* Estos límites deberán ser configurables por la veterinaria.

#### **Historia clínica**

* Los clientes podrán consultar **toda la historia clínica disponible de sus mascotas**.  
* El acceso estará restringido exclusivamente a las mascotas sobre las cuales el cliente tenga autorización.  
* Los profesionales autorizados podrán consultar y modificar registros clínicos, incluyendo registros anteriores.  
* Las modificaciones sobre información clínica deberán quedar contempladas dentro del sistema de auditoría.

#### **Asistente virtual**

El asistente virtual tendrá un acceso controlado a la información disponible para cada usuario.

Podrá:

* Consultar información general autorizada de la veterinaria.  
* Consultar información seleccionada de la historia clínica.  
* Consultar información de las mascotas del cliente cuando tenga los permisos correspondientes.  
* Ayudar al cliente a identificar el servicio o tratamiento que necesita según la información disponible.  
* Ayudar al cliente a encontrar un turno adecuado.  
* Asistir al cliente durante el proceso de solicitud de un turno.  
* Consultar información actualizada de turnos y tratamientos cuando corresponda.

La información clínica disponible para el asistente será **seleccionada y definida por la veterinaria**, evitando que el modelo tenga acceso indiscriminado a la historia clínica.

El asistente deberá respetar los permisos del usuario y nunca podrá acceder a información de otros clientes o pacientes sin autorización.

Las situaciones que requieran derivación a un profesional serán definidas progresivamente en función del **contexto y conocimiento autorizado que se incorpore al asistente**.

El asistente no reemplazará la evaluación profesional ni realizará diagnósticos autónomos.

#### **Notificaciones**

VetZen utilizará inicialmente:

* **Notificaciones Push**.  
* **Email**.

Los recordatorios estarán habilitados para los eventos correspondientes y deberán poder configurarse según las necesidades de la veterinaria.

---

## **Alcance funcional cerrado**

Con estas definiciones, el alcance principal de VetZen queda compuesto por:

1. **Usuarios y permisos**  
2. **Clientes**  
3. **Mascotas / pacientes**  
4. **Historia clínica**  
5. **Servicios**  
6. **Tratamientos**  
7. **Turnos y agenda**  
8. **Notificaciones**  
9. **Asistente virtual contextualizado**  
10. **Seguridad**

Y con dos reglas especialmente importantes:

> **La información clínica pertenece al ámbito profesional, pero el cliente podrá consultar la historia clínica completa de sus propias mascotas.**

> **El asistente virtual no tendrá acceso libre a la información clínica: solamente podrá utilizar información autorizada y datos correspondientes al usuario autenticado.**

También queda definido que el asistente tendrá un rol **activo**, no solamente de preguntas y respuestas: podrá ayudar al cliente a encontrar y solicitar un turno, siempre dentro de las reglas del sistema.
