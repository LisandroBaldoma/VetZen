<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"
        name="viewport">
    <meta content="mobile_tab" name="shell-type">
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
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "background": "#eefeed",
                        "secondary-container": "#bfee85",
                        "surface-container-highest": "#d7e7d6",
                        "primary-fixed-dim": "#8dd889",
                        "tertiary-container": "#ab630a",
                        "on-surface": "#121e14",
                        "on-primary-fixed-variant": "#005315",
                        "on-tertiary-fixed-variant": "#6b3b00",
                        "on-surface-variant": "#40493e",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#e8f8e7",
                        "on-background": "#121e14",
                        "on-primary-container": "#f7fff1",
                        "surface-container": "#e3f2e1",
                        "surface-dim": "#cfdece",
                        "secondary-fixed": "#c2f188",
                        "on-error-container": "#93000a",
                        "error-container": "#ffdad6",
                        "primary-fixed": "#a8f5a3",
                        "on-primary-fixed": "#002204",
                        "on-secondary-container": "#476d12",
                        "on-error": "#ffffff",
                        "outline-variant": "#c0c9bb",
                        "on-secondary": "#ffffff",
                        "secondary-fixed-dim": "#a7d46f",
                        "primary-container": "#3b833f",
                        "on-secondary-fixed": "#102000",
                        "tertiary": "#894d00",
                        "surface-container-high": "#ddecdc",
                        "surface-variant": "#d7e7d6",
                        "surface-tint": "#236c2b",
                        "error": "#ba1a1a",
                        "on-tertiary": "#ffffff",
                        "surface-bright": "#eefeed",
                        "inverse-surface": "#263328",
                        "secondary": "#43690d",
                        "on-tertiary-container": "#fffbff",
                        "on-primary": "#ffffff",
                        "inverse-primary": "#8dd889",
                        "on-tertiary-fixed": "#2d1600",
                        "surface": "#eefeed",
                        "outline": "#707a6d",
                        "tertiary-fixed-dim": "#ffb875",
                        "on-secondary-fixed-variant": "#2f4f00",
                        "primary": "#206928",
                        "tertiary-fixed": "#ffdcc0",
                        "inverse-on-surface": "#e5f5e4"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "margin": "1rem",
                        "gutter-desktop": "1.5rem",
                        "margin-desktop": "2.5rem",
                        "space-xl": "2.25rem",
                        "margin-tablet": "1.5rem",
                        "space-md": "1rem",
                        "space-xs": "0.25rem",
                        "gutter": "1.25rem",
                        "space-lg": "1.5rem",
                        "space-sm": "0.5rem"
                    },
                    fontFamily: {
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "display-lg-mobile": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"],
                        "label-lg": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "label-sm": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "body-sm": ["Plus Jakarta Sans"]
                    },
                    fontSize: {
                        "headline-lg-mobile": ["1.5rem", {
                            "lineHeight": "2rem",
                            "fontWeight": "600"
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
                        "body-lg": ["1.0625rem", {
                            "lineHeight": "1.625rem",
                            "fontWeight": "400"
                        }],
                        "label-lg": ["0.875rem", {
                            "lineHeight": "1.25rem",
                            "letterSpacing": "0.01em",
                            "fontWeight": "600"
                        }],
                        "headline-md": ["1.375rem", {
                            "lineHeight": "1.875rem",
                            "fontWeight": "600"
                        }],
                        "label-sm": ["0.6875rem", {
                            "lineHeight": "0.875rem",
                            "letterSpacing": "0.03em",
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
                        "display-lg": ["3rem", {
                            "lineHeight": "3.5rem",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "headline-sm": ["1.125rem", {
                            "lineHeight": "1.625rem",
                            "fontWeight": "600"
                        }],
                        "body-sm": ["0.8125rem", {
                            "lineHeight": "1.25rem",
                            "fontWeight": "400"
                        }]
                    }
                }
            }
        };
    </script>
</head>

<body class="bg-surface font-body-md text-on-surface flex flex-col min-h-screen">
    <header class="fixed top-0 w-full z-50 pt-safe bg-surface/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="h-16 px-space-md flex items-center justify-between gap-space-sm">
            <div class="flex items-center gap-space-sm min-w-0"><img alt="logo.png" class="h-8 w-auto object-contain"
                    src="https://lh3.googleusercontent.com/aida/AEtjO1VjP_3hoVwfwHiJFhaIUTyYs32mg_WMzHpX3Ekr1k8FcxeUz2oRf2TDaH5Tdq6eT7CQP7FUp3ASd2hcXLkEr6tWkYy6N76C6t4Snhr3ddjqpNKhQnbex8cWLtjpfte1MNeuWLgYfdP5ssHUnuvhaK-puMIQA0bF3nLIbPOAJuG0iigD_U-xYGiOPDkMRfAzSbQX1I3GVVBqi856HGn3BmVEyZmSzXrcI9piXj3YIeMVTkWDMDCut0dGH_9n5lywUlMCXcISnPdMgA">
                <div class="flex flex-col min-w-0"><span
                        class="text-[15px] font-bold tracking-tight text-primary truncate leading-tight">VetZen
                        Clínica</span></div>
            </div>
            <div class="flex items-center gap-space-xs shrink-0"><button aria-label="Notificaciones"
                    class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors"><span
                        class="material-symbols-outlined text-[22px]">notifications</span></button>
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0"><span
                        class="material-symbols-outlined text-on-primary text-[18px]">person</span></div>
            </div>
        </div>
    </header>
    <main class="flex flex-col relative w-full pt-16 pb-24 bg-surface min-h-screen">
        <div class="flex flex-col w-full px-space-md py-space-sm gap-space-md">
            <!-- 1. BREADCRUMB / RETORNO -->
            <div class="flex items-center justify-between w-full">
                <a class="flex items-center gap-1.5 py-2 pr-3 text-on-surface-variant hover:text-primary transition-colors select-none"
                    href="#">
                    <span class="material-symbols-outlined text-[20px]">arrow_back_ios</span>
                    <span class="font-label-lg text-label-lg">Pacientes</span>
                </a>
                <button aria-label="Más opciones del paciente"
                    class="w-11 h-11 flex items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[22px]">more_vert</span>
                </button>
            </div>
            <!-- 2. PET CONTEXT HEADER -->
            <div
                class="w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex items-center justify-between gap-space-md">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div
                        class="w-14 h-14 rounded-full overflow-hidden bg-surface-container shrink-0 flex items-center justify-center">
                        <img class="w-full h-full object-cover"
                            data-alt="Close-up warm portrait of a healthy, friendly golden retriever looking gently into the camera with soft natural daylight, cream tones, and safe clinical boutique warmth"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAHfLFcQhLDzeQsi6s-ZOJjT8yXLvbq6xjPddqGnYF38eec-6B3P14fJUz2a82OlJQDK2mjP0cwYrnClsvK4-16gUaTQhlFR9RZixmM_ybiO_e9UPX339UbuPykfGJPxf4NfLRCwtUWxcgGFYfVIloY75AeSXeyaWpRZYT9mg70yz65ECOodFt3g8AJRWtjlaGrZBlnqZrwZdmM19NVg62CFbi63iiPXeVqQx-vmjwyD2YH_QTmUM9fHA">
                    </div>
                    <div class="flex flex-col min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-headline-sm text-headline-sm text-inverse-surface truncate">Luna</span>
                            <span
                                class="px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm shrink-0">Canino</span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant truncate">Golden Retriever · Hembra
                            · 4 años</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-0.5 truncate">Tutor/a: <span
                                class="font-semibold text-inverse-surface">María González</span></p>
                    </div>
                </div>
                <button aria-label="Editar información de Luna"
                    class="w-11 h-11 shrink-0 flex items-center justify-center rounded-xl bg-surface-container-low text-inverse-surface hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                </button>
            </div>
            <!-- 3. PATIENT NAVIGATION -->
            <div class="relative w-full -mx-space-md px-space-md overflow-x-auto no-scrollbar py-1">
                <div class="flex items-center gap-2 min-w-max pr-6"><a
                        class="px-4 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:bg-surface-container transition-colors"
                        href="#">Resumen</a><a
                        class="flex items-center gap-1.5 px-4 py-2 rounded-full bg-primary text-on-primary font-label-md text-label-md shadow-sm"
                        href="#"><span class="material-symbols-outlined text-[18px]">menu_book</span>Historia
                        clínica</a><a
                        class="px-4 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:bg-surface-container transition-colors"
                        href="#">Solicitudes</a><a
                        class="px-4 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:bg-surface-container transition-colors"
                        href="#">Tratamientos</a></div>
            </div>
            <!-- 4. PAGE CONTENT HEADER & CTA -->
            <div class="flex flex-col gap-space-sm pt-1">
                <div class="flex flex-col">
                    <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-inverse-surface">Historia clínica
                    </h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Evolución y registros clínicos del
                        paciente.</p>
                </div>
                <button
                    class="w-full h-11 flex items-center justify-center gap-2 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg shadow-sm hover:opacity-95 active:scale-[0.99] transition-all">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Nuevo registro
                </button>
            </div>
            <!-- 5. CLINICAL TIMELINE -->
            <div class="relative w-full flex flex-col pt-2">
                <!-- Continuous Timeline Stem -->
                <div class="absolute left-[13px] top-6 bottom-6 w-[2px] bg-outline-variant/50"></div>
                <!-- REGISTRO 1: Sesión -->
                <div class="relative flex items-start gap-3.5 pb-6">
                    <div
                        class="w-7 h-7 rounded-full bg-tertiary-fixed flex items-center justify-center shrink-0 z-10 shadow-sm mt-0.5">
                        <span class="material-symbols-outlined text-tertiary text-[15px]">spa</span></div>
                    <div class="flex-1 bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col gap-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2"><span
                                    class="font-label-sm text-label-sm text-on-surface-variant font-bold">28 SEP
                                    2026</span><span
                                    class="px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant font-label-sm text-label-sm">Sesión</span>
                            </div>
                        </div>
                        <h2 class="font-headline-sm text-headline-sm text-inverse-surface">Seguimiento de sesión</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Paciente tolera correctamente la
                            sesión terapéutica en cabina. Sin signos de dolor o resistencia durante el procedimiento
                            pasivo articular.</p>
                        <div class="flex items-center justify-between pt-1 mt-1"><button
                                class="h-10 px-3.5 rounded-lg bg-surface-container-low text-inverse-surface font-label-md text-label-md flex items-center gap-1 hover:bg-surface-container transition-colors">Ver
                                registro<span
                                    class="material-symbols-outlined text-[16px]">arrow_forward</span></button><button
                                aria-label="Editar registro 28 SEP"
                                class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant/70 hover:text-inverse-surface hover:bg-surface-container transition-colors"><span
                                    class="material-symbols-outlined text-[18px]">edit</span></button></div>
                    </div>
                </div>
                <!-- REGISTRO 2: Evolución -->
                <div class="relative flex items-start gap-3.5 pb-6">
                    <div
                        class="w-7 h-7 rounded-full bg-secondary-fixed flex items-center justify-center shrink-0 z-10 shadow-sm mt-0.5">
                        <span class="material-symbols-outlined text-secondary text-[15px]">trending_up</span></div>
                    <div class="flex-1 bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col gap-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2"><span
                                    class="font-label-sm text-label-sm text-on-surface-variant font-bold">21 SEP
                                    2026</span><span
                                    class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm">Evolución</span>
                            </div>
                        </div>
                        <h2 class="font-headline-sm text-headline-sm text-inverse-surface">Control de evolución</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Se observa mejor tolerancia al
                            movimiento en tren posterior y respuesta favorable al plan inicial. Caminata fluida al
                            ingreso.</p>
                        <div class="flex items-center justify-between pt-1 mt-1"><button
                                class="h-10 px-3.5 rounded-lg bg-surface-container-low text-inverse-surface font-label-md text-label-md flex items-center gap-1 hover:bg-surface-container transition-colors">Ver
                                registro<span
                                    class="material-symbols-outlined text-[16px]">arrow_forward</span></button><button
                                aria-label="Editar registro 21 SEP"
                                class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant/70 hover:text-inverse-surface hover:bg-surface-container transition-colors"><span
                                    class="material-symbols-outlined text-[18px]">edit</span></button></div>
                    </div>
                </div>
                <!-- REGISTRO 3: Evaluación (Truncado interactivo) -->
                <div class="relative flex items-start gap-3.5 pb-6">
                    <div
                        class="w-7 h-7 rounded-full bg-error-container flex items-center justify-center shrink-0 z-10 shadow-sm mt-0.5">
                        <span class="material-symbols-outlined text-error text-[15px]">assignment</span></div>
                    <div
                        class="flex-1 bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col gap-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2"><span
                                    class="font-label-sm text-label-sm text-on-surface-variant font-bold">14 SEP
                                    2026</span><span
                                    class="px-2 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm">Evaluación</span>
                            </div>
                        </div>
                        <h2 class="font-headline-sm text-headline-sm text-inverse-surface">Evaluación inicial</h2>
                        <div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">Paciente ingresa
                                con sensibilidad marcada en tren posterior y disminución leve del rango de movimiento
                                coxofemoral. Responde dócilmente a estímulos vocales calmos. Se pauta esquema de manejo
                                articular y pautas de descanso activo en domicilio.</p>
                        </div>
                        <div class="flex items-center justify-between pt-1 mt-1"><button
                                class="h-10 px-3.5 rounded-lg bg-surface-container-low text-inverse-surface font-label-md text-label-md flex items-center gap-1 hover:bg-surface-container transition-colors">Ver
                                registro<span
                                    class="material-symbols-outlined text-[16px]">arrow_forward</span></button><button
                                aria-label="Editar registro 14 SEP"
                                class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant/70 hover:text-inverse-surface hover:bg-surface-container transition-colors"><span
                                    class="material-symbols-outlined text-[18px]">edit</span></button></div>
                    </div>
                </div>
                <!-- REGISTRO 4: Consulta -->
                <div class="relative flex items-start gap-3.5">
                    <div
                        class="w-7 h-7 rounded-full bg-surface-container-highest flex items-center justify-center shrink-0 z-10 shadow-sm mt-0.5">
                        <span class="material-symbols-outlined text-primary text-[15px]">medical_services</span></div>
                    <div
                        class="flex-1 bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col gap-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2"><span
                                    class="font-label-sm text-label-sm text-on-surface-variant font-bold">02 SEP
                                    2026</span><span
                                    class="px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm">Consulta</span>
                            </div>
                        </div>
                        <h2 class="font-headline-sm text-headline-sm text-inverse-surface">Consulta por rigidez
                            matutina</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Tutor refiere dificultad para
                            levantarse en días fríos o húmedos. Examen general normotenso, reflejos conservados.</p>
                        <div class="flex items-center justify-between pt-1 mt-1"><button
                                class="h-10 px-3.5 rounded-lg bg-surface-container-low text-inverse-surface font-label-md text-label-md flex items-center gap-1 hover:bg-surface-container transition-colors">Ver
                                registro<span
                                    class="material-symbols-outlined text-[16px]">arrow_forward</span></button><button
                                aria-label="Editar registro 02 SEP"
                                class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant/70 hover:text-inverse-surface hover:bg-surface-container transition-colors"><span
                                    class="material-symbols-outlined text-[18px]">edit</span></button></div>
                    </div>
                </div>
            </div>
            <!-- 6. VARIANTE NORMATIVA: EMPTY STATE DEMO -->

        </div>
    </main>
    <nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/85 backdrop-blur-xl shadow-[0_-2px_12px_rgba(38,51,40,0.06)]"
        data-active-classes="text-primary font-bold">
        <div class="flex justify-around items-center h-16 px-space-xs">
            <a class="flex flex-col items-center justify-center min-w-[64px] h-12 gap-0.5 text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="inicio" href="#">
                <span class="material-symbols-outlined text-[24px]">dashboard</span>
                <span class="text-xs font-semibold">Inicio</span>
            </a>
            <a class="flex flex-col items-center justify-center min-w-[64px] h-12 gap-0.5 text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="pacientes" href="#">
                <span class="material-symbols-outlined text-[24px]"
                    style="font-variation-settings: 'FILL' 1;">pets</span>
                <span class="text-xs font-bold">Pacientes</span>
            </a>
            <a class="flex flex-col items-center justify-center min-w-[64px] h-12 gap-0.5 text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="solicitudes" href="#">
                <span class="material-symbols-outlined text-[24px]">assignment</span>
                <span class="text-xs font-semibold">Solicitudes</span>
            </a>
            <a class="flex flex-col items-center justify-center min-w-[64px] h-12 gap-0.5 text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="menu-gestion" href="#">
                <span class="material-symbols-outlined text-[24px]">admin_panel_settings</span>
                <span class="text-xs font-semibold">Gestión</span>
            </a>
        </div>
    </nav>


</body>

</html>
