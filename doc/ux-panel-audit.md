Quiero realizar una auditoría funcional y de experiencia de usuario del frontend actual de VetZen antes de comenzar su rediseño.

El objetivo de esta tarea NO es implementar cambios todavía. Necesito que examines exhaustivamente lo que ya existe, reconstruyas los recorridos actuales de los usuarios y generes un informe que permita entender qué pantallas, funciones, acciones, nombres y flujos deberían reorganizarse.

## 1. Contexto y documentación

Antes de analizar el frontend:

1. Lee completamente `AGENTS.md`.
2. Revisa la documentación funcional y técnica disponible, especialmente:

   * `spec.md`
   * `technical.md`
   * `features.md`
   * Documentos de las features implementadas.
   * Especialmente `04-Clients.md`, `05-Pets.md`, `06-MedicalRecords.md`, `07-services.md` y `08-treatments-and-sessions.md`.
3. Examina las rutas, controladores, middleware, permisos, componentes React, layouts, páginas Inertia, formularios, navegación, modelos, factories y seeders relevantes.
4. Si Laravel Boost está disponible, úsalo para consultar rutas, estructura de la aplicación y base de datos.
5. Diferencia expresamente:

   * Lo especificado en la documentación.
   * Lo implementado en el backend.
   * Lo visible y utilizable desde el frontend.
   * Lo implementado pero inaccesible desde la interfaz.
   * Lo documentado pero todavía no implementado.

No asumas que la documentación coincide con el código. Usa el código y el funcionamiento real como evidencia.

## 2. Ejecución y exploración de la aplicación

Si el entorno puede ejecutarse:

1. Levanta o utiliza la aplicación sin modificar su comportamiento.
2. Identifica usuarios de prueba, factories o seeders existentes.
3. Recorre la aplicación como:

   * Visitante no autenticado.
   * Cliente.
   * Administrador o profesional.
4. Si existen herramientas de navegador, inspecciona visualmente las pantallas.
5. Revisa también estados alternativos:

   * Listados vacíos.
   * Listados con datos.
   * Formularios con errores.
   * Estados de carga.
   * Mensajes de éxito y error.
   * Acceso no autorizado.
   * Registros activos e inactivos.
   * Elementos eliminados o inexistentes.
6. Si no puedes ejecutar o recorrer alguna parte, indícalo como limitación. No inventes comportamientos.

No alteres datos importantes ni realices cambios destructivos.

## 3. Flujo completo de autenticación

Documenta el recorrido actual desde que una persona ingresa por primera vez:

1. Página pública o pantalla inicial.
2. Registro.
3. Validaciones del registro.
4. Creación de `user` y `client`.
5. Inicio de sesión.
6. Recuperación de contraseña.
7. Verificación de correo, si corresponde.
8. Autenticación de dos factores, si corresponde.
9. Redirección posterior al login.
10. Dashboard al que llega cada rol.
11. Cierre de sesión.

Explica qué información se solicita, dónde se guarda y qué experiencia recibe el usuario en cada paso.

Genera un diagrama Mermaid del flujo real de autenticación y acceso según el rol.

## 4. Flujo actual del cliente

Reconstruye el recorrido completo disponible para un cliente.

Incluye, como mínimo:

* Dashboard.
* Perfil y configuración de cuenta.
* Datos personales.
* Mascotas.
* Alta, edición, consulta y eliminación de mascotas.
* Historia clínica.
* Servicios disponibles.
* Solicitud o asignación de tratamientos.
* Tratamientos de sus mascotas.
* Procedimientos.
* Sesiones.
* Turnos, si están implementados.
* Navegación para regresar a pantallas anteriores.
* Acciones a las que puede acceder.
* Funcionalidades existentes en backend que el cliente no puede alcanzar desde la interfaz.

Para cada recorrido, explica:

* Desde dónde comienza.
* Qué botones o enlaces utiliza.
* Qué páginas atraviesa.
* Qué información ve.
* Qué decisiones debe tomar.
* Cómo termina el proceso.
* Qué confirmación recibe.
* Dónde se interrumpe o resulta confuso.

Genera uno o más diagramas Mermaid con los flujos reales del cliente.

## 5. Flujo actual del administrador o profesional

Reconstruye el recorrido completo disponible para el administrador.

Incluye, como mínimo:

* Dashboard administrativo.
* Clientes.
* Mascotas.
* Historias clínicas.
* Servicios.
* Procedimientos.
* Tratamientos configurables o reutilizables.
* Tratamientos asignados a mascotas.
* Procedimientos asignados a tratamientos.
* Sesiones.
* Estados y cambios de estado.
* Usuarios, profesionales, permisos y configuración, si existen.
* Acciones disponibles únicamente mediante rutas internas.
* Funcionalidades implementadas pero no incluidas en el menú.

Presta especial atención al flujo actual de tratamientos:

1. Cómo se crea un tratamiento reutilizable.
2. Cómo se relaciona con servicios y procedimientos.
3. Cómo se asigna a una mascota.
4. Cómo se programan o registran sesiones.
5. Desde qué pantalla se accede a cada paso.
6. Qué ocurre cuando todavía no existen tratamientos configurados.
7. Qué diferencias existen entre:

   * Servicio.
   * Procedimiento.
   * Tratamiento del catálogo o plantilla.
   * Tratamiento asignado a una mascota.
   * Sesión.
8. Si los nombres usados en código, documentación e interfaz representan conceptos diferentes o ambiguos.

Genera uno o más diagramas Mermaid con los flujos reales del administrador.

## 6. Mapa actual del panel

Construye un mapa completo de navegación separado por rol.

Debe mostrar:

* Elementos del menú principal.
* Submenús.
* Páginas accesibles desde cada elemento.
* Pantallas que solamente se alcanzan desde otra pantalla.
* Rutas sin enlace visible.
* Acciones principales y secundarias.
* Breadcrumbs o mecanismos para volver.
* Páginas compartidas entre roles.
* Posibles callejones sin salida.

Presenta:

1. Un mapa conceptual general.
2. Un árbol de navegación del cliente.
3. Un árbol de navegación del administrador.
4. Una tabla con estas columnas:

| Rol | Grupo actual | Pantalla | Ruta | Forma de acceso | Acción principal | Acciones secundarias | Problemas detectados |

## 7. Inventario de vistas y componentes

Crea un inventario de todas las vistas relevantes del frontend.

Para cada vista registra:

| Vista | Ruta | Rol | Propósito | Datos mostrados | Acciones | Componentes utilizados | Estados contemplados | Problemas |

También identifica:

* Layouts.
* Menús.
* Breadcrumbs.
* Encabezados de página.
* Tablas.
* Tarjetas.
* Buscadores.
* Filtros.
* Paginación.
* Formularios.
* Selectores.
* Modales.
* Alertas.
* Badges de estado.
* Botones.
* Menús de acciones.
* Componentes repetidos.
* Componentes similares implementados de formas diferentes.

Indica qué componentes podrían unificarse en un sistema de diseño interno.

## 8. Revisión de experiencia de usuario

Evalúa cada módulo considerando:

* Claridad de nombres.
* Jerarquía visual.
* Organización de acciones.
* Consistencia.
* Facilidad para encontrar funciones.
* Cantidad de pasos.
* Retroalimentación después de una acción.
* Prevención de errores.
* Estados vacíos.
* Mensajes de error.
* Formularios demasiado largos.
* Acciones duplicadas.
* Botones innecesarios.
* Uso confuso de íconos.
* Acciones importantes ocultas.
* Enlaces colocados en datos secundarios.
* Navegación hacia atrás.
* Breadcrumbs.
* Confirmación de acciones sensibles.
* Accesibilidad básica.
* Comportamiento responsive.
* Coherencia entre frontend y reglas del backend.

No te limites a aspectos visuales. Analiza el flujo de trabajo completo.

## 9. Revisión de nombres y conceptos

Identifica términos ambiguos, duplicados o demasiado técnicos.

Presta especial atención a:

* Servicios.
* Procedimientos.
* Tratamientos.
* Plantillas de tratamiento.
* Tratamientos de mascotas.
* Sesiones.
* Clientes.
* Pacientes.
* Mascotas.
* Historia clínica.
* Estado.
* Asignar, crear, programar, registrar y completar.

Entrega una tabla:

| Nombre actual | Lugar donde aparece | Qué representa realmente | Problema | Nombre sugerido |

Las sugerencias deben estar pensadas para el lenguaje cotidiano de una clínica veterinaria y no solamente para coincidir con los nombres técnicos de las tablas.

## 10. Evidencia visual

Si puedes utilizar un navegador:

1. Captura las pantallas principales en escritorio.
2. Captura los estados más importantes o problemáticos.
3. Incluye las capturas en una carpeta de auditoría claramente identificada.
4. Vincula cada captura desde el informe.
5. No incluyas información personal o sensible real.

Si no puedes generar capturas, describe con precisión la estructura visual observada en cada página.

## 11. Propuesta inicial de reorganización

Después de documentar el estado actual, presenta una propuesta inicial de arquitectura de información.

Evalúa como hipótesis esta agrupación:

* Inicio

  * Dashboard

* Pacientes

  * Clientes
  * Mascotas
  * Historias clínicas

* Atención

  * Tratamientos de mascotas
  * Sesiones
  * Agenda

* Catálogo clínico

  * Servicios
  * Procedimientos
  * Plantillas de tratamiento

* Administración

  * Profesionales
  * Usuarios
  * Configuración

No adoptes esta estructura automáticamente. Compárala con las funciones realmente implementadas y explica:

* Qué mantendrías.
* Qué modificarías.
* Qué elementos agruparías.
* Qué elementos no deberían estar en el menú principal.
* Qué pantallas deberían ser contextuales.
* Qué funciones deberían aparecer como accesos rápidos.
* Qué diferencias debería tener la navegación del cliente.
* Qué funcionalidades futuras deben contemplarse sin mostrarlas como existentes.

Genera:

1. Árbol propuesto del panel administrativo.
2. Árbol propuesto del panel del cliente.
3. Diagrama del flujo clínico propuesto.
4. Comparación entre arquitectura actual y propuesta.

## 12. Priorización de problemas

Clasifica los hallazgos en:

* Críticos: bloquean un flujo.
* Altos: dificultan una tarea importante.
* Medios: generan confusión o pasos innecesarios.
* Bajos: problemas visuales o de consistencia.

Para cada hallazgo incluye:

| Prioridad | Módulo | Problema | Evidencia | Consecuencia | Recomendación |

Separa las recomendaciones en:

* Cambios rápidos.
* Cambios estructurales.
* Mejoras visuales.
* Mejoras de accesibilidad.
* Funcionalidades faltantes.
* Decisiones de negocio pendientes.

## 13. Entregable

Crea un informe Markdown dentro de la documentación del proyecto. Si no existe una ubicación definida, utiliza:

`docs/ux/current-panel-audit.md`

El informe debe ser autocontenido y permitir que otra persona entienda el frontend sin tener que explorar primero todo el código.

Debe contener:

1. Resumen ejecutivo.
2. Alcance y limitaciones.
3. Estado real frente a la documentación.
4. Flujo de autenticación.
5. Flujo del cliente.
6. Flujo del administrador.
7. Mapas de navegación actuales.
8. Inventario de vistas.
9. Inventario de componentes.
10. Análisis de tratamientos y sesiones.
11. Problemas de nombres y conceptos.
12. Hallazgos UX/UI.
13. Arquitectura propuesta.
14. Diagramas Mermaid.
15. Problemas priorizados.
16. Recomendaciones.
17. Preguntas y decisiones pendientes.
18. Próximos pasos sugeridos.

## Restricciones

* No implementes todavía el rediseño.
* No cambies rutas, componentes, estilos, modelos, migraciones ni lógica.
* No corrijas problemas durante la auditoría.
* No realices refactors.
* No modifiques la documentación funcional existente.
* Solamente crea el informe y, si es posible, las evidencias visuales.
* No presentes como existente algo que solamente está documentado.
* No propongas funcionalidades sin distinguirlas claramente de las ya implementadas.
* Fundamenta cada conclusión con rutas, archivos, componentes o comportamientos observados.
* Si una funcionalidad no puede comprobarse, indícala como “no verificada”.

## Cierre de la tarea

Al finalizar, responde con:

1. Ruta del informe creado.
2. Resumen de los cinco problemas más importantes.
3. Flujos que pudiste verificar.
4. Flujos que no pudiste verificar y por qué.
5. Preguntas que necesitan una decisión del responsable del producto.
6. Confirmación de que no se modificó la aplicación.
7. Lista exacta de archivos creados o modificados.

No realices commit.
