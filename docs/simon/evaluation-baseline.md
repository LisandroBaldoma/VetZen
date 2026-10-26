# SIMON-04 Evaluation Baseline

## Runbook

1. Configure `SIMON_LLM_PROVIDER=groq` and `SIMON_LLM_MODEL=openai/gpt-oss-20b`.
2. Run `php artisan simon:index-knowledge-base`.
3. Run `php artisan test tests/Feature/Simon`.
4. Review `tests/Fixtures/Simon/evaluation.php` manually with the configured
   provider only in an approved development environment.

## Baseline

- Dataset: 10 versioned cases, C1-C5 and A1-A5.
- Retrieval: procedural sections are expected first for C2 and A4.
- Grounding: C5 must return `not_allowed_for_role`; no administrative
  instructions are sent to the client.
- Dynamic data: A5 explains the Turnos flow but does not claim availability or
  inspect actual appointments.
- Observability: traces contain aggregate retrieval metrics, latency and
  provider usage when supplied. They never contain questions, answers, prompts,
  chunks, PII or document identifiers.

## Results

Record the execution date, environment, model and per-case observations here
when a manual provider evaluation is performed. Automated tests do not call
Groq.
