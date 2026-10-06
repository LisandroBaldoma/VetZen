# UX-07 - Solicitudes de atención

> Estado: Atención V1 implementada. Verificación automática completada en el
> cierre; la revisión visual manual responsive y de teclado es no bloqueante y
> queda como mejora futura.

## 1. Objetivo

Dar continuidad al recorrido entre servicios, mascotas, solicitudes y
tratamientos, manteniendo ServiceRequest como intención de atención y no como
turno, diagnóstico o tratamiento.

## 2. Dependencias

- UX-01 y UX-03 para navegación y contexto de mascota.
- UX-06 para el catálogo de Services.
- F08 para estados, resolución y ownership.

## 3. Alcance

- Entrada contextual desde el detalle de un Service activo.
- Creación cliente para una mascota propia con Service preseleccionado.
- Listado y detalle cliente dentro de la mascota.
- Listado admin con filtros, paginación y detalle de resolución.
- Cancelación administrativa separada de la resolución.
- Enlace al PetTreatment resultante cuando la solicitud está resuelta.

No incluye turnos, comunicación clínica, selección de Treatment por el cliente,
notificaciones ni cancelación cliente.

## 4. Contrato funcional

1. La UI cliente crea solicitudes `pending` para un Service activo y una Pet
   propia. La Policy actual también autoriza creación administrativa para una
   Pet autorizada; la regla durable de exposición administrativa queda pendiente
   de unificación con F08.
2. El backend deriva la Pet desde la ruta y rechaza ownership manipulado.
3. El cliente no selecciona Treatment ni controla estados profesionales.
4. Admin resuelve únicamente solicitudes pendientes.
5. La resolución exige Service activo y Treatment activo del mismo Service.
6. La resolución crea asignación, snapshots y sesiones en una transacción y luego
   enlaza la solicitud con el PetTreatment creado.
7. Cancelar conserva el historial y no crea otros recursos.

## 5. Experiencia

- Desde un Service, “Solicitar atención” abre el formulario con servicio
  preseleccionado y permite elegir únicamente mascotas propias.
- La solicitud muestra mascota, responsable o servicio según el contexto de rol.
- Los estados aparecen en español.
- El formulario de resolución presenta solo plantillas compatibles.
- Resolver y cancelar son acciones diferenciadas con consecuencias explícitas.
- Listados administrativos densos usan tabla en escritorio y tarjetas en móvil.

## 6. Seguridad y datos

- Autenticación en todas las rutas y rol admin en acciones profesionales.
- Policies y consultas relacionales verifican `User → Client → Pet → ServiceRequest`.
- El frontend nunca es la única barrera de autorización.
- Las props omiten relaciones y campos internos no consumidos.

## 7. Criterios de aceptación

- [x] El cliente inicia una solicitud desde un servicio y conserva el contexto.
- [x] El cliente consulta exclusivamente solicitudes de mascotas propias.
- [x] Admin filtra, abre, cancela y resuelve solicitudes pendientes.
- [x] Un Service inactivo bloquea la resolución.
- [x] Una resolución redirige al tratamiento creado.
- [x] Estados vacíos, errores y vistas responsive están implementados.
- [x] Pruebas HTTP cubren éxito, validación, ownership y estados.
- [ ] Revisión visual manual responsive y de teclado completada.

## 8. Decisiones y mejoras futuras

- Unificar la documentación de creación administrativa: F08/Policy la permiten,
  mientras este flujo V1 solo expone la entrada cliente.
- La revisión manual responsive y de teclado no bloquea el cierre funcional de
  Atención V1.
