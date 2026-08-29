Vamos a implementar la Feature 04 — Clientes de VetZen.

Antes de modificar código, lee y utiliza como contexto:

\- spec.md  
\- technical.md  
\- features.md  
\- features/01-authentication.md  
\- features/04-clients.md

Objetivo de la feature:

Permitir gestionar la información de las personas responsables de las mascotas.

Alcance funcional:

\- Registro de clientes.  
\- Administración de información personal.  
\- Administración de información de contacto.  
\- Asociación de mascotas.  
\- Consulta de turnos.  
\- Consulta de tratamientos.

Reglas principales:

\- Cada cliente podrá administrar sus propias mascotas.  
\- Un cliente no podrá acceder a información perteneciente a otros clientes.  
\- El acceso a información clínica deberá respetar las reglas de autorización.  
\- El usuario autenticado debe ser identificado correctamente antes de permitir acceder a información de cliente.  
\- No debe ser posible acceder a información de otro cliente manipulando identificadores, URLs, parámetros o solicitudes.  
\- Las restricciones de acceso deben aplicarse en backend y no depender únicamente de la interfaz.

OBJETIVO TÉCNICO

Implementa esta feature siguiendo la arquitectura y las convenciones definidas en technical.md.

Antes de implementar:

1\. Analiza la estructura actual del proyecto.  
2\. Revisa cómo está implementada la autenticación.  
3\. Identifica qué partes de esta feature ya existen.  
4\. Identifica las entidades y relaciones necesarias para cumplir el alcance funcional.  
5\. Revisa si existen funcionalidades relacionadas con mascotas, turnos o tratamientos y reutiliza correctamente lo existente.  
6\. No dupliques lógica existente.  
7\. No agregues funcionalidades que no estén definidas en esta feature.  
8\. Si existe alguna decisión necesaria que no esté definida en la documentación, detente y explícala antes de tomar una decisión arbitraria.

IMPLEMENTACIÓN

Una vez realizado el análisis, implementa la Feature 04 completa.

Debe incluir como mínimo:

\- Gestión del perfil del cliente.  
\- Información personal.  
\- Información de contacto.  
\- Asociación entre cliente y usuario autenticado.  
\- Preparación de la relación con sus mascotas.  
\- Acceso restringido a los propios datos del cliente.  
\- Protección contra acceso a información de otros clientes.  
\- Preparación de las relaciones necesarias para consultar posteriormente turnos y tratamientos.  
\- Validaciones correspondientes.  
\- Autorización en backend.

No implementes todavía:

\- Historia clínica.  
\- Gestión completa de mascotas.  
\- Gestión completa de turnos.  
\- Gestión completa de tratamientos.  
\- Roles y permisos avanzados si todavía pertenecen a otra feature.  
\- Asistente virtual.

Si alguna de estas funcionalidades es necesaria únicamente para establecer una relación o estructura requerida por Clientes, implementa solamente lo mínimo necesario y no su comportamiento completo.

SEGURIDAD

Presta especial atención a la autorización.

Un cliente debe poder acceder únicamente a su propia información.

No confíes en IDs proporcionados por el frontend para determinar qué cliente puede consultar o modificar.

Verifica siempre que el usuario autenticado tenga autorización sobre el recurso solicitado.

TESTING

Después de implementar:

1\. Ejecuta los tests existentes.  
2\. Crea los tests necesarios para esta feature.  
3\. Como mínimo cubre:  
   \- creación de un cliente;  
   \- consulta de sus propios datos;  
   \- modificación de sus propios datos;  
   \- intento de acceder a otro cliente;  
   \- intento de modificar otro cliente;  
   \- validaciones;  
   \- asociación correcta con el usuario autenticado.  
4\. Verifica que los tests de funcionalidades existentes continúen funcionando.

FRONTEND

Implementa las interfaces necesarias para que el cliente pueda gestionar su información.

Respeta las convenciones de React y el diseño definido en el proyecto.

No introduzcas una librería de UI nueva si no está definida en technical.md.

VALIDACIÓN FINAL

Cuando termines:

\- Ejecuta toda la suite de tests.  
\- Ejecuta las herramientas de calidad disponibles en el proyecto.  
\- Verifica que no existan errores de build.  
\- Revisa la implementación contra features/04-clients.md.  
\- Comprueba que no se haya implementado funcionalidad fuera del alcance.

Al finalizar, no te limites a decir "terminado".

Entrégame un resumen con:

1\. Archivos creados o modificados.  
2\. Funcionalidades implementadas.  
3\. Reglas de autorización implementadas.  
4\. Tests creados o modificados.  
5\. Comandos de validación ejecutados.  
6\. Resultado de los tests.  
7\. Decisiones técnicas tomadas.  
8\. Cualquier punto pendiente o riesgo detectado.  
