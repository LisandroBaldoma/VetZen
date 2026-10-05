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
        <div class="flex flex-col w-full pb-10">
            <!-- 1. Contextual Sub-bar Navigation -->
            <div class="flex items-center justify-between px-margin py-space-sm bg-surface">
                <a class="inline-flex items-center gap-space-xs text-on-surface-variant hover:text-primary transition-colors min-h-[44px] min-w-[44px] -ml-2 px-2 rounded-lg"
                    href="#">
                    <span class="material-symbols-outlined text-[20px] text-primary">arrow_back</span>
                    <span class="font-label-lg text-label-lg font-semibold text-on-surface">Pacientes</span>
                </a>
                <button aria-label="Más opciones del paciente"
                    class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[22px]">more_horiz</span>
                </button>
            </div>
            <div class="flex flex-col gap-space-md px-margin">
                <!-- 2. PetContextHeader (Compact, persistent, verified structure) -->
                <div class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm">
                    <div class="flex items-center gap-space-md">
                        <div class="relative flex-shrink-0">
                            <img class="w-14 h-14 rounded-full object-cover shadow-sm"
                                data-alt="A warm, charming portrait of a golden retriever dog sitting calmly in natural warm sunlight, friendly and healthy look, veterinary photography style with soft focus background"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBC4JJKJmktvlD2rmFe53uA4K27KxMl1Zc52Ht_gB1brd_8LbU95Jt1J70-4ssvgCvsZDk5nkxVT4j-uQrzls6BBbA9Zi35XpkJYHABAL_f4ot4jP2TBp2JzmDpxY6TRZDUUA8RH0cPCh5-ZZfTFqmXM-mbiCK3k6wdjXKzlcr3zCIrZsQm4DqJivllijPXZdWreMLE9g6Xb2ioTyI4QQdtT1pt9oU8naLLeuLXCMbMeYcrWX4tnVzamA">
                            <div
                                class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-primary rounded-full flex items-center justify-center shadow-xs">
                                <span class="material-symbols-outlined text-on-primary text-[10px]"
                                    style="font-variation-settings: 'FILL' 1;">pets</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-space-xs mb-0.5">
                                <h2 class="font-headline-sm text-headline-sm text-on-surface truncate">Luna</h2>
                                <span
                                    class="bg-surface-container text-on-surface-variant font-label-sm text-label-sm px-2 py-0.5 rounded-full flex-shrink-0">Canino</span>
                            </div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant truncate">Golden Retriever ·
                                Hembra</p>
                            <div class="flex items-center gap-1 mt-0.5 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px]">person</span>
                                <span class="font-body-sm text-body-sm text-on-surface truncate">María González</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-space-md pt-space-xs flex justify-end">
                        <button
                            class="inline-flex items-center justify-center gap-1.5 h-9 px-3.5 rounded-lg bg-surface-container-low text-on-surface hover:bg-surface-container active:scale-[0.98] transition-all font-label-md text-label-md">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">edit</span>
                            <span class="">Editar paciente</span>
                        </button>
                    </div>
                </div>
                <!-- 3. PatientNavigation (Smooth Horizontal Scroll with visual overflow cue) -->
                <div class="relative -mx-margin px-margin overflow-x-auto no-scrollbar scroll-smooth">
                    <div class="flex items-center gap-space-xs min-w-max pb-1">
                        <a class="px-3.5 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors"
                            href="#">
                            Resumen
                        </a>
                        <a class="px-3.5 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors"
                            href="#">
                            Historia clínica
                        </a>
                        <a class="px-3.5 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors"
                            href="#">
                            Solicitudes
                        </a>
                        <a class="px-4 py-2 rounded-full font-label-md text-label-md bg-primary text-on-primary flex items-center gap-1.5 shadow-sm"
                            href="#">
                            <span class="material-symbols-outlined text-[16px]"
                                style="font-variation-settings: 'FILL' 1;">medical_services</span>
                            <span class="">Tratamientos</span>
                        </a>
                    </div>
                </div>
                <!-- 4. Page Header & Primary Action -->
                <div class="flex flex-col gap-space-xs mt-1">
                    <div class="flex items-baseline justify-between">
                        <h3
                            class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-on-surface tracking-tight">
                            Tratamientos</h3>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">4 tratamientos</span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant">Consultá los tratamientos asignados y
                        su progreso.</p>
                    <button
                        class="mt-space-sm w-full h-12 bg-primary hover:bg-primary-container active:scale-[0.99] text-on-primary rounded-xl font-label-lg text-label-lg font-semibold flex items-center justify-center gap-space-xs shadow-md shadow-primary/10 transition-all">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        <span class="">Asignar tratamiento</span>
                    </button>
                </div>
                <!-- 5. Listado de Tratamientos Asignados -->
                <!-- SECCIÓN A: En curso y pendientes -->
                <section class="flex flex-col gap-space-sm mt-space-sm">
                    <div class="flex items-center gap-space-xs">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        <h4 class="font-label-lg text-label-lg font-bold text-on-surface uppercase tracking-wider">
                            Tratamientos actuales</h4>
                    </div>
                    <!-- Card 1: Rehabilitación postoperatoria -->
                    <article
                        class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm relative overflow-hidden flex flex-col gap-space-sm">
                        <div class="flex items-start justify-between gap-space-sm">
                            <div class="min-w-0">
                                <span
                                    class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide">Fisioterapia</span>
                                <h5
                                    class="font-headline-sm text-headline-sm text-on-surface font-semibold truncate mt-0.5">
                                    Rehabilitación postoperatoria</h5>
                            </div>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                En curso
                            </span>
                        </div>
                        <!-- TreatmentProgress -->
                        <div class="flex flex-col gap-1.5 mt-1 bg-surface-container-low p-space-sm rounded-lg">
                            <div class="flex justify-between items-center text-on-surface">
                                <span class="font-body-sm text-body-sm font-semibold">3 de 5 sesiones completadas</span>
                                <span class="font-label-md text-label-md font-bold text-primary">60%</span>
                            </div>
                            <div class="w-full h-2 bg-surface-variant rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full transition-all duration-500"
                                    style="width: 60%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-1 text-on-surface-variant">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">event</span>
                                <span class="font-label-sm text-label-sm">Inicio · 12 sep 2026</span>
                            </div>
                            <button
                                class="inline-flex items-center gap-1 font-label-md text-label-md text-primary font-semibold hover:bg-surface-container-low px-2 py-1.5 rounded-lg transition-colors min-h-[36px]">
                                <span class="">Ver tratamiento</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </article>
                    <!-- Card 2: Manejo del dolor -->
                    <article
                        class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm relative overflow-hidden flex flex-col gap-space-sm">
                        <div class="flex items-start justify-between gap-space-sm">
                            <div class="min-w-0">
                                <span
                                    class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide">Acupuntura</span>
                                <h5
                                    class="font-headline-sm text-headline-sm text-on-surface font-semibold truncate mt-0.5">
                                    Manejo del dolor</h5>
                            </div>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-tertiary-fixed text-tertiary font-label-sm text-label-sm flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                                Pendiente
                            </span>
                        </div>
                        <!-- TreatmentProgress -->
                        <div class="flex flex-col gap-1.5 mt-1 bg-surface-container-low p-space-sm rounded-lg">
                            <div class="flex justify-between items-center text-on-surface">
                                <span class="font-body-sm text-body-sm font-semibold">0 de 4 sesiones
                                    completadas</span>
                                <span class="font-label-md text-label-md font-bold text-on-surface-variant">0%</span>
                            </div>
                            <div class="w-full h-2 bg-surface-variant rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full transition-all duration-500"
                                    style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-1 text-on-surface-variant">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">event</span>
                                <span class="font-label-sm text-label-sm">Inicio · 20 oct 2026</span>
                            </div>
                            <button
                                class="inline-flex items-center gap-1 font-label-md text-label-md text-primary font-semibold hover:bg-surface-container-low px-2 py-1.5 rounded-lg transition-colors min-h-[36px]">
                                <span class="">Ver tratamiento</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </article>
                </section>
                <!-- SECCIÓN B: Historial de tratamientos -->
                <section class="flex flex-col gap-space-sm mt-space-md">
                    <div class="flex items-center gap-space-xs">
                        <span class="w-2 h-2 rounded-full bg-on-surface-variant"></span>
                        <h4 class="font-label-lg text-label-lg font-bold text-on-surface uppercase tracking-wider">
                            Historial de tratamientos</h4>
                    </div>
                    <!-- Card 3: Recuperación funcional -->
                    <article
                        class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm relative overflow-hidden flex flex-col gap-space-sm">
                        <div class="flex items-start justify-between gap-space-sm">
                            <div class="min-w-0">
                                <span
                                    class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide">Fisioterapia</span>
                                <h5
                                    class="font-headline-sm text-headline-sm text-on-surface font-semibold truncate mt-0.5">
                                    Recuperación funcional</h5>
                            </div>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container-high text-primary font-label-sm text-label-sm flex-shrink-0">
                                <span class="material-symbols-outlined text-[14px]">check</span>
                                Completado
                            </span>
                        </div>
                        <!-- TreatmentProgress -->
                        <div class="flex flex-col gap-1.5 mt-1 bg-surface-container-low p-space-sm rounded-lg">
                            <div class="flex justify-between items-center text-on-surface">
                                <span class="font-body-sm text-body-sm font-semibold">6 de 6 sesiones
                                    completadas</span>
                                <span class="font-label-md text-label-md font-bold text-primary">100%</span>
                            </div>
                            <div class="w-full h-2 bg-surface-variant rounded-full overflow-hidden">
                                <div class="h-full bg-primary-container rounded-full" style="width: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-1 text-on-surface-variant">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">event_available</span>
                                <span class="font-label-sm text-label-sm">Inicio · 4 jun 2026 · Finalizado</span>
                            </div>
                            <button
                                class="inline-flex items-center gap-1 font-label-md text-label-md text-on-surface-variant font-semibold hover:bg-surface-container-low px-2 py-1.5 rounded-lg transition-colors min-h-[36px]">
                                <span class="">Ver tratamiento</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </article>
                    <!-- Card 4: Plan terapéutico anterior -->
                    <article
                        class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm relative overflow-hidden flex flex-col gap-space-sm opacity-90">
                        <div class="flex items-start justify-between gap-space-sm">
                            <div class="min-w-0">
                                <span
                                    class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide">Fisioterapia</span>
                                <h5
                                    class="font-headline-sm text-headline-sm text-on-surface font-semibold truncate mt-0.5">
                                    Plan terapéutico anterior</h5>
                            </div>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-error-container text-error font-label-sm text-label-sm flex-shrink-0">
                                <span class="material-symbols-outlined text-[14px]">cancel</span>
                                Cancelado
                            </span>
                        </div>
                        <!-- TreatmentProgress -->
                        <div class="flex flex-col gap-1.5 mt-1 bg-surface-container-low p-space-sm rounded-lg">
                            <div class="flex justify-between items-center text-on-surface-variant">
                                <span class="font-body-sm text-body-sm">1 de 5 sesiones completadas</span>
                                <span class="font-label-md text-label-md font-medium text-error">20%</span>
                            </div>
                            <div class="w-full h-2 bg-surface-variant rounded-full overflow-hidden">
                                <div class="h-full bg-error/70 rounded-full" style="width: 20%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-1 text-on-surface-variant">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">event</span>
                                <span class="font-label-sm text-label-sm">Inicio · 10 feb 2026</span>
                            </div>
                            <button
                                class="inline-flex items-center gap-1 font-label-md text-label-md text-on-surface-variant font-semibold hover:bg-surface-container-low px-2 py-1.5 rounded-lg transition-colors min-h-[36px]">
                                <span class="">Ver tratamiento</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </article>
                </section>
                <!-- Warm Assurance Clinical Footer Note -->
                <div class="mt-space-sm p-space-md rounded-xl bg-surface-container-low flex items-center gap-space-sm">
                    <div
                        class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center text-primary flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">verified_user</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Todos los tratamientos son
                        supervisados bajo estándares de bienestar integral VetZen.</p>
                </div>
            </div>
        </div>
        <script>
            // Micro-interaction for smooth horizontal touch cue on navigation
            const navContainer = document.querySelector('.overflow-x-auto');
            if (navContainer) {
                const activeTab = navContainer.querySelector('.bg-primary');
                if (activeTab) {
                    activeTab.scrollIntoView({
                        behavior: 'smooth',
                        inline: 'nearest',
                        block: 'nearest'
                    });
                }
            }
        </script>
    </main>
    <nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(38,51,40,0.05)]"
        data-active-classes="text-primary font-label-md">
        <div class="flex justify-around items-center h-16 px-space-xs"><a
                class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="inicio" href="#"><span
                    class="material-symbols-outlined text-[24px]">dashboard</span><span
                    class="font-label-sm text-label-sm mt-0.5">Inicio</span></a><a aria-current="page"
                class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] transition-colors text-primary font-label-md"
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
