<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="web_blank" name="shell-type" />
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
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "outline-variant": "#c0c9bb",
                        "on-secondary-container": "#476d12",
                        "error-container": "#ffdad6",
                        "on-primary-fixed": "#002204",
                        "on-primary-container": "#f7fff1",
                        "tertiary-fixed-dim": "#ffb875",
                        "background": "#eefeed",
                        "on-tertiary": "#ffffff",
                        "on-secondary": "#ffffff",
                        "on-surface": "#121e14",
                        "tertiary": "#894d00",
                        "surface-tint": "#236c2b",
                        "surface": "#eefeed",
                        "on-primary-fixed-variant": "#005315",
                        "surface-container-highest": "#d7e7d6",
                        "primary-fixed-dim": "#8dd889",
                        "surface-dim": "#cfdece",
                        "on-tertiary-fixed": "#2d1600",
                        "secondary-fixed-dim": "#a7d46f",
                        "on-background": "#121e14",
                        "secondary": "#43690d",
                        "inverse-surface": "#263328",
                        "on-error": "#ffffff",
                        "on-primary": "#ffffff",
                        "outline": "#707a6d",
                        "on-secondary-fixed-variant": "#2f4f00",
                        "on-tertiary-fixed-variant": "#6b3b00",
                        "inverse-primary": "#8dd889",
                        "tertiary-container": "#ab630a",
                        "secondary-container": "#bfee85",
                        "on-surface-variant": "#40493e",
                        "primary-container": "#3b833f",
                        "on-secondary-fixed": "#102000",
                        "tertiary-fixed": "#ffdcc0",
                        "primary": "#206928",
                        "primary-fixed": "#a8f5a3",
                        "surface-container-high": "#ddecdc",
                        "inverse-on-surface": "#e5f5e4",
                        "error": "#ba1a1a",
                        "surface-container-lowest": "#ffffff",
                        "surface-variant": "#d7e7d6",
                        "surface-container": "#e3f2e1",
                        "secondary-fixed": "#c2f188",
                        "surface-container-low": "#e8f8e7",
                        "on-tertiary-container": "#fffbff",
                        "on-error-container": "#93000a",
                        "surface-bright": "#eefeed"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "space-lg": "1.5rem",
                        "space-sm": "0.5rem",
                        "space-xs": "0.25rem",
                        "space-xl": "2.25rem",
                        "margin-tablet": "1.5rem",
                        "gutter": "1.25rem",
                        "margin": "1rem",
                        "gutter-desktop": "1.5rem",
                        "margin-desktop": "2.5rem",
                        "space-md": "1rem"
                    },
                    "fontFamily": {
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "body-sm": ["Plus Jakarta Sans"],
                        "label-sm": ["Plus Jakarta Sans"],
                        "label-lg": ["Plus Jakarta Sans"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "display-lg-mobile": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "headline-lg-mobile": ["1.5rem", {
                            "lineHeight": "2rem",
                            "fontWeight": "600"
                        }],
                        "label-md": ["0.75rem", {
                            "lineHeight": "1rem",
                            "letterSpacing": "0.02em",
                            "fontWeight": "600"
                        }],
                        "headline-lg": ["2rem", {
                            "lineHeight": "2.5rem",
                            "letterSpacing": "-0.015em",
                            "fontWeight": "600"
                        }],
                        "body-lg": ["1.0625rem", {
                            "lineHeight": "1.625rem",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["1.375rem", {
                            "lineHeight": "1.875rem",
                            "fontWeight": "600"
                        }],
                        "body-md": ["0.9375rem", {
                            "lineHeight": "1.5rem",
                            "fontWeight": "400"
                        }],
                        "body-sm": ["0.8125rem", {
                            "lineHeight": "1.25rem",
                            "fontWeight": "400"
                        }],
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
                        "headline-sm": ["1.125rem", {
                            "lineHeight": "1.625rem",
                            "fontWeight": "600"
                        }],
                        "display-lg-mobile": ["2rem", {
                            "lineHeight": "2.5rem",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "700"
                        }],
                        "display-lg": ["3rem", {
                            "lineHeight": "3.5rem",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }]
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface font-body-md text-body-md text-on-surface antialiased min-h-screen">
    <main class="w-full min-h-screen bg-surface flex flex-col justify-center">
        <div class="flex flex-col w-full min-h-screen">
            <div class="grid grid-cols-1 lg:grid-cols-12 min-h-screen w-full">
                <!-- LADO IZQUIERDO: Identidad, Marca y Bienestar Terapéutico -->
                <div
                    class="lg:col-span-6 xl:col-span-7 bg-surface-container-low flex flex-col justify-between p-8 sm:p-12 xl:p-16 relative overflow-hidden">
                    <!-- Decoración orgánica y botánica de fondo -->
                    <div
                        class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-secondary-container/40 blur-3xl pointer-events-none">
                    </div>
                    <div
                        class="absolute -bottom-24 right-0 w-80 h-80 rounded-full bg-primary-fixed/30 blur-2xl pointer-events-none">
                    </div>
                    <!-- Círculos concéntricos zen decorativos en SVG -->
                    <svg aria-hidden="true"
                        class="absolute -right-20 top-1/4 w-96 h-96 text-outline-variant/30 pointer-events-none select-none"
                        fill="none" viewbox="0 0 400 400">
                        <circle cx="200" cy="200" r="180" stroke="currentColor" stroke-dasharray="8 8"
                            stroke-width="1.5"></circle>
                        <circle cx="200" cy="200" r="130" stroke="currentColor" stroke-width="1.2"></circle>
                        <circle cx="200" cy="200" r="75" stroke="currentColor" stroke-dasharray="4 4"
                            stroke-width="1"></circle>
                    </svg>
                    <!-- Encabezado / Branding Superior -->
                    <div class="relative z-10">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-full bg-surface-container-lowest p-1.5 shadow-sm flex items-center justify-center shrink-0">
                                <img alt="VetZen Isotipo" class="w-full h-full object-contain"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuC6Sf130MpSGp4pl2JL8IB1Bvm_qxdbO3aabEzduY7TuOTxi_6UQvtxtzVabvUpfRvvEfdqDyRXtUUyWkTG6utvcr3GlF1oWgcK4dzNRC5MMGlrpCjEi9SObkHUVHyaJa-HwOV2silTbvzd8yZ_oqnsdefmKSlvfJ_Y1rLo33hIN03CEuktE1DQzvB3-YrFZbBpeMD6R4907x52Ino0jbMFjSMMmBuCTBsDErGPrD7PMVXJaaz_E6H0pw" />
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="font-display-lg text-headline-lg tracking-tight text-on-surface font-bold leading-none">VetZen</span>
                                <span
                                    class="font-label-md text-label-md text-secondary uppercase tracking-wider mt-1">Clínica
                                    Veterinaria &amp; Gestión Terapéutica</span>
                            </div>
                        </div>
                    </div>
                    <!-- Contenido Central Inspirador & Tarjetas Clínicas -->
                    <div class="relative z-10 my-12 max-w-xl">
                        <div
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md shadow-sm mb-6">
                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            <span>Ecosistema Clínico Unificado</span>
                        </div>
                        <h2
                            class="font-headline-lg text-headline-lg text-on-surface font-semibold tracking-tight leading-snug">
                            Bienestar animal, precisión médica y calidez en cada tratamiento.
                        </h2>
                        <p class="font-body-lg text-body-lg text-on-surface-variant mt-4 leading-relaxed">
                            Diseñado para acompañar la labor médica con serenidad, trazabilidad total de historiales
                            clínicos y un vínculo compasivo con cada paciente.
                        </p>
                        <!-- Bloque de Métricas & Indicadores Terapéuticos -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10">
                            <!-- Card Métrica 1 -->
                            <div
                                class="bg-surface-container-lowest/90 backdrop-blur-sm p-5 rounded-xl shadow-sm flex flex-col justify-between">
                                <div class="flex items-center justify-between mb-3">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined text-[22px]">health_and_safety</span>
                                    </span>
                                    <span class="font-headline-md text-headline-md font-bold text-primary">98%</span>
                                </div>
                                <div>
                                    <p class="font-label-lg text-label-lg text-on-surface font-medium">Adherencia
                                        Terapéutica</p>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Seguimiento
                                        guiado en cada etapa</p>
                                </div>
                            </div>
                            <!-- Card Métrica 2 -->
                            <div
                                class="bg-surface-container-lowest/90 backdrop-blur-sm p-5 rounded-xl shadow-sm flex flex-col justify-between">
                                <div class="flex items-center justify-between mb-3">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-secondary">
                                        <span class="material-symbols-outlined text-[22px]">monitoring</span>
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 font-label-sm text-label-sm text-secondary bg-secondary-container/60 px-2.5 py-0.5 rounded-full font-semibold">
                                        Activo
                                    </span>
                                </div>
                                <div>
                                    <p class="font-label-lg text-label-lg text-on-surface font-medium">Monitoreo
                                        Constante</p>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Alertas y pautas
                                        en tiempo real</p>
                                </div>
                            </div>
                        </div>
                        <!-- Cita Clínica de Confianza -->
                        <div class="mt-8 p-4 rounded-xl bg-surface-container/70 flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px] mt-0.5 shrink-0"
                                style="font-variation-settings: 'FILL' 1;">spa</span>
                            <p class="font-body-sm text-body-sm text-on-surface-variant italic">
                                “La armonía entre el rigor clínico y el trato empático transforma sustancialmente la
                                evolución de nuestros pacientes.”
                            </p>
                        </div>
                    </div>
                    <!-- Footer Izquierdo -->
                    <div
                        class="relative z-10 pt-6 flex flex-wrap items-center justify-between gap-4 font-label-sm text-label-sm text-on-surface-variant">
                        <span>© 2024 VetZen Animal Care · Todos los derechos reservados</span>
                        <div class="flex items-center gap-5">
                            <a class="hover:text-primary transition-colors" href="#">Privacidad</a>
                            <span class="text-outline-variant">•</span>
                            <a class="hover:text-primary transition-colors" href="#">Términos del Servicio</a>
                            <span class="text-outline-variant">•</span>
                            <a class="hover:text-primary transition-colors" href="#">Soporte Clínico</a>
                        </div>
                    </div>
                </div>
                <!-- LADO DERECHO: Formulario de Inicio de Sesión -->
                <div
                    class="lg:col-span-6 xl:col-span-5 bg-surface-container-lowest flex flex-col justify-center items-center px-6 sm:px-12 xl:px-16 py-12 relative">
                    <div class="w-full max-w-md mx-auto">
                        <!-- Identificador Móvil / Reducido (solo visible si se colapsa la pantalla) -->
                        <div class="lg:hidden flex items-center gap-3 mb-8">
                            <img alt="VetZen Isotipo" class="w-10 h-10 object-contain"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4c-C2swg--VT9RL-9Iw9YtxZYWt8Nvz0eGkonUqDE95GeTSSP2dr1d_cC4jY3F3fexw1IJVfD71n7AzueRJg3O0Qy9dgXr7gXff4wrm8EhWk8XvKS73gmKd-P22F8n9i_cNGQFl641W8lk1Ec-M0y3h8JSTT5RVyaiH7UW99wYY2hsCKnL5cxx8doMepI7eeTGdZpgMJbEtx9Y1NcMxLyHY_Zk5y79k6UQZw1WjONYpbcP7uDl93M0A" />
                            <div>
                                <span
                                    class="font-headline-md text-headline-md font-bold text-on-surface block leading-none">VetZen</span>
                                <span class="font-label-sm text-label-sm text-secondary">Gestión Terapéutica</span>
                            </div>
                        </div>
                        <!-- Encabezado del Formulario -->
                        <div class="mb-8">
                            <h1 class="font-headline-lg text-headline-lg font-bold text-on-surface tracking-tight">
                                Bienvenido a VetZen
                            </h1>
                            <p class="font-body-md text-body-md text-on-surface-variant mt-2">
                                Gestioná tus pacientes, tratamientos y seguimiento desde un solo lugar.
                            </p>
                        </div>
                        <!-- Formulario Principal -->
                        <form class="space-y-5" id="loginForm" onsubmit="event.preventDefault(); handleLoginSubmit();">
                            <!-- Campo: Correo Electrónico -->
                            <div class="space-y-1.5">
                                <label class="block font-label-lg text-label-lg text-on-surface font-semibold"
                                    for="email">
                                    Correo electrónico
                                </label>
                                <div
                                    class="relative rounded-xl bg-surface-container-low transition-all focus-within:bg-surface-container-lowest focus-within:shadow-md">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                        <span class="material-symbols-outlined text-[20px]">mail</span>
                                    </div>
                                    <input
                                        class="w-full pl-11 pr-4 py-3 bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none rounded-xl"
                                        id="email" name="email" placeholder="doctora@vetzen.com"
                                        required="" type="email" />
                                </div>
                            </div>
                            <!-- Campo: Contraseña -->
                            <div class="space-y-1.5">
                                <label class="block font-label-lg text-label-lg text-on-surface font-semibold"
                                    for="password">
                                    Contraseña
                                </label>
                                <div
                                    class="relative rounded-xl bg-surface-container-low transition-all focus-within:bg-surface-container-lowest focus-within:shadow-md">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                        <span class="material-symbols-outlined text-[20px]">lock</span>
                                    </div>
                                    <input
                                        class="w-full pl-11 pr-11 py-3 bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none rounded-xl"
                                        id="password" name="password" placeholder="••••••••••••" required=""
                                        type="password" />
                                    <button aria-label="Mostrar u ocultar contraseña"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-outline hover:text-on-surface transition-colors cursor-pointer"
                                        id="togglePasswordBtn" onclick="togglePasswordVisibility()" type="button">
                                        <span class="material-symbols-outlined text-[20px]"
                                            id="passwordIcon">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Fila de Utilidad: Recordarme & Recuperación de Contraseña -->
                            <div class="flex items-center justify-between pt-1">
                                <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                    <input class="sr-only peer" id="remember" type="checkbox" />
                                    <div
                                        class="w-5 h-5 rounded-md bg-surface-container flex items-center justify-center transition-colors peer-checked:bg-primary">
                                        <span
                                            class="material-symbols-outlined text-on-primary text-[16px] opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                                    </div>
                                    <span
                                        class="font-body-sm text-body-sm text-on-surface-variant font-medium">Recordarme</span>
                                </label>
                                <a class="font-label-lg text-label-lg text-primary hover:text-on-primary-fixed-variant transition-colors font-semibold"
                                    href="#">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                            <!-- Botón de Acción Principal (CTA) -->
                            <div class="pt-2">
                                <button
                                    class="w-full bg-primary hover:bg-primary-container text-on-primary py-3.5 px-6 rounded-xl font-label-lg text-label-lg font-semibold tracking-wide shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
                                    id="submitButton" type="submit">
                                    <span>Ingresar</span>
                                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                                </button>
                            </div>
                        </form>
                        <!-- Mensaje de Estado / Notificación Dinámica (inicialmente oculto) -->
                        <div class="hidden mt-4 p-3 rounded-lg bg-surface-container font-body-sm text-body-sm text-on-surface-variant flex items-center gap-2"
                            id="loginFeedback">
                            <span class="material-symbols-outlined text-primary text-[18px]">verified_user</span>
                            <span>Comprobando credenciales clínicas...</span>
                        </div>
                        <!-- Aviso y Respaldo Institucional -->
                        <div class="mt-12 pt-6 flex items-center justify-center gap-2 text-center">
                            <span class="material-symbols-outlined text-secondary text-[18px]"
                                style="font-variation-settings: 'FILL' 1;">verified</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">
                                Acceso seguro para profesionales veterinarios y tutores autorizados.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('password');
                const passwordIcon = document.getElementById('passwordIcon');
                if (!passwordInput || !passwordIcon) return;

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.textContent = 'visibility_off';
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.textContent = 'visibility';
                }
            }

            function handleLoginSubmit() {
                const feedback = document.getElementById('loginFeedback');
                const submitBtn = document.getElementById('submitButton');
                if (!feedback || !submitBtn) return;

                submitBtn.classList.add('opacity-80', 'pointer-events-none');
                feedback.classList.remove('hidden');

                setTimeout(() => {
                    feedback.innerHTML =
                        '<span class="material-symbols-outlined text-primary text-[18px]">check_circle</span> <span>Sesión autorizada. Redirigiendo a la agenda terapéutica...</span>';
                }, 1000);
            }
        </script>
    </main>
</body>

</html>
