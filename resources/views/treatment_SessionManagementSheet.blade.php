<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"
        name="viewport" />
    <meta content="mobile_tab" name="shell-type" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-warm": "#FDFAF3",
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
                        "terracotta": "#da5f42",
                        "terracotta-dark": "#8f2c16",
                        "terracotta-light": "#fdf0ee",
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
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        "2xl": "1rem",
                        "3xl": "1.5rem",
                        full: "9999px"
                    },
                    fontFamily: {
                        "body-md": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                        "label-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "label-md": ["Plus Jakarta Sans", "sans-serif"],
                        "label-lg": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    spacing: {
                        margin: "1rem",
                        "space-xs": "0.25rem",
                        "space-sm": "0.5rem",
                        "space-md": "1rem",
                        "space-lg": "1.5rem"
                    }
                }
            }
        };
    </script>
    <style>
        @layer base {

            html,
            body {
                width: 100vw;
                margin: 0;
                padding: 0;
                height: 100%;
                overflow: hidden;
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
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body
    class="bg-surface text-on-surface font-body-md flex flex-col h-screen antialiased select-none overflow-hidden relative">
    <!-- SELECTOR DE VARIANTES DIDÁCTICO (BARRA SUPERIOR COMPACTA Z-50) -->
    <aside aria-label="Visualizador didáctico de variantes"
        class="fixed top-0 left-0 right-0 z-50 px-3 py-1.5 bg-[#f5f1e8] border-b border-[#e2d9cc] shadow-xs flex flex-col gap-1">
        <div class="flex items-center justify-between text-[10px] font-semibold text-[#5c5446]">
            <span class="flex items-center gap-1 font-bold text-primary">
                <span class="material-symbols-outlined text-[13px]">view_carousel</span>
                <span>ESTADOS Y VARIANTES:</span>
            </span>
            <button
                class="px-2 py-0.5 rounded bg-error-container text-on-error-container hover:bg-error/20 flex items-center gap-1 transition-colors text-[10px] font-bold"
                onclick="toggleErrorBanner()">
                <span class="material-symbols-outlined text-[12px]">bug_report</span> Probar Error
            </button>
        </div>
        <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 text-[11px] font-medium no-scrollbar">
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-primary text-on-primary font-bold shadow-xs whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-A" onclick="setVariant('A')">A: Pending</button>
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-B" onclick="setVariant('B')">B: Modal Parcial</button>
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-C" onclick="setVariant('C')">C: Modal 100%</button>
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-D" onclick="setVariant('D')">D: Res. Completado</button>
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-E" onclick="setVariant('E')">E: Modal Cancelar</button>
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-F" onclick="setVariant('F')">F: Res. Cancelado</button>
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-G" onclick="setVariant('G')">G: S4 Completed</button>
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-H" onclick="setVariant('H')">H: S3 Cancelled</button>
            <button
                class="var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] transition-colors"
                id="btn-var-I" onclick="setVariant('I')">I: Read-only</button>
        </div>
    </aside>
    <!-- TOP HEADER DE VETZEN (debajo del inspector) -->
    <header
        class="fixed top-[46px] w-full z-20 bg-surface-warm/90 backdrop-blur-md border-b border-surface-container-high/60">
        <div class="h-12 px-margin flex items-center justify-between gap-space-sm">
            <div class="flex items-center gap-space-sm min-w-0">
                <img alt="VetZen Logo" class="h-6 w-auto object-contain flex-shrink-0"
                    src="https://lh3.googleusercontent.com/aida/AEtjO1UIIXb3RkrDmnSaWIfJF4d-eqC2mZKdjzjlGxwhaHoGAoYpp6xOQbCKYBv0SucTPN1TRKmutF-e_HUaIyabT4oPnACpW7E6xOqvB0yBn4HRr3u5qqWqKMyr8y03tQkM0xOfH2biVBnJezoh1u1FdCVctc8hOlT0WNmy5qYLql5OeqMvhsSFCZL57LaVrqYW1JrleJpHP4X3wBb-kRCZW8dMZ0Y1x0_HtypP4o58d4Hmqjjr4T0cVKu6RRyF-wNY_xaM5EqRC41utA" />
                <div class="flex flex-col min-w-0">
                    <span class="text-[9px] text-primary uppercase font-bold tracking-wider truncate">VetZen
                        Clínica</span>
                    <h1 class="text-xs font-bold text-on-surface truncate">Pacientes · Tratamientos</h1>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <button
                    class="w-7 h-7 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container">
                    <span class="material-symbols-outlined text-[18px]">notifications</span>
                </button>
                <div
                    class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-on-primary text-[10px] font-bold">
                    VZ</div>
            </div>
        </div>
    </header>
    <!-- PANTALLA DE FONDO: DETALLE DE TRATAMIENTO LUNA (atenuada) -->
    <div
        class="w-full flex-1 flex flex-col px-margin pt-[98px] pb-24 opacity-30 pointer-events-none filter blur-[0.5px]">
        <!-- Pet Card Compacto -->
        <div
            class="w-full bg-surface-container-lowest rounded-xl p-3 shadow-xs mb-3 flex items-center justify-between border border-surface-container-high/40">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full overflow-hidden bg-surface-container flex-shrink-0">
                    <img class="w-full h-full object-cover" data-alt="Golden retriever portrait"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzzoEHlWN7Xh0dRGM7ItvMbYDZtm3QQmGfOr18NVcjtClMgAzXlWvFwqk8l7-A5U3MUNU4-VHYVFV24dUeeVyTAPaZLfdN0lewAsINTHn6wNr4WQr4NRm59Oi4imSno2YVl1BkdWUlGggueY7k3dsqAlOaJwJaurjb_9JmWmr-SIcZDGw_Jmpq-hRUA8VRV6EqWWafY00Ws4I6bSMJumJq9X2xfpu9P3G6T2rPtfKGD71s6xiPKgR7g" />
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <h2 class="font-bold text-sm text-on-surface">Luna</h2>
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                    </div>
                    <p class="text-xs text-on-surface-variant">Canino · Golden Retriever · Tutor: María González</p>
                </div>
            </div>
        </div>
        <!-- Treatment Card -->
        <div
            class="w-full bg-surface-container-lowest rounded-xl p-3.5 shadow-xs mb-3 border border-surface-container-high/40">
            <div class="flex justify-between items-start mb-1">
                <div>
                    <span class="text-[10px] font-bold text-primary tracking-wide uppercase">Fisioterapia</span>
                    <h3 class="font-bold text-base text-on-surface">Rehabilitación postoperatoria</h3>
                </div>
                <span
                    class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-[11px] font-semibold">En
                    curso</span>
            </div>
            <p class="text-xs text-on-surface-variant mb-2.5">Protocolo de recuperación muscular y rango articular de
                miembro pélvico izquierdo.</p>
            <div class="bg-surface-container-low rounded-lg p-2.5">
                <div class="flex justify-between text-xs font-semibold mb-1">
                    <span>Progreso terapéutico</span>
                    <span class="text-primary font-bold">3 de 5 sesiones (60%)</span>
                </div>
                <div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full" style="width: 60%;"></div>
                </div>
            </div>
        </div>
        <!-- Timeline cards previas -->
        <div class="flex flex-col gap-2">
            <div class="p-3 rounded-lg bg-surface-container-lowest flex justify-between items-center text-xs">
                <span class="font-semibold text-on-surface">Sesión 1 · 04 oct 2026</span>
                <span class="text-primary font-bold">Completada</span>
            </div>
            <div class="p-3 rounded-lg bg-surface-container-lowest flex justify-between items-center text-xs">
                <span class="font-semibold text-on-surface">Sesión 2 · 08 oct 2026</span>
                <span class="text-primary font-bold">Completada</span>
            </div>
            <div class="p-3 rounded-lg bg-surface-container-lowest flex justify-between items-center text-xs">
                <span class="font-semibold text-on-surface">Sesión 3 · 12 oct 2026</span>
                <span class="text-terracotta font-bold">Cancelada</span>
            </div>
        </div>
    </div>
    <!-- BACKDROP TRANSLÚCIDO OSCURECIDO -->
    <div class="fixed inset-0 z-30 bg-inverse-surface/40 backdrop-blur-[2px] transition-opacity pointer-events-auto"
        onclick="closeAllOverlays()"></div>
    <!-- BANNER DE ERROR DE NEGOCIO (Dismissible / Toggleable) -->
    <div class="fixed top-[52px] inset-x-3 z-50 hidden transition-all" id="error-banner">
        <div
            class="p-3 rounded-xl bg-error-container text-on-error-container border border-error/20 shadow-lg flex items-start justify-between gap-2">
            <div class="flex items-start gap-2">
                <span class="material-symbols-outlined text-[20px] text-error flex-shrink-0 mt-0.5">error</span>
                <div class="flex flex-col">
                    <span class="text-xs font-bold">Operación rechazada</span>
                    <span class="text-[12px] leading-tight text-on-surface">No se pudo modificar la sesión. El
                        tratamiento ya no permite modificar sus sesiones.</span>
                </div>
            </div>
            <button class="text-on-error-container hover:text-error p-1" onclick="toggleErrorBanner()">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>
    </div>
    <!-- SHEET PRINCIPAL: SessionManagementSheet (MOBILE BOTTOM SHEET & DESKTOP SIDE SHEET) -->
    <div class="fixed inset-x-0 bottom-0 top-[52px] md:top-14 md:bottom-0 md:left-auto md:right-0 md:w-[440px] z-40 flex flex-col bg-surface-warm rounded-t-3xl md:rounded-t-none md:rounded-l-2xl shadow-[0_-8px_32px_rgba(18,30,20,0.22)] overflow-hidden transition-transform"
        id="session-sheet">
        <!-- Sheet Drag Handle (Mobile) & Header Completo y Despejado -->
        <div
            class="flex-shrink-0 z-20 bg-surface-warm/95 backdrop-blur-md pt-3 px-margin pb-3 border-b border-[#e6decf] flex flex-col">
            <!-- Handlebar sutil rounded en el tope -->
            <div class="w-10 h-1.5 rounded-full bg-[#d0c6b6] self-center mb-2.5 md:hidden"></div>
            <div class="flex items-start justify-between gap-2">
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="font-headline-sm text-base font-bold text-on-surface" id="sheet-title">Gestionar
                            sesión 5</h2>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5 bg-[#FEF6EB] text-[#9C4E05]"
                            id="sheet-badge">
                            <span class="w-2 h-2 rounded-full bg-[#EA9640]" id="sheet-badge-dot"></span>
                            <span id="sheet-badge-text">Pendiente</span>
                        </span>
                    </div>
                    <span class="text-xs text-on-surface-variant truncate mt-1" id="sheet-subtitle">Rehabilitación
                        postoperatoria · Luna</span>
                </div>
                <button aria-label="Cerrar"
                    class="w-11 h-11 -mr-1.5 -mt-1 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors"
                    onclick="alert('Cerrar sheet')">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>
        </div>
        <!-- BANNER INFORMATIVO CONDICIONAL POR ESTADO (Variantes G, H, I) -->
        <div class="hidden px-margin pt-3 flex-shrink-0" id="state-info-banner">
            <div class="p-3 rounded-xl text-xs flex items-start gap-2.5" id="state-info-box">
                <span class="material-symbols-outlined text-[18px] flex-shrink-0 mt-0.5"
                    id="state-info-icon">info</span>
                <span class="leading-relaxed" id="state-info-text"></span>
            </div>
        </div>
        <!-- CONTENIDO SCROLLEABLE DEL SHEET CON PADDING INFERIOR SEGURO (pb-28) -->
        <div class="flex-1 overflow-y-auto px-margin pt-3.5 pb-28 flex flex-col gap-4 no-scrollbar"
            id="sheet-main-content">
            <!-- ========================================== -->
            <!-- SECCIÓN FORMULARIO DE DATOS DE LA SESIÓN   -->
            <!-- ========================================== -->
            <section class="flex flex-col gap-3" id="form-section">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase font-bold text-on-surface-variant tracking-wider">DATOS DE LA
                        SESIÓN</span>
                    <span
                        class="text-[11px] font-semibold text-[#206928] flex items-center gap-1 bg-[#e3f2e1] px-2 py-0.5 rounded-full"
                        id="editable-pill">
                        <span class="material-symbols-outlined text-[13px]">lock_open</span> Editable
                    </span>
                </div>
                <!-- Campo 1: Fecha y hora programada -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-on-surface">Fecha y hora programada</label>
                    <div class="relative w-full">
                        <input
                            class="w-full h-11 pl-3.5 pr-10 rounded-xl bg-white border border-[#E2D9CC] text-on-surface text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all disabled:bg-surface-container/60 disabled:text-outline disabled:border-transparent"
                            id="input-datetime" type="text" value="18 oct 2026, 16:30 hs" />
                        <div
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant flex items-center">
                            <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                        </div>
                    </div>
                </div>
                <!-- Campo 2: Valor de la sesión + REGLA MONEDA -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-on-surface">Valor de la sesión</label>
                    <div class="relative w-full">
                        <input
                            class="w-full h-11 pl-3.5 pr-14 rounded-xl bg-white border border-[#E2D9CC] text-on-surface text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all disabled:bg-surface-container/60 disabled:text-outline disabled:border-transparent font-medium"
                            id="input-price" type="text" value="$ 18.000,00" />
                        <span
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-on-surface-variant">ARS</span>
                    </div>
                    <span class="text-[11px] text-outline font-normal">Moneda: ARS</span>
                </div>
                <!-- Campo 3: Notas de la sesión -->
                <div class="flex flex-col gap-1">
                    <div class="flex justify-between items-center text-xs">
                        <label class="font-semibold text-on-surface">Notas de la sesión <span
                                class="text-outline font-normal">(opcional)</span></label>
                        <span class="text-[11px] text-outline font-medium" id="char-counter">101 / 5000</span>
                    </div>
                    <textarea
                        class="w-full p-3 rounded-xl bg-white border border-[#E2D9CC] text-on-surface text-xs leading-relaxed focus:border-primary focus:ring-1 focus:ring-primary outline-none resize-none transition-all disabled:bg-surface-container/60 disabled:text-outline disabled:border-transparent"
                        id="input-notes" rows="3">Paciente con buena tolerancia al ejercicio pasivo en tren posterior. Continuar con pauta establecida.</textarea>
                    <span class="text-[11px] text-outline leading-tight">Anotaciones operativas internas. No reemplazan
                        la Historia Clínica.</span>
                </div>
                <!-- BOTÓN GUARDAR / CORREGIR DATOS -->
                <div class="mt-1 flex flex-col gap-1.5" id="save-btn-container">
                    <button
                        class="w-full h-11 rounded-xl bg-[#478F49] hover:bg-[#3d7a3e] text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm active:scale-[0.99] transition-all"
                        id="btn-save-data" onclick="triggerSaveFeedback()">
                        <span class="material-symbols-outlined text-[18px]">check</span>
                        <span id="save-btn-label">Guardar cambios</span>
                    </button>
                    <span class="text-center text-[11px] font-semibold text-[#478F49] opacity-0 transition-opacity"
                        id="save-toast">¡Datos de la sesión guardados correctamente!</span>
                </div>
            </section>
            <!-- SEPARADOR SUTIL -->
            <div class="w-full h-px bg-[#e6decf] my-0.5" id="section-divider"></div>
            <!-- ============================================== -->
            <!-- SECCIÓN ACCIONES DE ESTADO DE LA SESIÓN        -->
            <!-- ============================================== -->
            <section class="flex flex-col gap-2.5" id="state-actions-section">
                <div class="flex flex-col">
                    <span class="text-xs uppercase font-bold text-on-surface-variant tracking-wider">ESTADO DE LA
                        SESIÓN</span>
                    <span class="text-xs text-outline leading-relaxed mt-0.5">Las acciones de estado impactan
                        directamente en el progreso del tratamiento.</span>
                </div>
                <div class="flex flex-col gap-3 mt-1.5" id="state-controls-wrapper">
                    <!-- Acción 1: Marcar Completada -->
                    <div class="flex flex-col gap-1.5" id="complete-action-box">
                        <button
                            class="w-full h-12 rounded-xl bg-[#EAF4EB] border border-[#478F49]/40 hover:bg-[#dcf0de] text-[#2A5A2B] font-bold text-sm flex items-center justify-center gap-2 shadow-xs transition-colors"
                            onclick="setVariant('B')">
                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                            <span>Marcar como completada</span>
                        </button>
                        <div class="flex items-center gap-1.5 px-1 text-on-surface-variant text-[11px]">
                            <span class="material-symbols-outlined text-[15px] text-[#478F49]">trending_up</span>
                            <span>Sumará 1 sesión al progreso terapéutico (pasará a 4 de 5).</span>
                        </div>
                    </div>
                    <!-- Acción 2: Cancelar Sesión + REGLA COPY CANCELACIÓN -->
                    <div class="flex flex-col gap-1.5 pt-1" id="cancel-action-box">
                        <button
                            class="w-full h-11 rounded-xl bg-[#FDF2F0] border border-[#da5f42]/30 hover:bg-[#fbdcd6] text-[#da5f42] font-semibold text-xs flex items-center justify-center gap-2 transition-colors"
                            onclick="setVariant('E')">
                            <span class="material-symbols-outlined text-[18px]">cancel</span>
                            <span>Cancelar sesión</span>
                        </button>
                        <!-- TEXTO NORMATIVO EXACTO -->
                        <div class="flex items-start gap-1.5 px-1 text-outline text-[11px] leading-snug">
                            <span class="material-symbols-outlined text-[14px] flex-shrink-0 mt-0.5">info</span>
                            <span>VetZen generará automáticamente una nueva sesión pendiente si es necesaria para
                                mantener la cantidad de sesiones requeridas.</span>
                        </div>
                    </div>
                </div>
            </section>
            <!-- RESULT FEEDBACK STATE CONTAINER (Para Variantes D y F) -->
            <section class="hidden flex flex-col gap-3 py-2" id="feedback-result-view">
                <div class="p-4 rounded-2xl flex flex-col gap-2 items-center text-center" id="feedback-result-card">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" id="feedback-result-icon">
                        <span class="material-symbols-outlined text-[28px]">check_circle</span>
                    </div>
                    <h3 class="font-bold text-base text-on-surface" id="feedback-result-title"></h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed max-w-xs" id="feedback-result-desc"></p>
                    <div class="w-full mt-2 p-2.5 rounded-xl bg-surface-container-lowest text-xs text-left"
                        id="feedback-result-meta"></div>
                </div>
                <div class="flex flex-col gap-2 mt-1" id="feedback-result-actions">
                    <!-- Botones dinámicos según D o F -->
                </div>
            </section>
        </div>
    </div>
    <!-- ============================================== -->
    <!-- DIÁLOGOS MODALES (ConfirmActionDialogs)         -->
    <!-- ============================================== -->
    <!-- MODAL CONFIRMAR COMPLETAR (Variantes B y C) -->
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 hidden" id="modal-complete">
        <div class="absolute inset-0 bg-inverse-surface/60 backdrop-blur-xs" onclick="closeAllOverlays()"></div>
        <div
            class="relative w-full max-w-sm bg-surface-warm rounded-2xl p-5 shadow-2xl flex flex-col gap-3 z-10 border border-[#e2d9cc]">
            <div
                class="w-12 h-12 rounded-full bg-[#EAF4EB] flex items-center justify-center self-center text-[#2A5A2B]">
                <span class="material-symbols-outlined text-[28px]">task_alt</span>
            </div>
            <div class="flex flex-col items-center text-center gap-1.5">
                <h4 class="font-bold text-base text-on-surface">Marcar sesión como completada</h4>
                <p class="text-xs text-on-surface-variant leading-relaxed" id="modal-complete-body">
                    Esta sesión contará para el progreso del tratamiento. El progreso pasará de 3 de 5 a 4 de 5 sesiones
                    completadas.
                </p>
            </div>
            <div class="flex items-center gap-2.5 mt-2">
                <button
                    class="flex-1 h-11 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold transition-colors"
                    onclick="setVariant('A')">
                    Volver
                </button>
                <button
                    class="flex-1 h-11 rounded-xl bg-[#478f49] hover:bg-[#3d7a3f] text-white text-xs font-bold flex items-center justify-center gap-1 shadow-sm transition-colors"
                    onclick="setVariant('D')">
                    <span class="material-symbols-outlined text-[16px]">check</span>
                    <span>Completar sesión</span>
                </button>
            </div>
        </div>
    </div>
    <!-- MODAL CONFIRMAR CANCELAR (Variante E) -->
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 hidden" id="modal-cancel">
        <div class="absolute inset-0 bg-inverse-surface/60 backdrop-blur-xs" onclick="closeAllOverlays()"></div>
        <div
            class="relative w-full max-w-sm bg-surface-warm rounded-2xl p-5 shadow-2xl flex flex-col gap-3 z-10 border border-terracotta/20">
            <div
                class="w-12 h-12 rounded-full bg-terracotta-light flex items-center justify-center self-center text-terracotta">
                <span class="material-symbols-outlined text-[28px]">warning</span>
            </div>
            <div class="flex flex-col items-center text-center gap-1.5">
                <h4 class="font-bold text-base text-on-surface">Cancelar sesión 5</h4>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    La sesión permanecerá en el historial del tratamiento. VetZen generará automáticamente una nueva
                    sesión pendiente si es necesaria para mantener la cantidad de sesiones requeridas.
                </p>
            </div>
            <div class="flex items-center gap-2.5 mt-2">
                <button
                    class="flex-1 h-11 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold transition-colors"
                    onclick="setVariant('A')">
                    Volver
                </button>
                <button
                    class="flex-1 h-11 rounded-xl bg-terracotta hover:bg-[#c95237] text-white text-xs font-bold flex items-center justify-center gap-1 shadow-sm transition-colors"
                    onclick="setVariant('F')">
                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                    <span>Cancelar sesión</span>
                </button>
            </div>
        </div>
    </div>
    <!-- BOTTOM NAVIGATION NORMADA -->
    <nav
        class="fixed bottom-0 w-full z-30 pb-safe bg-surface-warm/95 backdrop-blur-xl border-t border-surface-container-high/60">
        <div class="flex justify-around items-center h-16 px-space-xs">
            <a class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-on-surface-variant hover:text-on-surface transition-colors"
                href="#">
                <span class="material-symbols-outlined text-[22px]">dashboard</span>
                <span class="text-[11px] mt-0.5 font-medium">Inicio</span>
            </a>
            <a class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-primary font-bold transition-colors"
                href="#">
                <span class="material-symbols-outlined text-[22px]">pets</span>
                <span class="text-[11px] mt-0.5">Pacientes</span>
            </a>
            <a class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-on-surface-variant hover:text-on-surface transition-colors"
                href="#">
                <span class="material-symbols-outlined text-[22px]">event_note</span>
                <span class="text-[11px] mt-0.5 font-medium">Solicitudes</span>
            </a>
            <a class="flex-1 flex flex-col items-center justify-center h-full min-h-[44px] text-on-surface-variant hover:text-on-surface transition-colors"
                href="#">
                <span class="material-symbols-outlined text-[22px]">medical_services</span>
                <span class="text-[11px] mt-0.5 font-medium">Gestión</span>
            </a>
        </div>
    </nav>
    <!-- LÓGICA INTERACTIVA DEL VISUALIZADOR DE VARIANTES (A - I) -->
    <script>
        let currentVariant = 'A';

        function setVariant(variantId) {
            currentVariant = variantId;

            // Update variant selector buttons styling
            document.querySelectorAll('.var-btn').forEach(b => {
                b.className =
                    'var-btn px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant hover:bg-surface-container-high whitespace-nowrap text-[11px] font-medium transition-colors';
            });
            const activeBtn = document.getElementById('btn-var-' + variantId);
            if (activeBtn) {
                activeBtn.className =
                    'var-btn px-2.5 py-1 rounded-full bg-primary text-on-primary font-bold shadow-xs whitespace-nowrap text-[11px] transition-colors';
            }

            // Hide modals
            document.getElementById('modal-complete').classList.add('hidden');
            document.getElementById('modal-cancel').classList.add('hidden');

            // Elements to configure
            const titleEl = document.getElementById('sheet-title');
            const badgeEl = document.getElementById('sheet-badge');
            const badgeDot = document.getElementById('sheet-badge-dot');
            const badgeText = document.getElementById('sheet-badge-text');
            const subtitleEl = document.getElementById('sheet-subtitle');
            const infoBanner = document.getElementById('state-info-banner');
            const infoBox = document.getElementById('state-info-box');
            const infoIcon = document.getElementById('state-info-icon');
            const infoText = document.getElementById('state-info-text');

            const formSection = document.getElementById('form-section');
            const stateSection = document.getElementById('state-actions-section');
            const feedbackView = document.getElementById('feedback-result-view');
            const sectionDivider = document.getElementById('section-divider');

            const dtInput = document.getElementById('input-datetime');
            const priceInput = document.getElementById('input-price');
            const notesInput = document.getElementById('input-notes');
            const editablePill = document.getElementById('editable-pill');
            const saveBtnContainer = document.getElementById('save-btn-container');
            const saveBtnLabel = document.getElementById('save-btn-label');
            const completeActionBox = document.getElementById('complete-action-box');
            const cancelActionBox = document.getElementById('cancel-action-box');

            // Default state reset
            formSection.classList.remove('hidden');
            stateSection.classList.remove('hidden');
            feedbackView.classList.add('hidden');
            sectionDivider.classList.remove('hidden');
            infoBanner.classList.add('hidden');
            saveBtnContainer.classList.remove('hidden');

            dtInput.disabled = false;
            priceInput.disabled = false;
            notesInput.disabled = false;
            editablePill.classList.remove('hidden');
            saveBtnLabel.innerText = "Guardar cambios";

            if (variantId === 'A') {
                // Pending — Edición normal
                titleEl.innerText = "Gestionar sesión 5";
                subtitleEl.innerText = "Rehabilitación postoperatoria · Luna";
                badgeEl.className =
                    "px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5 bg-[#FEF6EB] text-[#9C4E05]";
                badgeDot.className = "w-2 h-2 rounded-full bg-[#EA9640]";
                badgeText.innerText = "Pendiente";

                dtInput.value = "18 oct 2026, 16:30 hs";
                priceInput.value = "$ 18.000,00";
                notesInput.value =
                    "Paciente con buena tolerancia al ejercicio pasivo en tren posterior. Continuar con pauta establecida.";

                completeActionBox.classList.remove('hidden');
                cancelActionBox.classList.remove('hidden');

            } else if (variantId === 'B') {
                // Confirmar completar — Progreso parcial
                setVariant('A');
                currentVariant = 'B';
                const modal = document.getElementById('modal-complete');
                document.getElementById('modal-complete-body').innerText =
                    "Esta sesión contará para el progreso del tratamiento. El progreso pasará de 3 de 5 a 4 de 5 sesiones completadas.";
                modal.classList.remove('hidden');

            } else if (variantId === 'C') {
                // Confirmar completar — Alcanza 100%
                setVariant('A');
                currentVariant = 'C';
                const modal = document.getElementById('modal-complete');
                document.getElementById('modal-complete-body').innerText =
                    "Esta sesión completará las 5 sesiones requeridas. Al confirmar, el tratamiento quedará automáticamente Completado.";
                modal.classList.remove('hidden');

            } else if (variantId === 'D') {
                // Resultado completado
                titleEl.innerText = "Sesión 5";
                badgeEl.className =
                    "px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5 bg-[#EAF4EB] text-[#2D5C2E]";
                badgeDot.className = "w-2 h-2 rounded-full bg-[#478F49]";
                badgeText.innerText = "Completada";

                formSection.classList.add('hidden');
                stateSection.classList.add('hidden');
                sectionDivider.classList.add('hidden');
                feedbackView.classList.remove('hidden');

                const fCard = document.getElementById('feedback-result-card');
                fCard.className =
                    "p-5 rounded-2xl bg-[#EAF4EB]/80 flex flex-col gap-2 items-center text-center border border-[#478F49]/30";
                const fIcon = document.getElementById('feedback-result-icon');
                fIcon.className =
                    "w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#478F49] shadow-xs";
                fIcon.innerHTML = `<span class="material-symbols-outlined text-[28px]">verified</span>`;
                document.getElementById('feedback-result-title').innerText = "✓ Sesión completada";
                document.getElementById('feedback-result-desc').innerText =
                    "4 de 5 sesiones completadas (80% del tratamiento). Se actualizó el plan operativo del paciente.";

                document.getElementById('feedback-result-meta').innerHTML = `
          <div class="flex flex-col gap-1 text-[11px] text-on-surface">
            <span class="font-semibold text-primary">Resumen registrado:</span>
            <span>• Fecha: 18 oct 2026, 16:30 hs</span>
            <span>• Arancel: $ 18.000,00 ARS</span>
          </div>
        `;

                document.getElementById('feedback-result-actions').innerHTML = `
          <button onclick="alert('Abriendo formulario de evolución clínica...')" class="w-full h-11 rounded-xl bg-[#478F49] text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition-colors">
            <span class="material-symbols-outlined text-[18px]">edit_note</span>
            <span>Registrar evolución</span>
          </button>
          <button onclick="setVariant('A')" class="w-full h-10 rounded-xl bg-white border border-[#E2D9CC] text-on-surface font-semibold text-xs transition-colors">
            Cerrar
          </button>
        `;

            } else if (variantId === 'E') {
                // Confirmar cancelar
                setVariant('A');
                currentVariant = 'E';
                document.getElementById('modal-cancel').classList.remove('hidden');

            } else if (variantId === 'F') {
                // Resultado cancelado
                titleEl.innerText = "Sesión 5 (Cancelada)";
                badgeEl.className =
                    "px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5 bg-[#FDF2F0] text-[#da5f42]";
                badgeDot.className = "w-2 h-2 rounded-full bg-terracotta";
                badgeText.innerText = "Cancelada";

                formSection.classList.add('hidden');
                stateSection.classList.add('hidden');
                sectionDivider.classList.add('hidden');
                feedbackView.classList.remove('hidden');

                const fCard = document.getElementById('feedback-result-card');
                fCard.className =
                    "p-5 rounded-2xl bg-[#FDF2F0] flex flex-col gap-2 items-center text-center border border-terracotta/25";
                const fIcon = document.getElementById('feedback-result-icon');
                fIcon.className =
                    "w-12 h-12 rounded-full bg-white flex items-center justify-center text-terracotta shadow-xs";
                fIcon.innerHTML = `<span class="material-symbols-outlined text-[28px]">event_busy</span>`;
                document.getElementById('feedback-result-title').innerText = "✓ Sesión cancelada";
                document.getElementById('feedback-result-desc').innerText =
                    "La sesión permanece disponible en el historial.";

                document.getElementById('feedback-result-meta').innerHTML = `
          <div class="flex items-start gap-2 text-[11px] text-terracotta-dark font-medium">
            <span class="material-symbols-outlined text-[16px] flex-shrink-0 mt-0.5">add_circle</span>
            <span>Sesión 6 · Pendiente · Sin programar generada en el plan operativo para cumplir las 5 requeridas.</span>
          </div>
        `;

                document.getElementById('feedback-result-actions').innerHTML = `
          <button onclick="setVariant('A')" class="w-full h-11 rounded-xl bg-white border border-[#E2D9CC] text-on-surface font-semibold text-xs transition-colors">
            Cerrar
          </button>
        `;

            } else if (variantId === 'G') {
                // Completed — Corrección de datos
                titleEl.innerText = "Gestionar sesión 4";
                badgeEl.className =
                    "px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5 bg-[#EAF4EB] text-[#2D5C2E]";
                badgeDot.className = "w-2 h-2 rounded-full bg-[#478F49]";
                badgeText.innerText = "Completada";

                infoBanner.classList.remove('hidden');
                infoBox.className =
                    "p-3 rounded-xl text-xs flex items-start gap-2.5 bg-surface-container-low text-on-surface border border-primary/20";
                infoIcon.className = "material-symbols-outlined text-[18px] text-primary flex-shrink-0 mt-0.5";
                infoIcon.innerText = "verified";
                infoText.innerText =
                    "Estado final. Puedes corregir fecha, precio y notas mientras el tratamiento continúe en curso. No permite cambiar estado.";

                dtInput.value = "15 oct 2026, 17:00 hs";
                priceInput.value = "$ 18.000,00";
                notesInput.value = "Sesión ejecutada con éxito. Aplicación de láser y propiocepción en plato.";

                saveBtnLabel.innerText = "Corregir datos de la sesión";
                stateSection.classList.add('hidden');
                sectionDivider.classList.add('hidden');

            } else if (variantId === 'H') {
                // Cancelled — Corrección de datos
                titleEl.innerText = "Gestionar sesión 3";
                badgeEl.className =
                    "px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5 bg-[#FDF2F0] text-[#da5f42]";
                badgeDot.className = "w-2 h-2 rounded-full bg-terracotta";
                badgeText.innerText = "Cancelada";

                infoBanner.classList.remove('hidden');
                infoBox.className =
                    "p-3 rounded-xl text-xs flex items-start gap-2.5 bg-terracotta-light text-terracotta-dark border border-terracotta/20";
                infoIcon.className = "material-symbols-outlined text-[18px] text-terracotta flex-shrink-0 mt-0.5";
                infoIcon.innerText = "info";
                infoText.innerText =
                    "Estado final. Puedes corregir anotaciones o datos históricos. No permite reactivar ni cambiar estado.";

                dtInput.value = "12 oct 2026, 16:30 hs";
                priceInput.value = "$ 18.000,00";
                notesInput.value = "El tutor canceló con 2 horas de anticipación por indisponibilidad de traslado.";

                saveBtnLabel.innerText = "Corregir datos de la sesión";
                stateSection.classList.add('hidden');
                sectionDivider.classList.add('hidden');

            } else if (variantId === 'I') {
                // Read-only por tratamiento no operativo
                titleEl.innerText = "Sesión 2 (Consulta)";
                badgeEl.className =
                    "px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5 bg-surface-container-high text-on-surface-variant";
                badgeDot.className = "w-2 h-2 rounded-full bg-outline";
                badgeText.innerText = "Solo lectura";

                infoBanner.classList.remove('hidden');
                infoBox.className =
                    "p-3 rounded-xl text-xs flex items-start gap-2.5 bg-surface-container text-on-surface border border-surface-container-high";
                infoIcon.className = "material-symbols-outlined text-[18px] text-outline flex-shrink-0 mt-0.5";
                infoIcon.innerText = "lock";
                infoText.innerText = "Este tratamiento está completado. La sesión está disponible solo para consulta.";

                dtInput.value = "08 oct 2026, 16:30 hs";
                priceInput.value = "$ 18.000,00";
                notesInput.value = "Sesión número 2 archivada en el plan postoperatorio cerrado.";

                dtInput.disabled = true;
                priceInput.disabled = true;
                notesInput.disabled = true;
                editablePill.classList.add('hidden');
                saveBtnContainer.classList.add('hidden');
                stateSection.classList.add('hidden');
                sectionDivider.classList.add('hidden');
            }

            // Update character counter
            document.getElementById('char-counter').innerText = notesInput.value.length + " / 5000";
        }

        function toggleErrorBanner() {
            const banner = document.getElementById('error-banner');
            banner.classList.toggle('hidden');
        }

        function closeAllOverlays() {
            document.getElementById('modal-complete').classList.add('hidden');
            document.getElementById('modal-cancel').classList.add('hidden');
            if (currentVariant === 'B' || currentVariant === 'C' || currentVariant === 'E') {
                setVariant('A');
            }
        }

        function triggerSaveFeedback() {
            const toast = document.getElementById('save-toast');
            toast.style.opacity = '1';
            setTimeout(() => {
                toast.style.opacity = '0';
            }, 2500);
        }

        // Live counter on textarea
        document.getElementById('input-notes').addEventListener('input', function(e) {
            document.getElementById('char-counter').innerText = e.target.value.length + ' / 5000';
        });

        // Iniciar con variante A
        setVariant('A');
    </script>
</body>

</html>
