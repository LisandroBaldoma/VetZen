<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport" />
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
    <header class="fixed top-0 w-full z-50 pt-safe bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(38,51,40,0.05)]">
        <div class="h-16 px-space-md flex items-center justify-between">
            <div class="flex items-center gap-space-sm"><img alt="logo.png" class="h-8 w-auto object-contain" src="https://lh3.googleusercontent.com/aida/AEtjO1VjP_3hoVwfwHiJFhaIUTyYs32mg_WMzHpX3Ekr1k8FcxeUz2oRf2TDaH5Tdq6eT7CQP7FUp3ASd2hcXLkEr6tWkYy6N76C6t4Snhr3ddjqpNKhQnbex8cWLtjpfte1MNeuWLgYfdP5ssHUnuvhaK-puMIQA0bF3nLIbPOAJuG0iigD_U-xYGiOPDkMRfAzSbQX1I3GVVBqi856HGn3BmVEyZmSzXrcI9piXj3YIeMVTkWDMDCut0dGH_9n5lywUlMCXcISnPdMgA" />
                <div class="flex flex-col"><span class="font-headline-sm text-headline-sm tracking-tight text-on-surface leading-none">VETZEN CLÍNICA</span>
                    <div class="flex items-center gap-space-xs"><span class="font-label-sm text-label-sm text-primary font-semibold tracking-wide uppercase">Pacientes</span></div>
                </div>
            </div>
            <div class="flex items-center gap-space-xs"><button aria-label="Notificaciones" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors"><span class="material-symbols-outlined text-[22px]">notifications</span></button>
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div>
            </div>
        </div>
    </header>
    <main class="flex-1 w-full pt-16 pb-24 bg-surface">
        <div class="w-full max-w-lg mx-auto">
            <div class="flex flex-col w-full px-space-md py-space-sm space-y-space-md">
                <div class="flex flex-col space-y-space-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight">Pacientes</h1>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Gestioná los pacientes y accedé a su información clínica.</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm">
                            6 registrados
                        </span>
                    </div>
                    <button class="w-full bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg rounded-xl py-3 px-4 shadow-sm min-h-[44px] flex items-center justify-center gap-space-xs transition-colors active:scale-[0.99]" type="button">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        <span>Nuevo paciente</span>
                    </button>
                </div>
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[20px] pointer-events-none">search</span>
                    <input class="w-full bg-surface-container-lowest text-on-surface placeholder:text-outline font-body-md text-body-md pl-10 pr-4 py-2.5 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 min-h-[44px]" id="patientSearchInput" placeholder="Buscar paciente por nombre o tutor..." type="text" />
                </div>
                <div class="flex flex-col space-y-space-sm" id="patientListContainer">
                    <!-- Luna -->
                    <div class="patient-card w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col space-y-space-sm transition-all" data-name="Luna" data-tutor="María González">
                        <div class="flex items-start gap-space-sm">
                            <img alt="Luna" class="w-[52px] h-[52px] rounded-full object-cover shrink-0 shadow-sm" data-alt="A gentle Golden Retriever dog sitting calmly looking into camera with warm natural daylight, expressive friendly brown eyes, golden fur, warm neutral clinic background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDuStuoIrbC9iCDiISDqO8XKp2V8x0ihXN-nHxp5tu3x1YQINk-mOk16ejFuBRfHVs88RIw9eBBsILbSkzV8CmKIYtvfvFd4U7DBw7YotxmS8O_D1Q6Z1sLfMptHCoOXTL-tx22lfP9Dj-8KoP2Z4EcDK4UlvT2htV-csJ9EdgZH07eZW1xeGGD1a1AHXRc6YgDGwaQVnsPcu8p0zBqiMgojmP0TbtCLM_gNf5cZ9MtVOuyxITywem6YA" />
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h2 class="font-headline-sm text-headline-sm text-on-surface truncate">Luna</h2>
                                    <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant shrink-0">
                                        Hembra
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant font-medium truncate">
                                    Canino · Golden Retriever
                                </p>
                                <div class="flex items-center gap-1 mt-0.5 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[15px] text-outline shrink-0">person</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Tutor: María González</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-space-xs pt-1">
                            <button aria-label="Editar datos de Luna" class="min-h-[44px] min-w-[44px] flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors" type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <button class="min-h-[44px] px-3.5 py-2 bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md rounded-lg flex items-center gap-1.5 transition-colors" type="button">
                                <span>Ver ficha</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- Milo -->
                    <div class="patient-card w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col space-y-space-sm transition-all" data-name="Milo" data-tutor="Carlos Fernández">
                        <div class="flex items-start gap-space-sm">
                            <img alt="Milo" class="w-[52px] h-[52px] rounded-full object-cover shrink-0 shadow-sm" data-alt="A handsome european shorthair tabby cat with bright green eyes and neat brown striped coat, calm veterinary setting, soft daylight." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC87eddMq5_6J78y6GkwGEMLoIOIup4Js5uBH80UHWZ6mgDHtmbNVhua_GWJIuhW_TfwWZpTqzEoCbKdemrx8Br-M03vyDj54RFEO1WmpA9IcR1k5paqX-e_3IGUUhGGZ0goVWSbz8MM6gbiZik2ryigXDLFM_fu1iIVHmSiFKBgKb6Zep6QsmxIqE7dZormzjLqQ5bldK7SM8rIUbwMHymYfdTxFuV8bwPiFnxt-JXFK3rmgV7_xx-vQ" />
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h2 class="font-headline-sm text-headline-sm text-on-surface truncate">Milo</h2>
                                    <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant shrink-0">
                                        Macho
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant font-medium truncate">
                                    Felino · Mestizo
                                </p>
                                <div class="flex items-center gap-1 mt-0.5 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[15px] text-outline shrink-0">person</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Tutor: Carlos Fernández</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-space-xs pt-1">
                            <button aria-label="Editar datos de Milo" class="min-h-[44px] min-w-[44px] flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors" type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <button class="min-h-[44px] px-3.5 py-2 bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md rounded-lg flex items-center gap-1.5 transition-colors" type="button">
                                <span>Ver ficha</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- Kira -->
                    <div class="patient-card w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col space-y-space-sm transition-all" data-name="Kira" data-tutor="Sofía Martínez">
                        <div class="flex items-start gap-space-sm">
                            <img alt="Kira" class="w-[52px] h-[52px] rounded-full object-cover shrink-0 shadow-sm" data-alt="An attentive black and white Border Collie dog with alert ears and warm amber eyes sitting gracefully in a bright veterinary clinic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDoAHf4cQ12uxSQZH2q0UxSE8bsqLdYmziVtgU4zYQuPAuIM4Z-GsQIrcPircDN4xOBMceK0zG7-Q1P5kcHow7JWgCd-40zwdRTF-Xr-e-KDhesvLo7_tg8mnVAd5E9-WdM07C5UwWEoijxSmWAL1ipdCCgu6Gpc8DlAeL6MRidLNVGt_NzJxDJZjPgaqxR0uggQcz0WQKoIdnbr68BZZ8TrwjS8dlX5rp9jyTdUu4lbAObLp3VRTUt_Q" />
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h2 class="font-headline-sm text-headline-sm text-on-surface truncate">Kira</h2>
                                    <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant shrink-0">
                                        Hembra
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant font-medium truncate">
                                    Canino · Border Collie
                                </p>
                                <div class="flex items-center gap-1 mt-0.5 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[15px] text-outline shrink-0">person</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Tutor: Sofía Martínez</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-space-xs pt-1">
                            <button aria-label="Editar datos de Kira" class="min-h-[44px] min-w-[44px] flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors" type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <button class="min-h-[44px] px-3.5 py-2 bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md rounded-lg flex items-center gap-1.5 transition-colors" type="button">
                                <span>Ver ficha</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- Thor -->
                    <div class="patient-card w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col space-y-space-sm transition-all" data-name="Thor" data-tutor="Martín López">
                        <div class="flex items-start gap-space-sm">
                            <img alt="Thor" class="w-[52px] h-[52px] rounded-full object-cover shrink-0 shadow-sm" data-alt="A noble German Shepherd dog with calm intelligent gaze, rich tan and black coat, poised in soft indoor lighting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAypQmIHWzqp6mrB8wFfPN0MLPGx1Dj-_7cjNuXWFGqqDY4JgjrU7ZTrCoB-fCgeDlUxjbrazNl2NlNYGKABcAupGDxAxr8F6wBLV2iqrfeZgQhxDvf-CQ8lpMjH1vF69xHeTZvrbHBXDn5URNTukSgVDvah_LqL0rmdUFUq_RAURJIcI0k9AklTVQ-nWhQpDNRN9O2OyFVoFGLEQiB09CIh_KSnCvVW9RqpO2Fo2fO2ERvuA7rLGp_sA" />
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h2 class="font-headline-sm text-headline-sm text-on-surface truncate">Thor</h2>
                                    <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant shrink-0">
                                        Macho
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant font-medium truncate">
                                    Canino · Ovejero Alemán
                                </p>
                                <div class="flex items-center gap-1 mt-0.5 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[15px] text-outline shrink-0">person</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Tutor: Martín López</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-space-xs pt-1">
                            <button aria-label="Editar datos de Thor" class="min-h-[44px] min-w-[44px] flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors" type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <button class="min-h-[44px] px-3.5 py-2 bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md rounded-lg flex items-center gap-1.5 transition-colors" type="button">
                                <span>Ver ficha</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- Bastián (Neutral Silhouette / Paw Placeholder) -->
                    <div class="patient-card w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col space-y-space-sm transition-all" data-name="Bastián" data-tutor="Valentina Silva">
                        <div class="flex items-start gap-space-sm">
                            <div class="w-[52px] h-[52px] rounded-full bg-surface-container-high flex items-center justify-center shrink-0 shadow-sm text-outline">
                                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">pets</span>
                            </div>
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h2 class="font-headline-sm text-headline-sm text-on-surface truncate">Bastián</h2>
                                    <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant shrink-0">
                                        Macho
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant font-medium truncate">
                                    Canino · Mestizo
                                </p>
                                <div class="flex items-center gap-1 mt-0.5 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[15px] text-outline shrink-0">person</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Tutor: Valentina Silva</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-space-xs pt-1">
                            <button aria-label="Editar datos de Bastián" class="min-h-[44px] min-w-[44px] flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors" type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <button class="min-h-[44px] px-3.5 py-2 bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md rounded-lg flex items-center gap-1.5 transition-colors" type="button">
                                <span>Ver ficha</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    <!-- Olivia -->
                    <div class="patient-card w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col space-y-space-sm transition-all" data-name="Olivia" data-tutor="Fernando Gómez">
                        <div class="flex items-start gap-space-sm">
                            <img alt="Olivia" class="w-[52px] h-[52px] rounded-full object-cover shrink-0 shadow-sm" data-alt="A graceful Siamese cat with deep blue almond eyes, creamy coat with dark brown points on ears and face, relaxed and content in a serene clinic space." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBmUiodFC_GTyBhcqz0uC1xeC2h661TG7-dFVPH6pap7Zq2UbKCJBmdWMOscT2LSefRG5as-_7-7bQdfTQ3o3ksc-hhMUrVrs8pEcInX3YBDuy1B0cJAWOFZy7sm1N3GG48DNOa0vEQDRe9E3G322sHVxXRs5fWZcbcaAcwSgdkVt546hq_EqXeomG3VLav5ps84SI3MgrbGg4bZheP7lY6ctZ-JgnUbI3cyZTPiatzl71ZPmdjZ6TNnw" />
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h2 class="font-headline-sm text-headline-sm text-on-surface truncate">Olivia</h2>
                                    <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant shrink-0">
                                        Hembra
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant font-medium truncate">
                                    Felino · Siamés
                                </p>
                                <div class="flex items-center gap-1 mt-0.5 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[15px] text-outline shrink-0">person</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Tutor: Fernando Gómez</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-space-xs pt-1">
                            <button aria-label="Editar datos de Olivia" class="min-h-[44px] min-w-[44px] flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors" type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <button class="min-h-[44px] px-3.5 py-2 bg-surface-container-low hover:bg-surface-container text-on-surface font-label-md text-label-md rounded-lg flex items-center gap-1.5 transition-colors" type="button">
                                <span>Ver ficha</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="hidden flex-col items-center justify-center py-space-xl text-center" id="noResultsState">
                    <div class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center text-outline mb-space-sm">
                        <span class="material-symbols-outlined text-[26px]">search_off</span>
                    </div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">No se encontraron pacientes</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Revisá el nombre o tutor ingresado.</p>
                </div>
            </div>
            <script>
                (function() {
                    const searchInput = document.getElementById('patientSearchInput');
                    const cards = document.querySelectorAll('.patient-card');
                    const noResults = document.getElementById('noResultsState');

                    if (searchInput) {
                        searchInput.addEventListener('input', function(e) {
                            const query = e.target.value.toLowerCase().trim();
                            let visibleCount = 0;

                            cards.forEach(card => {
                                const name = (card.getAttribute('data-name') || '').toLowerCase();
                                const tutor = (card.getAttribute('data-tutor') || '').toLowerCase();

                                if (name.includes(query) || tutor.includes(query)) {
                                    card.classList.remove('hidden');
                                    visibleCount++;
                                } else {
                                    card.classList.add('hidden');
                                }
                            });

                            if (visibleCount === 0) {
                                noResults.classList.remove('hidden');
                                noResults.classList.add('flex');
                            } else {
                                noResults.classList.add('hidden');
                                noResults.classList.remove('flex');
                            }
                        });
                    }
                })();
            </script>
        </div>
    </main>
    <nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface-container-lowest/95 backdrop-blur-xl shadow-[0_-2px_12px_rgba(38,51,40,0.06)]" data-active-classes="text-primary font-semibold">
        <div class="h-16 px-space-sm max-w-lg mx-auto flex items-center justify-around"><a class="flex flex-col items-center justify-center flex-1 h-full py-1 text-on-surface-variant hover:text-primary transition-colors" data-path="inicio" href="#"><span class="material-symbols-outlined text-[24px]">dashboard</span><span class="font-label-sm text-label-sm mt-0.5">Inicio</span></a><a aria-current="page" class="flex flex-col items-center justify-center flex-1 h-full py-1 transition-colors text-primary font-semibold" data-path="pacientes" href="#"><span class="material-symbols-outlined text-[24px]">pets</span><span class="font-label-sm text-label-sm mt-0.5">Pacientes</span></a><a class="flex flex-col items-center justify-center flex-1 h-full py-1 text-on-surface-variant hover:text-primary transition-colors" data-path="solicitudes" href="#"><span class="material-symbols-outlined text-[24px]">event_note</span><span class="font-label-sm text-label-sm mt-0.5">Solicitudes</span></a><a class="flex flex-col items-center justify-center flex-1 h-full py-1 text-on-surface-variant hover:text-primary transition-colors" data-path="gestion" href="#"><span class="material-symbols-outlined text-[24px]">medical_services</span><span class="font-label-sm text-label-sm mt-0.5">Gestión</span></a></div>
    </nav>
</body>

</html>