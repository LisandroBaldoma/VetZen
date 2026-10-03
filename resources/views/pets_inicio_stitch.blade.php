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
                width: 100%;
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
                        "on-surface": "#121e14",
                        "on-tertiary-container": "#fffbff",
                        "surface-container-lowest": "#ffffff",
                        "error-container": "#ffdad6",
                        "tertiary": "#894d00",
                        "primary": "#206928",
                        "surface": "#eefeed",
                        "on-tertiary-fixed-variant": "#6b3b00",
                        "surface-tint": "#236c2b",
                        "primary-fixed-dim": "#8dd889",
                        "outline": "#707a6d",
                        "primary-fixed": "#a8f5a3",
                        "secondary-fixed-dim": "#a7d46f",
                        "on-tertiary-fixed": "#2d1600",
                        "on-secondary-fixed": "#102000",
                        "inverse-on-surface": "#e5f5e4",
                        "tertiary-container": "#ab630a",
                        "on-secondary-fixed-variant": "#2f4f00",
                        "tertiary-fixed": "#ffdcc0",
                        "surface-variant": "#d7e7d6",
                        "on-primary-container": "#f7fff1",
                        "surface-bright": "#eefeed",
                        "surface-container": "#e3f2e1",
                        "on-primary-fixed-variant": "#005315",
                        "on-secondary": "#ffffff",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff",
                        "surface-container-highest": "#d7e7d6",
                        "surface-container-high": "#ddecdc",
                        "on-primary-fixed": "#002204",
                        "secondary": "#43690d",
                        "surface-dim": "#cfdece",
                        "on-secondary-container": "#476d12",
                        "inverse-primary": "#8dd889",
                        "on-tertiary": "#ffffff",
                        "background": "#eefeed",
                        "secondary-container": "#bfee85",
                        "inverse-surface": "#263328",
                        "on-error-container": "#93000a",
                        "primary-container": "#3b833f",
                        "surface-container-low": "#e8f8e7",
                        "on-surface-variant": "#40493e",
                        "tertiary-fixed-dim": "#ffb875",
                        "outline-variant": "#c0c9bb",
                        "on-primary": "#ffffff",
                        "secondary-fixed": "#c2f188",
                        "on-background": "#121e14"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "margin-desktop": "2.5rem",
                        "margin": "1rem",
                        "margin-tablet": "1.5rem",
                        "gutter-desktop": "1.5rem",
                        "space-xl": "2.25rem",
                        "space-xs": "0.25rem",
                        "space-lg": "1.5rem",
                        "space-sm": "0.5rem",
                        "space-md": "1rem",
                        "gutter": "1.25rem"
                    },
                    fontFamily: {
                        "headline-sm": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"],
                        "label-sm": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "label-lg": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "body-sm": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "display-lg-mobile": ["Plus Jakarta Sans"]
                    },
                    fontSize: {
                        "headline-sm": ["1.125rem", {
                            lineHeight: "1.625rem",
                            fontWeight: "600"
                        }],
                        "body-lg": ["1.0625rem", {
                            lineHeight: "1.625rem",
                            fontWeight: "400"
                        }],
                        "label-sm": ["0.6875rem", {
                            lineHeight: "0.875rem",
                            letterSpacing: "0.03em",
                            fontWeight: "600"
                        }],
                        "headline-md": ["1.375rem", {
                            lineHeight: "1.875rem",
                            fontWeight: "600"
                        }],
                        "headline-lg": ["2rem", {
                            lineHeight: "2.5rem",
                            letterSpacing: "-0.015em",
                            fontWeight: "600"
                        }],
                        "body-md": ["0.9375rem", {
                            lineHeight: "1.5rem",
                            fontWeight: "400"
                        }],
                        "label-lg": ["0.875rem", {
                            lineHeight: "1.25rem",
                            letterSpacing: "0.01em",
                            fontWeight: "600"
                        }],
                        "display-lg": ["3rem", {
                            lineHeight: "3.5rem",
                            letterSpacing: "-0.02em",
                            fontWeight: "700"
                        }],
                        "body-sm": ["0.8125rem", {
                            lineHeight: "1.25rem",
                            fontWeight: "400"
                        }],
                        "headline-lg-mobile": ["1.5rem", {
                            lineHeight: "2rem",
                            fontWeight: "600"
                        }],
                        "label-md": ["0.75rem", {
                            lineHeight: "1rem",
                            letterSpacing: "0.02em",
                            fontWeight: "600"
                        }],
                        "display-lg-mobile": ["2rem", {
                            lineHeight: "2.5rem",
                            letterSpacing: "-0.01em",
                            fontWeight: "700"
                        }]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen flex flex-col">
    <header
        class="fixed top-0 w-full z-50 pt-safe bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(38,51,40,0.05)]">
        <div class="h-16 px-space-md flex items-center justify-between">
            <div class="flex items-center gap-space-sm"><img alt="logo.png" class="h-8 w-auto object-contain"
                    src="https://lh3.googleusercontent.com/aida/AEtjO1VjP_3hoVwfwHiJFhaIUTyYs32mg_WMzHpX3Ekr1k8FcxeUz2oRf2TDaH5Tdq6eT7CQP7FUp3ASd2hcXLkEr6tWkYy6N76C6t4Snhr3ddjqpNKhQnbex8cWLtjpfte1MNeuWLgYfdP5ssHUnuvhaK-puMIQA0bF3nLIbPOAJuG0iigD_U-xYGiOPDkMRfAzSbQX1I3GVVBqi856HGn3BmVEyZmSzXrcI9piXj3YIeMVTkWDMDCut0dGH_9n5lywUlMCXcISnPdMgA" />
                <div class="flex flex-col"><span
                        class="font-headline-sm text-headline-sm tracking-tight text-on-surface leading-none">VETZEN
                        CLÍNICA</span>
                    <div class="flex items-center gap-space-xs"><span
                            class="font-label-sm text-label-sm text-primary font-semibold tracking-wide uppercase">Pacientes</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-space-xs"><button aria-label="Notificaciones"
                    class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors"><span
                        class="material-symbols-outlined text-[22px]">notifications</span></button>
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span
                        class="material-symbols-outlined text-on-primary text-[18px]">person</span></div>
            </div>
        </div>
    </header>
    <main class="flex-1 w-full pt-16 pb-24 bg-surface">
        <div class="w-full max-w-lg mx-auto">
            <div class="flex flex-col w-full px-space-md py-space-md">
                <!-- Page Header -->
                <div class="flex flex-col mb-space-lg">
                    <div class="flex items-center justify-between">
                        <h1 class="font-headline-md text-headline-md text-on-surface">Pacientes</h1>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm">
                            <span class="w-2 h-2 rounded-full bg-outline-variant"></span>
                            0 registros
                        </span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                        Gestioná los pacientes y accedé a su información clínica.
                    </p>
                </div>
                <!-- Empty State Container -->
                <div
                    class="flex flex-col items-center justify-center bg-surface-container-lowest rounded-xl p-space-lg shadow-sm text-center">
                    <!-- Visual Badge & Icon Motif -->
                    <div class="relative mb-space-md">
                        <div
                            class="w-20 h-20 rounded-full bg-surface-container flex items-center justify-center text-primary shadow-inner">
                            <span class="material-symbols-outlined text-[38px]"
                                style="font-variation-settings: 'FILL' 1;">pets</span>
                        </div>
                        <div
                            class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                        </div>
                    </div>
                    <!-- Empty State Content -->
                    <h2 class="font-headline-sm text-headline-sm text-on-surface mb-space-xs">
                        No hay pacientes todavía
                    </h2>
                    <p
                        class="font-body-md text-body-md text-on-surface-variant max-w-[280px] mb-space-lg leading-relaxed">
                        Registrá el primer paciente para comenzar a gestionar su información clínica y su evolución
                        médica.
                    </p>
                    <!-- Primary CTA -->
                    <button
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs min-h-[44px] px-space-lg py-3 rounded-lg bg-primary text-on-primary font-label-lg text-label-lg shadow-sm hover:bg-primary-container active:scale-[0.98] transition-all"
                        type="button">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        <span>Nuevo paciente</span>
                    </button>
                </div>
                <!-- Warm Clinical Guidance Card -->
                <div
                    class="mt-space-md bg-surface-container-low rounded-xl p-space-md flex items-start gap-space-sm shadow-sm">
                    <div
                        class="w-8 h-8 rounded-lg bg-surface-container-high text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">verified_user</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-label-md text-label-md text-on-surface font-semibold">Organización clínica
                            centralizada</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 leading-snug">
                            Cada paciente registrado cuenta con su propio historial, fichas de consulta, planes de
                            vacunación y seguimiento de sesiones.
                        </p>
                    </div>
                </div>
                <!-- Quick Pre-registration Checklist -->
                <div class="mt-space-md grid grid-cols-2 gap-space-sm">
                    <div class="bg-surface-container-lowest rounded-xl p-space-md flex flex-col gap-1 shadow-sm">
                        <div
                            class="w-7 h-7 rounded-full bg-surface-container text-primary flex items-center justify-center mb-1">
                            <span class="material-symbols-outlined text-[16px]">badge</span>
                        </div>
                        <span class="font-label-md text-label-md text-on-surface font-semibold">Identificación</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Microchip, especie, raza y tutor
                            a cargo.</span>
                    </div>
                    <div class="bg-surface-container-lowest rounded-xl p-space-md flex flex-col gap-1 shadow-sm">
                        <div
                            class="w-7 h-7 rounded-full bg-surface-container text-primary flex items-center justify-center mb-1">
                            <span class="material-symbols-outlined text-[16px]">medical_information</span>
                        </div>
                        <span class="font-label-md text-label-md text-on-surface font-semibold">Anamnesis</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Alergias, patologías previas y
                            peso actual.</span>
                    </div>
                </div>
            </div>
            <script>
                // Ensure the "Pacientes" nav item reflects the active view state cleanly
                (function() {
                    const navLinks = document.querySelectorAll('nav a[data-path]');
                    navLinks.forEach(link => {
                        const isPacientes = link.getAttribute('data-path') === 'pacientes';
                        if (isPacientes) {
                            link.classList.remove('text-on-surface-variant');
                            link.classList.add('text-primary', 'font-semibold');
                            const icon = link.querySelector('.material-symbols-outlined');
                            if (icon) {
                                icon.style.fontVariationSettings = "'FILL' 1";
                            }
                        } else {
                            link.classList.remove('text-primary', 'font-semibold');
                            link.classList.add('text-on-surface-variant');
                            const icon = link.querySelector('.material-symbols-outlined');
                            if (icon) {
                                icon.style.fontVariationSettings = "'FILL' 0";
                            }
                        }
                    });
                })();
            </script>
        </div>
    </main>
    <nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface-container-lowest/95 backdrop-blur-xl shadow-[0_-2px_12px_rgba(38,51,40,0.06)]"
        data-active-classes="text-primary font-semibold">
        <div class="h-16 px-space-sm max-w-lg mx-auto flex items-center justify-around"><a
                class="flex flex-col items-center justify-center flex-1 h-full py-1 text-on-surface-variant hover:text-primary transition-colors"
                data-path="inicio" href="#"><span
                    class="material-symbols-outlined text-[24px]">dashboard</span><span
                    class="font-label-sm text-label-sm mt-0.5">Inicio</span></a><a aria-current="page"
                class="flex flex-col items-center justify-center flex-1 h-full py-1 transition-colors text-primary font-semibold"
                data-path="pacientes" href="#"><span
                    class="material-symbols-outlined text-[24px]">pets</span><span
                    class="font-label-sm text-label-sm mt-0.5">Pacientes</span></a><a
                class="flex flex-col items-center justify-center flex-1 h-full py-1 text-on-surface-variant hover:text-primary transition-colors"
                data-path="solicitudes" href="#"><span
                    class="material-symbols-outlined text-[24px]">event_note</span><span
                    class="font-label-sm text-label-sm mt-0.5">Solicitudes</span></a><a
                class="flex flex-col items-center justify-center flex-1 h-full py-1 text-on-surface-variant hover:text-primary transition-colors"
                data-path="gestion" href="#"><span
                    class="material-symbols-outlined text-[24px]">medical_services</span><span
                    class="font-label-sm text-label-sm mt-0.5">Gestión</span></a></div>
    </nav>
</body>

</html>
