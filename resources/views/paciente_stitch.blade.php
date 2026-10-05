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
        <div class="flex flex-col w-full">
            <!-- Interactive View Controller Wrapper -->
            <div class="flex flex-col w-full px-space-md py-space-sm space-y-space-md" id="pacientes-view-container">
                <!-- 1. Page Header -->
                <header class="flex flex-col space-y-space-sm">
                    <div class="flex items-start justify-between gap-space-sm">
                        <div class="flex flex-col min-w-0">
                            <h2 class="text-headline-lg-mobile font-headline-lg-mobile text-on-surface">Pacientes</h2>
                            <p class="text-body-md font-body-md text-on-surface-variant leading-snug mt-0.5">
                                Gestioná los pacientes y accedé a su información clínica.
                            </p>
                        </div>
                    </div>
                    <!-- Action CTA -->
                    <button
                        class="w-full h-11 bg-primary hover:bg-primary-container active:scale-[0.99] text-on-primary font-label-lg text-label-lg rounded-lg flex items-center justify-center gap-space-xs shadow-sm transition-all duration-150"
                        type="button">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        <span class="">+ Nuevo paciente</span>
                    </button>
                </header>
                <!-- 2. Search Input Mobile-First & Count -->
                <div class="flex flex-col space-y-space-xs">
                    <div class="relative w-full flex items-center bg-surface-container-lowest rounded-lg shadow-sm">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px]">search</span>
                        </div>
                        <input
                            class="w-full h-11 pl-10 pr-10 bg-transparent text-body-md font-body-md text-on-surface placeholder:text-outline focus:outline-none"
                            id="patient-search-input" placeholder="Buscar paciente..." type="text">
                        <button aria-label="Limpiar búsqueda"
                            class="absolute right-2.5 w-7 h-7 flex items-center justify-center rounded-full text-outline hover:text-on-surface hover:bg-surface-container-low transition-colors hidden"
                            id="search-clear-btn" type="button">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>
                    <div class="flex items-center justify-between px-1"><span
                            class="text-sm font-medium text-on-surface-variant" id="patient-count-badge">6 pacientes
                            registrados</span><span class="text-xs text-outline font-medium">Ordenado por
                            recientes</span></div>
                </div>
                <!-- 3. Patient List (PatientList Container) -->
                <div class="flex flex-col space-y-space-sm w-full" id="patient-list">
                    <article
                        class="patient-card bg-[#FFFFFF] rounded-2xl p-3.5 border border-[#EAE4D9] shadow-sm flex flex-col space-y-3 cursor-pointer hover:shadow-md transition-all active:scale-[0.99]"
                        data-patient-name="Luna">
                        <div class="flex items-center gap-3">
                            <div
                                class="relative w-[52px] h-[52px] rounded-xl overflow-hidden shrink-0 bg-surface-container-low border border-[#EAE4D9]/60">
                                <img alt="Foto de Luna" class="w-full h-full object-cover"
                                    data-alt="Close-up portrait of a cheerful Golden Retriever dog with shiny golden fur looking directly at the camera with gentle warm eyes, soft warm daylight, high quality veterinary care pet photography."
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9iXQnimQ3hvmG0-pmWrvN8hnPi4ziBmbwgHXZH-RSU_aBqrNiWh43C_wHkm9CkOuohHfyyRWzFawOcC4fxdX-bdnRSNbGUVBjGgER969JIDq2i7G77VpjVm4jAaq20QL0wNz4fjcVNwNCeaCtgleFH38gAGxVeXOhTQMkEV0iQS5bctLZE1upThWyFMPiA6KZMwasWUOZ2l1O1IyMvjK1LkncMMAqe8PtNuqTFEGNaOUTb9OKfbwF1g">
                            </div>
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h3 class="text-base font-bold text-on-surface truncate">Luna</h3>
                                    <span
                                        class="text-xs px-2.5 py-0.5 rounded-full bg-surface-container-low text-secondary font-semibold">Canino</span>
                                </div>
                                <p class="text-sm font-medium text-on-surface-variant truncate mt-0.5">Golden Retriever
                                </p>
                                <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mt-1">
                                    <span class="text-outline font-medium">Responsable:</span>
                                    <span class="text-sm font-semibold text-on-surface truncate">María González</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-1 border-t border-[#EAE4D9]/60">
                            <button
                                class="flex-1 h-11 px-3.5 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-[#263328] font-semibold text-sm flex items-center justify-center gap-1.5 transition-colors"
                                type="button">
                                <span class="">Ver ficha</span>
                                <span class="material-symbols-outlined text-[18px] text-primary">chevron_right</span>
                            </button>
                            <button aria-label="Editar Luna"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors"
                                type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                        </div>
                    </article>

                    <article
                        class="patient-card bg-[#FFFFFF] rounded-2xl p-3.5 border border-[#EAE4D9] shadow-sm flex flex-col space-y-3 cursor-pointer hover:shadow-md transition-all active:scale-[0.99]"
                        data-patient-name="Milo">
                        <div class="flex items-center gap-3">
                            <div
                                class="relative w-[52px] h-[52px] rounded-xl overflow-hidden shrink-0 bg-surface-container-low border border-[#EAE4D9]/60">
                                <img alt="Foto de Milo" class="w-full h-full object-cover"
                                    data-alt="Warm studio photo of a calm European Shorthair tabby cat with green eyes and clean striped gray and brown fur, sitting peacefully on a light natural surface, veterinary clinic setting."
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDdvJRG6FOeownlYWcsHxpOpqW62O5pJ9gUakLO8QG3ee53VV-nPV3RhicFmQDjYThtFK1LjM8EFEDVdRDmxEXTQysuW03-Fh8TMqqQ8AIKyM0SLdrLVGGgT0_2WgXShQIp0bRXQBt3L7HnIkBZoezHsh6SMC421vq9XV1L7W-LhgdX_ZVOcoHiMzmClL3C5hhKJoP5-67LS7JExoIYYw2uQnWzKmYk4SBN6EeZDm_xgEwBTbcxd0q-VQ">
                            </div>
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h3 class="text-base font-bold text-on-surface truncate">Milo</h3>
                                    <span
                                        class="text-xs px-2.5 py-0.5 rounded-full bg-surface-container-low text-secondary font-semibold">Felino</span>
                                </div>
                                <p class="text-sm font-medium text-on-surface-variant truncate mt-0.5">Gato Europeo</p>
                                <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mt-1">
                                    <span class="text-outline font-medium">Responsable:</span>
                                    <span class="text-sm font-semibold text-on-surface truncate">Carlos Bianchi</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-1 border-t border-[#EAE4D9]/60">
                            <button
                                class="flex-1 h-11 px-3.5 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-[#263328] font-semibold text-sm flex items-center justify-center gap-1.5 transition-colors"
                                type="button">
                                <span class="">Ver ficha</span>
                                <span class="material-symbols-outlined text-[18px] text-primary">chevron_right</span>
                            </button>
                            <button aria-label="Editar Milo"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors"
                                type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                        </div>
                    </article>

                    <article
                        class="patient-card bg-[#FFFFFF] rounded-2xl p-3.5 border border-[#EAE4D9] shadow-sm flex flex-col space-y-3 cursor-pointer hover:shadow-md transition-all active:scale-[0.99]"
                        data-patient-name="Thor">
                        <div class="flex items-center gap-3">
                            <div
                                class="relative w-[52px] h-[52px] rounded-xl overflow-hidden shrink-0 bg-surface-container-low border border-[#EAE4D9]/60">
                                <img alt="Foto de Thor" class="w-full h-full object-cover"
                                    data-alt="Cute French Bulldog with fawn coat and dark muzzle looking curiously toward the lens, soft ambient warm clinic lighting, pristine modern medical background."
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBlnoMGOi5D6QeJTSs0qLZ7vWDFXMLVIWdhRnYzy9CvbImpPJFMlT1fCXpCoBJXXy3cSl1xN_Ldx_jhtp_6FtwzXJGvaYjVIRow6u9WhELYjjcA6KiBxA2le6jxWaLnMdgsCJ3c8W7POGZ1L10Ka8jK0wPOzqKucrgwJsWXUCmFJAcJKe_8hchVu1zXwJrrthIt63JUwSJAEKbH7U_FL3-m4gxKK1rhqY9aVLcxSWq_CMrD-feEnIkq3w">
                            </div>
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h3 class="text-base font-bold text-on-surface truncate">Thor</h3>
                                    <span
                                        class="text-xs px-2.5 py-0.5 rounded-full bg-surface-container-low text-secondary font-semibold">Canino</span>
                                </div>
                                <p class="text-sm font-medium text-on-surface-variant truncate mt-0.5">Bulldog Francés
                                </p>
                                <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mt-1">
                                    <span class="text-outline font-medium">Responsable:</span>
                                    <span class="text-sm font-semibold text-on-surface truncate">Matías Rossi</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-1 border-t border-[#EAE4D9]/60">
                            <button
                                class="flex-1 h-11 px-3.5 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-[#263328] font-semibold text-sm flex items-center justify-center gap-1.5 transition-colors"
                                type="button">
                                <span class="">Ver ficha</span>
                                <span class="material-symbols-outlined text-[18px] text-primary">chevron_right</span>
                            </button>
                            <button aria-label="Editar Thor"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors"
                                type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                        </div>
                    </article>

                    <article
                        class="patient-card bg-[#FFFFFF] rounded-2xl p-3.5 border border-[#EAE4D9] shadow-sm flex flex-col space-y-3 cursor-pointer hover:shadow-md transition-all active:scale-[0.99]"
                        data-patient-name="Kira">
                        <div class="flex items-center gap-3">
                            <div
                                class="relative w-[52px] h-[52px] rounded-xl overflow-hidden shrink-0 bg-surface-container-low border border-[#EAE4D9]/60">
                                <img alt="Foto de Kira" class="w-full h-full object-cover"
                                    data-alt="Friendly medium-sized mixed breed dog with playful alert ears, expressive brown eyes, healthy coat, warm soft indoor lighting with a clean aesthetic."
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5t9xnjmkcHL75z8oBrvRgltUNeFkNY8-q6nCcp_t1B7g-GlYlGx2OrKnot5R4mcx-yYTQ34F3B9XDw2tSXCJ1jnu_FNWomY5XuadNN1hwbd1UOcJsEV5wTY_CdyjDFxWItTHTnlDOmnYHigKeekkrQrf5CwLgsb6elRLkiT2myiKf_XLCmkjk4wC0RFPrJly0NlVyxW4iScG1maNAQXMOJGnyT1sUnI9QALYAmkemqMm2UwLNsQau6Q">
                            </div>
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h3 class="text-base font-bold text-on-surface truncate">Kira</h3>
                                    <span
                                        class="text-xs px-2.5 py-0.5 rounded-full bg-surface-container-low text-secondary font-semibold">Canino</span>
                                </div>
                                <p class="text-sm font-medium text-on-surface-variant truncate mt-0.5">Mestiza</p>
                                <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mt-1">
                                    <span class="text-outline font-medium">Responsable:</span>
                                    <span class="text-sm font-semibold text-on-surface truncate">Sofía
                                        Valenzuela</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-1 border-t border-[#EAE4D9]/60">
                            <button
                                class="flex-1 h-11 px-3.5 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-[#263328] font-semibold text-sm flex items-center justify-center gap-1.5 transition-colors"
                                type="button">
                                <span class="">Ver ficha</span>
                                <span class="material-symbols-outlined text-[18px] text-primary">chevron_right</span>
                            </button>
                            <button aria-label="Editar Kira"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors"
                                type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                        </div>
                    </article>

                    <article
                        class="patient-card bg-[#FFFFFF] rounded-2xl p-3.5 border border-[#EAE4D9] shadow-sm flex flex-col space-y-3 cursor-pointer hover:shadow-md transition-all active:scale-[0.99]"
                        data-patient-name="Rocco">
                        <div class="flex items-center gap-3">
                            <div
                                class="relative w-[52px] h-[52px] rounded-xl shrink-0 bg-surface-container border border-[#EAE4D9]/60 flex flex-col items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[20px] leading-none mb-0.5"
                                    style="font-variation-settings: 'FILL' 1;">pets</span>
                                <span class="text-xs font-bold uppercase tracking-wider text-on-surface">R</span>
                            </div>
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <div class="flex items-center gap-1.5">
                                        <h3 class="text-base font-bold text-on-surface truncate">Rocco</h3>
                                        <span
                                            class="text-[11px] px-1.5 py-0.2 rounded bg-surface-container-high text-on-surface-variant font-medium">Sin
                                            foto</span>
                                    </div>
                                    <span
                                        class="text-xs px-2.5 py-0.5 rounded-full bg-surface-container-low text-secondary font-semibold">Canino</span>
                                </div>
                                <p class="text-sm font-medium text-outline truncate italic mt-0.5">Raza no especificada
                                </p>
                                <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mt-1">
                                    <span class="text-outline font-medium">Responsable:</span>
                                    <span class="text-sm font-semibold text-on-surface truncate">Juan Ignacio
                                        Pereyra</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-1 border-t border-[#EAE4D9]/60">
                            <button
                                class="flex-1 h-11 px-3.5 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-[#263328] font-semibold text-sm flex items-center justify-center gap-1.5 transition-colors"
                                type="button">
                                <span class="">Ver ficha</span>
                                <span class="material-symbols-outlined text-[18px] text-primary">chevron_right</span>
                            </button>
                            <button aria-label="Editar Rocco"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors"
                                type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                        </div>
                    </article>

                    <article
                        class="patient-card bg-[#FFFFFF] rounded-2xl p-3.5 border border-[#EAE4D9] shadow-sm flex flex-col space-y-3 cursor-pointer hover:shadow-md transition-all active:scale-[0.99]"
                        data-patient-name="Simón">
                        <div class="flex items-center gap-3">
                            <div
                                class="relative w-[52px] h-[52px] rounded-xl overflow-hidden shrink-0 bg-surface-container-low border border-[#EAE4D9]/60">
                                <img alt="Foto de Simón" class="w-full h-full object-cover"
                                    data-alt="Portrait of an elegant Siamese cat with striking deep blue sapphire eyes, cream body, dark chocolate points on ears and face, calm indoor veterinary portrait."
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBvxCoc4fCuQcFilnD_8zkC6wUIog0rPHmUYqlOvSHHr8hXULicrded6fLHe7tr0beITNTVnZZMRa_o1vJrrTE6m4-p5weH2h4EheJuROZVFsAL9eNJvza1Rc5AlcpTDg5TQYr_fhEgiQLIDQQLSciRc5yccct-N0ycsIxxzHXREYHhcRZug8ZIyNJbcH4UqNADaAm-Oh1x4636muq9GYoerCcbCpuAEzNllVKBisS4CIXFS0z6fzFKNQ">
                            </div>
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-1">
                                    <h3 class="text-base font-bold text-on-surface truncate">Simón</h3>
                                    <span
                                        class="text-xs px-2.5 py-0.5 rounded-full bg-surface-container-low text-secondary font-semibold">Felino</span>
                                </div>
                                <p class="text-sm font-medium text-on-surface-variant truncate mt-0.5">Siamés</p>
                                <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mt-1">
                                    <span class="text-outline font-medium">Responsable:</span>
                                    <span class="text-sm font-semibold text-on-surface truncate">Lucía Morales</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-1 border-t border-[#EAE4D9]/60">
                            <button
                                class="flex-1 h-11 px-3.5 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-[#263328] font-semibold text-sm flex items-center justify-center gap-1.5 transition-colors"
                                type="button">
                                <span class="">Ver ficha</span>
                                <span class="material-symbols-outlined text-[18px] text-primary">chevron_right</span>
                            </button>
                            <button aria-label="Editar Simón"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 rounded-xl bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors"
                                type="button">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                        </div>
                    </article>
                </div>
                <!-- 4. Empty State Component (Demonstrative / Interactive) -->
                <div class="hidden flex-col items-center justify-center py-8 px-4 bg-[#FFFFFF] rounded-2xl border border-[#EAE4D9] shadow-sm text-center"
                    id="search-no-results">
                    <div
                        class="w-14 h-14 rounded-full bg-surface-container-low flex items-center justify-center text-primary mb-2.5">
                        <span class="material-symbols-outlined text-[28px]">search_off</span>
                    </div>
                    <h3 class="text-base font-bold text-on-surface mb-1">No se encontraron pacientes</h3>
                    <p class="text-sm text-on-surface-variant max-w-[260px] mb-4">Revisá el nombre o los datos
                        ingresados para volver a intentar.</p>
                    <button
                        class="h-10 px-4 bg-[#F7F4EE] hover:bg-[#EAE4D9]/70 border border-[#EAE4D9] text-[#263328] font-semibold text-xs rounded-lg flex items-center gap-1.5 transition-colors"
                        id="reset-search-btn" type="button">
                        <span class="material-symbols-outlined text-[16px]">refresh</span>
                        <span class="">Restablecer búsqueda</span>
                    </button>
                </div>
                <!-- 5. PetIdentity Specification Sheet -->
                <section
                    class="rounded-2xl border border-[#EAE4D9] bg-[#FFFFFF] p-4 flex flex-col space-y-3 mt-2 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-primary font-bold text-sm">
                            <span class="material-symbols-outlined text-[18px]">library_books</span>
                            <span class="">Estados y variantes normativas</span>
                        </div>
                        <span
                            class="text-[11px] font-medium text-outline bg-surface-container-low px-2 py-0.5 rounded-full">3
                            casos</span>
                    </div>

                    <div class="space-y-2.5">
                        <!-- Caso 1: Sin pacientes registrados (Empty State Real) -->
                        <details class="group rounded-xl border border-[#EAE4D9] bg-[#FDFAF3] overflow-hidden">
                            <summary
                                class="flex items-center justify-between p-3 cursor-pointer text-xs font-bold text-on-surface select-none hover:bg-surface-container-low/60 transition-colors">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-primary">folder_open</span>
                                    <span class="">1. Estado: Sin pacientes registrados</span>
                                </span>
                                <span
                                    class="material-symbols-outlined text-[18px] text-outline group-open:rotate-180 transition-transform">expand_more</span>
                            </summary>
                            <div
                                class="p-4 pt-1 flex flex-col items-center justify-center text-center bg-[#FFFFFF] border-t border-[#EAE4D9]">
                                <div
                                    class="w-12 h-12 rounded-full bg-surface-container-low flex items-center justify-center text-primary mb-2 mt-2">
                                    <span class="material-symbols-outlined text-[24px]">pets</span>
                                </div>
                                <h4 class="text-sm font-bold text-on-surface">Aún no hay pacientes registrados</h4>
                                <p class="text-xs text-on-surface-variant max-w-[240px] mt-1 mb-3.5">Comenzá
                                    registrando a la primera mascota de la clínica para gestionar sus historias
                                    clínicas.</p>
                                <button
                                    class="h-10 px-4 bg-primary hover:bg-primary-container text-on-primary font-semibold text-xs rounded-lg flex items-center gap-1.5 shadow-sm transition-colors"
                                    type="button">
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                    <span class="">+ Nuevo paciente</span>
                                </button>
                            </div>
                        </details>

                        <!-- Caso 2: Sin resultados de búsqueda -->
                        <details class="group rounded-xl border border-[#EAE4D9] bg-[#FDFAF3] overflow-hidden">
                            <summary
                                class="flex items-center justify-between p-3 cursor-pointer text-xs font-bold text-on-surface select-none hover:bg-surface-container-low/60 transition-colors">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-primary">search_off</span>
                                    <span class="">2. Estado: Búsqueda sin resultados</span>
                                </span>
                                <span
                                    class="material-symbols-outlined text-[18px] text-outline group-open:rotate-180 transition-transform">expand_more</span>
                            </summary>
                            <div
                                class="p-3.5 bg-[#FFFFFF] border-t border-[#EAE4D9] flex flex-col items-center text-center">
                                <span class="text-xs text-on-surface-variant">Se activa dinámicamente al buscar
                                    términos sin coincidencias (ej: <em>"Rex"</em>), con botón para limpiar el filtro al
                                    instante.</span>
                            </div>
                        </details>

                        <!-- Caso 3: PetIdentity Fallback (Sin foto) -->
                        <details class="group rounded-xl border border-[#EAE4D9] bg-[#FDFAF3] overflow-hidden"
                            open="">
                            <summary
                                class="flex items-center justify-between p-3 cursor-pointer text-xs font-bold text-on-surface select-none hover:bg-surface-container-low/60 transition-colors">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-primary">badge</span>
                                    <span class="">3. Fallback: Mascota sin foto (PetIdentity)</span>
                                </span>
                                <span
                                    class="material-symbols-outlined text-[18px] text-outline group-open:rotate-180 transition-transform">expand_more</span>
                            </summary>
                            <div class="p-3 bg-[#FFFFFF] border-t border-[#EAE4D9] flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-xl bg-surface-container flex flex-col items-center justify-center text-primary shrink-0 border border-[#EAE4D9]">
                                    <span class="material-symbols-outlined text-[18px] leading-none mb-0.5"
                                        style="font-variation-settings: 'FILL' 1;">pets</span>
                                    <span class="text-[11px] font-bold text-on-surface">R</span>
                                </div>
                                <div class="flex flex-col text-xs">
                                    <span class="font-bold text-on-surface">Contenedor 52x52 con Huella +
                                        Inicial</span>
                                    <span class="text-on-surface-variant text-[11px]">Asegura armonía visual cuando el
                                        tutor no suministra foto de perfil.</span>
                                </div>
                            </div>
                        </details>
                    </div>
                </section>
            </div>
        </div>
        <script>
            (function initPatientsScreen() {
                const searchInput = document.getElementById('patient-search-input');
                const clearBtn = document.getElementById('search-clear-btn');
                const countBadge = document.getElementById('patient-count-badge');
                const patientCards = document.querySelectorAll('.patient-card');
                const toggleEmptyBtn = document.getElementById('toggle-empty-state-btn');
                const toggleStateText = document.getElementById('toggle-state-text');
                const listContainer = document.getElementById('patient-list');
                const emptyState = document.getElementById('patient-empty-state');

                let isSimulatingEmpty = false;

                // Search filter micro-interaction
                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        const query = e.target.value.trim().toLowerCase();

                        if (query.length > 0) {
                            clearBtn.classList.remove('hidden');
                        } else {
                            clearBtn.classList.add('hidden');
                        }

                        let visibleCount = 0;
                        patientCards.forEach(card => {
                            const cardText = card.textContent.toLowerCase();
                            if (cardText.includes(query)) {
                                card.style.display = 'flex';
                                visibleCount++;
                            } else {
                                card.style.display = 'none';
                            }
                        });

                        if (countBadge) {
                            countBadge.textContent =
                                `${visibleCount} paciente${visibleCount === 1 ? '' : 's'} encontrado${visibleCount === 1 ? '' : 's'}`;
                        }
                    });
                }

                if (clearBtn) {
                    clearBtn.addEventListener('click', function() {
                        searchInput.value = '';
                        clearBtn.classList.add('hidden');
                        patientCards.forEach(card => card.style.display = 'flex');
                        if (countBadge) {
                            countBadge.textContent = `${patientCards.length} pacientes registrados`;
                        }
                        searchInput.focus();
                    });
                }

                // Toggle Empty State demo switch
                if (toggleEmptyBtn) {
                    toggleEmptyBtn.addEventListener('click', function() {
                        isSimulatingEmpty = !isSimulatingEmpty;

                        if (isSimulatingEmpty) {
                            listContainer.classList.add('hidden');
                            emptyState.classList.remove('hidden');
                            emptyState.classList.add('flex');
                            toggleStateText.textContent = 'Ver lista con datos';
                            if (countBadge) countBadge.textContent = '0 pacientes';
                        } else {
                            listContainer.classList.remove('hidden');
                            emptyState.classList.add('hidden');
                            emptyState.classList.remove('flex');
                            toggleStateText.textContent = 'Ver estado vacío';
                            if (countBadge) countBadge.textContent = `${patientCards.length} pacientes registrados`;
                            if (searchInput) searchInput.value = '';
                            clearBtn.classList.add('hidden');
                            patientCards.forEach(card => card.style.display = 'flex');
                        }
                    });
                }
            })();
        </script>
    </main>
    <nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/85 backdrop-blur-xl shadow-[0_-2px_12px_rgba(38,51,40,0.06)]"
        data-active-classes="text-primary font-bold">
        <div class="flex justify-around items-center h-16 px-space-xs">
            <a class="flex flex-col items-center justify-center min-w-[64px] h-12 gap-0.5 text-on-surface-variant hover:text-on-surface transition-colors"
                data-path="inicio" href="#">
                <span class="material-symbols-outlined text-[24px]">dashboard</span>
                <span class="text-xs font-semibold">Inicio</span>
            </a>
            <a aria-current="page"
                class="flex flex-col items-center justify-center min-w-[64px] h-12 gap-0.5 transition-colors text-primary font-bold"
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
