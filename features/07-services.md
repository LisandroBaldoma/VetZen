# Feature 07 — Servicios y procedimientos

## 1. Objetivo

Implementar el catálogo terapéutico base de VetZen:

```text
Service
  └── hasMany Procedure
```

Service representa un área terapéutica general y Procedure una técnica de ese
servicio. Tratamientos, asignaciones a mascotas y sesiones pertenecen a F08.

## 2. Alcance

Admin puede listar, consultar, crear, editar, activar y desactivar servicios y
procedimientos. Procedure se administra dentro de su Service. No existe
eliminación, Soft Deletes ni reasignación entre servicios.

Client puede consultar servicios activos y sus procedimientos activos en modo
lectura. Los estados vacíos deben entregarse como arrays y renderizarse sin
errores.

## 3. Autorización

| Acción | `client` | `admin` |
| --- | --- | --- |
| Consultar Service y Procedure activos | Permitido | Permitido |
| Consultar recursos inactivos | Denegado | Permitido |
| Crear o modificar Service | Denegado | Permitido |
| Crear o modificar Procedure | Denegado | Permitido |

La autorización se aplica mediante autenticación, Policies y Form Requests. El
frontend no constituye una barrera de seguridad.

## 4. Service

| Campo | Regla |
| --- | --- |
| `name` | requerido, string máximo 255, único globalmente |
| `description` | requerida, string, texto plano |
| `is_active` | booleano, default `true` |

Service no tiene precio, moneda, duración ni modalidades.

## 5. Procedure

| Campo | Regla |
| --- | --- |
| `service_id` | requerido y derivado del Service de la ruta |
| `name` | requerido, string máximo 255, único dentro del servicio |
| `description` | nullable, string, texto plano |
| `duration_minutes` | nullable, integer entre 1 y 1440 |
| `is_active` | booleano, default `true` |

Procedure no tiene precio, moneda, sesiones ni información clínica.

## 6. Reglas de negocio

1. Todo Procedure pertenece a un único Service.
2. `Procedure(service_id, name)` es único; el mismo nombre puede existir en
   servicios diferentes.
3. `service_id` no se acepta como fuente de asociación ni se modifica durante
   la edición.
4. Las rutas anidadas impiden resolver un Procedure desde otro Service.
5. Desactivar conserva identidad y referencias históricas.
6. Solo recursos activos se muestran en el catálogo cliente.
7. Los procedimientos se ordenan establemente por nombre e ID.
8. Un Service sin procedimientos entrega `procedures: []`.

## 7. Rutas y frontend

La administración de Procedure usa rutas anidadas bajo
`/admin/services/{service}/procedures`, bindings acotados y páginas
React/Inertia para listado, alta, detalle y edición. Desde Service existe acceso
directo a sus procedimientos y navegación de regreso.

El formulario muestra nombre, descripción opcional, duración orientativa y
estado; presenta errores, bloquea envíos duplicados y permite cancelar.

El catálogo cliente muestra nombre, descripción y duración de procedimientos
activos, sin datos administrativos.

## 8. Persistencia

Se reutilizan las tablas y columnas vigentes. La foreign key de Procedure hacia
Service es restrictiva y existe un índice único compuesto por `service_id` y
`name`. No se crea una migración nueva.

## 9. Seeders y factories

Procedure cuenta con Factory y estado inactivo. ProcedureSeeder se ejecuta
después de ServiceSeeder, localiza servicios por nombre estable y es idempotente.
No crea tratamientos ni sesiones.

## 10. Testing requerido

Las pruebas HTTP cubren flujos admin, denegación a client, redirección de guest,
validación, duración nullable y positiva, unicidad por Service, bindings
anidados, inmutabilidad de `service_id`, arrays vacíos, lectura comercial activa,
ausencia de delete e idempotencia del seeder.

## 11. Criterios de aceptación

* [x] Service conserva su gestión administrativa y catálogo cliente.
* [x] Procedure pertenece a Service y no tiene precio.
* [x] Admin administra procedimientos desde React/Inertia.
* [x] Client no accede a rutas administrativas.
* [x] Cliente consulta únicamente procedimientos activos de servicios activos.
* [x] Backend protege asociación, autorización, validación y bindings anidados.
* [x] Estados vacíos se entregan como arrays.
* [x] No existe eliminación física.
* [x] Factory y Seeder idempotente están definidos.

## 12. Fuera de alcance

F07 no implementa Treatment, asociaciones Treatment–Procedure, PetTreatment,
snapshots, TreatmentSession, precios, progreso, reemplazos, pagos ni
facturación. Ese dominio pertenece a F08.

## 13. Decisiones

Se respetan los nombres del esquema vigente: `duration_minutes` e `is_active`.
No quedan decisiones pendientes dentro del alcance de F07.
