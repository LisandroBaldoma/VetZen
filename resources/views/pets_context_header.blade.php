<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"
        name="viewport" />
    <meta content="mobile_tab" name="shell-type" />
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
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
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
                    src="https://lh3.googleusercontent.com/aida/AEtjO1VjP_3hoVwfwHiJFhaIUTyYs32mg_WMzHpX3Ekr1k8FcxeUz2oRf2TDaH5Tdq6eT7CQP7FUp3ASd2hcXLkEr6tWkYy6N76C6t4Snhr3ddjqpNKhQnbex8cWLtjpfte1MNeuWLgYfdP5ssHUnuvhaK-puMIQA0bF3nLIbPOAJuG0iigD_U-xYGiOPDkMRfAzSbQX1I3GVVBqi856HGn3BmVEyZmSzXrcI9piXj3YIeMVTkWDMDCut0dGH_9n5lywUlMCXcISnPdMgA" />
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
        <div class="flex flex-col w-full">
            <!-- Sub-barra superior contextual -->
            <div class="flex items-center justify-between px-space-md py-space-xs mb-space-xs">
                <a aria-label="Volver al listado de pacientes"
                    class="inline-flex items-center gap-1.5 h-11 px-2.5 rounded-full text-on-surface hover:bg-surface-container-high transition-colors active:scale-95"
                    href="#">
                    <span class="material-symbols-outlined text-[20px] text-primary">arrow_back</span>
                    <span class="font-label-lg text-label-lg text-on-surface">Pacientes</span>
                </a>
                <button aria-label="Más opciones del paciente"
                    class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high active:scale-95 transition-all"
                    type="button">
                    <span class="material-symbols-outlined text-[22px]">more_horiz</span>
                </button>
            </div>
            <div class="flex flex-col px-space-md space-y-space-md">
                <!-- PetContextHeader -->
                <section class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm">
                    <div class="flex items-start gap-3.5">
                        <div class="relative shrink-0">
                            <img class="w-16 h-16 rounded-full object-cover shadow-sm bg-surface-container"
                                data-alt="Close-up portrait of a friendly female Golden Retriever named Luna with honey-toned golden fur, warm expressive amber eyes, and soft natural indoor lighting inside a calm modern veterinary clinic."
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuASokA9nbAhXUzGtJjZKfsIq-LWvUW-OTjUyVMajSfYYN5g30_i_BltTN40XCVSWeuKPH4q58hI86XJwu1LL_5RZN80_zAU0I0VFU8jK9oA0fsxbqP0JIAZ-p4vyUc59cVCJcdq0DxT_ECXE6upRJ_WSedYY2FM4MGEjM1oy8-UhbhN1AVdwM3hV8VA5k3VcYN__TQwr16ZKNSgD3sgAlwiUcmOYkGtyWvMQgDnmVohMK_1veLgZ7Fx8Q" />
                            <span
                                class="absolute bottom-0 right-0 w-4 h-4 bg-primary rounded-full flex items-center justify-center shadow-xs">
                                <span class="material-symbols-outlined text-on-primary text-[10px]"
                                    style="font-variation-settings: 'FILL' 1;">pets</span>
                            </span>
                        </div>
                        <div class="flex flex-col min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1
                                    class="font-headline-md text-headline-md text-on-surface leading-tight tracking-tight">
                                    Luna</h1>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm">
                                    Canino
                                </span>
                            </div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Golden Retriever ·
                                Hembra</p>
                            <div
                                class="flex items-center gap-1.5 mt-2 font-body-sm text-body-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-[17px] text-outline">person</span>
                                <span class="truncate">Tutor/a: <span
                                        class="font-label-lg text-label-lg text-on-surface font-semibold">María
                                        González</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3.5 flex justify-end">
                        <button
                            class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-full bg-surface-container-low text-primary hover:bg-surface-container active:scale-95 transition-all shadow-xs"
                            type="button">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                            <span class="font-label-lg text-label-lg">Editar paciente</span>
                        </button>
                    </div>
                </section>
                <!-- PatientNavigation -->
                <nav aria-label="Secciones del paciente" class="relative w-full">
                    <div class="flex items-center gap-2 overflow-x-auto pb-1.5 scrollbar-none pr-8">
                        <!-- Tab Activo: Resumen -->
                        <button
                            class="inline-flex items-center gap-1.5 h-11 px-4 rounded-full bg-primary text-on-primary font-label-lg text-label-lg shrink-0 shadow-sm transition-transform active:scale-95"
                            type="button">
                            <span class="material-symbols-outlined text-[18px]"
                                style="font-variation-settings: 'FILL' 1;">grid_view</span>
                            <span>Resumen</span>
                        </button>
                        <!-- Tab Inactivo: Historia clínica -->
                        <button
                            class="inline-flex items-center gap-1.5 h-11 px-3.5 rounded-full bg-surface-container-lowest text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low font-label-lg text-label-lg shrink-0 transition-all active:scale-95"
                            type="button">
                            <span class="material-symbols-outlined text-[18px]">clinical_notes</span>
                            <span>Historia clínica</span>
                        </button>
                        <!-- Tab Inactivo: Solicitudes -->
                        <button
                            class="inline-flex items-center gap-1.5 h-11 px-3.5 rounded-full bg-surface-container-lowest text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low font-label-lg text-label-lg shrink-0 transition-all active:scale-95"
                            type="button">
                            <span class="material-symbols-outlined text-[18px]">assignment</span>
                            <span>Solicitudes</span>
                        </button>
                        <!-- Tab Inactivo: Tratamientos (con peek visual natural) -->
                        <button
                            class="inline-flex items-center gap-1.5 h-11 px-3.5 rounded-full bg-surface-container-lowest text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low font-label-lg text-label-lg shrink-0 transition-all active:scale-95"
                            type="button">
                            <span class="material-symbols-outlined text-[18px]">medication</span>
                            <span>Tratamientos</span>
                        </button>
                    </div>
                    <!-- Gradiente sutil indicador de scroll táctil horizontal -->
                    <div
                        class="pointer-events-none absolute right-0 top-0 bottom-1.5 w-6 bg-gradient-to-l from-surface to-transparent">
                    </div>
                </nav>
                <!-- Información General -->
                <section class="flex flex-col">
                    <div class="flex items-center gap-2 mb-2 px-1">
                        <span class="material-symbols-outlined text-[20px] text-primary">info</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">Información general</h2>
                    </div>
                    <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
                        <div class="flex items-center justify-between px-space-md py-3.5 bg-surface-container-lowest">
                            <span class="font-body-md text-body-md text-on-surface-variant">Especie</span>
                            <span class="font-label-lg text-label-lg text-on-surface">Canino</span>
                        </div>
                        <div class="flex items-center justify-between px-space-md py-3.5 bg-surface-container-low/40">
                            <span class="font-body-md text-body-md text-on-surface-variant">Raza</span>
                            <span class="font-label-lg text-label-lg text-on-surface">Golden Retriever</span>
                        </div>
                        <div class="flex items-center justify-between px-space-md py-3.5 bg-surface-container-lowest">
                            <span class="font-body-md text-body-md text-on-surface-variant">Sexo</span>
                            <span class="font-label-lg text-label-lg text-on-surface">Hembra</span>
                        </div>
                        <div class="flex items-center justify-between px-space-md py-3.5 bg-surface-container-low/40">
                            <span class="font-body-md text-body-md text-on-surface-variant">Fecha de nacimiento</span>
                            <div class="flex items-center gap-1.5 text-right">
                                <span class="font-label-lg text-label-lg text-on-surface">12 de marzo de 2022</span>
                                <span
                                    class="font-label-sm text-label-sm px-1.5 py-0.5 rounded bg-surface-container text-on-surface-variant">4
                                    años</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between px-space-md py-3.5 bg-surface-container-lowest">
                            <span class="font-body-md text-body-md text-on-surface-variant">Peso</span>
                            <span class="font-label-lg text-label-lg text-on-surface">28,4 kg</span>
                        </div>
                        <div class="flex items-center justify-between px-space-md py-3.5 bg-surface-container-low/40">
                            <span class="font-body-md text-body-md text-on-surface-variant">Color</span>
                            <span class="font-label-lg text-label-lg text-on-surface">Dorado</span>
                        </div>
                    </div>
                </section>
                <!-- Notas -->
                <section class="flex flex-col pb-2">
                    <div class="flex items-center gap-2 mb-2 px-1">
                        <span class="material-symbols-outlined text-[20px] text-primary">description</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">Notas</h2>
                    </div>
                    <div class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm">
                        <p class="font-body-md text-body-md text-on-surface leading-relaxed">
                            Paciente tranquilo durante la manipulación. Presenta sensibilidad al subir escalones y en
                            tren posterior durante días húmedos.
                        </p>
                        <div
                            class="mt-3.5 pt-3 flex items-center justify-between text-on-surface-variant bg-surface-container-low/50 px-3 py-2 rounded-lg">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-outline">lock</span>
                                <span class="font-label-sm text-label-sm">Campo general del paciente</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-outline">Modificable vía «Editar
                                paciente»</span>
                        </div>
                    </div>
                </section>
            </div>
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
