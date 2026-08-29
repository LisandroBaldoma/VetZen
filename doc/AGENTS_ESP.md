# VetZen — Instrucciones para Agentes de IA

## 1. Contexto del proyecto

VetZen es una plataforma de gestión veterinaria construida con Laravel, React, Inertia y una base de datos relacional.

El desarrollo sigue un flujo basado en documentación y features.

La documentación del proyecto es la fuente de verdad para requisitos de producto, reglas de negocio, arquitectura, alcance de las features y decisiones explícitamente resueltas.

Antes de implementar funcionalidad, el agente debe comprender la documentación relevante e inspeccionar la implementación actual.

Laravel Boost complementa esta documentación proporcionando herramientas para inspeccionar la aplicación y consultar documentación del framework. No reemplaza las especificaciones de producto de VetZen.

---

## 2. Fuente de verdad

Los principales documentos del proyecto son:

* `spec.md` — requisitos del producto y comportamiento de negocio.
* `technical.md` — arquitectura, decisiones técnicas, restricciones y decisiones técnicas pendientes.
* `features.md` — mapa global y requisitos generales de las features.
* `features/*.md` — especificación detallada y contrato de implementación de cada feature.

Estos documentos tienen responsabilidades diferentes y no deben tratarse como intercambiables.

Al implementar una feature, su archivo correspondiente dentro de `features/` será el contrato principal de implementación, pero deberá ser compatible con `spec.md`, `technical.md` y las features anteriores relevantes.

Si la documentación y la implementación se contradicen, el agente no debe elegir silenciosamente una de ellas.

Debe identificar la inconsistencia e informarla antes de tomar una decisión que modifique comportamiento del producto, seguridad, ownership o arquitectura.

---

## 3. Flujo obligatorio de una feature

Antes de implementar o modificar una feature:

1. Leer `spec.md`.
2. Leer `technical.md`.
3. Leer `features.md`.
4. Leer la especificación de la feature activa.
5. Leer las features anteriores directamente relacionadas cuando la nueva feature dependa de su dominio, ownership, autorización o comportamiento.
6. Inspeccionar la implementación existente.
7. Inspeccionar esquema de base de datos, modelos, relaciones, rutas, Policies, Form Requests, controllers/actions, páginas React/Inertia, componentes, factories y tests relevantes.
8. Utilizar Laravel Boost cuando proporcione información estructurada de la aplicación.
9. Utilizar la búsqueda de documentación de Boost antes de depender de comportamientos o APIs específicos de una versión.
10. Identificar decisiones pendientes que afecten la implementación.
11. Recién entonces crear el plan de implementación o modificar código.

No comenzar una implementación solamente a partir del prompt del usuario cuando exista una especificación de la feature.

No asumir que la especificación representa perfectamente la implementación actual. Verificar ambas.

---

## 4. Alcance de las features

Implementar solamente la especificación de la feature activa.

No implementar anticipadamente features futuras.

Las relaciones futuras pueden considerarse para tomar buenas decisiones arquitectónicas, pero no justifican crear de forma especulativa:

* tablas;
* modelos;
* controllers;
* servicios;
* permisos;
* jobs;
* eventos;
* secciones frontend;
* integraciones;
* abstracciones.

Por ejemplo:

* Pets podrá relacionarse posteriormente con tratamientos, turnos, planes de seguimiento e historia clínica.
* La información clínica podrá ser utilizada posteriormente por un asistente IA.
* Esas relaciones futuras no justifican implementar dichas features anticipadamente.

Preferir el diseño más simple que satisfaga correctamente la especificación actual y que sea razonablemente extensible.

Evitar sobreingeniería.

---

## 5. Decisiones pendientes

Las especificaciones pueden contener:

```text
DECISIÓN PENDIENTE
DECISIÓN TÉCNICA PENDIENTE
```

Estas decisiones están intencionalmente sin resolver.

Nunca resolverlas silenciosamente.

Antes de implementar código afectado por una de ellas:

1. identificar la decisión pendiente;
2. explicar por qué la implementación depende de ella;
3. presentar alternativas razonables cuando sea útil;
4. detener la parte afectada hasta resolver la decisión.

Las partes independientes de la feature pueden continuar si no dependen de esa decisión.

Una vez que el usuario resuelva una decisión, actualizar o solicitar la actualización de la documentación correspondiente antes de tratarla como una regla permanente.

No convertir una recomendación en requisito sin aprobación explícita.

---

## 6. Primero la arquitectura existente

Siempre inspeccionar el código existente antes de introducir un patrón nuevo.

Seguir las convenciones existentes para:

* controllers;
* Form Requests;
* Policies;
* models;
* relaciones;
* migrations;
* routes;
* páginas Inertia;
* componentes React;
* layouts;
* factories;
* seeders;
* tests.

Reutilizar componentes y patrones existentes cuando corresponda.

No introducir repositories, service layers, DTOs, actions, interfaces, eventos u otras abstracciones solamente porque sean teóricamente deseables.

Introducir una abstracción únicamente cuando la feature actual proporcione una razón concreta.

Preferir las convenciones de Laravel y la arquitectura existente de VetZen frente a infraestructura personalizada innecesaria.

---

## 7. Modelo de ownership

VetZen utiliza ownership de recursos como una frontera fundamental de seguridad.

La cadena actual comienza con:

```text
User
  ↓
Client
  ↓
Pet
  ↓
Recursos propiedad del cliente
```

Los recursos futuros pueden extender esta cadena.

Por ejemplo:

```text
User
  ↓
Client
  ↓
Pet
  ↓
Recurso clínico
```

El ownership debe verificarse siempre en backend.

Nunca confiar en identificadores enviados desde frontend como prueba de ownership.

Campos como:

```text
user_id
client_id
pet_id
created_by
updated_by
role
permissions
```

no deben asignarse masivamente cuando eso pueda modificar ownership, autoría o autorización.

Siempre que sea posible, derivar el ownership mediante:

```text
usuario autenticado
        ↓
relación autorizada
        ↓
recurso
```

No aceptar una foreign key enviada desde frontend cuando el backend pueda obtenerla de forma segura desde el contexto autenticado.

---

## 8. Autorización

La visibilidad en frontend no es seguridad.

La autorización siempre debe aplicarse mediante Laravel en backend.

Utilizar Policies para autorización a nivel de recurso y comprobaciones de ownership.

Utilizar Spatie Laravel Permission para roles y capacidades generales cuando corresponda.

Mantener la autorización simple salvo que la especificación de la feature requiera explícitamente permisos granulares.

Los roles base actuales son:

```text
admin
client
```

No crear roles adicionales ni grandes matrices de permisos sin un requisito explícito.

Un cliente nunca debe obtener acceso a recursos de otro cliente manipulando:

* URLs;
* parámetros de ruta;
* payloads;
* foreign keys;
* requests de Inertia;
* requests HTTP directos.

La autorización horizontal debe probarse explícitamente.

Cuando un recurso pertenezca a un Pet, la autorización del cliente deberá verificar finalmente toda la relación de ownership y no confiar solamente en el ID del recurso.

---

## 9. Información clínica

La información clínica es información sensible de la aplicación.

Los recursos clínicos siempre deben consultarse mediante relaciones autenticadas y autorizadas.

Para acceso de clientes, el ownership deberá resolverse mediante:

```text
User
  ↓
Client
  ↓
Pet
  ↓
Recurso clínico
```

No exponer información clínica simplemente porque exista un ID válido.

El acceso del cliente a información clínica deberá permanecer como solo lectura salvo que una futura especificación cambie explícitamente esta regla.

No implementar acceso de IA a información clínica salvo que la feature activa lo requiera explícitamente.

La futura funcionalidad de IA deberá respetar las mismas fronteras de autenticación, autorización, ownership y selección de información que el resto de VetZen.

Los datos clínicos nunca deben quedar automáticamente disponibles para un modelo de IA simplemente porque existan en la base de datos.

---

## 10. Validación y Mass Assignment

Utilizar validación backend de Laravel para toda entrada persistida proporcionada por usuarios.

Preferir Form Requests cuando sea consistente con la aplicación existente.

Validación y autorización son conceptos diferentes.

Un request válido no necesariamente está autorizado.

La validación frontend puede mejorar UX, pero nunca reemplaza la validación backend.

Proteger campos de ownership, autoría, roles y permisos contra mass assignment inseguro.

No permitir reasignar recursos manipulando:

```text
user_id
client_id
pet_id
created_by
updated_by
```

cuando dichos valores deban ser determinados por backend.

---

## 11. Cambios de base de datos

Antes de crear o modificar una migration:

1. inspeccionar el esquema actual mediante Laravel Boost cuando esté disponible;
2. inspeccionar migrations relacionadas;
3. inspeccionar modelos y relaciones;
4. verificar la especificación de la feature activa;
5. comprobar decisiones pendientes que afecten al esquema.

No modificar migrations históricas que puedan haber sido ejecutadas, salvo instrucción explícita.

Crear nuevas migrations para evolucionar el esquema.

No introducir Soft Deletes salvo que la documentación correspondiente lo haya decidido explícitamente para ese recurso.

No agregar columnas especulativas para features futuras.

Mantener integridad relacional mediante foreign keys y constraints apropiadas cuando sean consistentes con el modelo de dominio aprobado.

---

## 12. Uso de Laravel Boost

Laravel Boost será la capa preferida para inspeccionar la aplicación cuando sus herramientas correspondan.

Utilizar Boost cuando sea apropiado para consultar:

* esquema de base de datos;
* configuración;
* rutas;
* documentación del ecosistema Laravel;
* logs y errores;
* estado de la aplicación disponible mediante sus herramientas.

Preferir las herramientas estructuradas de Boost frente a inspecciones improvisadas cuando ambas proporcionen la misma información.

Antes de depender de APIs de Laravel, Inertia, autenticación, autorización, testing, filesystem, queues u otros componentes cuyo comportamiento dependa de versiones instaladas, utilizar la búsqueda de documentación de Boost.

Laravel Boost complementa la documentación del proyecto.

No define los requisitos de negocio de VetZen.

El comportamiento de negocio debe provenir de las especificaciones y decisiones explícitamente resueltas, no de convenciones del framework ni suposiciones del agente.

---

## 13. Frontend

El frontend utiliza React con Inertia.

Seguir la arquitectura de UI, componentes, layouts y convenciones existentes.

Antes de crear un componente nuevo:

1. inspeccionar componentes existentes;
2. reutilizar uno existente cuando corresponda;
3. seguir los patrones establecidos.

Las interfaces de cliente y admin pueden mostrar acciones y navegación diferentes.

Las restricciones frontend existen para mejorar la experiencia del usuario.

La autorización backend sigue siendo obligatoria.

No duplicar reglas de ownership o autorización en React como único mecanismo de seguridad.

No introducir una nueva arquitectura de estado o UI sin un requisito concreto.

---

## 14. Testing

Cada implementación debe agregar o actualizar tests relevantes.

Preferir Feature tests para comportamiento de la aplicación.

Como mínimo probar:

* comportamiento exitoso esperado;
* fallos de validación relevantes;
* autorización;
* ownership;
* caminos de error importantes;
* regresión de comportamiento existente directamente relacionado.

Para recursos propiedad de clientes, probar explícitamente autorización horizontal utilizando al menos dos clientes y sus recursos.

Ejemplo:

```text
Client A → Resource A
Client B → Resource B

Client A puede acceder Resource A.
Client A no puede acceder Resource B.
```

Cuando admin tenga acceso más amplio, probar ese comportamiento por separado.

Para ownership anidado, probar la relación completa.

Ejemplo:

```text
Client A → Pet A → Clinical Record A
Client B → Pet B → Clinical Record B
```

Un request de Client A nunca debe acceder a Clinical Record B.

Siempre que sea práctico, probar autorización mediante rutas HTTP reales de la aplicación y no solamente mediante métodos aislados de Policies.

Ejecutar durante el desarrollo el conjunto mínimo de tests que cubra el cambio.

Antes de declarar una feature completa, ejecutar todos los tests afectados y verificar que funcionalidades anteriores relacionadas no hayan sufrido regresiones.

Nunca afirmar que los tests pasan si no fueron ejecutados correctamente.

---

## 15. Documentación y reglas permanentes

No crear archivos de documentación salvo solicitud explícita.

No duplicar innecesariamente una misma regla de negocio en múltiples documentos.

El comportamiento del producto pertenece a:

```text
spec.md
features.md
features/*.md
```

Las decisiones técnicas y arquitectónicas pertenecen a:

```text
technical.md
```

El flujo de trabajo del agente pertenece a:

```text
AGENTS.md
```

Las reglas permanentes específicas de implementación para agentes pueden pertenecer a:

```text
.ai/rules/
```

cuando Laravel Boost lo soporte.

Utilizar `record-rule` de Laravel Boost para reglas permanentes orientadas a agentes descubiertas durante la implementación cuando corresponda.

No utilizar la memoria del agente como único lugar donde almacenar una decisión del proyecto.

Las decisiones importantes deben permanecer visibles y versionadas en el repositorio.

---

## 16. Principios de desarrollo con IA

Los agentes de IA ayudan a implementar; no definen los requisitos del producto.

Nunca inventar reglas de negocio para desbloquear una implementación.

Nunca considerar correcto código generado sin verificarlo.

Para cada implementación significativa:

```text
Requerimiento
    ↓
Especificación
    ↓
Implementación
    ↓
Autorización
    ↓
Tests
    ↓
Verificación
```

Utilizar IA para acelerar:

* inspección del repositorio;
* implementación;
* refactoring;
* generación de tests;
* consulta de documentación;
* debugging.

No utilizar IA para evitar decisiones de producto o arquitectura todavía pendientes.

Cuando una incertidumbre afecte seguridad, ownership, información clínica, diseño de base de datos o comportamiento público, exponer la incertidumbre en lugar de adivinar.

---

## 17. Definition of Done

Una feature no está terminada simplemente porque funcione la UI.

Antes de considerarla completa, verificar:

```text
Especificación
      ↓
Base de datos
      ↓
Backend
      ↓
Autorización
      ↓
Frontend
      ↓
Tests
      ↓
Verificación
```

Confirmar que:

* la implementación coincide con la especificación activa;
* continúa siendo compatible con `spec.md` y `technical.md`;
* la autorización está aplicada en backend;
* las reglas de ownership están protegidas;
* existe validación backend;
* mass assignment es seguro;
* los tests relevantes pasan;
* las herramientas requeridas de formato y calidad pasan;
* no se implementó funcionalidad futura no relacionada;
* ninguna decisión pendiente fue resuelta silenciosamente.

Si algún punto requerido no puede verificarse, informarlo.

---

## 18. Informe final del agente

Ser conciso al informar trabajo realizado.

Al finalizar una feature o tarea significativa, resumir:

```text
IMPLEMENTED
FILES CHANGED
DATABASE
AUTHORIZATION
VALIDATION
TESTS
PENDING DECISIONS
SCOPE VERIFICATION
```

Incluir solamente las secciones relevantes.

Distinguir claramente entre:

```text
implementado
verificado
no probado
pendiente
```

Nunca afirmar que un comando, migration, build o test funcionó si no fue ejecutado exitosamente.

Nunca declarar completa una feature si fallan tests requeridos o queda una decisión pendiente que bloquea su implementación.

---

## 19. Principio central

Cuando deba elegirse entre:

```text
adivinar
```

y:

```text
consultar la especificación,
inspeccionar la aplicación,
utilizar Laravel Boost,
o solicitar una decisión
```

siempre elegir verificar.

Construir solamente lo que VetZen requiere actualmente, proteger ownership e información clínica desde backend, respetar la arquitectura existente y dejar el código preparado para la siguiente feature sin implementarla anticipadamente.

<!-- Debajo de esta línea se conserva el bloque generado por Laravel Boost. -->

<!-- No eliminar manualmente las reglas de framework/packages generadas por Boost. -->

<laravel-boost-guidelines>

[CONSERVAR AQUÍ SIN MODIFICACIONES EL CONTENIDO ACTUAL GENERADO POR LARAVEL BOOST]

</laravel-boost-guidelines>
