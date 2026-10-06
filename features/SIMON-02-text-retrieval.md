# SIMON-02 - Indexación y recuperación textual

> Estado: implementada y verificada automáticamente. Esta feature no integra
> proveedores LLM, embeddings, vector store, chat, rutas HTTP, UI, datos dinámicos ni
> acciones del asistente.

## Objetivo

Convertir las guías curadas de `docs/help/` en documentos y chunks persistidos,

## Alcance

* comando `simon:index-knowledge-base` para indexar guías con frontmatter;
* validación de metadata obligatoria y roles `admin`/`client`;
* tablas para documentos y chunks;
* búsqueda por términos normalizados y filtrada por rol antes de puntuar;
* priorización opcional por módulo;
* pruebas de indexación, actualización, limpieza y aislamiento por rol.

## Reglas

1. Solo se indexan documentos Markdown con frontmatter válido.
2. `docs/help/README.md` no se indexa porque no representa una guía de usuario.
3. La base se sincroniza: los archivos eliminados se eliminan del índice.
4. La búsqueda devuelve únicamente documentos `available` cuyo rol incluye el
   rol solicitado.
5. La búsqueda usa términos normalizados y no depende de capacidades específicas
   de SQLite, MySQL o MariaDB.
6. SIMON-02 no recibe usuarios, pet IDs, datos clínicos ni permisos desde HTTP.

## Evolución

SIMON-03 consume el resultado de esta búsqueda para generar respuestas grounded
mediante un proveedor LLM. SIMON-04 evalúa y mide la puntuación textual antes de
que una evolución posterior pueda reemplazarla o complementarla con embeddings.

## Criterios de aceptación

* [x] El comando `simon:index-knowledge-base` indexa las guías de `docs/help/`.
* [x] La indexación valida frontmatter, crea chunks y elimina documentos que ya
  no existen en la Knowledge Base.
* [x] La búsqueda solo recupera documentos disponibles para el rol solicitado.
* [x] El módulo actual puede priorizar resultados que tengan la misma relevancia
  textual.
* [x] Las pruebas cubren indexación, sincronización, validación, aislamiento por
  rol y recuperación de las guías de proyecto.
