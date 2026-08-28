# VetZen — AI Agent Instructions

## 1. Project Context

VetZen is a veterinary management platform built with Laravel, React, Inertia, and a relational database.

Development follows a documentation-first, feature-driven workflow.

Project documentation is the source of truth for product requirements, business rules, architecture, feature scope, and explicitly resolved decisions.

Before implementing functionality, understand the relevant documentation and inspect the current implementation.

Laravel Boost supplements this documentation by providing application-aware inspection and framework documentation. It does not replace VetZen's product specifications.

---

## 2. Source of Truth

The main project documents are:

* `spec.md` — product requirements and business behavior.
* `technical.md` — architecture, technical decisions, constraints, and pending technical decisions.
* `features.md` — global feature map and high-level feature requirements.
* `features/*.md` — detailed specification and implementation contract for each feature.

These documents have different responsibilities and must not be treated as interchangeable.

When implementing a feature, the corresponding file in `features/` is the primary implementation contract, but it must remain compatible with `spec.md`, `technical.md`, and the relevant previous features.

If documentation and implementation disagree, do not silently choose one.

Identify the inconsistency and report it before making a decision that changes product behavior, security, data ownership, or architecture.

---

## 3. Mandatory Feature Workflow

Before implementing or modifying a feature:

1. Read `spec.md`.
2. Read `technical.md`.
3. Read `features.md`.
4. Read the specification for the active feature.
5. Read directly related previous feature specifications when the active feature depends on their domain model, ownership, authorization, or behavior.
6. Inspect the existing implementation.
7. Inspect the relevant database schema, models, relationships, routes, Policies, Form Requests, controllers/actions, React/Inertia pages, components, factories, and tests.
8. Use Laravel Boost tools when they provide structured information about the application.
9. Use Boost documentation search before relying on version-specific Laravel ecosystem behavior or APIs.
10. Identify unresolved decisions that affect implementation.
11. Only then create an implementation plan or modify code.

Do not begin implementation based only on the user's prompt when a feature specification exists.

Do not assume that a specification describes the current implementation perfectly. Verify both.

---

## 4. Feature Scope

Implement only the active feature specification.

Do not implement future features preemptively.

Future relationships may be considered when making architectural decisions, but they do not justify creating speculative:

* tables;
* models;
* controllers;
* services;
* permissions;
* jobs;
* events;
* frontend sections;
* integrations;
* abstractions.

For example:

* Pets may later have treatments, appointments, follow-up plans, and clinical records.
* Clinical information may later be consumed by an AI assistant.
* These future relationships do not justify implementing those features early.

Prefer the simplest design that correctly satisfies the current specification while remaining reasonably extensible.

Avoid overengineering.

---

## 5. Pending Decisions

Feature and technical specifications may contain sections marked as:

```text
DECISIÓN PENDIENTE
DECISIÓN TÉCNICA PENDIENTE
```

These decisions are intentionally unresolved.

Never silently resolve a pending decision.

Before implementing code affected by one:

1. identify the pending decision;
2. explain why implementation depends on it;
3. present reasonable alternatives when useful;
4. stop implementation of the affected part until the decision is resolved.

Unrelated parts of the feature may continue when they do not depend on that decision.

Once the user resolves a decision, update or request an update to the appropriate project documentation before treating it as a durable project rule.

Do not convert a recommendation into a requirement without explicit approval.

---

## 6. Existing Architecture First

Always inspect existing code before introducing a new pattern.

Follow established project conventions for:

* controllers;
* Form Requests;
* Policies;
* models;
* relationships;
* migrations;
* routes;
* Inertia pages;
* React components;
* layouts;
* factories;
* seeders;
* tests.

Reuse existing components and patterns where appropriate.

Do not introduce repositories, service layers, DTOs, actions, interfaces, events, or other abstractions merely because they are theoretically desirable.

Introduce an abstraction only when the current feature provides a concrete reason for it.

Prefer Laravel conventions and the existing VetZen architecture over unnecessary custom infrastructure.

---

## 7. Ownership Model

VetZen uses resource ownership as a fundamental security boundary.

The current ownership chain begins with:

```text
User
  ↓
Client
  ↓
Pet
  ↓
Client-owned resources
```

Future resources may extend this chain.

For example:

```text
User
  ↓
Client
  ↓
Pet
  ↓
Clinical Resource
```

Ownership must always be verified on the backend.

Never trust ownership identifiers received from the frontend as proof of ownership.

Fields such as:

```text
user_id
client_id
pet_id
created_by
updated_by
role
permissions
```

must not be mass assigned when doing so could change ownership, authorship, or authorization.

Whenever possible, derive ownership from:

```text
authenticated user
        ↓
authorized relationship
        ↓
resource
```

Do not accept a frontend-provided foreign key when the backend can derive it safely from the authenticated context.

---

## 8. Authorization

Frontend visibility is not security.

Authorization must always be enforced by Laravel on the backend.

Use Policies for resource-level authorization and ownership checks.

Use Spatie Laravel Permission for roles and broader application capabilities when appropriate.

Keep authorization simple unless the active feature specification explicitly requires granular permissions.

The current baseline roles are:

```text
admin
client
```

Do not create additional roles or large permission matrices without an explicit requirement.

A client must never gain access to another client's resources by manipulating:

* URLs;
* route parameters;
* request payloads;
* foreign keys;
* Inertia requests;
* direct HTTP requests.

Horizontal authorization must be explicitly tested.

When a resource belongs to a Pet, client authorization must ultimately verify the complete ownership relationship rather than trusting only the resource ID.

---

## 9. Clinical Data

Clinical information is sensitive application data.

Clinical resources must always be accessed through authenticated and authorized relationships.

For client access, ownership must ultimately resolve through:

```text
User
  ↓
Client
  ↓
Pet
  ↓
Clinical Resource
```

Do not expose clinical information simply because a resource ID exists.

Client access to clinical information must remain read-only unless a future specification explicitly changes that rule.

Do not implement AI access to clinical information unless explicitly required by the active feature.

Future AI functionality must respect the same authentication, authorization, ownership, and data-selection boundaries as the rest of VetZen.

Clinical data must never become automatically available to an AI model merely because it exists in the database.

---

## 10. Validation and Mass Assignment

Use Laravel backend validation for all persisted user input.

Prefer Form Requests when consistent with the existing application.

Validation and authorization are separate concerns.

A valid request is not necessarily an authorized request.

Frontend validation may improve user experience but never replaces backend validation.

Protect ownership, authorship, role, and permission fields from unsafe mass assignment.

Do not allow a request to reassign a resource by manipulating fields such as:

```text
user_id
client_id
pet_id
created_by
updated_by
```

when those values should be determined by the backend.

---

## 11. Database Changes

Before creating or modifying a migration:

1. inspect the current database schema using Laravel Boost when available;
2. inspect related migrations;
3. inspect related models and relationships;
4. verify the active feature specification;
5. check for unresolved decisions affecting the schema.

Do not modify historical migrations that may already have been executed unless explicitly instructed.

Create new migrations for schema evolution.

Do not introduce Soft Deletes unless the relevant project or feature documentation explicitly decides to use them for that resource.

Do not add speculative columns for future features.

Maintain relational integrity through appropriate foreign keys and constraints when consistent with the approved domain model.

---

## 12. Laravel Boost Usage

Laravel Boost is the preferred application-aware inspection layer when its tools apply.

Use Boost when appropriate to inspect:

* database schema;
* application configuration;
* routes;
* Laravel ecosystem documentation;
* logs and errors;
* application state exposed through available Boost tools.

Prefer Boost's structured tools over improvised inspection when both provide the same information.

Before relying on Laravel, Inertia, authentication, authorization, testing, filesystem, queue, or other ecosystem APIs whose behavior may depend on installed versions, use Boost documentation search.

Laravel Boost supplements the project documentation.

It does not define VetZen business requirements.

Business behavior must come from VetZen specifications and explicitly resolved decisions, not from framework conventions or agent assumptions.

---

## 13. Frontend

The frontend uses React with Inertia.

Follow the existing UI architecture, components, layouts, and naming conventions.

Before creating a new component:

1. inspect existing components;
2. reuse an existing component when appropriate;
3. follow established project patterns.

Client and admin interfaces may expose different actions and navigation.

Frontend restrictions exist for user experience only.

Backend authorization remains mandatory.

Do not duplicate ownership or authorization rules in React as the only enforcement mechanism.

Do not introduce a new frontend state-management or UI architecture without a concrete requirement.

---

## 14. Testing

Every feature implementation must add or update relevant tests.

Prefer Feature tests for application behavior.

At minimum, test:

* expected successful behavior;
* relevant validation failures;
* authorization;
* ownership;
* important failure paths;
* regression of directly related existing behavior.

For client-owned resources, explicitly test horizontal authorization using at least two different clients and their resources.

Example:

```text
Client A → Resource A
Client B → Resource B

Client A can access Resource A.
Client A cannot access Resource B.
```

When an admin has broader access, test that behavior separately.

For nested ownership, test the complete relationship.

Example:

```text
Client A → Pet A → Clinical Record A
Client B → Pet B → Clinical Record B
```

A request from Client A must never access Clinical Record B.

Test authorization through real HTTP application routes whenever practical rather than testing only Policy methods in isolation.

Run the narrowest relevant test suite during implementation.

Before declaring a feature complete, run all tests affected by the feature and verify that previous related functionality has not regressed.

Never claim that tests pass unless they were actually executed successfully.

---

## 15. Documentation and Durable Rules

Do not create documentation files unless explicitly requested.

Do not duplicate the same business rule across multiple documents unnecessarily.

Product behavior belongs in:

```text
spec.md
features.md
features/*.md
```

Technical and architectural decisions belong in:

```text
technical.md
```

Agent workflow belongs in:

```text
AGENTS.md
```

Durable implementation-specific agent rules may belong in:

```text
.ai/rules/
```

when supported by Laravel Boost.

Use Laravel Boost `record-rule` for durable agent-facing rules discovered during implementation when appropriate.

Do not use agent memory as the only place where a project decision is stored.

Important project decisions must remain visible and version-controlled in the repository.

---

## 16. AI Development Principles

AI agents assist implementation; they do not define product requirements.

Never invent business rules to unblock implementation.

Never treat generated code as correct without verification.

For every meaningful implementation:

```text
Requirement
    ↓
Specification
    ↓
Implementation
    ↓
Authorization
    ↓
Tests
    ↓
Verification
```

Use AI to accelerate:

* repository inspection;
* implementation;
* refactoring;
* test generation;
* documentation lookup;
* debugging.

Do not use AI to bypass unresolved product or architectural decisions.

When uncertainty affects security, ownership, clinical information, database design, or public behavior, surface the uncertainty instead of guessing.

---

## 17. Definition of Done

A feature is not complete merely because the UI works.

Before considering a feature complete, verify:

```text
Specification
      ↓
Database
      ↓
Backend
      ↓
Authorization
      ↓
Frontend
      ↓
Tests
      ↓
Verification
```

Confirm that:

* implementation matches the active feature specification;
* implementation remains compatible with `spec.md` and `technical.md`;
* authorization is enforced in the backend;
* ownership rules are protected;
* backend validation exists;
* mass assignment is safe;
* relevant tests pass;
* formatting and required quality tools pass;
* no unrelated future functionality was introduced;
* no unresolved decision was silently implemented.

If any required item cannot be verified, report it.

---

## 18. Agent Completion Report

Be concise when reporting implementation work.

When completing a feature or significant implementation task, summarize:

```text
IMPLEMENTED
FILES CHANGED
DATABASE
AUTHORIZATION
VALIDATION
TESTS
PENDING DECISIONS
SCOPE VERIFICATION
```

Only include sections that are relevant to the task.

Clearly distinguish between:

```text
implemented
verified
not tested
pending
```

Never claim that a command, migration, build, or test succeeded unless it was actually executed successfully.

Never claim that a feature is complete when required tests fail or an implementation-blocking decision remains unresolved.

---

## 19. Core Principle

When choosing between:

```text
guessing
```

and:

```text
checking the specification,
inspecting the application,
using Laravel Boost,
or asking for a decision
```

always choose verification.

Build only what VetZen currently requires, protect ownership and clinical data at the backend, follow the existing architecture, and leave the codebase ready for the next feature without implementing that feature early.

<!-- Keep the Laravel Boost generated guidelines below this line. -->

<!-- Do not manually remove framework/package rules generated by Boost. -->

<laravel-boost-guidelines>

[KEEP THE EXISTING LARAVEL BOOST GENERATED CONTENT HERE UNCHANGED]

</laravel-boost-guidelines>
