# UX-05 - Historia clínica

> Estado: implementada y verificada automáticamente. Permanece pendiente la
> revisión manual responsive y de teclado de la sección 11.

## 1. Objetivo

Completar la experiencia de historia clínica contextual al paciente y hacer
efectiva la lectura de toda la historia propia para clientes, manteniendo la
escritura administrativa, la auditoría y el aislamiento horizontal existentes.

## 2. Fuentes y dependencias

- `AGENTS.md`, `spec.md`, `technical.md` y `features.md`.
- `features/06-MedicalRecords.md` como contrato funcional principal.
- `doc/ux/current-panel-audit.md` y `doc/ux/panel-redesign-spec.md`.
- UX-01 para shell y breadcrumbs.
- UX-03 para `PetContextHeader` y contexto persistente.
- UX-04 para acceso autenticado sin verificación de correo obligatoria.

El proyecto usa PHPUnit, React, Inertia, Wayfinder y Tailwind CSS v4.

## 3. Situación actual

- El índice cliente filtra `is_visible_to_client = true`.
- `ClinicalRecordPolicy::view` también deniega registros con ese atributo en
  `false`, aunque F06 ya establece acceso completo para el propietario.
- Las páginas clínicas conservan textos en inglés y tarjetas idénticas en todos
  los anchos.
- La cronología admin no muestra el autor aunque el backend ya lo carga.
- Alta y edición no conservan cabecera del paciente ni breadcrumbs.
- Los controladores serializan modelos Eloquent completos en lugar de props
  explícitas.
- No existe backend de filtros o paginación para historia clínica.

## 4. Alcance

1. Retirar `is_visible_to_client` como filtro y condición de autorización para
   el cliente propietario.
2. Mantener la autorización completa `User → Client → Pet → ClinicalRecord`.
3. Transformar explícitamente las props clínicas y el contexto de mascota.
4. Presentar el historial como cronología responsive con fecha, tipo localizado
   y título; admin ve además autor y visibilidad histórica.
5. Mostrar el contenido clínico completo únicamente en el detalle.
6. Localizar al español listados, detalles y formularios clínicos.
7. Incorporar `PetContextHeader`, breadcrumbs y una sola `h1` en alta y edición.
8. Mantener estados vacíos y acciones reales por rol.
9. Ampliar pruebas HTTP de lectura completa, ownership, props, formularios y
   autorización.

## 5. Fuera de alcance

- Búsqueda, filtros, paginación o query parameters nuevos.
- Nuevas rutas, endpoints globales o API REST.
- Eliminación, Soft Deletes, anulación o versionado adicional.
- Migraciones o eliminación de `is_visible_to_client` del esquema.
- Nuevos tipos o campos clínicos.
- Permisos profesionales granulares.
- Relación persistente con tratamientos o sesiones.
- Exposición de información clínica al asistente virtual.

## 6. Reglas funcionales

1. El cliente consulta todos los registros de sus mascotas propias en modo solo
   lectura, independientemente del valor histórico de
   `is_visible_to_client`.
2. El cliente no consulta historias o registros de mascotas ajenas, aunque
   manipule la mascota o el registro de la URL.
3. El cliente no dispone de endpoints ni controles de alta o edición.
4. Admin consulta, crea y edita registros de cualquier paciente autorizado.
5. No existe eliminación de registros clínicos.
6. La cronología se ordena por `occurred_at DESC` y luego `created_at DESC`.
7. `is_visible_to_client` permanece editable y auditable como atributo
   histórico, pero la interfaz debe aclarar que no restringe la lectura actual
   del propietario.
8. Fecha, tipo y título aparecen en ambos roles. Autor y visibilidad histórica
   aparecen solo en admin.
9. El contenido completo aparece en el detalle, no en la cronología.

## 7. Autorización y seguridad

- Las rutas conservan middleware `auth`; la verificación de correo no condiciona
  el acceso en la versión actual según UX-04.
- Las rutas admin conservan middleware de rol y Policies.
- El índice cliente parte de una mascota autorizada y consulta exclusivamente su
  relación `clinicalRecords`.
- El detalle comprueba que el registro pertenece a la mascota de la URL y que la
  mascota pertenece al cliente autenticado.
- Retirar la condición de visibilidad no modifica ownership ni concede escritura.
- `pet_id`, `client_id`, `created_by` y `updated_by` continúan determinados por el
  backend y no se exponen como campos editables.
- Las props omiten claves de ownership, rutas internas, emails y timestamps no
  utilizados por la página.

## 8. Datos de presentación

### Contexto de mascota

Se entrega el shape mínimo compatible con `PetContextHeader`: datos clínicos y
demográficos necesarios, disponibilidad de foto y, solo para admin, nombre del
responsable.

### Resumen de cronología

- `id`
- `type`
- `title`
- `occurred_at`
- `is_visible_to_client` y `creator.name` solo para admin

### Detalle

Incluye los campos del resumen y `content`. Admin recibe además
`is_visible_to_client`, `creator.name` y `updater.name`.

### Formulario

Recibe tipos aprobados y, en edición, únicamente `id`, `type`, `title`,
`content`, `occurred_at` e `is_visible_to_client`.

## 9. Interfaz

### Cronología

La historia usa una lista cronológica semántica, no una tabla densa. Cada
entrada completa es un enlace Inertia con foco visible y objetivo táctil mínimo
de 44 px. En desktop la línea temporal refuerza fecha y secuencia; en móvil cada
entrada mantiene una tarjeta compacta sin overflow.

No se muestran filtros porque no existe un contrato backend que los soporte.

### Detalle

Mantiene la cabecera contextual y presenta tipo, fecha, contenido con saltos de
línea y metadatos administrativos cuando corresponda.

### Alta y edición

Mantienen el contexto del paciente, breadcrumbs profundos y una única `h1`.
Todos los campos tienen etiqueta visible, errores asociados, estado de carga y
copy en español.

El selector histórico de visibilidad explica que el propietario puede consultar
todo el historial y que el valor se conserva solo como referencia previa.

## 10. Estados

- Sin registros admin: “No hay registros clínicos para este paciente.” y acción
  “Nuevo registro”.
- Sin registros cliente: “Todavía no hay registros clínicos para esta mascota.”
- Acceso ajeno o combinación padre-hijo incorrecta: respuesta backend `403` sin
  serializar contexto clínico.
- Datos opcionales ausentes: se omiten o muestran “No informado” sin inventar
  contenido.

## 11. Responsive y accesibilidad

- Validación manual prevista a 320, 375, 390, 768 y 1280 px.
- Encabezado y acción se apilan cuando sea necesario.
- Ningún contenido clínico genera overflow horizontal de página.
- Existe una sola `h1`; las secciones internas comienzan en `h2`.
- Orden visual y de foco coinciden.
- Tipo, visibilidad y acciones no dependen únicamente del color.
- Los enlaces y controles interactivos tienen foco visible y tamaño táctil
  adecuado.

## 12. Pruebas

Las pruebas PHPUnit deben cubrir mediante HTTP:

- cliente ve registros históricos con visibilidad `true` y `false` de una
  mascota propia;
- cliente abre el detalle de ambos registros;
- cliente no consulta registros, incluso históricos ocultos, de otro cliente;
- cliente no abre formularios ni ejecuta escritura admin;
- admin recibe autor y visibilidad en la cronología;
- índices, detalles y formularios reciben props explícitas sin ownership o datos
  personales innecesarios;
- una combinación incorrecta de mascota y registro es rechazada también al
  editar;
- validación y auditoría existentes continúan funcionando;
- la cronología incluye registros históricamente ocultos y conserva su orden.

La adaptación responsive y el foco requieren revisión manual porque el proyecto
no incorpora pruebas de navegador.

## 13. Archivos previstos

- `features/UX-05-clinical-history.md`.
- Controladores y Policy de `ClinicalRecord`.
- Componentes `clinical-record-*`.
- Páginas clínicas admin y cliente existentes.
- Tipos frontend relacionados.
- `tests/Feature/ClinicalRecord/ClinicalRecordManagementTest.php`.

## 14. Criterios de aceptación

- [x] Cliente consulta toda la historia clínica de sus mascotas propias.
- [x] Cliente no consulta historias ajenas ni modifica registros.
- [x] `is_visible_to_client` no participa en autorización o filtrado cliente.
- [x] Admin conserva alta, consulta, edición, autoría y auditoría.
- [x] La cronología está localizada y muestra los metadatos definidos por rol.
- [x] El contenido completo aparece en detalle.
- [x] Alta y edición conservan contexto, breadcrumbs y una sola `h1`.
- [x] Las props clínicas son explícitas y mínimas.
- [x] No se agregan filtros, rutas, migraciones ni funcionalidades futuras.
- [x] Pruebas, formato, tipos, lint y build pasan.
- [ ] La revisión manual no detecta overflow o bloqueos de teclado.

## 15. Decisiones pendientes

No existen decisiones pendientes dentro de UX-05. El acceso cliente completo y
el carácter histórico de `is_visible_to_client` están resueltos en F06 y en el
rediseño del panel.

## 16. Definición de terminado

UX-05 queda terminada cuando ambos roles reciben una historia clínica contextual,
localizada y responsive, el cliente lee todos los registros propios sin debilitar
ownership, admin conserva escritura y auditoría, y las verificaciones
automatizadas pasan. La revisión responsive y de accesibilidad manual se informa
por separado.
