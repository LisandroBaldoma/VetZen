<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"
        name="viewport" />
    <meta content="mobile_tab" name="shell-type" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-error": "#ffffff",
                        "tertiary-fixed": "#ffdcc0",
                        "outline-variant": "#c0c9bb",
                        "on-error-container": "#93000a",
                        "primary-container": "#3b833f",
                        "surface-bright": "#eefeed",
                        "surface-variant": "#d7e7d6",
                        "inverse-primary": "#8dd889",
                        "inverse-surface": "#263328",
                        "on-secondary-fixed-variant": "#2f4f00",
                        "surface-dim": "#cfdece",
                        "on-secondary-fixed": "#102000",
                        "on-tertiary-container": "#fffbff",
                        "on-tertiary-fixed": "#2d1600",
                        "secondary-container": "#bfee85",
                        "on-primary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "primary-fixed": "#a8f5a3",
                        "inverse-on-surface": "#e5f5e4",
                        "background": "#eefeed",
                        "on-tertiary": "#ffffff",
                        "tertiary-fixed-dim": "#ffb875",
                        "error-container": "#ffdad6",
                        "surface-container-high": "#ddecdc",
                        "outline": "#707a6d",
                        "on-surface": "#121e14",
                        "primary": "#206928",
                        "surface-tint": "#236c2b",
                        "surface-container-highest": "#d7e7d6",
                        "secondary-fixed": "#c2f188",
                        "surface-container-low": "#e8f8e7",
                        "secondary-fixed-dim": "#a7d46f",
                        "error": "#ba1a1a",
                        "tertiary-container": "#ab630a",
                        "on-secondary": "#ffffff",
                        "on-primary-fixed-variant": "#005315",
                        "on-secondary-container": "#476d12",
                        "on-tertiary-fixed-variant": "#6b3b00",
                        "on-primary-fixed": "#002204",
                        "surface": "#eefeed",
                        "tertiary": "#894d00",
                        "on-surface-variant": "#40493e",
                        "surface-container": "#e3f2e1",
                        "primary-fixed-dim": "#8dd889",
                        "on-primary-container": "#f7fff1",
                        "secondary": "#43690d",
                        "on-background": "#121e14"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "margin": "1rem",
                        "space-sm": "0.5rem",
                        "space-xl": "2.25rem",
                        "margin-tablet": "1.5rem",
                        "space-md": "1rem",
                        "margin-desktop": "2.5rem",
                        "space-lg": "1.5rem",
                        "space-xs": "0.25rem",
                        "gutter": "1.25rem",
                        "gutter-desktop": "1.5rem"
                    },
                    fontFamily: {
                        "headline-lg": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "label-lg": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "display-lg-mobile": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "body-sm": ["Plus Jakarta Sans"],
                        "label-sm": ["Plus Jakarta Sans"]
                    },
                    fontSize: {
                        "headline-lg": ["2rem", {
                            lineHeight: "2.5rem",
                            letterSpacing: "-0.015em",
                            fontWeight: "600"
                        }],
                        "headline-md": ["1.375rem", {
                            lineHeight: "1.875rem",
                            fontWeight: "600"
                        }],
                        "display-lg": ["3rem", {
                            lineHeight: "3.5rem",
                            letterSpacing: "-0.02em",
                            fontWeight: "700"
                        }],
                        "headline-lg-mobile": ["1.5rem", {
                            lineHeight: "2rem",
                            fontWeight: "600"
                        }],
                        "body-lg": ["1.0625rem", {
                            lineHeight: "1.625rem",
                            fontWeight: "400"
                        }],
                        "headline-sm": ["1.125rem", {
                            lineHeight: "1.625rem",
                            fontWeight: "600"
                        }],
                        "label-lg": ["0.875rem", {
                            lineHeight: "1.25rem",
                            letterSpacing: "0.01em",
                            fontWeight: "600"
                        }],
                        "body-md": ["0.9375rem", {
                            lineHeight: "1.5rem",
                            fontWeight: "400"
                        }],
                        "display-lg-mobile": ["2rem", {
                            lineHeight: "2.5rem",
                            letterSpacing: "-0.01em",
                            fontWeight: "700"
                        }],
                        "label-md": ["0.75rem", {
                            lineHeight: "1rem",
                            letterSpacing: "0.02em",
                            fontWeight: "600"
                        }],
                        "body-sm": ["0.8125rem", {
                            lineHeight: "1.25rem",
                            fontWeight: "400"
                        }],
                        "label-sm": ["0.6875rem", {
                            lineHeight: "0.875rem",
                            letterSpacing: "0.03em",
                            fontWeight: "600"
                        }]
                    }
                }
            }
        };
    </script>
    <style>
        @layer base {

            html,
            body {
                width: 100%;
                margin: 0;
                padding: 0;
                min-height: 100vh;
            }

            body {
                overscroll-behavior-y: none;
                -webkit-tap-highlight-color: transparent;
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
</head>

<body class="bg-surface font-body-md text-body-md text-on-surface antialiased flex flex-col min-h-screen">
    <header
        class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(38,51,40,0.04)] pt-safe">
        <div class="h-16 px-gutter flex items-center justify-between gap-space-md">
            <div class="flex items-center gap-space-sm"><img alt="logo.png" class="h-8 w-auto object-contain"
                    src="https://lh3.googleusercontent.com/aida/AEtjO1UIIXb3RkrDmnSaWIfJF4d-eqC2mZKdjzjlGxwhaHoGAoYpp6xOQbCKYBv0SucTPN1TRKmutF-e_HUaIyabT4oPnACpW7E6xOqvB0yBn4HRr3u5qqWqKMyr8y03tQkM0xOfH2biVBnJezoh1u1FdCVctc8hOlT0WNmy5qYLql5OeqMvhsSFCZL57LaVrqYW1JrleJpHP4X3wBb-kRCZW8dMZ0Y1x0_HtypP4o58d4Hmqjjr4T0cVKu6RRyF-wNY_xaM5EqRC41utA" />
                <div class="flex flex-col"><span
                        class="font-label-md text-label-md text-primary font-semibold leading-none">VetZen</span><span
                        class="font-label-sm text-label-sm text-on-surface-variant leading-tight">Clínica</span></div>
            </div>
            <div class="flex items-center justify-center flex-1"><span
                    class="font-headline-sm text-headline-sm text-on-surface text-center truncate">Inicio</span></div>
            <div class="flex items-center gap-space-xs"><button aria-label="Notificaciones"
                    class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container transition-colors"><span
                        class="material-symbols-outlined text-[22px]">notifications</span></button>
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span
                        class="material-symbols-outlined text-on-primary text-[18px]">person</span></div>
            </div>
        </div>
    </header>
    <main class="flex-1 w-full bg-surface pt-16 pb-24 px-margin flex flex-col">
        <div class="flex flex-col w-full pb-8 space-y-4">
            <!-- 1. Saludo cálido y selector de mascotas -->
            <section class="flex flex-col space-y-3 pt-1">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <h1
                            class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface tracking-tight font-bold">
                            Hola, Lisandro
                        </h1>
                        <p class="font-body-sm text-body-sm text-on-surface-variant font-medium">
                            Cuidado continuo de tus mascotas
                        </p>
                    </div>
                    <div
                        class="flex items-center space-x-1.5 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant text-label-sm font-label-sm">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span>Clínica Abierta</span>
                    </div>
                </div>
                <!-- Horizontal Quick Selector Pills -->
                <div class="flex items-center space-x-2 overflow-x-auto no-scrollbar py-0.5">
                    <!-- Pill activa: Rocky -->
                    <button
                        class="flex items-center space-x-2 px-3.5 py-2 rounded-full bg-primary text-on-primary shadow-sm min-h-[44px] shrink-0 font-label-md text-label-md transition-transform active:scale-95"
                        type="button">
                        <span class="w-2 h-2 rounded-full bg-secondary-container"></span>
                        <span>Rocky</span>
                        <span class="opacity-80 font-normal text-label-sm">· Ovejero</span>
                    </button>
                    <!-- Pill secundaria: Mia -->
                    <button
                        class="flex items-center space-x-2 px-3.5 py-2 rounded-full bg-surface-container-lowest text-on-surface hover:bg-surface-container min-h-[44px] shrink-0 font-label-md text-label-md shadow-sm transition-transform active:scale-95"
                        type="button">
                        <span class="material-symbols-outlined text-[18px] text-tertiary">cruelty_free</span>
                        <span>Mia</span>
                        <span class="text-on-surface-variant font-normal text-label-sm">· Felino</span>
                    </button>
                    <!-- Botón + Registrar -->
                    <button
                        class="flex items-center space-x-1.5 px-3 py-2 rounded-full bg-surface-container text-on-surface-variant hover:text-primary min-h-[44px] shrink-0 font-label-md text-label-md transition-colors active:scale-95"
                        type="button">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        <span>Registrar</span>
                    </button>
                </div>
            </section>
            <!-- 2. Card Destacada de la Mascota Protagonista (Rocky) -->
            <section
                class="bg-surface-container-lowest rounded-2xl p-4 shadow-sm flex flex-col space-y-4 relative overflow-hidden">
                <div class="flex items-start justify-between gap-3">
                    <div class="relative shrink-0">
                        <img alt="Rocky - Ovejero Alemán"
                            class="w-[68px] h-[68px] rounded-2xl object-cover shadow-inner"
                            src="https://lh3.googleusercontent.com/aida/AEtjO1VVx6iq42bmrkdrLiSdEfnFDoweifD9PMbFJ7Aum3pQamA_Y12ek-YUu7ELzmhYO7dKA_eenwS5iUGr1GmpexPHxxPw2MKZJtJ3D32M3qM0vBeEgrivzxu7R_Mq_WL50rrjTwouTllbyrDQufzWP59i9556Vjag3H44_Pydn4lKT-cXr4PBz-nM5Pm5D3zrmXKJw2QQ4vT8-athGozcM6_tADBDbb1fG7k7kh888-xilzhddx_3b2dl02ul" />
                        <span
                            class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-primary rounded-full flex items-center justify-center shadow">
                            <span class="w-2 h-2 bg-on-primary rounded-full"></span>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold truncate">Rocky</h2>
                            <span
                                class="px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed-variant text-label-sm font-label-sm shrink-0">
                                En atención activa
                            </span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">
                            Ovejero Alemán · 6 años · Macho · 32 kg
                        </p>
                        <div class="mt-2">
                            <a class="inline-flex items-center gap-1 font-label-md text-label-md text-primary font-semibold hover:underline"
                                href="#">
                                <span>Ver ficha completa</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Grilla compacta de 3 métricas de atención -->
                <div class="grid grid-cols-3 gap-2 pt-1">
                    <div class="flex flex-col p-2.5 rounded-xl bg-surface-container-low">
                        <div class="flex items-center text-primary mb-1">
                            <span class="material-symbols-outlined text-[18px]">vital_signs</span>
                        </div>
                        <span class="font-label-md text-label-md font-bold text-on-surface">2 en curso</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Tratamientos</span>
                    </div>
                    <div class="flex flex-col p-2.5 rounded-xl bg-surface-container-low">
                        <div class="flex items-center text-secondary mb-1">
                            <span class="material-symbols-outlined text-[18px]">event</span>
                        </div>
                        <span class="font-label-md text-label-md font-bold text-on-surface truncate">8 Oct ·
                            16:30</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Próxima sesión</span>
                    </div>
                    <div class="flex flex-col p-2.5 rounded-xl bg-surface-container-low">
                        <div class="flex items-center text-tertiary-container mb-1">
                            <span class="material-symbols-outlined text-[18px]">task_alt</span>
                        </div>
                        <span class="font-label-md text-label-md font-bold text-on-surface">4 de 6</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Completadas</span>
                    </div>
                </div>
            </section>
            <!-- 3. Card de Próxima Sesión (Prioridad visual número 1 de atención) -->
            <section
                class="bg-surface-container-low rounded-2xl p-4 shadow-sm flex flex-col space-y-3 relative overflow-hidden">
                <!-- Encabezado de la card -->
                <div class="flex items-center justify-between">
                    <span
                        class="px-2.5 py-1 rounded-full bg-surface-container-highest text-primary font-label-sm text-label-sm font-semibold tracking-wide">
                        PRÓXIMA SESIÓN
                    </span>
                    <span class="font-label-md text-label-md font-semibold text-on-surface-variant">
                        Sesión 5 de 6
                    </span>
                </div>
                <!-- Título del tratamiento -->
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold leading-snug">
                        Fisioterapia y Rehabilitación postoperatoria
                    </h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                        Recuperación tendinosa miembro posterior izquierdo
                    </p>
                </div>
                <!-- Horario destacado de alta visibilidad -->
                <div class="bg-surface-container-lowest rounded-xl p-3.5 flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-on-primary-fixed shrink-0">
                            <span class="material-symbols-outlined text-[22px]">calendar_clock</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-headline-sm text-headline-sm font-bold text-primary">
                                Mar 8 Oct · 16:30 hs
                            </span>
                            <span
                                class="font-label-sm text-label-sm text-secondary font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                Confirmada por clínica
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Detalles clave -->
                <div class="flex flex-col space-y-2 pt-0.5 text-on-surface-variant font-body-sm text-body-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary shrink-0">stethoscope</span>
                        <span class="truncate"><strong class="font-semibold text-on-surface">Dra. Valenzuela</strong>
                            (Medicina Física)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary shrink-0">location_on</span>
                        <span class="truncate">Sede Central · Sala Terapéutica 2</span>
                    </div>
                </div>
                <!-- Acciones táctiles ergonómicas (>=44px) -->
                <div class="flex items-center gap-2 pt-1">
                    <a class="flex-1 min-h-[44px] bg-primary text-on-primary rounded-xl font-label-lg text-label-lg font-semibold flex items-center justify-center gap-1.5 shadow-sm active:scale-[0.98] transition-transform"
                        href="#">
                        <span>Detalle de sesión</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                    <button aria-label="Cómo llegar a Sede Central"
                        class="w-11 h-11 bg-surface-container-lowest text-on-surface-variant rounded-xl flex items-center justify-center shadow-xs hover:bg-surface-container active:scale-95 transition-colors shrink-0"
                        type="button">
                        <span class="material-symbols-outlined text-[20px]">directions</span>
                    </button>
                </div>
            </section>
            <!-- 4. Tratamientos Activos (Columna simple para mobile) -->
            <section class="flex flex-col space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">
                        Tratamientos activos (2)
                    </h2>
                    <a class="font-label-md text-label-md text-primary font-semibold hover:underline" href="#">
                        Ver historial
                    </a>
                </div>
                <!-- Card 1 -->
                <div class="bg-surface-container-lowest rounded-2xl p-4 shadow-sm flex flex-col space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-label-lg text-label-lg text-on-surface font-bold">
                                Fisioterapia y Rehabilitación
                            </h3>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Paciente: Rocky</span>
                        </div>
                        <span
                            class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold shrink-0">
                            En curso
                        </span>
                    </div>
                    <!-- Barra de progreso al 67% -->
                    <div class="flex flex-col space-y-1.5">
                        <div
                            class="flex items-center justify-between font-label-sm text-label-sm text-on-surface-variant">
                            <span>Progreso asistencial</span>
                            <span class="font-bold text-on-surface">4 de 6 sesiones (67%)</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                            <div class="h-full bg-primary rounded-full" style="width: 67%;"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t-0 font-label-md text-label-md">
                        <span class="text-on-surface-variant">
                            Próxima: <strong class="text-on-surface font-semibold">8 Oct · 16:30 hs</strong>
                        </span>
                        <a class="text-primary font-semibold hover:underline inline-flex items-center gap-0.5"
                            href="#">
                            <span>Ver tratamiento</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-surface-container-lowest rounded-2xl p-4 shadow-sm flex flex-col space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-label-lg text-label-lg text-on-surface font-bold">
                                Plan Nutricional y Articular
                            </h3>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Paciente: Rocky</span>
                        </div>
                        <span
                            class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold shrink-0">
                            En curso
                        </span>
                    </div>
                    <!-- Barra de progreso al 50% -->
                    <div class="flex flex-col space-y-1.5">
                        <div
                            class="flex items-center justify-between font-label-sm text-label-sm text-on-surface-variant">
                            <span>Progreso de controles</span>
                            <span class="font-bold text-on-surface">2 de 4 controles (50%)</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                            <div class="h-full bg-secondary rounded-full" style="width: 50%;"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-1 font-label-md text-label-md">
                        <span class="text-on-surface-variant">
                            Próxima: <strong class="text-on-surface font-semibold">22 Oct · 10:00 hs</strong>
                        </span>
                        <a class="text-primary font-semibold hover:underline inline-flex items-center gap-0.5"
                            href="#">
                            <span>Ver tratamiento</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </section>
            <!-- 5. Solicitud Pendiente (Bloque compacto y cálido) -->
            <section class="bg-surface-container rounded-2xl p-4 shadow-sm flex flex-col space-y-2.5">
                <div class="flex items-center justify-between">
                    <span
                        class="px-2.5 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-label-sm font-semibold tracking-wide">
                        ESPERANDO CONFIRMACIÓN
                    </span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">
                        3 Oct
                    </span>
                </div>
                <div>
                    <h3 class="font-label-lg text-label-lg font-bold text-on-surface leading-snug">
                        Consulta de control odontológico preventivo
                    </h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                        Recibida para Rocky. Asignación de turno dentro de 24 hs hábiles.
                    </p>
                </div>
                <div class="pt-1">
                    <a class="inline-flex items-center gap-1 font-label-md text-label-md text-tertiary font-semibold hover:underline"
                        href="#">
                        <span>Ver solicitud</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </section>
            <!-- 6. Selector contextual de Otra Mascota (Mia) -->
            <section
                class="bg-surface-container-lowest rounded-2xl p-3.5 shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <img alt="Mia - Felino doméstico" class="w-11 h-11 rounded-xl object-cover shrink-0 shadow-inner"
                        src="https://lh3.googleusercontent.com/aida/AEtjO1XKCdrZuCb8hHcAEoE18UdSEojREFADMJHPVbFF5SdqLygxmfbg_7pTo9S0_mZW4mqvZYqyrhwex4BUaCgSv4KgX-P3rgn5_Nc9VyJdLBK5egHJhw92gs0qFeHAHZhDKA92V2ibyX6rMius5yUovuwp52rXQzdo6MKQAvZddmFw_Zdw_YWqmvpXLDiruSzMKJG7m7n0CgN_PpQ7SJsyTPEde0172L1TIohN1Dg948vpxoNj5SyTB0ft3_U" />
                    <div class="flex flex-col min-w-0">
                        <span class="font-label-lg text-label-lg font-bold text-on-surface truncate">Mia</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Felino doméstico · 3
                            años · Al día</span>
                    </div>
                </div>
                <button
                    class="min-h-[44px] px-3.5 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors active:scale-95 shrink-0 flex items-center gap-1.5"
                    type="button">
                    <span>Ver perfil</span>
                    <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                </button>
            </section>
            <!-- 7. Soporte / Centro de Contacto Rápido -->
            <section class="bg-surface-container-low rounded-2xl p-4 shadow-sm flex flex-col space-y-3">
                <div class="flex items-start gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0">
                        <span class="material-symbols-outlined text-[22px]">support_agent</span>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="font-label-lg text-label-lg font-bold text-on-surface">
                            ¿Dudas post-sesión?
                        </h4>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            Contactar guardia clínica veterinaria directamente
                        </p>
                    </div>
                </div>
                <a class="min-h-[44px] bg-primary text-on-primary rounded-xl font-label-lg text-label-lg font-semibold flex items-center justify-center gap-2 shadow-sm active:scale-[0.98] transition-transform"
                    href="https://wa.me/" rel="noopener noreferrer" target="_blank">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                    <span>WhatsApp Guardia VetZen</span>
                </a>
            </section>
        </div>
        <script>
            // Micro-interacción ligera para alternar pets de forma ágil y táctil
            document.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Feedback táctil suave adicional si es soportado
                    if (window.navigator && window.navigator.vibrate) {
                        window.navigator.vibrate(8);
                    }
                });
            });
        </script>
    </main>
    <nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(38,51,40,0.05)]"
        data-active-classes="text-primary font-semibold">
        <div class="flex justify-around items-center h-16 px-space-xs"><a aria-current="page"
                class="flex flex-col items-center justify-center min-w-[64px] h-12 transition-colors text-primary font-semibold"
                data-path="inicio" href="#"><span
                    class="material-symbols-outlined text-[24px]">home</span><span
                    class="font-label-sm text-label-sm mt-space-xs">Inicio</span></a><a
                class="flex flex-col items-center justify-center min-w-[64px] h-12 text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="mis-mascotas" href="#"><span
                    class="material-symbols-outlined text-[24px]">pets</span><span
                    class="font-label-sm text-label-sm mt-space-xs">Mis Mascotas</span></a><a
                class="flex flex-col items-center justify-center min-w-[64px] h-12 text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="tratamientos" href="#"><span
                    class="material-symbols-outlined text-[24px]">medication</span><span
                    class="font-label-sm text-label-sm mt-space-xs">Tratamientos</span></a><a
                class="flex flex-col items-center justify-center min-w-[64px] h-12 text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="solicitudes-servicios" href="#"><span
                    class="material-symbols-outlined text-[24px]">calendar_month</span><span
                    class="font-label-sm text-label-sm mt-space-xs">Solicitudes</span></a></div>
    </nav>
</body>

</html>
