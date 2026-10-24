# Baseline de evaluación de Simón

## Ejecución manual

Configurá desarrollo con Groq y el baseline:

```env
SIMON_LLM_PROVIDER=groq
SIMON_LLM_MODEL=openai/gpt-oss-20b
SIMON_TRACE_LOGGING=true
```

Después de actualizar guías, ejecutá:

```bash
php artisan simon:index-knowledge-base
```

Para cada caso en `tests/Fixtures/Simon/evaluation.php`, iniciá sesión con el
rol indicado, realizá la pregunta exacta, registrá resultado y fuentes, y buscá
el `simon_trace_id` en `storage/logs/laravel.log`. Registrá los conteos de
retrieval, duración y usage del evento `simon.provider.completed`.

No registrar preguntas, respuestas, prompts, chunks ni secretos en informes
compartidos.

## Baseline inicial

| Caso | Resultado inicial | Clasificación |
| --- | --- | --- |
| C1 | Correcto | comportamiento esperado |
| C2 | Falta de información pese a evidencia recuperada | ranking/guía a corregir |
| C3 | Correcto | comportamiento esperado |
| C4 | Correcto | comportamiento esperado |
| C5 | Correcto: cliente no puede asignar y puede consultar | `NOT_ALLOWED_FOR_ROLE`; aislamiento funcional obligatorio |
| A1 | Parcial: usó `en curso` como estado de solicitud | alucinación terminológica |
| A2 | Correcto | comportamiento esperado |
| A3 | Correcto | comportamiento esperado |
| A4 | Falta de información pese a evidencia recuperada | ranking/guía a corregir |
| A5 | Seguro pero no distingue función no disponible | KB `unavailable` |

El objetivo deseable es que al menos nueve de los diez casos presenten el
comportamiento esperado. Un rechazo grounded de una funcionalidad inexistente
cuenta como correcto.
