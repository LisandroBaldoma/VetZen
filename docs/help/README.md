# Base de conocimiento de Simón

Este directorio contiene la documentación de ayuda aprobada para Simón. Describe
cómo usar VetZen actualmente; no es documentación técnica, de planificación ni
una fuente de datos clínicos.

## Fuentes de verdad

Antes de crear o actualizar una guía, verificar en este orden:

1. rutas, controladores, Policies, Form Requests, modelos y pruebas;
2. páginas React/Inertia y labels visibles;
3. `spec.md`, `technical.md`, `features.md` y la feature relacionada;
4. auditorías UX solo como apoyo, no como sustituto del código.

Si el código y la documentación histórica discrepan, la guía debe describir el
comportamiento implementado y la contradicción debe registrarse en la feature
activa o en la documentación de producto correspondiente.

## Alcance

Las guías actuales cubren solo los roles `client` y `admin`, y los módulos ya
implementados. No documentar como disponible:

* notificaciones;
* profesionales adicionales, gestión de permisos o roles nuevos;
* acciones de IA, diagnósticos, tratamientos veterinarios o medicamentos;
* datos dinámicos de clientes, mascotas o historia clínica.

## Frontmatter obligatorio

```yaml
---
id: client.solicitar-atencion
title: Solicitar atención para una mascota
roles: [client]
module: service-requests
route_names:
  - services.show
  - pets.service-requests.create
keywords:
  - solicitar atención
  - nueva solicitud
status: available
grounding: allowed
feature_ids: [08, UX-07]
source_refs:
  - routes/web.php
  - docs/ui-audit/06-user-flows.md
reviewed_at: 2026-10-06
---
```

`route_names` usa nombres de Laravel, no URLs. `roles` permite filtrar antes de
un retrieval futuro. `module`, `route_names` y `keywords` permiten priorizar una
guía según el contexto de página. `status` solo puede ser `available` para una
funcionalidad implementada o `unavailable` para una funcionalidad verificada
como no disponible. `grounding` puede ser `allowed` (valor predeterminado),
`unavailable` (valor predeterminado cuando el estado es `unavailable`) o
`not_allowed_for_role` cuando la funcionalidad existe, pero el rol indicado no
puede realizar la acción. Este último debe incluir una alternativa permitida en
el contenido. `source_refs` y `reviewed_at` permiten revisar la vigencia. Git
reemplaza la necesidad de un campo de versión manual.

## Estructura de contenido

Toda guía debe incluir, cuando corresponda:

```md
# Título

## Cuándo usarlo

## Cómo hacerlo

1. Paso verificable.

## Qué sucede después

## Importante

## Relacionado
```

Usar lenguaje de producto y pasos concretos. No exponer nombres de modelos,
tablas, Policies, IDs ni detalles de implementación a usuarios.

## Mantenimiento

Cuando una feature agrega o modifica una tarea visible, debe actualizar las
guías afectadas, verificar sus referencias y agregar preguntas al set de
revisado contra la implementación.
