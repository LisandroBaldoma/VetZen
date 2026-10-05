# UX-12 - Migración visual de listados de mascotas y pacientes

> Estado: implementada. Verificación automatizada completada; revisión visual
> manual responsive pendiente.

## Objetivo

Migrar las referencias visuales de Stitch para los listados de mascotas y
pacientes a componentes React reutilizables, conservando los contratos,
rutas, roles, Policies y reglas de ownership existentes.

## Alcance

- Rediseñar `/pets` para clientes y `/admin/pets` para administradores.
- Compartir encabezado de listado, tarjetas y estado vacío con variantes de
  texto y datos por rol.
- Enviar `sex` como dato de presentación necesario para las tarjetas.
- Mantener la tabla de escritorio administrativa y reemplazar su menú de
  acciones por enlaces visibles a editar y ver ficha.
- Usar solo fotos obtenidas desde la ruta autorizada de la aplicación y
  avatares cuando no exista foto.
- Mantener los bloques informativos del estado vacío como contenido estático
  de la vista, sin convertirlos en datos clínicos ni funcionalidades nuevas.

## Fuera de alcance

- Búsqueda, filtros o paginación.
- Nuevas rutas, endpoints, relaciones, migraciones o reglas de autorización.
- Lectura o escritura de vacunación, anamnesis u otros datos clínicos desde los
  bloques informativos estáticos.

## Seguridad y datos

- Cliente recibe únicamente mascotas de su propio `Client`.
- Admin recibe el responsable mediante `client.user` sin N+1.
- Las props de listado no exponen claves de ownership, rutas internas de foto ni
  timestamps.
- La ruta existente de foto sigue autorizando el acceso antes de responder.

## Verificación

- Pruebas HTTP de los contratos de listado cliente y admin, incluyendo `sex`.
- Formato PHP y TypeScript, lint, chequeo de tipos y build.
- Revisión manual responsive pendiente para 320, 375, 390, 768 y 1280 px.
