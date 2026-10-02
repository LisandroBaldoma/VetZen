<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="web_dashboard" name="shell-type" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <style>
        @layer base {

            html,
            body {
                margin: 0;
                padding: 0;
            }

            body {
                overscroll-behavior: none;
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
            "darkMode": "class",
            "theme": {
                "extend": {
                    "colors": {
                        "secondary-container": "#bfee85",
                        "on-error-container": "#93000a",
                        "on-secondary-fixed": "#102000",
                        "background": "#eefeed",
                        "on-error": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed": "#2d1600",
                        "primary-container": "#3b833f",
                        "on-primary-fixed": "#002204",
                        "on-tertiary": "#ffffff",
                        "surface-container": "#e3f2e1",
                        "on-secondary": "#ffffff",
                        "outline": "#707a6d",
                        "surface-tint": "#236c2b",
                        "on-tertiary-container": "#fffbff",
                        "inverse-primary": "#8dd889",
                        "tertiary-container": "#ab630a",
                        "on-background": "#121e14",
                        "tertiary-fixed-dim": "#ffb875",
                        "error-container": "#ffdad6",
                        "on-secondary-fixed-variant": "#2f4f00",
                        "on-surface-variant": "#40493e",
                        "surface-dim": "#cfdece",
                        "error": "#ba1a1a",
                        "primary-fixed": "#a8f5a3",
                        "on-primary": "#ffffff",
                        "outline-variant": "#c0c9bb",
                        "surface-variant": "#d7e7d6",
                        "secondary": "#43690d",
                        "on-primary-fixed-variant": "#005315",
                        "on-primary-container": "#f7fff1",
                        "primary": "#206928",
                        "secondary-fixed-dim": "#a7d46f",
                        "surface-container-low": "#e8f8e7",
                        "on-surface": "#121e14",
                        "secondary-fixed": "#c2f188",
                        "on-secondary-container": "#476d12",
                        "primary-fixed-dim": "#8dd889",
                        "tertiary-fixed": "#ffdcc0",
                        "surface-bright": "#eefeed",
                        "inverse-on-surface": "#e5f5e4",
                        "inverse-surface": "#263328",
                        "on-tertiary-fixed-variant": "#6b3b00",
                        "surface-container-high": "#ddecdc",
                        "surface-container-highest": "#d7e7d6",
                        "surface": "#eefeed",
                        "tertiary": "#894d00"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "space-sm": "0.5rem",
                        "margin-desktop": "2.5rem",
                        "margin-tablet": "1.5rem",
                        "space-lg": "1.5rem",
                        "space-xl": "2.25rem",
                        "margin": "1rem",
                        "gutter": "1.25rem",
                        "space-xs": "0.25rem",
                        "gutter-desktop": "1.5rem",
                        "space-md": "1rem"
                    },
                    "fontFamily": {
                        "label-md": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "label-lg": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"],
                        "label-sm": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "display-lg-mobile": ["Plus Jakarta Sans"],
                        "body-sm": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "label-md": ["0.75rem", {
                            "lineHeight": "1rem",
                            "letterSpacing": "0.02em",
                            "fontWeight": "600"
                        }],
                        "display-lg": ["3rem", {
                            "lineHeight": "3.5rem",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "label-lg": ["0.875rem", {
                            "lineHeight": "1.25rem",
                            "letterSpacing": "0.01em",
                            "fontWeight": "600"
                        }],
                        "body-lg": ["1.0625rem", {
                            "lineHeight": "1.625rem",
                            "fontWeight": "400"
                        }],
                        "label-sm": ["0.6875rem", {
                            "lineHeight": "0.875rem",
                            "letterSpacing": "0.03em",
                            "fontWeight": "600"
                        }],
                        "headline-lg-mobile": ["1.5rem", {
                            "lineHeight": "2rem",
                            "fontWeight": "600"
                        }],
                        "display-lg-mobile": ["2rem", {
                            "lineHeight": "2.5rem",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "700"
                        }],
                        "body-sm": ["0.8125rem", {
                            "lineHeight": "1.25rem",
                            "fontWeight": "400"
                        }],
                        "body-md": ["0.9375rem", {
                            "lineHeight": "1.5rem",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["1.375rem", {
                            "lineHeight": "1.875rem",
                            "fontWeight": "600"
                        }],
                        "headline-sm": ["1.125rem", {
                            "lineHeight": "1.625rem",
                            "fontWeight": "600"
                        }],
                        "headline-lg": ["2rem", {
                            "lineHeight": "2.5rem",
                            "letterSpacing": "-0.015em",
                            "fontWeight": "600"
                        }]
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface font-body-md text-body-md text-on-surface antialiased">
    <aside
        class="fixed left-0 top-0 h-full w-72 bg-surface-container-lowest z-50 flex flex-col justify-between shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="flex flex-col">
            <div class="h-16 px-space-lg flex items-center gap-space-sm bg-surface-container-lowest"><img alt="logo.png"
                    class="h-8 w-auto object-contain"
                    src="https://lh3.googleusercontent.com/aida/AEtjO1VjP_3hoVwfwHiJFhaIUTyYs32mg_WMzHpX3Ekr1k8FcxeUz2oRf2TDaH5Tdq6eT7CQP7FUp3ASd2hcXLkEr6tWkYy6N76C6t4Snhr3ddjqpNKhQnbex8cWLtjpfte1MNeuWLgYfdP5ssHUnuvhaK-puMIQA0bF3nLIbPOAJuG0iigD_U-xYGiOPDkMRfAzSbQX1I3GVVBqi856HGn3BmVEyZmSzXrcI9piXj3YIeMVTkWDMDCut0dGH_9n5lywUlMCXcISnPdMgA" />
                <div class="flex flex-col"><span
                        class="font-headline-sm text-headline-sm text-on-surface font-bold leading-none tracking-tight">VetZen</span><span
                        class="font-label-sm text-label-sm text-on-surface-variant leading-none mt-1">Clínica
                        Veterinaria</span></div>
            </div>
            <div class="px-space-md py-space-sm">
                <nav class="flex flex-col gap-space-xs"
                    data-active-classes="bg-surface-container-high text-primary font-label-lg font-semibold rounded-lg">
                    <a aria-current="page"
                        class="flex items-center gap-space-sm px-space-md py-space-sm transition-colors bg-surface-container-high text-primary font-label-lg font-semibold rounded-lg"
                        data-path="inicio" href="#"><span
                            class="material-symbols-outlined text-[20px]">grid_view</span><span>Inicio</span></a><a
                        class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container-high hover:text-on-surface transition-colors"
                        data-path="clientes" href="#"><span
                            class="material-symbols-outlined text-[20px]">group</span><span>Clientes</span></a><a
                        class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container-high hover:text-on-surface transition-colors"
                        data-path="pacientes" href="#"><span
                            class="material-symbols-outlined text-[20px]">pets</span><span>Pacientes</span></a><a
                        class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container-high hover:text-on-surface transition-colors"
                        data-path="solicitudes-de-atencion" href="#"><span
                            class="material-symbols-outlined text-[20px]">pending_actions</span><span>Solicitudes de
                            atención</span></a><a
                        class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container-high hover:text-on-surface transition-colors"
                        data-path="servicios-clinicos" href="#"><span
                            class="material-symbols-outlined text-[20px]">medical_services</span><span>Servicios
                            clínicos</span></a><a
                        class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container-high hover:text-on-surface transition-colors"
                        data-path="procedimientos-clinicos" href="#"><span
                            class="material-symbols-outlined text-[20px]">vital_signs</span><span>Procedimientos
                            clínicos</span></a><a
                        class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container-high hover:text-on-surface transition-colors"
                        data-path="plantillas-de-tratamiento" href="#"><span
                            class="material-symbols-outlined text-[20px]">description</span><span>Plantillas de
                            tratamiento</span></a></nav>
            </div>
        </div>
        <div class="p-space-md bg-surface-container-lowest">
            <div class="bg-surface-container-low rounded-xl p-space-sm flex items-center justify-between gap-space-xs">
                <div class="flex items-center gap-space-sm min-w-0">
                    <div class="w-9 h-9 rounded-full bg-primary flex-shrink-0 flex items-center justify-center"><span
                            class="material-symbols-outlined text-on-primary text-[20px]">person</span></div>
                    <div class="flex flex-col truncate"><span
                            class="font-label-lg text-label-lg text-on-surface font-semibold truncate">Dra. Carmen
                            V.</span><span
                            class="font-label-sm text-label-sm text-on-surface-variant truncate">Directora Médica</span>
                    </div>
                </div>
                <div class="flex items-center gap-1"><button
                        class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors"
                        title="Ajustes" type="button"><span
                            class="material-symbols-outlined text-[18px]">settings</span></button><button
                        class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors"
                        title="Cerrar sesión" type="button"><span
                            class="material-symbols-outlined text-[18px]">logout</span></button></div>
            </div>
        </div>
    </aside>
    <div class="pl-72">
        <header
            class="fixed top-0 left-72 right-0 h-16 bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-space-xl">
            <div class="flex items-center gap-space-md"><img alt="logo.png" class="h-8 w-auto object-contain"
                    src="https://lh3.googleusercontent.com/aida/AEtjO1VjP_3hoVwfwHiJFhaIUTyYs32mg_WMzHpX3Ekr1k8FcxeUz2oRf2TDaH5Tdq6eT7CQP7FUp3ASd2hcXLkEr6tWkYy6N76C6t4Snhr3ddjqpNKhQnbex8cWLtjpfte1MNeuWLgYfdP5ssHUnuvhaK-puMIQA0bF3nLIbPOAJuG0iigD_U-xYGiOPDkMRfAzSbQX1I3GVVBqi856HGn3BmVEyZmSzXrcI9piXj3YIeMVTkWDMDCut0dGH_9n5lywUlMCXcISnPdMgA" />
                <nav class="flex items-center gap-space-xs font-label-md text-label-md text-on-surface-variant"><span
                        class="hover:text-on-surface transition-colors">VetZen</span><span
                        class="material-symbols-outlined text-[14px]">chevron_right</span><span
                        class="text-primary font-semibold">Clínica</span></nav>
                <div
                    class="hidden md:flex items-center gap-space-xs px-space-sm py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span><span>Atención Médica Activa</span></div>
            </div>
            <div class="flex items-center gap-space-md"><button
                    class="relative p-space-xs rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors"
                    title="Notificaciones" type="button"><span
                        class="material-symbols-outlined text-[22px]">notifications</span><span
                        class="absolute top-1 right-1 w-2 h-2 rounded-full bg-primary"></span></button>
                <div class="flex items-center gap-space-sm pl-space-sm">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span
                            class="material-symbols-outlined text-on-primary text-[18px]">person</span></div>
                </div>
            </div>
        </header>
        <main class="relative pt-16 bg-surface min-h-screen">
            <div class="max-w-7xl mx-auto px-space-xl py-space-lg">
                <div class="flex flex-col w-full">
                    <!-- PAGE HEADER & PRIMARY KPI -->
                    <div class="flex flex-col gap-space-lg mb-space-xl">
                        <!-- Top Bar: Title & Action -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-md">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-space-sm flex-wrap">
                                    <h1
                                        class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">
                                        Inicio</h1>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-primary font-label-md text-label-md">
                                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                                        Panel Operativo Clínico
                                    </span>
                                </div>
                                <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
                                    Bienvenida de nuevo. Gestión diaria de terapias y seguimiento clínico integral.
                                </p>
                            </div>
                            <div class="flex items-center gap-space-sm">
                                <button
                                    class="h-11 px-space-lg rounded-xl bg-primary text-on-primary font-label-lg text-label-lg shadow-sm hover:bg-primary-container transition-all flex items-center gap-2 active:scale-95"
                                    type="button">
                                    <span class="material-symbols-outlined text-[20px]">add</span>
                                    <span>+ Nuevo paciente</span>
                                </button>
                            </div>
                        </div>
                        <!-- KPI / Overview Highlights Banner -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
                            <!-- Tarjeta KPI Principal: Solicitudes pendientes -->
                            <div
                                class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm flex flex-col justify-between group hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between gap-space-md">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-tertiary-fixed/40 flex items-center justify-center text-tertiary">
                                            <span class="material-symbols-outlined text-[26px]">pending_actions</span>
                                        </div>
                                        <div>
                                            <span
                                                class="font-display-lg text-display-lg font-bold text-on-surface leading-none"
                                                id="kpi-count">4</span>
                                            <p
                                                class="font-label-lg text-label-lg text-on-surface font-semibold mt-0.5">
                                                Solicitudes pendientes</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-[#EA9640]/15 text-[#8A4F13] font-label-sm text-label-sm font-semibold tracking-wide">
                                        Requieren revisión
                                    </span>
                                </div>
                                <div
                                    class="mt-4 pt-3 flex items-center justify-between border-t border-surface-variant/40">
                                    <p
                                        class="font-body-sm text-body-sm text-on-surface-variant max-w-[210px] leading-snug">
                                        Pacientes esperando confirmación de triage y evaluación inicial.
                                    </p>
                                    <a class="inline-flex items-center gap-1 font-label-md text-label-md text-primary font-bold hover:underline shrink-0"
                                        href="#">
                                        Ver todas
                                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                            <!-- Métricas complementarias de soporte clínico -->
                            <div
                                class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined text-[26px]">vital_signs</span>
                                        </div>
                                        <div>
                                            <span
                                                class="font-display-lg text-display-lg font-bold text-on-surface leading-none">12</span>
                                            <p
                                                class="font-label-lg text-label-lg text-on-surface font-semibold mt-0.5">
                                                Terapias hoy</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-secondary-container/50 text-secondary font-label-sm text-label-sm font-semibold">
                                        En cronograma
                                    </span>
                                </div>
                                <div
                                    class="mt-4 pt-3 flex items-center justify-between border-t border-surface-variant/40">
                                    <p class="font-body-sm text-body-sm text-on-surface-variant text-sm">
                                        8 completadas • 4 programadas para turno tarde.
                                    </p>
                                    <span class="font-label-md text-label-md text-on-surface-variant font-medium">85%
                                        ocupación</span>
                                </div>
                            </div>
                            <div
                                class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center text-secondary">
                                            <span class="material-symbols-outlined text-[26px]">medical_services</span>
                                        </div>
                                        <div>
                                            <span
                                                class="font-display-lg text-display-lg font-bold text-on-surface leading-none">98.4%</span>
                                            <p
                                                class="font-label-lg text-label-lg text-on-surface font-semibold mt-0.5">
                                                Adherencia médica</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-primary-fixed text-on-primary-fixed-variant font-label-sm text-label-sm font-semibold">
                                        Óptima
                                    </span>
                                </div>
                                <div
                                    class="mt-4 pt-3 flex items-center justify-between border-t border-surface-variant/40">
                                    <p class="font-body-sm text-body-sm text-on-surface-variant text-sm">
                                        Cumplimiento activo de protocolos terapéuticos semanales.
                                    </p>
                                    <span class="font-label-md text-label-md text-primary font-bold">Sin alertas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SOLICITUDES PRIORITARIAS (SECCIÓN OPERATIVA) -->
                    <section class="flex flex-col gap-space-md mb-space-xl">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm pb-1">
                            <div class="flex items-center gap-3">
                                <h2 class="font-headline-md text-headline-md text-on-surface font-bold tracking-tight">
                                    Solicitudes prioritarias</h2>
                                <span
                                    class="px-2.5 py-0.5 rounded-full bg-surface-container-high text-primary font-label-md text-label-md font-bold"
                                    id="requests-badge">
                                    4 pendientes
                                </span>
                            </div>
                            <!-- Interactive Toggle for empty-state review -->
                            <button
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface-variant font-label-md text-label-md transition-colors w-fit"
                                id="toggle-empty-state" type="button">
                                <span class="material-symbols-outlined text-[18px]">sync_alt</span>
                                <span>Alternar estado vacío</span>
                            </button>
                        </div>
                        <!-- Active List of Requests -->
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-space-md transition-all" id="requests-grid">
                            <!-- Card 1: Luna -->
                            <article
                                class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm hover:shadow-md transition-all flex flex-col justify-between gap-space-md group">
                                <div class="flex items-start gap-space-md">
                                    <div
                                        class="relative w-16 h-16 rounded-2xl overflow-hidden shrink-0 bg-surface-container-low shadow-sm">
                                        <img class="w-full h-full object-cover"
                                            data-alt="High resolution portrait of a friendly gentle Golden Retriever dog named Luna with shiny warm honey coat, calm veterinary clinical natural lighting with soft green background bokeh"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAhY-yyvjurvW8zkTC_4BB-CrT7rRHtFMu9wgIgmoqDEpKsJkK9KjP94VzeiZYx0YRqL2f2NpGyvI9AL3Td_g9_T0xqGt4WP_Ce4jedxgQ0bfQzTsCCHFQbRiMSpibK0K--y9veS5KKzmyZPwGJZcdGb0QgL8HC6BtgWrCj5vdmsoERdrzOLSf1_QfINgASZo_S7IWndiVl8NP8xbEwHyf33oV-WxtQxhIqCLfErt82NKJdlFLa7w7wHA" />
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-1">
                                            <div>
                                                <h3
                                                    class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">
                                                    Luna</h3>
                                                <p
                                                    class="font-body-sm text-body-sm text-on-surface-variant font-medium">
                                                    Canino • Golden Retriever • 4 años</p>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-[#EA9640]/15 text-[#8A4F13] font-label-sm text-label-sm font-semibold tracking-wide shrink-0">
                                                Pendiente
                                            </span>
                                        </div>
                                        <!-- Terapia vinculada -->
                                        <div
                                            class="inline-flex items-center gap-1.5 text-primary font-label-md text-label-md font-semibold mt-1">
                                            <span class="material-symbols-outlined text-[18px]">healing</span>
                                            <span>Acupuntura Veterinaria</span>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="font-body-md text-body-md text-on-surface-variant bg-surface-container-low/60 rounded-xl p-3">
                                    Sesión de evaluación preliminar para manejo del dolor articular crónico en cadera y
                                    rodillas.
                                </p>
                                <div class="flex items-center justify-between gap-3 pt-2">
                                    <span
                                        class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        Recibido hace 25 min
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            class="h-10 px-4 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md font-semibold transition-colors"
                                            type="button">
                                            Ficha de Luna
                                        </button>
                                        <button
                                            class="h-10 px-4 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md font-semibold transition-colors flex items-center gap-1"
                                            type="button">
                                            <span>Ver solicitud</span>
                                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                        </button>
                                    </div>
                                </div>
                            </article>
                            <!-- Card 2: Milo -->
                            <article
                                class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm hover:shadow-md transition-all flex flex-col justify-between gap-space-md group">
                                <div class="flex items-start gap-space-md">
                                    <div
                                        class="relative w-16 h-16 rounded-2xl overflow-hidden shrink-0 bg-surface-container-low shadow-sm">
                                        <img class="w-full h-full object-cover"
                                            data-alt="Charming tabby domestic European shorthair cat Milo with serene green eyes, clean clinical warm veterinary ambiance, sharp focus and tender expression"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBydb61frKayTJyHalyWF1l26PRi9e7eYNFz7h3_YCTNWAGpcx__eToUSSKwPOSDvgbkh87gjzkxab-OPJea9OVmmymJtDdEFim8vkY6A9KrH-d4x2rEfxaQxGgBm84FRLWkeQMxgIK8XIYUu9eUaYXUAKN1lUtgcc5oZ4a9O8PQpu966k9U2Nx2sG7CQMifBBEHCs_Kq5jvDlMEKq18jegsfznG_TNqzRA5cWEAeABUkuqKOQtsbeE1w" />
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-1">
                                            <div>
                                                <h3
                                                    class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">
                                                    Milo</h3>
                                                <p
                                                    class="font-body-sm text-body-sm text-on-surface-variant font-medium">
                                                    Felino • Gato Europeo • 6 años</p>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-[#EA9640]/15 text-[#8A4F13] font-label-sm text-label-sm font-semibold tracking-wide shrink-0">
                                                Pendiente
                                            </span>
                                        </div>
                                        <!-- Terapia vinculada -->
                                        <div
                                            class="inline-flex items-center gap-1.5 text-primary font-label-md text-label-md font-semibold mt-1">
                                            <span class="material-symbols-outlined text-[18px]">fitness_center</span>
                                            <span>Fisioterapia y Rehabilitación</span>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="font-body-md text-body-md text-on-surface-variant bg-surface-container-low/60 rounded-xl p-3">
                                    Control post-quirúrgico de miembro posterior izquierdo con rangos de movimiento y
                                    fortalecimiento.
                                </p>
                                <div class="flex items-center justify-between gap-3 pt-2">
                                    <span
                                        class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        Recibido hace 1 hora
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            class="h-10 px-4 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md font-semibold transition-colors"
                                            type="button">
                                            Ficha de Milo
                                        </button>
                                        <button
                                            class="h-10 px-4 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md font-semibold transition-colors flex items-center gap-1"
                                            type="button">
                                            <span>Ver solicitud</span>
                                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                        </button>
                                    </div>
                                </div>
                            </article>
                            <!-- Card 3: Thor -->
                            <article
                                class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm hover:shadow-md transition-all flex flex-col justify-between gap-space-md group">
                                <div class="flex items-start gap-space-md">
                                    <div
                                        class="relative w-16 h-16 rounded-2xl overflow-hidden shrink-0 bg-surface-container-low shadow-sm">
                                        <img class="w-full h-full object-cover"
                                            data-alt="Sweet expressive French Bulldog Thor with clean fawn fur coat in warm natural daylight inside veterinary rehabilitation studio"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAj7CSkPL21ByPyXnWsO2Sh-0czMl2YxrTCJLWjNKsz-vjmBTuajh-ees3CsUOs2H3pQLg9hPjPiIIyvXuDru7upGVlgOc_srnBXgUYjkdTy3cDgH0v-O9SdE0gMs9RMiKStWugxfWOWLgButJZrUKUVenfORuYVlolj9fvQ5fM4L5d4TpxDE5WVHcn8BHk9VE_GxRhXkalI6YGlYti3SAI8SraNX-OFFDepCCAfqMjTaEBXDSU5lgQ9w" />
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-1">
                                            <div>
                                                <h3
                                                    class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">
                                                    Thor</h3>
                                                <p
                                                    class="font-body-sm text-body-sm text-on-surface-variant font-medium">
                                                    Canino • Bulldog Francés • 2 años</p>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-[#EA9640]/15 text-[#8A4F13] font-label-sm text-label-sm font-semibold tracking-wide shrink-0">
                                                Pendiente
                                            </span>
                                        </div>
                                        <!-- Terapia vinculada -->
                                        <div
                                            class="inline-flex items-center gap-1.5 text-primary font-label-md text-label-md font-semibold mt-1">
                                            <span class="material-symbols-outlined text-[18px]">flare</span>
                                            <span>Terapia Láser</span>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="font-body-md text-body-md text-on-surface-variant bg-surface-container-low/60 rounded-xl p-3">
                                    Estimulación de cicatrización dermatológica en pliegues nasolabiales y descongestión
                                    epidérmica.
                                </p>
                                <div class="flex items-center justify-between gap-3 pt-2">
                                    <span
                                        class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        Recibido hace 2 horas
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            class="h-10 px-4 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md font-semibold transition-colors"
                                            type="button">
                                            Ficha de Thor
                                        </button>
                                        <button
                                            class="h-10 px-4 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md font-semibold transition-colors flex items-center gap-1"
                                            type="button">
                                            <span>Ver solicitud</span>
                                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                        </button>
                                    </div>
                                </div>
                            </article>
                            <!-- Card 4: Kira -->
                            <article
                                class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm hover:shadow-md transition-all flex flex-col justify-between gap-space-md group">
                                <div class="flex items-start gap-space-md">
                                    <div
                                        class="relative w-16 h-16 rounded-2xl overflow-hidden shrink-0 bg-surface-container-low shadow-sm">
                                        <img class="w-full h-full object-cover"
                                            data-alt="Attentive mixed-breed medium dog Kira with warm gentle hazel eyes resting comfortably on clean linen veterinary consultation bed"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuA5t_qStAgCBd81QURYaQwSBUMzCfF6CTIXj8v_dmTzyidQzqSbPHwxRU3RPWpmL7nNjhBaAoYDac_0t7p4QsW0ZweAav97O4G6jEbM0rdcpWZt2CksyHiTFuIt_DeWe-MRh_Ct0sPqTL2mnVBddZHKRIPOfvWLWcCFIMZtpaVuE_h0ugYmUjs7ebTuqGzlWSseBupWbNpS1SRJEKx_RniqsLYoKbl-5aLKJ9l3tyhQyrgQm5v1pL7s0g" />
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-1">
                                            <div>
                                                <h3
                                                    class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">
                                                    Kira</h3>
                                                <p
                                                    class="font-body-sm text-body-sm text-on-surface-variant font-medium">
                                                    Canino • Mestiza • 8 años</p>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 rounded-full bg-[#EA9640]/15 text-[#8A4F13] font-label-sm text-label-sm font-semibold tracking-wide shrink-0">
                                                Pendiente
                                            </span>
                                        </div>
                                        <!-- Terapia vinculada -->
                                        <div
                                            class="inline-flex items-center gap-1.5 text-primary font-label-md text-label-md font-semibold mt-1">
                                            <span class="material-symbols-outlined text-[18px]">spa</span>
                                            <span>Fitoterapia y Nutrición Clínica</span>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="font-body-md text-body-md text-on-surface-variant bg-surface-container-low/60 rounded-xl p-3">
                                    Ajuste de dieta terapéutica botánica para soporte digestivo y hepático en paciente
                                    senior.
                                </p>
                                <div class="flex items-center justify-between gap-3 pt-2">
                                    <span
                                        class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        Recibido hace 3 horas
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            class="h-10 px-4 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md font-semibold transition-colors"
                                            type="button">
                                            Ficha de Kira
                                        </button>
                                        <button
                                            class="h-10 px-4 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md font-semibold transition-colors flex items-center gap-1"
                                            type="button">
                                            <span>Ver solicitud</span>
                                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <!-- Empty State View (Toggleable) -->
                        <div class="hidden bg-surface-container-lowest rounded-2xl p-space-xl shadow-sm text-center flex-col items-center justify-center py-16"
                            id="requests-empty-state">
                            <div
                                class="w-16 h-16 rounded-full bg-surface-container-high text-primary flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-[32px]">task_alt</span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Bandeja de triage
                                al día</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant max-w-md mt-1 mb-6">
                                No hay solicitudes de atención pendientes de revisión en este momento. Todas las
                                evaluaciones preliminares han sido procesadas.
                            </p>
                            <button
                                class="h-11 px-6 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-semibold shadow-sm hover:bg-primary-container transition-colors inline-flex items-center gap-2"
                                type="button">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                <span>Crear solicitud manual</span>
                            </button>
                        </div>
                    </section>
                    <!-- ACCESOS RÁPIDOS: CATÁLOGO Y PROCESOS -->
                    <section class="flex flex-col gap-space-md mb-space-xl">
                        <div>
                            <h2 class="font-headline-md text-headline-md text-on-surface font-bold tracking-tight">
                                Accesos rápidos</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Catálogo y Procesos esenciales
                                de la práctica clínica</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-space-md">
                            <!-- 1: Pacientes -->
                            <a class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center group border border-transparent hover:border-surface-variant"
                                href="#">
                                <div
                                    class="w-12 h-12 rounded-xl bg-surface-container-low group-hover:bg-primary-fixed text-primary flex items-center justify-center mb-3 transition-colors">
                                    <span class="material-symbols-outlined text-[24px]">pets</span>
                                </div>
                                <span
                                    class="font-label-lg text-label-lg text-on-surface font-bold group-hover:text-primary transition-colors">Pacientes</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Expedientes
                                    activos</span>
                            </a>
                            <!-- 2: Admisiones -->
                            <a class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center group border border-transparent hover:border-surface-variant"
                                href="#">
                                <div
                                    class="w-12 h-12 rounded-xl bg-surface-container-low group-hover:bg-primary-fixed text-primary flex items-center justify-center mb-3 transition-colors">
                                    <span class="material-symbols-outlined text-[24px]">assignment_turned_in</span>
                                </div>
                                <span
                                    class="font-label-lg text-label-lg text-on-surface font-bold group-hover:text-primary transition-colors">Admisiones</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Solicitudes
                                    triage</span>
                            </a>
                            <!-- 3: Servicios -->
                            <a class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center group border border-transparent hover:border-surface-variant"
                                href="#">
                                <div
                                    class="w-12 h-12 rounded-xl bg-surface-container-low group-hover:bg-primary-fixed text-primary flex items-center justify-center mb-3 transition-colors">
                                    <span class="material-symbols-outlined text-[24px]">medical_services</span>
                                </div>
                                <span
                                    class="font-label-lg text-label-lg text-on-surface font-bold group-hover:text-primary transition-colors">Servicios</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Terapias y
                                    medicina</span>
                            </a>
                            <!-- 4: Procedimientos -->
                            <a class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center group border border-transparent hover:border-surface-variant"
                                href="#">
                                <div
                                    class="w-12 h-12 rounded-xl bg-surface-container-low group-hover:bg-primary-fixed text-primary flex items-center justify-center mb-3 transition-colors">
                                    <span class="material-symbols-outlined text-[24px]">vital_signs</span>
                                </div>
                                <span
                                    class="font-label-lg text-label-lg text-on-surface font-bold group-hover:text-primary transition-colors">Procedimientos</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Maniobras y
                                    pautas</span>
                            </a>
                            <!-- 5: Plantillas -->
                            <a class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center group border border-transparent hover:border-surface-variant"
                                href="#">
                                <div
                                    class="w-12 h-12 rounded-xl bg-surface-container-low group-hover:bg-primary-fixed text-primary flex items-center justify-center mb-3 transition-colors">
                                    <span class="material-symbols-outlined text-[24px]">description</span>
                                </div>
                                <span
                                    class="font-label-lg text-label-lg text-on-surface font-bold group-hover:text-primary transition-colors">Plantillas</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Planes
                                    terapéuticos</span>
                            </a>
                        </div>
                    </section>
                    <!-- GUÍA DE ESTADOS CLÍNICOS & ESPECIFICACIÓN DEL SISTEMA VISUAL -->
                    <section class="bg-surface-container-lowest rounded-2xl shadow-sm p-space-lg mb-space-lg">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between pb-space-md border-b border-surface-variant/50 gap-space-sm">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-lg bg-surface-container-high text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">palette</span>
                                </div>
                                <div>
                                    <h2
                                        class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">
                                        Guía de Estados Clínicos y Especificación Visual</h2>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Vocabulario cromático
                                        unificado y componentes normativos de VetZen</p>
                                </div>
                            </div>
                            <button
                                class="inline-flex items-center gap-1 text-primary font-label-md text-label-md font-semibold hover:underline"
                                id="toggle-guide-body" type="button">
                                <span id="guide-toggle-text">Ocultar especificación</span>
                                <span class="material-symbols-outlined text-[18px]"
                                    id="guide-toggle-icon">expand_less</span>
                            </button>
                        </div>
                        <div class="flex flex-col gap-space-lg pt-space-lg" id="guide-content">
                            <!-- 8 Estados Semánticos Oficiales -->
                            <div>
                                <h4
                                    class="font-label-md text-label-md text-on-surface uppercase tracking-wider font-bold mb-space-sm">
                                    Semántica de Estados Médicos (8 Estados Oficiales)
                                </h4>
                                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-space-sm">
                                    <!-- 1. Activo -->
                                    <div class="bg-[#478F49]/10 rounded-xl p-3 flex flex-col gap-1 items-start">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-[#478F49] text-white font-label-sm text-label-sm font-semibold">Activo</span>
                                        <span
                                            class="font-label-sm text-label-sm text-[#263328] font-bold mt-1">#478F49</span>
                                        <span
                                            class="font-body-sm text-body-sm text-[#606C5D] text-[11px] leading-tight">Tratamiento
                                            en vigencia</span>
                                    </div>
                                    <!-- 2. Inactivo -->
                                    <div
                                        class="bg-surface-container-high/60 rounded-xl p-3 flex flex-col gap-1 items-start">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-outline-variant text-on-surface-variant font-label-sm text-label-sm font-semibold">Inactivo</span>
                                        <span class="font-label-sm text-label-sm text-[#263328] font-bold mt-1">Gris
                                            neutro</span>
                                        <span
                                            class="font-body-sm text-body-sm text-[#606C5D] text-[11px] leading-tight">Alta
                                            o sin terapia</span>
                                    </div>
                                    <!-- 3. Pendiente -->
                                    <div class="bg-[#EA9640]/15 rounded-xl p-3 flex flex-col gap-1 items-start">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-[#EA9640] text-white font-label-sm text-label-sm font-semibold">Pendiente</span>
                                        <span
                                            class="font-label-sm text-label-sm text-[#8A4F13] font-bold mt-1">#EA9640</span>
                                        <span
                                            class="font-body-sm text-body-sm text-[#606C5D] text-[11px] leading-tight">Triage
                                            / Espera</span>
                                    </div>
                                    <!-- 4. Resuelto -->
                                    <div class="bg-[#2A7B5E]/15 rounded-xl p-3 flex flex-col gap-1 items-start">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-[#2A7B5E] text-white font-label-sm text-label-sm font-semibold">Resuelto</span>
                                        <span
                                            class="font-label-sm text-label-sm text-[#1b503e] font-bold mt-1">#2A7B5E</span>
                                        <span
                                            class="font-body-sm text-body-sm text-[#606C5D] text-[11px] leading-tight">Síntoma
                                            mitigado</span>
                                    </div>
                                    <!-- 5. Cancelado -->
                                    <div class="bg-[#DA5F42]/15 rounded-xl p-3 flex flex-col gap-1 items-start">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-[#DA5F42] text-white font-label-sm text-label-sm font-semibold">Cancelado</span>
                                        <span
                                            class="font-label-sm text-label-sm text-[#8F2C16] font-bold mt-1">#DA5F42</span>
                                        <span
                                            class="font-body-sm text-body-sm text-[#606C5D] text-[11px] leading-tight">Sesión
                                            anulada</span>
                                    </div>
                                    <!-- 6. En curso -->
                                    <div class="bg-[#E8AD46]/20 rounded-xl p-3 flex flex-col gap-1 items-start">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-[#E8AD46] text-[#263328] font-label-sm text-label-sm font-semibold">En
                                            curso</span>
                                        <span
                                            class="font-label-sm text-label-sm text-[#73500d] font-bold mt-1">#E8AD46</span>
                                        <span
                                            class="font-body-sm text-body-sm text-[#606C5D] text-[11px] leading-tight">Sesión
                                            en cabina</span>
                                    </div>
                                    <!-- 7. Completado -->
                                    <div
                                        class="bg-secondary-container/40 rounded-xl p-3 flex flex-col gap-1 items-start">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">Completado</span>
                                        <span
                                            class="font-label-sm text-label-sm text-[#476d12] font-bold mt-1">Esmeralda</span>
                                        <span
                                            class="font-body-sm text-body-sm text-[#606C5D] text-[11px] leading-tight">Plan
                                            concluido</span>
                                    </div>
                                    <!-- 8. Suspendido -->
                                    <div class="bg-error-container/40 rounded-xl p-3 flex flex-col gap-1 items-start">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-[#DA5F42]/20 text-[#8F2C16] font-label-sm text-label-sm font-semibold">Suspendido</span>
                                        <span
                                            class="font-label-sm text-label-sm text-[#ba1a1a] font-bold mt-1">Terracota
                                            tenue</span>
                                        <span
                                            class="font-body-sm text-body-sm text-[#606C5D] text-[11px] leading-tight">Interrupción
                                            médica</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Muestra de Tokens y Componentes -->
                            <div
                                class="grid grid-cols-1 lg:grid-cols-2 gap-space-lg pt-2 border-t border-surface-variant/40">
                                <!-- Paleta base aprobada -->
                                <div class="flex flex-col gap-2">
                                    <h5
                                        class="font-label-sm text-label-sm text-on-surface-variant uppercase font-semibold">
                                        Tokens de Superficie y Texto</h5>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="p-2.5 rounded-xl bg-surface flex flex-col gap-0.5 shadow-sm">
                                            <span
                                                class="font-label-sm text-label-sm text-on-surface font-semibold">Background</span>
                                            <span
                                                class="font-body-sm text-body-sm text-on-surface-variant font-mono">#FDFAF3
                                                / surface</span>
                                        </div>
                                        <div
                                            class="p-2.5 rounded-xl bg-surface-container-lowest flex flex-col gap-0.5 shadow-sm">
                                            <span
                                                class="font-label-sm text-label-sm text-on-surface font-semibold">Card
                                                Surface</span>
                                            <span
                                                class="font-body-sm text-body-sm text-on-surface-variant font-mono">#FFFFFF
                                                / lowest</span>
                                        </div>
                                        <div class="p-2.5 rounded-xl bg-surface-container-high flex flex-col gap-0.5">
                                            <span
                                                class="font-label-sm text-label-sm text-on-surface font-semibold">Borders
                                                &amp; Subtle</span>
                                            <span
                                                class="font-body-sm text-body-sm text-on-surface-variant font-mono">#EAE4D9
                                                / variant</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 mt-2 px-1">
                                        <div class="flex items-center gap-1.5 text-on-surface">
                                            <span class="w-3 h-3 rounded-full bg-on-surface"></span>
                                            <span class="font-label-sm text-label-sm font-semibold">Primary Text
                                                (#263328)</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-on-surface-variant">
                                            <span class="w-3 h-3 rounded-full bg-on-surface-variant"></span>
                                            <span class="font-label-sm text-label-sm font-semibold">Secondary Text
                                                (#606C5D)</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Componentes estándar en vivo -->
                                <div class="flex flex-col gap-2">
                                    <h5
                                        class="font-label-sm text-label-sm text-on-surface-variant uppercase font-semibold">
                                        Botones e Inputs Clínicos</h5>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <button
                                            class="h-10 px-4 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-semibold shadow-sm hover:bg-primary-container transition-colors"
                                            type="button">
                                            Primario (42px)
                                        </button>
                                        <button
                                            class="h-10 px-4 rounded-xl bg-surface-container-lowest text-on-surface font-label-md text-label-md font-semibold shadow-sm hover:bg-surface-container-low transition-colors"
                                            type="button">
                                            Secundario Blanco
                                        </button>
                                        <div class="relative flex-1 min-w-[160px]">
                                            <span
                                                class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant text-[18px]">search</span>
                                            <input
                                                class="w-full h-10 pl-9 pr-3 rounded-xl bg-surface-container-lowest text-on-surface font-body-sm text-body-sm placeholder:text-on-surface-variant/70 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20"
                                                placeholder="Buscar paciente..." type="text" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Inline Script for Interactivity (Empty State & Guide Toggle) -->
                    <script>
                        (function() {
                            const toggleBtn = document.getElementById('toggle-empty-state');
                            const grid = document.getElementById('requests-grid');
                            const emptyState = document.getElementById('requests-empty-state');
                            const badge = document.getElementById('requests-badge');
                            const kpiCount = document.getElementById('kpi-count');

                            let isEmpty = false;

                            if (toggleBtn && grid && emptyState) {
                                toggleBtn.addEventListener('click', function() {
                                    isEmpty = !isEmpty;
                                    if (isEmpty) {
                                        grid.classList.add('hidden');
                                        emptyState.classList.remove('hidden');
                                        emptyState.classList.add('flex');
                                        badge.textContent = '0 pendientes';
                                        badge.classList.remove('text-primary');
                                        badge.classList.add('text-on-surface-variant');
                                        if (kpiCount) kpiCount.textContent = '0';
                                    } else {
                                        grid.classList.remove('hidden');
                                        emptyState.classList.add('hidden');
                                        emptyState.classList.remove('flex');
                                        badge.textContent = '4 pendientes';
                                        badge.classList.add('text-primary');
                                        badge.classList.remove('text-on-surface-variant');
                                        if (kpiCount) kpiCount.textContent = '4';
                                    }
                                });
                            }

                            // Collapsible visual guide
                            const guideToggleBtn = document.getElementById('toggle-guide-body');
                            const guideContent = document.getElementById('guide-content');
                            const guideToggleText = document.getElementById('guide-toggle-text');
                            const guideToggleIcon = document.getElementById('guide-toggle-icon');

                            if (guideToggleBtn && guideContent) {
                                let isCollapsed = false;
                                guideToggleBtn.addEventListener('click', function() {
                                    isCollapsed = !isCollapsed;
                                    if (isCollapsed) {
                                        guideContent.classList.add('hidden');
                                        guideToggleText.textContent = 'Mostrar especificación';
                                        guideToggleIcon.textContent = 'expand_more';
                                    } else {
                                        guideContent.classList.remove('hidden');
                                        guideToggleText.textContent = 'Ocultar especificación';
                                        guideToggleIcon.textContent = 'expand_less';
                                    }
                                });
                            }
                        })();
                    </script>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
