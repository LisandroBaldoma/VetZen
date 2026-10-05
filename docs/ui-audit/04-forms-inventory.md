# Auditoría de formularios

Los formularios usan Inertia `<Form>`/datos nativos y errores de Form Request. Todos requieren autenticación salvo Fortify. Los errores se muestran cerca del campo; clínica y resolución incluyen resumen. Las etiquetas de mascota/cliente heredadas aún están en inglés.

## Cuenta y autenticación

| Formulario | Endpoint / permiso | Campos |
|---|---|---|
| Login | `POST /login`, público | email requerido, password requerido, remember boolean; passkey alternativo. |
| Registro | `POST /register`, público | name, email, phone requeridos; password y confirmation requeridos, reglas `passwordRules`. |
| Recuperar/resetear | Fortify | email; luego token, email readonly, password y confirmation. |
| Perfil usuario | `PATCH /settings/profile`, auth, `ProfileUpdateRequest` | nombre y email requeridos; estado de verificación. |
| Perfil cliente | `PATCH /clients/{client}`, Policy update, `UpdateClientRequest` | phone, address, city, province, postal_code, document, birth_date; contacto/residencia, opcionales según request. |
| Seguridad | `PUT /settings/password`, auth + password confirm | current_password, password, password_confirmation; paneles separados para 2FA/passkeys. |

## Mascotas

**Crear/editar mascota**: cliente `POST/PATCH /pets`; admin `POST/PATCH /admin/pets`; `StorePetRequest`/`UpdatePetRequest`; Policy Pet. `PetFormFields` comparte los campos.

| Campo visible | Técnico / control / requerido | Regla y valor |
|---|---|---|
| Cliente (solo admin) | `client_id`, select nativo | requerido admin, debe existir; opciones nombre + email. |
| Nombre, especie, sexo | `name`, `species`, `sex`, text | requeridos, max 255/100/50. |
| Raza, color | `breed`, `color`, text | opcionales, max 100. |
| Fecha de nacimiento | `birth_date`, date | opcional, fecha no futura. |
| Peso | `weight`, number step .01 | opcional, 0 a 999999.99. |
| Foto | `photo`, file image | opcional, imagen max 5 MB. |
| Notas | `notes`, textarea | opcional, max 5000. |

Editar permite `DELETE /pets/{pet}/photo`; no tiene confirmación visual actual.

## Historia clínica (admin)

**Crear/editar registro**: `POST /admin/pets/{pet}/medical-records` o `PATCH .../{clinicalRecord}`; `Store/UpdateClinicalRecordRequest`; Policy ClinicalRecord admin. `ClinicalRecordFormFields`.

| Campo | Control / requerido | Validación/opciones/ayuda |
|---|---|---|
| Tipo | select, requerido | consultation, evaluation, evolution, session, other; etiquetas localizadas. |
| Fecha clínica | datetime-local, requerido | fecha <= ahora; valor del registro al editar. |
| Título | text, requerido | max 255. |
| Contenido clínico | textarea, requerido | texto libre. |
| Visibilidad histórica | select, requerido | booleano marcado visible/no visible; ayuda aclara que no limita lectura del responsable. |

## Catálogo admin

| Formulario | Endpoint / request | Campos |
|---|---|---|
| Servicio | POST/PATCH `/admin/services`, `Store/UpdateServiceRequest` | name requerido único max255; description requerida max10000; is_active checkbox, inicia activo. |
| Procedimiento | POST/PATCH anidado, `Store/UpdateProcedureRequest` | name requerido único por servicio; description opcional max10000; duration_minutes number opcional 1..1440; is_active checkbox. |
| Plantilla tratamiento | POST/PATCH anidado, `Store/UpdateTreatmentTemplateRequest` | name requerido único por servicio; description requerida max5000; estimated_sessions requerido 1..1000; `procedure_ids[]` multiselección requerida, activa y del servicio; is_active. |
| Cambio de estado | PATCH status de servicio/procedimiento/plantilla | `is_active` boolean; `CatalogStatusForm` deshabilita durante envío y usa confirmación nativa. |

## Solicitudes, tratamientos y sesiones

| Formulario | Endpoint / permiso | Campos |
|---|---|---|
| Solicitar atención | `POST /pets/{pet}/service-requests`, client owner, `StoreServiceRequestRequest` | `service_id` select activo requerido; `notes` textarea opcional max2000. Aclara que no agenda ni selecciona tratamiento. |
| Resolver solicitud / asignar tratamiento | `POST /admin/service-requests/{request}/resolution`, admin, `ResolveServiceRequestRequest` | treatment_id compatible; planned_sessions 1..1000; default_session_price decimal >=0; currency ARS; starts_on fecha; status pending/in_progress; notes max5000. |
| Asignación directa | `POST /admin/pets/{pet}/treatments`, admin, `StorePetTreatmentRequest` | mismos campos de asignación; tratamiento activo. |
| Ajustar asignación/estado | PATCH asignación/status, admin | sesiones previstas, precio predeterminado, moneda ARS, notas; transición suspend/cancel/resume según request. |
| Actualizar sesión | `PATCH /admin/treatment-sessions/{session}`, admin, `UpdateTreatmentSessionRequest` | scheduled_at opcional; price requerido decimal >=0; currency ARS; status pending/completed/cancelled; notes max5000. |

## Patrones y brechas

Reutilización comprobada: `InputError`, labels, controles UI, `PetFormFields`, `ClientProfileFields`, catálogo y clínica. Repetido sin componente: estructura de secciones, acciones guardar/cancelar, help text, resumen de errores, campos monetarios/estado y diálogo de confirmación. Mantener los formularios de tratamiento/sesión específicos: sus relaciones, snapshots y reglas no son equivalentes a un formulario de catálogo.
