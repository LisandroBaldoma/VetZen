# **VetZen — Continuación Feature 04: Clientes, roles y autorización**

Estamos desarrollando **VetZen**, una aplicación Laravel \+ React \+ Inertia.

Quiero continuar la implementación actual de la **Feature 04 — Clientes**.

## **IMPORTANTE**

No partas de cero.

Ya existe código funcional y debes analizarlo antes de modificar nada.

Actualmente el proyecto ya permite:

* registrar un usuario;  
* iniciar sesión;  
* crear automáticamente el registro `Client` asociado al usuario;  
* guardar datos obligatorios del cliente durante el registro, incluyendo teléfono;  
* acceder al panel autenticado;  
* actualizar correctamente los datos del cliente desde `Settings`.

Tu tarea es **continuar desde este estado**, reutilizando todo lo existente y evitando duplicar lógica.

---

# **1\. CONTEXTO OBLIGATORIO**

Antes de modificar código, lee obligatoriamente:

* `spec.md`  
* `technical.md`  
* `features.md`  
* `features/04-clients.md`  
* `features/04-clients-continuation.md`

Si alguno de esos archivos corresponde a una ruta o nombre ligeramente diferente dentro del repositorio, localiza el archivo equivalente sin sustituirlo por documentación no relacionada.

También inspecciona:

* `composer.json`  
* `package.json`  
* rutas web;  
* modelos `User` y `Client`;  
* migraciones existentes;  
* autenticación;  
* registro de usuarios;  
* controladores relacionados con Settings/Profile;  
* Form Requests;  
* Policies;  
* Middleware;  
* páginas React/Inertia;  
* layouts;  
* tests existentes.

La documentación tiene prioridad sobre cualquier supuesto.

No inventes reglas de negocio que no estén documentadas.

---

# **2\. OBJETIVO DE ESTA ITERACIÓN**

Cerrar la base de autorización y experiencia diferenciada para:

1. **administradores**;  
2. **clientes**;

mediante **Spatie Laravel Permission**.

Al finalizar esta iteración:

* todo usuario deberá tener un rol;  
* los clientes deberán tener el rol `client`;  
* deberá existir el rol `admin`;  
* un cliente podrá acceder y modificar únicamente su propia información;  
* un administrador podrá acceder a la gestión de clientes;  
* la navegación y el dashboard deberán adaptarse al rol;  
* un cliente tendrá una vista propia desde la cual pueda consultar, completar y modificar sus datos;  
* no deberá ser posible acceder a otro cliente manipulando IDs, URLs o requests;  
* Settings deberá continuar funcionando;  
* la lógica de actualización del cliente no deberá duplicarse entre Settings y el nuevo dashboard.

Esta implementación debe servir como base para futuras features de:

* mascotas;  
* turnos;  
* tratamientos;  
* profesionales;  
* historia clínica.

Pero **NO implementes todavía esas features**.

---

# **3\. PRIMERA FASE: AUDITORÍA DEL ESTADO ACTUAL**

Antes de escribir código, analiza el proyecto y determina:

### **Backend**

* cómo se crea actualmente `User`;  
* cómo se crea actualmente `Client`;  
* relación entre `User` y `Client`;  
* campos actuales de `clients`;  
* validaciones existentes;  
* controladores existentes;  
* Form Requests existentes;  
* Policies existentes;  
* middleware utilizado;  
* rutas protegidas;  
* forma en que el usuario autenticado obtiene su cliente.

### **Frontend**

* layout actual;  
* navegación lateral o superior;  
* dashboard actual;  
* Settings/Profile;  
* formularios existentes;  
* componentes reutilizables;  
* sistema de UI actual;  
* manejo de errores de Inertia;  
* estructura de páginas.

### **Spatie**

Comprueba si:

spatie/laravel-permission

ya está instalado y configurado.

Si no está instalado, intégralo siguiendo la documentación oficial compatible con la versión de Laravel utilizada por este proyecto.

No cambies de stack ni agregues otra librería de autorización.

---

# **4\. ROLES INICIALES**

Para esta iteración utiliza solamente:

admin  
client

No implementes todavía una matriz completa para profesionales.

La arquitectura deberá permitir agregar posteriormente roles como profesionales y permisos más específicos sin tener que rehacer la autorización.

Si el proyecto ya tiene una convención de nombres diferente, conserva la existente, siempre que no contradiga la documentación obligatoria.

---

# **5\. INTEGRACIÓN CON SPATIE**

Integra correctamente `spatie/laravel-permission`.

El modelo `User` deberá utilizar:

HasRoles

Configura correctamente:

* migraciones;  
* modelos;  
* middleware necesario;  
* roles;  
* permisos mínimos;  
* seeders.

La creación de roles y permisos debe ser reproducible mediante seeders.

No dependas de registros creados manualmente en la base de datos.

---

# **6\. ASIGNACIÓN AUTOMÁTICA DEL ROL CLIENT**

Todo usuario registrado mediante el flujo público de registro debe recibir automáticamente:

client

La asignación del rol debe realizarse en backend.

No confíes en un valor enviado por el frontend como:

role=client

Un usuario nunca debe poder registrarse públicamente como `admin` manipulando la request.

Revisa el flujo actual de registro y realiza esta integración sin romper:

registro User  
→ creación Client  
→ asociación User/Client  
→ asignación del rol client

Mantén la operación consistente.

Si actualmente existe una transacción para crear `User` y `Client`, reutilízala.

Si no existe y las operaciones deben ejecutarse de forma atómica, evalúa utilizar una transacción sin introducir arquitectura innecesaria.

---

# **7\. ADMINISTRADOR**

Crea un mecanismo reproducible para disponer de al menos un usuario administrador durante desarrollo.

Preferentemente utiliza:

* Seeder;  
* Factory cuando sea útil.

No agregues credenciales sensibles hardcodeadas para producción.

El rol `admin` debe poder gestionar clientes.

---

# **8\. AUTORIZACIÓN DE CLIENTES**

Este punto es crítico.

Un cliente solamente puede consultar o modificar el `Client` asociado al usuario autenticado.

Nunca utilices únicamente algo como:

Client::find($request-\>client\_id)

para decidir qué cliente se modifica.

Tampoco confíes en:

/client/{id}

sin autorización adicional.

Para operaciones del propio cliente, prioriza obtener el recurso desde el usuario autenticado, por ejemplo conceptualmente:

request()-\>user()-\>client

según las relaciones reales que existan en el proyecto.

La implementación exacta debe respetar la arquitectura actual.

---

# **9\. POLICIES**

Analiza si el proyecto ya utiliza Laravel Policies.

Si existe esta convención, continúa utilizándola.

Implementa autorización para `Client` de manera que conceptualmente:

### **Cliente**

Puede:

view own client  
update own client

No puede:

view another client  
update another client  
list all clients  
delete clients  
administrate clients

### **Admin**

Puede:

viewAny clients  
view client  
update client

La eliminación de clientes solo debe implementarse si ya forma parte explícita del alcance actual.

No agregues `delete` por iniciativa propia.

No utilices únicamente condiciones en React para proteger recursos.

La protección real debe existir en Laravel.

---

# **10\. PERMISOS**

Utiliza Spatie para permisos relacionados con capacidades de administración.

Mantén una nomenclatura consistente.

Por ejemplo, si el proyecto no tiene otra convención:

clients.viewAny  
clients.view  
clients.update

No crees decenas de permisos preventivamente.

Aplica únicamente los necesarios en esta iteración.

Diferencia:

rol / permiso

de:

propiedad del recurso

Spatie determina qué funcionalidades puede utilizar un usuario.

Las Policies y relaciones de dominio deben garantizar sobre qué recursos concretos puede actuar.

Un usuario con rol `client` NO obtiene acceso a otro cliente por tener permiso de actualizar un cliente.

---

# **11\. RUTAS**

Organiza las rutas diferenciando correctamente las áreas.

Conceptualmente debe existir una separación equivalente a:

dashboard cliente  
administración de clientes

No es obligatorio utilizar estas URLs exactas si contradicen las convenciones existentes.

Ejemplo conceptual:

/dashboard  
/client/profile  
/admin/clients  
/admin/clients/{client}

Reutiliza grupos de middleware existentes.

Las rutas administrativas deben estar protegidas en backend mediante roles y permisos.

Las rutas del cliente deben requerir autenticación.

No dependas de ocultar enlaces en React como mecanismo de seguridad.

---

# **12\. DASHBOARD DEL CLIENTE**

Implementa una experiencia de dashboard para el usuario con rol `client`.

Debe permitir como mínimo:

* visualizar sus datos personales;  
* visualizar sus datos de contacto;  
* completar datos faltantes;  
* modificar sus propios datos.

Reutiliza el formulario, las validaciones y la lógica existentes en Settings siempre que sea posible.

No copies y pegues dos implementaciones distintas del mismo formulario.

Si Settings y dashboard necesitan usar la misma lógica:

* extrae componentes React reutilizables;  
* reutiliza Form Requests;  
* reutiliza acciones, servicios o controladores existentes según la arquitectura actual.

Settings debe seguir funcionando después del refactor.

---

# **13\. DASHBOARD / ÁREA ADMIN**

Implementa la base de gestión de clientes para `admin`.

Como mínimo debe poder:

* acceder a una vista de clientes;  
* listar clientes existentes;  
* consultar los datos principales de un cliente;  
* acceder a su detalle;  
* editar la información permitida actualmente.

No agregues todavía:

* historia clínica;  
* tratamientos;  
* turnos;  
* gestión completa de mascotas.

Si existen relaciones preparadas, puedes mostrarlas como secciones vacías o estados preparados únicamente si esto encaja con el diseño existente.

No inventes datos.

---

# **14\. NAVEGACIÓN SEGÚN ROL**

Actualiza la navegación para diferenciar:

## **Cliente**

Debe ver únicamente opciones correspondientes a su experiencia.

Ejemplo conceptual:

Dashboard  
Mis datos  
Mis mascotas      \[solo si ya existe funcionalidad\]  
Turnos            \[solo si ya existe\]  
Tratamientos      \[solo si ya existe\]  
Settings

No implementes pantallas inexistentes solamente para completar el menú.

## **Admin**

Debe poder acceder a:

Dashboard  
Clientes

y a las funcionalidades administrativas que ya existan.

No muestres opciones que todavía no están implementadas.

Recuerda:

> ocultar un item del menú mejora la UX, pero no reemplaza la autorización backend.

---

# **15\. RELACIÓN USER ↔ CLIENT**

Verifica que la relación sea clara y consistente.

Conceptualmente esperamos algo equivalente a:

User  
    hasOne Client

Client  
    belongsTo User

No cambies la cardinalidad si el proyecto actual o la documentación indican otra cosa.

Comprueba además:

* foreign key;  
* índices;  
* restricciones;  
* comportamiento al eliminar usuarios;  
* nullability;  
* integridad referencial.

No generes una nueva migración que duplique columnas existentes.

---

# **16\. PREPARACIÓN PARA MASCOTAS**

Feature 04 necesita quedar preparada para la futura relación:

Client  
→ Pets

Si el modelo o la relación ya existe, reutilízalo.

Si todavía no existe la Feature Mascotas, no implementes su CRUD completo.

Solo implementa relaciones mínimas si son realmente necesarias y están respaldadas por la documentación.

No agregues campos de mascotas que todavía no hayan sido definidos.

---

# **17\. TURNOS Y TRATAMIENTOS**

La Feature Clientes contempla posteriormente consultar turnos y tratamientos.

En esta iteración:

NO implementes el comportamiento completo.

Solo conserva o prepara relaciones si ya existen entidades correspondientes.

No construyas:

* agenda;  
* solicitudes de turno;  
* tratamientos;  
* historia clínica;  
* seguimiento.

---

# **18\. VALIDACIONES**

Centraliza las validaciones en Laravel mediante Form Requests o el patrón ya utilizado por el proyecto.

Revisa las reglas actuales para:

* nombre;  
* apellido;  
* teléfono;  
* email;  
* otros campos existentes.

No inventes nuevos campos obligatorios.

Las validaciones frontend pueden mejorar la UX, pero Laravel sigue siendo la fuente real de validación.

---

# **19\. MASS ASSIGNMENT Y SEGURIDAD**

Revisa:

$fillable  
$guarded

Evita permitir la modificación accidental de campos como:

user\_id  
role  
permissions

desde formularios de cliente.

Un cliente nunca debe poder cambiar:

client.user\_id  
user.role  
user.permissions

mediante manipulación del request.

---

# **20\. INERTIA Y REACT**

Mantén el stack actual:

Laravel  
React  
Inertia

No agregues:

* API REST innecesaria;  
* Redux;  
* nueva librería UI;  
* nuevo router frontend;  
* Axios si Inertia ya cubre el caso;  
* arquitectura paralela.

Respeta los componentes y convenciones actuales.

---

# **21\. TESTS OBLIGATORIOS**

Antes de finalizar, crea o actualiza tests.

Usa Pest o PHPUnit según lo que ya utilice el proyecto.

Como mínimo prueba:

### **Registro**

un usuario registrado recibe el rol client

### **Asociación**

el usuario registrado tiene Client correctamente asociado

### **Cliente propio**

client puede consultar sus datos  
client puede modificar sus datos

### **Seguridad horizontal**

Crea:

Client A  
Client B

y verifica:

Client A NO puede consultar Client B  
Client A NO puede modificar Client B

Intenta hacerlo mediante las rutas HTTP reales.

No pruebes solamente que el enlace está oculto.

### **Admin**

Verifica:

admin puede acceder al listado de clientes  
admin puede consultar un cliente  
admin puede editar información permitida

### **Acceso administrativo**

Verifica:

client NO puede acceder a /admin/clients

o su ruta equivalente.

### **Manipulación del request**

Comprueba que un cliente no pueda enviar:

client\_id  
user\_id  
role  
permissions

para obtener acceso o modificar asociaciones.

### **Validaciones**

Mantén o crea tests para los campos requeridos de `Client`.

---

# **22\. REGRESIÓN**

Los tests existentes deben continuar pasando.

Presta especial atención a:

Authentication  
Registration  
Settings/Profile  
Client

No rompas el flujo actual de registro ni la actualización desde Settings.

---

# **23\. VALIDACIÓN TÉCNICA**

Al finalizar, inspecciona `composer.json` y `package.json` para identificar los comandos realmente disponibles.

Ejecuta como mínimo, cuando correspondan:

php artisan test

y las herramientas existentes del proyecto para:

PHP formatting  
PHP static analysis  
frontend lint  
frontend type checking  
frontend build

Ejemplos habituales pueden ser:

vendor/bin/pint  
npm run lint  
npm run types  
npm run build

pero NO asumas que existen.

Utiliza exclusivamente los scripts configurados realmente en este repositorio.

---

# **24\. NO HACER**

No implementes en esta iteración:

* historia clínica;  
* CRUD completo de mascotas;  
* CRUD de turnos;  
* CRUD de tratamientos;  
* agenda;  
* notificaciones;  
* asistente IA;  
* RAG;  
* auditoría completa;  
* roles profesionales avanzados;  
* panel avanzado de administración de permisos;  
* funcionalidades no documentadas.

Tampoco:

* cambies de arquitectura;  
* agregues una API innecesaria;  
* dupliques formularios;  
* dupliques validaciones;  
* reemplaces funcionalidad que actualmente funciona sin una razón técnica concreta.

---

# **25\. FORMA DE TRABAJO**

Trabaja en este orden:

1\. inspección  
2\. diagnóstico  
3\. propuesta  
4\. implementación backend  
5\. autorización  
6\. frontend  
7\. tests  
8\. validación  
9\. documentación

Antes de realizar cambios importantes, presenta brevemente:

CURRENT STATE  
ALREADY IMPLEMENTED  
MISSING  
FILES LIKELY TO CHANGE  
AUTHORIZATION STRATEGY  
IMPLEMENTATION PLAN

Después continúa con la implementación.

No te detengas por decisiones técnicas menores que puedan resolverse usando convenciones ya existentes en el proyecto.

Si encuentras una decisión de negocio no definida que cambie el comportamiento funcional, no la inventes:

DECISION REQUIRED  
Context:  
Options:  
Impact:  
Recommendation:

Detente únicamente en ese punto.

---

# **26\. CRITERIOS DE ACEPTACIÓN**

La iteración se considera completa solamente si:

* Spatie Laravel Permission está correctamente integrado.  
* `User` utiliza roles y permisos correctamente.  
* los nuevos usuarios públicos reciben `client`.  
* existe una forma reproducible de crear `admin`.  
* `User ↔ Client` está correctamente asociado.  
* el cliente puede ver sus datos.  
* el cliente puede modificar sus datos.  
* el cliente no puede consultar otro cliente.  
* el cliente no puede modificar otro cliente.  
* el cliente no puede acceder al área administrativa.  
* el admin puede acceder a la gestión de clientes.  
* existe un dashboard o vista apropiada para clientes.  
* existe un área de clientes para admin.  
* Settings continúa funcionando.  
* el backend protege los recursos independientemente del frontend.  
* no se implementaron features fuera de alcance.  
* los tests pasan.  
* el frontend compila correctamente.

---

# **27\. INFORME FINAL**

Al terminar, entrega:

## **Implementation summary**

### **Files created**

Lista y propósito.

### **Files modified**

Lista y propósito.

### **Authorization**

Explica:

* roles;  
* permisos;  
* Policies;  
* middleware;  
* protección de ownership.

### **Backend**

Explica los cambios principales.

### **Frontend**

Explica:

* dashboard client;  
* admin clients;  
* componentes reutilizados.

### **Tests**

Lista los escenarios cubiertos.

### **Commands executed**

Incluye los comandos realmente ejecutados.

### **Results**

Indica:

tests passed / failed  
lint  
typecheck  
build

### **Security verification**

Confirma específicamente:

Client A cannot view Client B  
Client A cannot update Client B  
Client cannot access admin clients  
Client cannot assign itself admin role  
Client cannot change user\_id/client ownership

### **Pending**

Lista únicamente cuestiones reales pendientes.

### **Scope verification**

Confirma que no implementaste funcionalidades fuera de Feature 04 y de la infraestructura mínima de roles y permisos necesaria para soportarla.

