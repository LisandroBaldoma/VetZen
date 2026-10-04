<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"
        name="viewport">
    <meta content="mobile_tab" name="shell-type">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet">
    <style>
        @layer base {

            html,
            body {
                width: 100vw;
                margin: 0;
                padding: 0;
            }

            body {
                overscroll-behavior: none;
            }

            .pb-safe {
                padding-bottom: env(safe-area-inset-bottom, 0px);
            }

            .pt-safe {
                padding-top: env(safe-area-inset-top, 0px);
            }

            main>:first-child {
                margin-top: 0 !important;
            }

            main>:last-child {
                margin-bottom: 0 !important;
            }
        }

        ::-webkit-scrollbar {
            display: none;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-surface-variant": "#40493e",
                        "on-background": "#121e14",
                        "on-primary-container": "#f7fff1",
                        "primary-fixed-dim": "#8dd889",
                        "on-tertiary-fixed": "#2d1600",
                        "on-secondary": "#ffffff",
                        "on-primary": "#ffffff",
                        "surface-tint": "#236c2b",
                        "surface-container-high": "#ddecdc",
                        "on-primary-fixed-variant": "#005315",
                        "outline-variant": "#c0c9bb",
                        "surface-dim": "#cfdece",
                        "on-secondary-container": "#476d12",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-container": "#fffbff",
                        "on-secondary-fixed": "#102000",
                        "surface-variant": "#d7e7d6",
                        "tertiary-container": "#ab630a",
                        "outline": "#707a6d",
                        "secondary": "#43690d",
                        "tertiary-fixed": "#ffdcc0",
                        "secondary-fixed-dim": "#a7d46f",
                        "tertiary-fixed-dim": "#ffb875",
                        "on-error-container": "#93000a",
                        "surface": "#eefeed",
                        "inverse-on-surface": "#e5f5e4",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-fixed-variant": "#2f4f00",
                        "on-primary-fixed": "#002204",
                        "on-tertiary-fixed-variant": "#6b3b00",
                        "primary-container": "#3b833f",
                        "inverse-primary": "#8dd889",
                        "error": "#ba1a1a",
                        "surface-container-highest": "#d7e7d6",
                        "primary-fixed": "#a8f5a3",
                        "on-error": "#ffffff",
                        "error-container": "#ffdad6",
                        "background": "#eefeed",
                        "on-surface": "#121e14",
                        "tertiary": "#894d00",
                        "primary": "#206928",
                        "secondary-fixed": "#c2f188",
                        "inverse-surface": "#263328",
                        "surface-container": "#e3f2e1",
                        "surface-container-low": "#e8f8e7",
                        "secondary-container": "#bfee85",
                        "surface-bright": "#eefeed"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "gutter-desktop": "1.5rem",
                        "margin-desktop": "2.5rem",
                        "space-xl": "2.25rem",
                        "space-sm": "0.5rem",
                        "margin": "1rem",
                        "space-lg": "1.5rem",
                        "gutter": "1.25rem",
                        "margin-tablet": "1.5rem",
                        "space-xs": "0.25rem",
                        "space-md": "1rem"
                    },
                    "fontFamily": {
                        "label-sm": ["Plus Jakarta Sans"],
                        "label-lg": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "body-sm": ["Plus Jakarta Sans"],
                        "display-lg-mobile": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "label-sm": ["0.6875rem", {
                            "lineHeight": "0.875rem",
                            "letterSpacing": "0.03em",
                            "fontWeight": "600"
                        }],
                        "label-lg": ["0.875rem", {
                            "lineHeight": "1.25rem",
                            "letterSpacing": "0.01em",
                            "fontWeight": "600"
                        }],
                        "headline-lg-mobile": ["1.5rem", {
                            "lineHeight": "2rem",
                            "fontWeight": "600"
                        }],
                        "headline-lg": ["2rem", {
                            "lineHeight": "2.5rem",
                            "letterSpacing": "-0.015em",
                            "fontWeight": "600"
                        }],
                        "body-md": ["0.9375rem", {
                            "lineHeight": "1.5rem",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["1.375rem", {
                            "lineHeight": "1.875rem",
                            "fontWeight": "600"
                        }],
                        "body-sm": ["0.8125rem", {
                            "lineHeight": "1.25rem",
                            "fontWeight": "400"
                        }],
                        "display-lg-mobile": ["2rem", {
                            "lineHeight": "2.5rem",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "700"
                        }],
                        "label-md": ["0.75rem", {
                            "lineHeight": "1rem",
                            "letterSpacing": "0.02em",
                            "fontWeight": "600"
                        }],
                        "headline-sm": ["1.125rem", {
                            "lineHeight": "1.625rem",
                            "fontWeight": "600"
                        }],
                        "display-lg": ["3rem", {
                            "lineHeight": "3.5rem",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "body-lg": ["1.0625rem", {
                            "lineHeight": "1.625rem",
                            "fontWeight": "400"
                        }]
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface text-on-surface font-body-md flex flex-col min-h-screen">
    <header class="fixed top-0 w-full z-50 bg-surface/85 backdrop-blur-xl pt-safe shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="h-16 px-margin flex items-center justify-between gap-space-sm">
            <div class="flex items-center gap-space-sm min-w-0"><img alt="logo.png"
                    class="h-8 w-auto object-contain flex-shrink-0"
                    src="https://lh3.googleusercontent.com/aida/AEtjO1UIIXb3RkrDmnSaWIfJF4d-eqC2mZKdjzjlGxwhaHoGAoYpp6xOQbCKYBv0SucTPN1TRKmutF-e_HUaIyabT4oPnACpW7E6xOqvB0yBn4HRr3u5qqWqKMyr8y03tQkM0xOfH2biVBnJezoh1u1FdCVctc8hOlT0WNmy5qYLql5OeqMvhsSFCZL57LaVrqYW1JrleJpHP4X3wBb-kRCZW8dMZ0Y1x0_HtypP4o58d4Hmqjjr4T0cVKu6RRyF-wNY_xaM5EqRC41utA">
                <div class="flex flex-col min-w-0"><span
                        class="font-label-sm text-label-sm text-primary uppercase tracking-wider truncate">VetZen
                        Clínica</span>
                    <h1 class="font-headline-sm text-headline-sm text-on-surface truncate leading-tight">Pacientes</h1>
                </div>
            </div>
            <div class="flex items-center gap-space-xs flex-shrink-0"><button aria-label="Notificaciones"
                    class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container transition-colors"><span
                        class="material-symbols-outlined text-[22px]">notifications</span></button>
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span
                        class="material-symbols-outlined text-on-primary text-[18px]">person</span></div>
            </div>
        </div>
    </header>
    <main class="flex-1 flex flex-col relative w-full pt-16 pb-20 bg-surface">
        <div class="flex flex-col w-full text-on-surface">
            <!-- 1. PACIENTE CONTEXT HEADER -->
            <section class="bg-surface-container-low px-margin pt-space-md pb-space-sm shadow-sm">
                <!-- Subnavegación del contexto clínico -->
                <div class="flex items-center justify-between gap-space-xs mb-space-sm">
                    <a class="inline-flex items-center gap-1.5 py-1 px-2.5 -ml-2 rounded-lg text-primary hover:bg-surface-container transition-colors min-h-[44px]"
                        href="#">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        <span class="font-label-md text-label-md">Pacientes</span>
                    </a>
                    <button aria-label="Más opciones del paciente"
                        class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[20px]">more_horiz</span>
                    </button>
                </div>
                <!-- Ficha rápida de Luna -->
                <div class="flex items-center gap-space-md">
                    <div class="relative flex-shrink-0">
                        <img class="w-14 h-14 rounded-full object-cover shadow-sm bg-surface-container"
                            data-alt="Golden Retriever dog smiling gently with warm golden fur in a modern bright veterinary clinic natural sunlight"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBCm3PkAlNzz7Y0pEbgtc56VsCKU0xgNKFaaa7P8QYmva7X8CuWn10d3Ur-ILMAuN9YOhpqxkwp2dUjfUmgwQ4_trUG7f5CyG9dDLE2e_ErxdJdhbUCBzSc117PHWqHu56So3DYzJiu3t17WT7YufekVmo6jsODl5R8bB62Bm7Qp8I-Trl5YDLtXRHYiAQNR9FX_g08MDvfYuV5hA4xKIZKsJkZc1XjhbvMXd4y6RdLt12jQliVGrzT5w">
                        <span
                            class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-primary rounded-full ring-2 ring-surface-container-low"
                            title="En atención activa"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-space-xs flex-wrap">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface truncate leading-tight">Luna
                            </h2>
                            <span
                                class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-medium">Canino</span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">Golden Retriever ·
                            Hembra</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant/80 truncate mt-0.5">Tutor/a: <span
                                class="text-on-surface font-medium">María González</span></p>
                    </div>
                    <button
                        class="flex-shrink-0 min-h-[44px] px-3 py-2 rounded-lg bg-surface-container-lowest text-on-surface font-label-md text-label-md flex items-center gap-1.5 shadow-sm active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[16px] text-primary">edit</span>
                        <span class="">Editar</span>
                    </button>
                </div>
                <!-- PatientNavigation tabs horizontales táctiles -->
                <div class="flex items-center gap-1.5 mt-space-md overflow-x-auto no-scrollbar pb-1">
                    <button
                        class="px-3.5 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:bg-surface-container whitespace-nowrap min-h-[40px] transition-colors">
                        Resumen
                    </button>
                    <button
                        class="px-3.5 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:bg-surface-container whitespace-nowrap min-h-[40px] transition-colors">
                        Historia clínica
                    </button>
                    <button
                        class="px-3.5 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:bg-surface-container whitespace-nowrap min-h-[40px] transition-colors">
                        Solicitudes
                    </button>
                    <button
                        class="px-4 py-2 rounded-full font-label-md text-label-md bg-primary text-on-primary whitespace-nowrap min-h-[40px] shadow-sm font-semibold flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">healing</span>
                        <span class="">Tratamientos</span>
                    </button>
                </div>
            </section>
            <!-- CONTENIDO PRINCIPAL DEL TRATAMIENTO -->
            <div class="px-margin py-space-md flex flex-col gap-space-lg">
                <!-- 2. NAVEGACIÓN HACIA ATRÁS (Nivel Tratamientos del paciente) -->
                <div>
                    <a class="inline-flex items-center gap-1 text-primary hover:text-surface-tint font-label-lg text-label-lg group min-h-[44px] -my-2 py-2"
                        href="#">
                        <span
                            class="material-symbols-outlined text-[20px] transition-transform group-hover:-translate-x-0.5">arrow_back</span>
                        <span class="">Volver a Tratamientos</span>
                    </a>
                </div>
                <!-- 3. ENCABEZADO DEL TRATAMIENTO (TreatmentHeader) -->
                <div
                    class="flex items-start justify-between gap-space-sm bg-surface-container-lowest p-space-md rounded-xl shadow-sm">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span
                                class="font-label-sm text-label-sm text-secondary uppercase tracking-wider font-semibold">Fisioterapia</span>
                            <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Protocolo #PT-409</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface font-bold leading-snug">
                            Rehabilitación postoperatoria</h3>
                        <!-- TreatmentStatusBadge oficial -->
                        <div
                            class="mt-2.5 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary-container/40 text-on-secondary-container">
                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            <span class="font-label-md text-label-md font-semibold">En curso</span>
                        </div>
                    </div>
                    <button aria-label="Gestionar tratamiento"
                        class="w-11 h-11 flex items-center justify-center rounded-xl bg-surface-container-low text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors flex-shrink-0 active:scale-95"
                        title="Gestionar tratamiento">
                        <span class="material-symbols-outlined text-[22px]">tune</span>
                    </button>
                </div>
                <!-- 4. PROGRESO TERAPÉUTICO DESTACADO (TreatmentProgress) -->
                <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant font-bold">Progreso
                            Terapéutico</span>
                        <span class="font-headline-sm text-headline-sm text-primary font-bold">60%</span>
                    </div>
                    <div class="flex items-baseline justify-between gap-2 mb-3">
                        <h4 class="font-headline-sm text-headline-sm text-on-surface font-semibold">3 de 5 sesiones
                            completadas</h4>
                    </div>
                    <!-- Barra de progreso estética -->
                    <div class="w-full h-3 bg-surface-container rounded-full overflow-hidden p-0.5">
                        <div class="h-full bg-primary rounded-full transition-all duration-700 ease-out"
                            style="width: 60%;"></div>
                    </div>
                    <div class="mt-2.5 flex items-center gap-1.5 text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px] text-primary">timelapse</span>
                        <span class="font-body-sm text-body-sm font-medium">2 sesiones requeridas por completar</span>
                    </div>
                </div>
                <!-- 5. INFORMACIÓN DEL TRATAMIENTO (MetaList) -->
                <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
                    <div class="px-space-md pt-space-md pb-space-xs">
                        <h4 class="font-label-lg text-label-lg text-on-surface font-bold">Información del tratamiento
                        </h4>
                    </div>
                    <div class="divide-y divide-surface-container-low">
                        <div class="flex items-center justify-between px-space-md py-3">
                            <span class="font-body-md text-body-md text-on-surface-variant">Fecha de inicio</span>
                            <span class="font-label-lg text-label-lg text-on-surface font-medium">12 sep 2026</span>
                        </div>
                        <div class="flex items-center justify-between px-space-md py-3">
                            <span class="font-body-md text-body-md text-on-surface-variant">Sesiones requeridas</span>
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">5 sesiones</span>
                        </div>
                        <div class="flex items-center justify-between px-space-md py-3">
                            <span class="font-body-md text-body-md text-on-surface-variant">Valor de referencia por
                                sesión</span>
                            <span class="font-label-lg text-label-lg text-primary font-semibold">$18.000 ARS</span>
                        </div>
                        <div class="flex items-center justify-between px-space-md py-3">
                            <span class="font-body-md text-body-md text-on-surface-variant">Moneda estipulada</span>
                            <span
                                class="font-label-md text-label-md px-2 py-0.5 rounded bg-surface-container text-on-surface font-medium">ARS
                                ($)</span>
                        </div>
                    </div>
                </div>
                <!-- 6. PROCEDIMIENTOS INCLUIDOS (ProcedureList) -->
                <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm">
                    <div class="mb-3">
                        <h4 class="font-label-lg text-label-lg text-on-surface font-bold">Procedimientos incluidos</h4>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Definidos al momento de la
                            asignación del tratamiento</p>
                    </div>
                    <div class="flex flex-wrap gap-2 pt-1">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-low text-on-surface font-body-sm text-body-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            <span class="">Terapia manual</span>
                        </div>
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-low text-on-surface font-body-sm text-body-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            <span class="">Láser terapéutico</span>
                        </div>
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-low text-on-surface font-body-sm text-body-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            <span class="">Ejercicio terapéutico</span>
                        </div>
                    </div>
                </div>
                <!-- 7. NOTAS DEL TRATAMIENTO -->
                <div class="bg-surface-container-low p-space-md rounded-xl flex items-start gap-space-sm">
                    <span
                        class="material-symbols-outlined text-secondary text-[22px] mt-0.5 flex-shrink-0">clinical_notes</span>
                    <div class="min-w-0 flex-1">
                        <span
                            class="font-label-sm text-label-sm uppercase font-bold text-on-surface-variant tracking-wider">Notas
                            del tratamiento</span>
                        <p class="font-body-sm text-body-sm text-on-surface mt-1 leading-relaxed">
                            Protocolo enfocado en fortalecimiento de tren posterior y control inflamatorio tras
                            intervención en rodilla derecha. Respuesta favorable a la flexión pasiva.
                        </p>
                    </div>
                </div>
                <!-- 8. SECCIÓN SESIONES (ORGANIZACIÓN JERÁRQUICA INTELIGENTE) -->
                <div class="flex flex-col gap-space-md">
                    <!-- Encabezado general de sesiones -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-headline-sm text-headline-sm text-on-surface font-bold">Sesiones</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">3 completadas · 2 por
                                completar</p>
                        </div>
                        <button
                            class="min-h-[44px] px-3.5 py-1.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md font-semibold flex items-center gap-1 shadow-sm active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            <span class="">Nueva sesión</span>
                        </button>
                    </div>
                    <!-- A. PRÓXIMA SESIÓN (NextSession - Foco Operativo) -->
                    <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-md relative overflow-hidden">
                        <!-- Indicador lateral distintivo cálido -->
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span
                                class="font-label-sm text-label-sm uppercase tracking-wider font-bold text-primary flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">event</span>
                                Próxima sesión programada
                            </span>
                            <span
                                class="font-label-md text-label-md px-2 py-0.5 rounded bg-surface-container text-on-surface font-bold">
                                Sesión 5
                            </span>
                        </div>
                        <div class="mt-2">
                            <div
                                class="font-headline-sm text-headline-sm text-on-surface font-bold flex items-center gap-2">
                                <span class="">18 oct 2026</span>
                                <span class="text-on-surface-variant font-normal text-body-lg">· 16:30 hs</span>
                            </div>
                            <p class="font-label-md text-label-md text-on-surface-variant mt-1">
                                Valor de sesión: <span class="text-on-surface font-semibold">$18.000 ARS</span>
                            </p>
                        </div>
                        <div
                            class="mt-4 pt-3 flex items-center justify-between gap-space-sm bg-surface-container-low/50 -mx-space-md -mb-space-md px-space-md py-3">
                            <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Especialista: Dra.
                                Valenzuela</span>
                            <button
                                class="min-h-[44px] px-4 py-2 rounded-lg bg-surface-container-lowest text-primary font-label-md text-label-md font-bold shadow-sm hover:bg-surface-container flex items-center gap-1 active:scale-95 transition-all">
                                <span class="">Gestionar sesión</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- B. SESIONES REALIZADAS (SessionHistory - Compacto y claro) -->
                    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-space-md">
                        <div class="flex items-center justify-between mb-3">
                            <span
                                class="font-label-sm text-label-sm uppercase tracking-wider font-bold text-on-surface-variant">
                                Sesiones realizadas (3)
                            </span>
                            <span class="font-label-sm text-label-sm text-secondary font-semibold">100%
                                asistidas</span>
                        </div>
                        <div class="space-y-2.5">
                            <!-- Sesión 4 -->
                            <div
                                class="flex items-center justify-between p-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div
                                        class="w-7 h-7 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-label-md text-label-md text-on-surface font-bold truncate">
                                            Sesión 4</p>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant truncate">11 oct
                                            2026 · $18.000</p>
                                    </div>
                                </div>
                                <button
                                    class="min-h-[44px] px-3 py-1 text-primary font-label-md text-label-md hover:underline flex items-center gap-0.5 flex-shrink-0">
                                    <span class="">Ver detalle</span>
                                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </button>
                            </div>
                            <!-- Sesión 2 -->
                            <div
                                class="flex items-center justify-between p-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div
                                        class="w-7 h-7 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-label-md text-label-md text-on-surface font-bold truncate">
                                            Sesión 2</p>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant truncate">4 oct
                                            2026 · $18.000</p>
                                    </div>
                                </div>
                                <button
                                    class="min-h-[44px] px-3 py-1 text-primary font-label-md text-label-md hover:underline flex items-center gap-0.5 flex-shrink-0">
                                    <span class="">Ver detalle</span>
                                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </button>
                            </div>
                            <!-- Sesión 1 -->
                            <div
                                class="flex items-center justify-between p-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div
                                        class="w-7 h-7 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-label-md text-label-md text-on-surface font-bold truncate">
                                            Sesión 1</p>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant truncate">27 sep
                                            2026 · $18.000</p>
                                    </div>
                                </div>
                                <button
                                    class="min-h-[44px] px-3 py-1 text-primary font-label-md text-label-md hover:underline flex items-center gap-0.5 flex-shrink-0">
                                    <span class="">Ver detalle</span>
                                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- C. PLAN RESTANTE (Sesiones pendientes sin programar) -->
                    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-space-md">
                        <div class="flex items-center justify-between mb-3">
                            <span
                                class="font-label-sm text-label-sm uppercase tracking-wider font-bold text-on-surface-variant">Otras
                                sesiones pendientes (1)</span>
                            <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                        </div>
                        <div class="p-3 rounded-lg bg-surface-container-low flex flex-col gap-2">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-label-lg text-label-lg font-bold text-on-surface">Sesión
                                            6</span>

                                    </div>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                                        Pendiente · Sin programar · $18.000 ARS
                                    </p>
                                </div>
                            </div>
                            <div class="flex justify-end pt-1">
                                <button
                                    class="min-h-[44px] px-4 py-2 rounded-lg bg-surface-container-highest text-primary font-label-md text-label-md font-bold hover:bg-primary hover:text-on-primary active:scale-95 transition-all flex items-center gap-1.5"><span
                                        class="">Gestionar sesión</span><span
                                        class="material-symbols-outlined text-[16px]">arrow_forward</span></button>
                            </div>
                        </div>
                    </div>
                    <!-- D. SESIONES CANCELADAS (Historial de incidencias) -->
                    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-space-md">
                        <details class="group">
                            <summary class="flex items-center justify-between cursor-pointer list-none min-h-[36px]">
                                <span
                                    class="font-label-sm text-label-sm uppercase tracking-wider font-bold text-error flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    Sesiones canceladas (1)
                                </span>
                                <span
                                    class="material-symbols-outlined text-[20px] text-on-surface-variant group-open:rotate-180 transition-transform">
                                    expand_more
                                </span>
                            </summary>
                            <div class="pt-3 space-y-2.5">
                                <div class="flex items-center justify-between p-2.5 rounded-lg bg-error-container/30">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div
                                            class="w-7 h-7 rounded-full bg-error text-on-error flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <p class="font-label-md text-label-md text-on-surface font-bold">Sesión
                                                    3</p>
                                                <span
                                                    class="font-label-sm text-label-sm px-1.5 py-0.2 rounded bg-error-container text-on-error-container font-semibold">Cancelada</span>
                                            </div>
                                            <p class="font-body-sm text-body-sm text-on-surface-variant truncate">8 oct
                                                2026 · $18.000</p>
                                        </div>
                                    </div>
                                    <button
                                        class="min-h-[44px] px-2.5 py-1 text-on-surface font-label-md text-label-md hover:underline flex-shrink-0">
                                        Ver motivo
                                    </button>
                                </div>
                                <p
                                    class="font-body-sm text-body-sm text-on-surface-variant bg-surface-container-low p-2.5 rounded-lg leading-relaxed">
                                    <span class="font-medium text-on-surface">Aviso de trazabilidad:</span> La
                                    cancelación de la sesión 3 generó automáticamente la sesión de reemplazo 6 para
                                    completar las 5 sesiones planificadas.
                                </p>
                            </div>
                        </details>
                    </div>
                </div>
                <!-- 9. ACCIONES SECUNDARIAS DEL TRATAMIENTO -->
                <div class="pt-2 pb-space-lg flex flex-col items-center gap-2">
                    <button
                        class="w-full min-h-[48px] py-2.5 px-4 rounded-xl bg-surface-container text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high font-label-md text-label-md font-semibold flex items-center justify-center gap-2 transition-colors active:scale-98">
                        <span class="material-symbols-outlined text-[18px]">settings</span>
                        <span class="">Opciones avanzadas del tratamiento</span>
                    </button>
                    <p class="font-label-sm text-label-sm text-on-surface-variant/70 text-center">Ajustar cantidad de
                        sesiones requeridas, suspender o cancelar tratamiento.</p>
                </div>
            </div>
        </div>
    </main>
    <nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(38,51,40,0.05)]"
        data-active-classes="text-primary font-label-md">
        <div class="flex justify-around items-center h-16 px-space-xs"><a
                class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="inicio" href="#"><span
                    class="material-symbols-outlined text-[24px]">dashboard</span><span
                    class="font-label-sm text-label-sm mt-0.5">Inicio</span></a><a
                class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="pacientes" href="#"><span
                    class="material-symbols-outlined text-[24px]">pets</span><span
                    class="font-label-sm text-label-sm mt-0.5">Pacientes</span></a><a
                class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="solicitudes" href="#"><span
                    class="material-symbols-outlined text-[24px]">event_note</span><span
                    class="font-label-sm text-label-sm mt-0.5">Solicitudes</span></a><a
                class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="gestion" href="#"><span
                    class="material-symbols-outlined text-[24px]">medical_services</span><span
                    class="font-label-sm text-label-sm mt-0.5">Gestión</span></a></div>
    </nav>


</body>

</html>
