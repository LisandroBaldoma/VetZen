Corrige exclusivamente los dos problemas críticos de autorización identificados en `docs/ux/current-panel-audit.md`.

Antes de modificar código, revisa `AGENTS.md`, las rutas, controladores, Policies y pruebas existentes.

Problemas:

1. Un cliente puede acceder a rutas administrativas de tratamientos y consultar tratamientos de mascotas ajenas porque `Admin/PetTreatmentController` no autoriza correctamente la mascota padre.
2. El propietario de una solicitud puede abrir la vista administrativa `GET /admin/service-requests/{serviceRequest}` porque la autorización permite al propietario acceder al controlador admin.

Comportamiento esperado:

* Todas las rutas `/admin/*` afectadas deben ser accesibles únicamente por administradores autorizados.
* Un cliente nunca debe recibir una página administrativa, aunque sea propietario de la mascota o solicitud.
* Las rutas cliente deben seguir permitiendo consultar solamente mascotas, solicitudes y tratamientos propios.
* Verifica que un tratamiento anidado pertenezca realmente a la mascota indicada en la URL.
* No dependas de ocultar enlaces en el frontend: la protección debe estar en middleware, Policies, controladores o binding correctamente acotado.
* Mantén el modelo actual de roles y permisos.
* No realices cambios de diseño, navegación, nombres ni funcionalidades.

Agrega pruebas Pest que cubran, como mínimo:

* Admin autorizado accede al listado y detalle administrativo.
* Cliente propietario no puede acceder a la vista administrativa de su solicitud.
* Cliente no puede consultar tratamientos mediante rutas administrativas.
* Cliente no puede acceder a tratamientos de una mascota ajena.
* Cliente puede seguir consultando sus tratamientos mediante las rutas cliente.
* Un tratamiento de otra mascota no puede utilizarse cambiando los parámetros anidados de la URL.
* Usuarios no autenticados son redirigidos al login.

Ejecuta las pruebas específicas y las verificaciones de calidad relacionadas. Si una prueba general falla por un problema previo no relacionado, indícalo sin modificar otras áreas.

Al finalizar informa:

1. Causa exacta de cada vulnerabilidad.
2. Solución aplicada.
3. Archivos modificados.
4. Pruebas agregadas y resultado.
5. Riesgos o casos pendientes.

No hagas commit.
