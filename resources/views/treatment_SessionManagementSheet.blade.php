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
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
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
                    src="https://lh3.googleusercontent.com/aida/AEtjO1UIIXb3RkrDmnSaWIfJF4d-eqC2mZKdjzjlGxwhaHoGAoYpp6xOQbCKYBv0SucTPN1TRKmutF-e_HUaIyabT4oPnACpW7E6xOqvB0yBn4HRr3u5qqWqKMyr8y03tQkM0xOfH2biVBnJezoh1u1FdCVctc8hOlT0WNmy5qYLql5OeqMvhsSFCZL57LaVrqYW1JrleJpHP4X3wBb-kRCZW8dMZ0Y1x0_HtypP4o58d4Hmqjjr4T0cVKu6RRyF-wNY_xaM5EqRC41utA" />
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
        <div class="flex flex-col w-full relative select-none">
            <!-- PANTALLA DE FONDO: DETALLE DE TRATAMIENTO (Debajo del Sheet) -->
            <div class="w-full flex flex-col px-margin pb-24 opacity-40 pointer-events-none">
                <!-- PetContextHeader Compacto -->
                <div
                    class="w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm mb-space-md flex items-center justify-between">
                    <div class="flex items-center gap-space-md min-w-0">
                        <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-surface-container">
                            <img class="w-full h-full object-cover"
                                data-alt="Golden retriever dog portrait with a calm, friendly expression in warm soft daylight, veterinary clinic interior setting"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzzoEHlWN7Xh0dRGM7ItvMbYDZtm3QQmGfOr18NVcjtClMgAzXlWvFwqk8l7-A5U3MUNU4-VHYVFV24dUeeVyTAPaZLfdN0lewAsINTHn6wNr4WQr4NRm59Oi4imSno2YVl1BkdWUlGggueY7k3dsqAlOaJwJaurjb_9JmWmr-SIcZDGw_Jmpq-hRUA8VRV6EqpWWafY00Ws4I6bSMJumJq9X2xfpu9P3G6T2rPtfKGD71s6xiPKgR7g" />
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5">
                                <h2 class="font-headline-sm text-headline-sm text-on-surface truncate">Luna</h2>
                                <span class="w-2 h-2 rounded-full bg-primary inline-block"></span>
                            </div>
                            <span class="font-body-sm text-body-sm text-on-surface-variant truncate">Canino · Golden
                                Retriever</span>
                            <span class="font-label-sm text-label-sm text-outline truncate">Tutor: María González</span>
                        </div>
                    </div>
                    <button
                        class="h-9 px-space-md bg-surface-container rounded-lg font-label-md text-label-md text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                        <span>Editar</span>
                    </button>
                </div>
                <!-- Patient Navigation Pills -->
                <div class="w-full flex items-center gap-space-xs overflow-x-auto pb-space-xs mb-space-md">
                    <div
                        class="h-8 px-space-md rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md flex items-center whitespace-nowrap">
                        Resumen</div>
                    <div
                        class="h-8 px-space-md rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md flex items-center whitespace-nowrap">
                        Historial</div>
                    <div
                        class="h-8 px-space-md rounded-full bg-primary text-on-primary font-label-md text-label-md flex items-center whitespace-nowrap">
                        Tratamientos</div>
                    <div
                        class="h-8 px-space-md rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md flex items-center whitespace-nowrap">
                        Vacunas</div>
                </div>
                <!-- Treatment Header Card -->
                <div class="w-full bg-surface-container-lowest rounded-xl p-space-md shadow-sm mb-space-md">
                    <div class="flex items-start justify-between gap-space-sm mb-space-xs">
                        <div class="flex flex-col min-w-0">
                            <span
                                class="font-label-sm text-label-sm text-primary uppercase tracking-wider">Fisioterapia</span>
                            <h3 class="font-headline-md text-headline-md text-on-surface">Rehabilitación postoperatoria
                            </h3>
                        </div>
                        <span
                            class="h-6 px-2.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm flex items-center font-semibold">En
                            curso</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-md">Protocolo de recuperación
                        muscular y rango articular de miembro pélvico izquierdo.</p>
                    <!-- Progress Section -->
                    <div class="w-full bg-surface-container-low rounded-lg p-space-md">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-label-md text-label-md text-on-surface font-semibold">Progreso
                                terapéutico</span>
                            <span class="font-label-md text-label-md text-primary font-bold">3 de 5 sesiones
                                (60%)</span>
                        </div>
                        <div class="w-full h-2.5 bg-surface-container-high rounded-full overflow-hidden flex">
                            <div class="h-full bg-primary rounded-full" style="width: 60%;"></div>
                        </div>
                    </div>
                </div>
                <!-- Timeline Mini Card List -->
                <div class="w-full flex flex-col gap-space-xs">
                    <div
                        class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-space-sm">
                            <div
                                class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-primary font-label-md text-label-md">
                                1</div>
                            <div class="flex flex-col">
                                <span class="font-label-md text-label-md text-on-surface">Sesión 1</span>
                                <span class="font-body-sm text-body-sm text-outline">04 oct 2026</span>
                            </div>
                        </div>
                        <span class="font-label-sm text-label-sm text-primary font-semibold">Completada</span>
                    </div>
                    <div
                        class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-space-sm">
                            <div
                                class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-primary font-label-md text-label-md">
                                2</div>
                            <div class="flex flex-col">
                                <span class="font-label-md text-label-md text-on-surface">Sesión 2</span>
                                <span class="font-body-sm text-body-sm text-outline">08 oct 2026</span>
                            </div>
                        </div>
                        <span class="font-label-sm text-label-sm text-primary font-semibold">Completada</span>
                    </div>
                    <div
                        class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-space-sm">
                            <div
                                class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-primary font-label-md text-label-md">
                                3</div>
                            <div class="flex flex-col">
                                <span class="font-label-md text-label-md text-on-surface">Sesión 3</span>
                                <span class="font-body-sm text-body-sm text-outline">12 oct 2026</span>
                            </div>
                        </div>
                        <span class="font-label-sm text-label-sm text-primary font-semibold">Completada</span>
                    </div>
                </div>
            </div>
            <!-- BACKDROP TRANSLÚCIDO OSCURECIDO -->
            <div class="fixed inset-0 z-40 bg-inverse-surface/40 backdrop-blur-[2px] transition-opacity"></div>
            <!-- SELECTOR DIDÁCTICO DE ESCENARIOS (Top Bar flotante para inspección interactiva) -->
            <div class="fixed top-20 left-0 right-0 z-50 px-3 flex flex-col items-center pointer-events-auto">
                <div
                    class="w-full max-w-sm bg-surface-container-lowest/95 backdrop-blur-md p-1.5 rounded-full shadow-lg flex items-center justify-between gap-1 overflow-x-auto text-[11px] font-label-sm">
                    <button
                        class="scenario-btn px-2.5 py-1 rounded-full bg-primary text-on-primary font-bold shadow-xs whitespace-nowrap transition-all"
                        id="btn-s5" onclick="switchScenario('s5')">S5: Pendiente</button>
                    <button
                        class="scenario-btn px-2.5 py-1 rounded-full text-on-surface-variant hover:bg-surface-container whitespace-nowrap transition-all"
                        id="btn-s6" onclick="switchScenario('s6')">S6: Sin fecha</button>
                    <button
                        class="scenario-btn px-2.5 py-1 rounded-full text-on-surface-variant hover:bg-surface-container whitespace-nowrap transition-all"
                        id="btn-s4" onclick="switchScenario('s4')">S4: Completada</button>
                    <button
                        class="scenario-btn px-2.5 py-1 rounded-full text-on-surface-variant hover:bg-surface-container whitespace-nowrap transition-all"
                        id="btn-s3" onclick="switchScenario('s3')">S3: Cancelada</button>
                    <button
                        class="px-2.5 py-1 rounded-full text-tertiary-container bg-tertiary-fixed/40 font-bold whitespace-nowrap"
                        onclick="openModal('complete')">Modal</button>
                </div>
            </div>
            <!-- BOTTOM SHEET PRINCIPAL: SessionManagementSheet -->
            <div class="fixed inset-x-0 bottom-16 z-50 flex flex-col bg-surface-container-lowest rounded-t-[28px] shadow-[0_-8px_32px_rgba(38,51,40,0.15)] max-h-[85vh] overflow-y-auto"
                id="session-sheet">
                <!-- Sheet Drag Handle & Close Header -->
                <div
                    class="sticky top-0 z-10 bg-surface-container-lowest/95 backdrop-blur-md pt-2 px-margin pb-2 flex flex-col">
                    <div class="w-10 h-1.2 rounded-full bg-outline-variant self-center my-1.5 h-1"></div>
                    <div class="flex items-start justify-between gap-2 pt-1">
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-2">
                                <h2 class="font-headline-md text-headline-md text-on-surface" id="sheet-title">
                                    Gestionar sesión 5</h2>
                                <div class="px-2.5 py-0.5 rounded-full bg-[#FEF6EB] text-[#8A4F13] flex items-center gap-1.5"
                                    id="status-badge">
                                    <span class="w-2 h-2 rounded-full bg-[#EA9640]"></span>
                                    <span class="font-label-sm text-label-sm font-semibold"
                                        id="status-text">Pendiente</span>
                                </div>
                            </div>
                            <span
                                class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">Rehabilitación
                                postoperatoria · Luna</span>
                        </div>
                        <button aria-label="Cerrar"
                            class="w-10 h-10 -mr-2 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                </div>
                <!-- SHEET SCROLLABLE CONTENT -->
                <div class="flex flex-col px-margin pb-space-lg pt-space-xs gap-space-lg">
                    <!-- ========================================== -->
                    <!-- SECCIÓN A: DATOS DE LA SESIÓN (Formulario) -->
                    <!-- ========================================== -->
                    <section class="flex flex-col gap-space-sm">
                        <div class="flex items-center justify-between">
                            <span
                                class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Datos
                                de la sesión</span>
                            <span class="font-label-sm text-label-sm text-primary flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[14px]">lock_reset</span> Editable
                            </span>
                        </div>
                        <!-- Campo 1: Fecha y hora -->
                        <div class="flex flex-col gap-1">
                            <label class="font-label-md text-label-md text-on-surface">Fecha y hora programada</label>
                            <div class="relative w-full">
                                <input
                                    class="w-full h-11 pl-3 pr-10 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md outline-none focus:bg-surface-container-lowest shadow-xs transition-all"
                                    id="input-datetime" type="text" value="18 oct 2026, 16:30 hs" />
                                <div
                                    class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[20px]">event</span>
                                </div>
                            </div>
                        </div>
                        <!-- Campo 2: Valor de la sesión -->
                        <div class="flex flex-col gap-1">
                            <label class="font-label-md text-label-md text-on-surface">Valor de la sesión</label>
                            <div class="relative w-full">
                                <input
                                    class="w-full h-11 pl-3 pr-12 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md outline-none focus:bg-surface-container-lowest shadow-xs transition-all"
                                    id="input-price" type="text" value="$ 18.000,00" />
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 font-label-sm text-label-sm text-on-surface-variant font-bold">ARS</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-outline">Moneda fijada al asignar el
                                tratamiento.</span>
                        </div>
                        <!-- Campo 3: Notas de la sesión -->
                        <div class="flex flex-col gap-1">
                            <div class="flex justify-between items-center">
                                <label class="font-label-md text-label-md text-on-surface">Notas de la sesión <span
                                        class="text-outline font-normal">(opcional)</span></label>
                                <span class="font-label-sm text-label-sm text-outline" id="char-counter">105 /
                                    5000</span>
                            </div>
                            <textarea
                                class="w-full p-3 rounded-lg bg-surface-container-low text-on-surface font-body-sm text-body-sm outline-none focus:bg-surface-container-lowest shadow-xs resize-none transition-all"
                                id="input-notes" rows="3">Paciente con buena tolerancia al ejercicio pasivo en tren posterior. Continuar con pauta establecida.</textarea>
                            <span class="font-label-sm text-label-sm text-outline">Anotaciones operativas internas. No
                                reemplazan la Historia Clínica.</span>
                        </div>
                        <!-- Botón Guardar Datos -->
                        <button
                            class="w-full h-11 mt-1 rounded-lg bg-primary text-on-primary font-label-lg text-label-lg flex items-center justify-center gap-2 shadow-sm active:scale-[0.99] transition-transform"
                            id="btn-save-data" onclick="showSaveFeedback()">
                            <span class="material-symbols-outlined text-[18px]">check</span>
                            <span>Guardar cambios</span>
                        </button>
                        <span class="text-center font-label-sm text-label-sm text-primary opacity-0 transition-opacity"
                            id="save-feedback">¡Datos de la sesión guardados correctamente!</span>
                    </section>
                    <!-- SEPARADOR VISUAL DEL SISTEMA (Sin borders duros) -->
                    <div class="w-full h-px bg-surface-container-high rounded-full my-0.5"></div>
                    <!-- ============================================== -->
                    <!-- SECCIÓN B: CAMBIO DE ESTADO DE LA SESIÓN       -->
                    <!-- ============================================== -->
                    <section class="flex flex-col gap-space-sm" id="state-section">
                        <div class="flex flex-col">
                            <span
                                class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Estado
                                de la sesión</span>
                            <span class="font-body-sm text-body-sm text-outline">Las acciones de estado impactan
                                directamente en el progreso del tratamiento.</span>
                        </div>
                        <!-- Dynamic State Actions Box -->
                        <div class="flex flex-col gap-space-sm mt-1" id="dynamic-state-actions">
                            <!-- CASO DEFAULT: SESIÓN PENDIENTE -->
                            <!-- Acción Primaria de Estado: Marcar Completada -->
                            <div class="flex flex-col gap-1.5">
                                <button
                                    class="w-full h-12 rounded-xl bg-surface-container text-primary font-label-lg text-label-lg flex items-center justify-center gap-2 shadow-sm active:bg-surface-container-high transition-colors"
                                    onclick="openModal('complete')">
                                    <span class="material-symbols-outlined text-[20px]">task_alt</span>
                                    <span class="font-bold">Marcar como completada</span>
                                </button>
                                <div class="flex items-center gap-1.5 px-1 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px] text-primary">trending_up</span>
                                    <span class="font-label-sm text-label-sm">Sumará 1 sesión al progreso terapéutico
                                        (pasará a 4 de 5).</span>
                                </div>
                            </div>
                            <!-- Acción Secundaria: Cancelar -->
                            <div class="flex flex-col gap-1.5 mt-2">
                                <button
                                    class="w-full h-11 rounded-lg bg-surface-container-low text-error font-label-md text-label-md flex items-center justify-center gap-2 hover:bg-error-container/30 transition-colors"
                                    onclick="openModal('cancel')">
                                    <span class="material-symbols-outlined text-[18px]">cancel</span>
                                    <span>Cancelar sesión</span>
                                </button>
                                <div class="flex items-start gap-1.5 px-1 text-outline">
                                    <span
                                        class="material-symbols-outlined text-[15px] flex-shrink-0 mt-0.5">info</span>
                                    <span class="font-label-sm text-label-sm">La sesión permanecerá en el historial.
                                        VetZen ofrecerá crear una nueva sesión para cumplir el objetivo clínico.</span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- ============================================== -->
            <!-- MODAL FLOTANTE DE CONFIRMACIÓN DIDÁCTICO      -->
            <!-- ============================================== -->
            <div class="fixed inset-0 z-50 flex items-center justify-center px-4 hidden" id="confirm-modal">
                <div class="absolute inset-0 bg-inverse-surface/60 backdrop-blur-xs" onclick="closeModal()"></div>
                <div
                    class="relative w-full max-w-xs bg-surface-container-lowest rounded-2xl p-space-lg shadow-xl flex flex-col gap-space-md z-10">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center self-center"
                        id="modal-icon-bg">
                        <span class="material-symbols-outlined text-[26px]" id="modal-icon">task_alt</span>
                    </div>
                    <div class="flex flex-col items-center text-center gap-1">
                        <h4 class="font-headline-sm text-headline-sm text-on-surface" id="modal-title">¿Completar
                            sesión 5?</h4>
                        <p class="font-body-sm text-body-sm text-on-surface-variant" id="modal-desc">Esta acción
                            actualizará el tratamiento a 4 de 5 sesiones completadas.</p>
                    </div>
                    <div class="flex flex-col gap-2 mt-1">
                        <button
                            class="w-full h-11 rounded-lg font-label-lg text-label-lg font-semibold flex items-center justify-center text-on-primary"
                            id="modal-confirm-btn" onclick="confirmAction()">
                            Confirmar
                        </button>
                        <button
                            class="w-full h-10 rounded-lg bg-surface-container font-label-md text-label-md text-on-surface-variant"
                            onclick="closeModal()">
                            Volver atrás
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let currentScenario = 's5';
            let pendingActionType = 'complete';

            const scenarios = {
                s5: {
                    title: 'Gestionar sesión 5',
                    badgeBg: '#FEF6EB',
                    badgeColor: '#8A4F13',
                    dotColor: '#EA9640',
                    status: 'Pendiente',
                    datetime: '18 oct 2026, 16:30 hs',
                    price: '$ 18.000,00',
                    notes: 'Paciente con buena tolerancia al ejercicio pasivo en tren posterior. Continuar con pauta establecida.',
                    actionsHtml: `
        <div class="flex flex-col gap-1.5">
          <button onclick="openModal('complete')" class="w-full h-12 rounded-xl bg-surface-container text-primary font-label-lg text-label-lg flex items-center justify-center gap-2 shadow-sm active:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-[20px]">task_alt</span>
            <span class="font-bold">Marcar como completada</span>
          </button>
          <div class="flex items-center gap-1.5 px-1 text-on-surface-variant">
            <span class="material-symbols-outlined text-[16px] text-primary">trending_up</span>
            <span class="font-label-sm text-label-sm">Sumará 1 sesión al progreso terapéutico (pasará a 4 de 5).</span>
          </div>
        </div>
        <div class="flex flex-col gap-1.5 mt-2">
          <button onclick="openModal('cancel')" class="w-full h-11 rounded-lg bg-surface-container-low text-error font-label-md text-label-md flex items-center justify-center gap-2 hover:bg-error-container/30 transition-colors">
            <span class="material-symbols-outlined text-[18px]">cancel</span>
            <span>Cancelar sesión</span>
          </button>
          <div class="flex items-start gap-1.5 px-1 text-outline">
            <span class="material-symbols-outlined text-[15px] flex-shrink-0 mt-0.5">info</span>
            <span class="font-label-sm text-label-sm">La sesión permanecerá en el historial. VetZen ofrecerá crear una nueva sesión para cumplir el objetivo clínico.</span>
          </div>
        </div>
      `
                },
                s6: {
                    title: 'Gestionar sesión 6',
                    badgeBg: '#FEF6EB',
                    badgeColor: '#8A4F13',
                    dotColor: '#EA9640',
                    status: 'Sin programar',
                    datetime: 'Sin fecha asignada',
                    price: '$ 18.000,00',
                    notes: 'Sesión complementaria generada tras cancelación previa. Pendiente de coordinación con María González.',
                    actionsHtml: `
        <div class="flex flex-col gap-1.5">
          <button onclick="alert('Se ha enviado solicitud de turno a la agenda')" class="w-full h-11 rounded-lg bg-primary text-on-primary font-label-lg text-label-lg flex items-center justify-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">calendar_add_on</span>
            <span>Asignar fecha y hora</span>
          </button>
          <span class="font-label-sm text-label-sm text-on-surface-variant px-1">Una vez agendada podrá ser ejecutada y completada.</span>
        </div>
        <div class="flex flex-col gap-1.5 mt-2">
          <button onclick="openModal('cancel')" class="w-full h-11 rounded-lg bg-surface-container-low text-error font-label-md text-label-md flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">delete</span>
            <span>Descartar sesión complementaria</span>
          </button>
        </div>
      `
                },
                s4: {
                    title: 'Gestionar sesión 4',
                    badgeBg: '#EAF4EB',
                    badgeColor: '#2D5C2E',
                    dotColor: '#478F49',
                    status: 'Completada',
                    datetime: '15 oct 2026, 17:00 hs',
                    price: '$ 18.000,00',
                    notes: 'Sesión ejecutada con éxito. Se aplicó láser terapéutico 4J/cm2 en cicatriz y ejercicios propioceptivos sobre plato de Freeman.',
                    actionsHtml: `
        <div class="p-space-md rounded-xl bg-surface-container-low flex flex-col gap-2">
          <div class="flex items-center gap-2 text-primary font-label-md text-label-md">
            <span class="material-symbols-outlined text-[18px]">verified</span>
            <span>Sesión computada en el progreso (4 de 5)</span>
          </div>
          <span class="font-body-sm text-body-sm text-on-surface-variant">Puedes corregir fecha, notas operativas o arancel. Si hubo un error en la toma clínica, puedes revertir su estado:</span>
          <button onclick="openModal('revert-complete')" class="w-full h-10 mt-1 rounded-lg bg-surface-container text-tertiary font-label-md text-label-md flex items-center justify-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">undo</span>
            <span>Reabrir sesión (volver a pendiente)</span>
          </button>
        </div>
      `
                },
                s3: {
                    title: 'Gestionar sesión 3 (Cancelada)',
                    badgeBg: '#FDF0EE',
                    badgeColor: '#8F2C16',
                    dotColor: '#DA5F42',
                    status: 'Cancelada',
                    datetime: '12 oct 2026, 16:30 hs',
                    price: '$ 18.000,00',
                    notes: 'El tutor canceló con 2 horas de anticipación por indisponibilidad de traslado.',
                    actionsHtml: `
        <div class="p-space-md rounded-xl bg-surface-container-low flex flex-col gap-2">
          <div class="flex items-center gap-2 text-error font-label-md text-label-md">
            <span class="material-symbols-outlined text-[18px]">event_busy</span>
            <span>Esta sesión no sumó al progreso terapéutico</span>
          </div>
          <span class="font-body-sm text-body-sm text-on-surface-variant">Esta sesión quedó registrada para trazabilidad de turnos. ¿Deseas restablecerla?</span>
          <button onclick="openModal('restore')" class="w-full h-10 mt-1 rounded-lg bg-surface-container text-on-surface font-label-md text-label-md flex items-center justify-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">refresh</span>
            <span>Restablecer a pendiente</span>
          </button>
        </div>
      `
                }
            };

            function switchScenario(key) {
                currentScenario = key;
                const data = scenarios[key];

                // Actualizar botones de escenario
                document.querySelectorAll('.scenario-btn').forEach(btn => {
                    btn.className =
                        'scenario-btn px-2.5 py-1 rounded-full text-on-surface-variant hover:bg-surface-container whitespace-nowrap transition-all';
                });
                const activeBtn = document.getElementById('btn-' + key);
                if (activeBtn) {
                    activeBtn.className =
                        'scenario-btn px-2.5 py-1 rounded-full bg-primary text-on-primary font-bold shadow-xs whitespace-nowrap transition-all';
                }

                // Actualizar Encabezado del Sheet
                document.getElementById('sheet-title').innerText = data.title;
                const badge = document.getElementById('status-badge');
                badge.style.backgroundColor = data.badgeBg;
                badge.style.color = data.badgeColor;
                badge.querySelector('span:first-child').style.backgroundColor = data.dotColor;
                document.getElementById('status-text').innerText = data.status;

                // Actualizar Campos
                document.getElementById('input-datetime').value = data.datetime;
                document.getElementById('input-price').value = data.price;
                const notesEl = document.getElementById('input-notes');
                notesEl.value = data.notes;
                document.getElementById('char-counter').innerText = notesEl.value.length + ' / 5000';

                // Actualizar Acciones de Estado
                document.getElementById('dynamic-state-actions').innerHTML = data.actionsHtml;
            }

            function showSaveFeedback() {
                const fb = document.getElementById('save-feedback');
                fb.style.opacity = '1';
                setTimeout(() => {
                    fb.style.opacity = '0';
                }, 2400);
            }

            function openModal(type) {
                pendingActionType = type;
                const modal = document.getElementById('confirm-modal');
                const title = document.getElementById('modal-title');
                const desc = document.getElementById('modal-desc');
                const btn = document.getElementById('modal-confirm-btn');
                const icon = document.getElementById('modal-icon');
                const iconBg = document.getElementById('modal-icon-bg');

                if (type === 'complete') {
                    title.innerText = '¿Marcar sesión como completada?';
                    desc.innerText = 'Sumará 1 sesión al progreso del tratamiento de Luna (alcanzando 4 de 5).';
                    btn.innerText = 'Sí, marcar completada';
                    btn.className =
                        'w-full h-11 rounded-lg font-label-lg text-label-lg font-semibold flex items-center justify-center bg-primary text-on-primary';
                    icon.innerText = 'task_alt';
                    icon.className = 'material-symbols-outlined text-[26px] text-primary';
                    iconBg.className =
                        'w-12 h-12 rounded-full flex items-center justify-center self-center bg-surface-container';
                } else if (type === 'cancel') {
                    title.innerText = '¿Cancelar esta sesión?';
                    desc.innerText =
                        'Permanecerá registrada en el historial. VetZen mantendrá el cupo pendiente si es necesario completar las 5 requeridas.';
                    btn.innerText = 'Confirmar cancelación';
                    btn.className =
                        'w-full h-11 rounded-lg font-label-lg text-label-lg font-semibold flex items-center justify-center bg-error text-on-error';
                    icon.innerText = 'warning';
                    icon.className = 'material-symbols-outlined text-[26px] text-error';
                    iconBg.className = 'w-12 h-12 rounded-full flex items-center justify-center self-center bg-error-container';
                } else if (type === 'revert-complete') {
                    title.innerText = '¿Reabrir esta sesión?';
                    desc.innerText = 'El tratamiento volverá a restar una sesión de su progreso terapéutico.';
                    btn.innerText = 'Sí, reabrir a pendiente';
                    btn.className =
                        'w-full h-11 rounded-lg font-label-lg text-label-lg font-semibold flex items-center justify-center bg-tertiary-container text-on-tertiary-container';
                    icon.innerText = 'undo';
                    icon.className = 'material-symbols-outlined text-[26px] text-tertiary-container';
                    iconBg.className = 'w-12 h-12 rounded-full flex items-center justify-center self-center bg-tertiary-fixed';
                } else if (type === 'restore') {
                    title.innerText = '¿Restablecer sesión?';
                    desc.innerText = 'Volverá al estado Pendiente con la fecha original asignada.';
                    btn.innerText = 'Restablecer';
                    btn.className =
                        'w-full h-11 rounded-lg font-label-lg text-label-lg font-semibold flex items-center justify-center bg-primary text-on-primary';
                    icon.innerText = 'refresh';
                    icon.className = 'material-symbols-outlined text-[26px] text-primary';
                    iconBg.className =
                        'w-12 h-12 rounded-full flex items-center justify-center self-center bg-surface-container';
                }

                modal.classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('confirm-modal').classList.add('hidden');
            }

            function confirmAction() {
                closeModal();
                if (pendingActionType === 'complete') {
                    switchScenario('s4');
                } else if (pendingActionType === 'cancel') {
                    switchScenario('s3');
                } else if (pendingActionType === 'revert-complete' || pendingActionType === 'restore') {
                    switchScenario('s5');
                }
            }

            // Contador de caracteres para notas
            document.getElementById('input-notes').addEventListener('input', function(e) {
                document.getElementById('char-counter').innerText = e.target.value.length + ' / 5000';
            });
        </script>
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
