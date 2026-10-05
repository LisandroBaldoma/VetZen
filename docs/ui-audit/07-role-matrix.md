# Matriz de roles y autorización

La fuente de autoridad es backend: todas las rutas de dominio usan `auth`; las administrativas usan `role:admin`; Policies verifican ownership y scoped bindings protegen recursos anidados. La UI solo refleja esas capacidades.

| Capacidad | Admin | Client |
|---|---|---|
| Inicio | dashboard operativo con solicitudes | dashboard de sus mascotas/solicitudes/tratamientos |
| Clientes | listar y editar, con permisos Spatie `clients.*` | editar solo su Client mediante Policy |
| Mascotas | listar, crear, ver y editar todas | crear, ver y editar únicamente las propias |
| Foto mascota | no ruta admin dedicada | ver/eliminar foto propia por ruta protegida |
| Historia clínica | leer, crear y editar cualquier paciente | leer todos los registros de mascotas propias; sin crear/editar |
| Servicios/procedimientos | CRUD y activar/desactivar | leer únicamente servicios/procedimientos activos |
| Plantillas | administrar catálogo | sin acceso directo |
| Solicitudes | listar, ver, resolver y cancelar todas | crear y consultar solo solicitudes de mascotas propias |
| Tratamientos y sesiones | asignar, actualizar asignación/estado y sesiones | leer solamente asignaciones/sesiones de mascotas propias |
| Configuración cuenta | perfil, seguridad, apariencia | igual; además perfil Client propio |

## Roles realmente existentes

Solo `admin` y `client` están seedados. No hay roles profesionales adicionales ni panel para administrar permisos, aunque el producto de alto nivel los contempla. Un usuario sin rol permitido no obtiene menú útil; no existe una pantalla explícita de acceso insuficiente.

## Ownership comprobado

`User → Client → Pet → ClinicalRecord/ServiceRequest/PetTreatment → TreatmentSession`. Policies de Pet, ClinicalRecord, ServiceRequest, PetTreatment y TreatmentSession siguen esa cadena para cliente. ServiceRequest y PetTreatment anidados también dependen de `scopeBindings()`.
