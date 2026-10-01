# Fichas Stitch: Mascotas e historia clínica

## Mis mascotas / pacientes admin

- **Objetivo:** encontrar, crear y abrir un paciente.
- **Usuario:** client ve propias; admin ve todas y responsable.
- **Información:** foto/avatar, nombre, especie, raza, sexo; admin añade responsable y acciones.
- **Acciones:** crear, abrir, editar; admin usa menú de fila.
- **Componentes:** PageHeader, responsive grid/list, PetIdentity, RowActions, EmptyState.
- **Estados:** sin mascotas; foto ausente; móvil cards vs escritorio tabla (admin).
- **Navegación:** detalle, crear, editar; client desde dashboard.

## Crear/editar mascota

- **Objetivo:** registrar o mantener datos del paciente; admin asigna responsable.
- **Usuario:** owner client o admin.
- **Formulario:** cliente admin; nombre, especie, raza, sexo, nacimiento, peso, color, foto, notas.
- **Estados:** valores iniciales, errores por campo, procesando; edición puede quitar foto.
- **Componentes:** PageHeader/EntityHeader, FormSection, PetFormFields, FormActions, confirmación de foto futura.
- **Dependencias:** Client para select admin; Policy Pet.

## Detalle/contexto de mascota

- **Objetivo:** ser hub seguro del paciente.
- **Usuario:** client dueño o admin.
- **Información:** identidad, foto, especie/raza/sexo, nacimiento, peso/color/notas según prop.
- **Acciones:** editar; abrir historia, solicitudes y tratamientos según rol.
- **Componentes:** PetContextHeader, PetSummary, navegación secundaria contextual.
- **Estados:** foto faltante, módulos sin datos.

## Historia clínica: listado, detalle y edición admin

- **Objetivo:** revisar evolución y, para admin, registrar/editar información clínica.
- **Usuario:** client lectura; admin lectura/escritura.
- **Información:** tipo, título, contenido, fecha clínica, creator/updater/auditoría, visibilidad histórica.
- **Acciones:** abrir registro; admin crear, editar y prellenar evolución.
- **Componentes:** PetContextHeader, Timeline, ClinicalRecordSummary/Detail, clinical form, EmptyState.
- **Estados:** sin registros, validación, tipo de registro; no hay borrado.
- **Dependencias:** Pet y ClinicalRecord; client puede leer todos los registros de su mascota aunque visibilidad esté marcada no visible.
