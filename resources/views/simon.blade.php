<!DOCTYPE html>

<html class="h-full" lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>VetZen - Asistente Simón &amp; Gestión de Pacientes</title>
    <!-- Tailwind CSS CDN with plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#F2FBF1',
                            100: '#E8F8E7',
                            200: '#CBEFC9',
                            500: '#478F49',
                            600: '#3A773C',
                            700: '#2E6130',
                            800: '#265328',
                            900: '#1A391C',
                        },
                        surface: {
                            cream: '#FDFAF3',
                            card: '#FFFFFF',
                            subtle: '#F7F6F0',
                            border: '#E8E5DD',
                        }
                    }
                }
            }
        }
    </script>
    <style data-purpose="custom-scrollbars">
        /* Custom subtle scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #D8D4CA;
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #BCB8AD;
        }
    </style>
    <style data-purpose="animations">
        @keyframes pulse-dot {

            0%,
            80%,
            100% {
                transform: scale(0.6);
                opacity: 0.4;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .typing-dot:nth-child(1) {
            animation: pulse-dot 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(2) {
            animation: pulse-dot 1.4s infinite ease-in-out 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation: pulse-dot 1.4s infinite ease-in-out 0.4s;
        }
    </style>
</head>

<body class="h-full bg-[#FDFAF3] text-[#2C332D] antialiased flex flex-col font-sans select-none overflow-hidden">
    <!-- BEGIN: ApplicationLayout -->
    <div class="flex h-screen w-full overflow-hidden">
        <!-- BEGIN: LeftSidebar -->
        <aside class="w-64 bg-white border-r border-[#E8E5DD] flex flex-col justify-between shrink-0 z-20">
            <div class="flex flex-col">
                <!-- Brand / Clinic Logo Header -->
                <div class="h-16 flex items-center gap-3 px-5 border-b border-[#E8E5DD]/60">
                    <div
                        class="w-9 h-9 rounded-xl bg-brand-100 flex items-center justify-center text-brand-700 shadow-sm">
                        <!-- Vet paw icon -->
                        <svg class="w-5 h-5 fill-current" viewbox="0 0 24 24">
                            <path
                                d="M12 14c-1.66 0-3 1.34-3 3 0 1.66 1.34 3 3 3s3-1.34 3-3c0-1.66-1.34-3-3-3zm-5.5-4c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5zm11 0c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5zm-8-5c-1.38 0-2.5 1.12-2.5 2.5S7.62 10 9 10s2.5-1.12 2.5-2.5S10.38 5 9 5zm6 0c-1.38 0-2.5 1.12-2.5 2.5S13.62 10 15 10s2.5-1.12 2.5-2.5S16.38 5 15 5z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-base text-gray-900 leading-tight">VetZen</h1>
                        <p class="text-[11px] text-gray-400 font-medium tracking-wide">Clínica Veterinaria</p>
                    </div>
                </div>
                <!-- Navigation Links -->
                <div class="p-3 space-y-6 overflow-y-auto">
                    <!-- Section: General -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">General</span>
                        <div class="mt-2 space-y-1">
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-[#F7F6F0] transition"
                                href="#">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                Inicio
                            </a>
                            <button
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-brand-800 bg-brand-50 hover:bg-brand-100 transition group text-left"
                                onclick="switchSimonState('conversation')">
                                <div class="flex items-center gap-3">
                                    <div class="relative w-4 h-4">
                                        <img alt="Simón Avatar" class="w-4 h-4 rounded-full object-cover"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBApsRraUt-XIXBxHsz43QLSa9vMYFujivJNalG_AjEfC1pO3qHgqgCFGUrhhtlSToXNQJ95YNrxRr9a4zV4vGRHPyIKDQkn8ONteM8gIKMohhpavlEM-oqTCZB5_2RQhY0MWxWOLAoT7Rx0Qyj-obQN3WisHmJpLWqAKrAHnKv1wYUdiAVFoIkFz0xSqfjuW0taTdy2mdegFSiHWUb7z7G2SbUdaYnvpgfrqcKQSCD" />
                                        <span
                                            class="absolute -top-0.5 -right-0.5 w-1.5 h-1.5 bg-brand-500 rounded-full ring-1 ring-white"></span>
                                    </div>
                                    <span>Simón (IA)</span>
                                </div>
                                <span
                                    class="text-[10px] bg-brand-200/80 text-brand-800 px-1.5 py-0.5 rounded-full font-semibold">Ayuda</span>
                            </button>
                        </div>
                    </div>
                    <!-- Section: Pacientes -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pacientes</span>
                        <div class="mt-2 space-y-1">
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-[#F7F6F0] transition"
                                href="#">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                Clientes
                            </a>
                            <!-- Active Route: Pacientes -->
                            <a class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-semibold text-brand-800 bg-brand-100/90 shadow-xs"
                                href="#">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor"
                                        viewbox="0 0 24 24">
                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    </svg>
                                    Pacientes
                                </div>
                                <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            </a>
                        </div>
                    </div>
                    <!-- Section: Atención -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Atención</span>
                        <div class="mt-2 space-y-1">
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-[#F7F6F0] transition"
                                href="#">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                Solicitudes de atención
                            </a>
                        </div>
                    </div>
                    <!-- Section: Catálogo Clínico -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Catálogo
                            Clínico</span>
                        <div class="mt-2 space-y-1">
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-[#F7F6F0] transition"
                                href="#">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                Servicios clínicos
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-[#F7F6F0] transition"
                                href="#">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                Procedimientos clínicos
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-[#F7F6F0] transition"
                                href="#">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                Plantillas de tratamiento
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Left Sidebar Footer: Doctor Profile & Quick Simon Badge -->
            <div class="p-3 border-t border-[#E8E5DD]/70 bg-gradient-to-t from-brand-50/40 to-white">
                <div class="flex items-center justify-between p-2 rounded-xl hover:bg-white transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-full bg-brand-500/20 text-brand-800 flex items-center justify-center font-bold text-xs ring-2 ring-brand-500/30">
                            VR
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-800">Dra. Valeria Ramos</p>
                            <p class="text-[11px] text-gray-400">Veterinaria Médica</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </div>
            </div>
        </aside>
        <!-- END: LeftSidebar -->
        <!-- BEGIN: MainWorkspace -->
        <main class="flex-1 flex flex-col min-w-0 bg-[#FDFAF3] overflow-hidden">
            <!-- Top Clinical Header & Breadcrumb -->
            <header class="h-16 bg-white border-b border-[#E8E5DD] px-8 flex items-center justify-between shrink-0">
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-gray-500">
                    <span class="hover:text-gray-700 cursor-pointer">VetZen</span>
                    <span class="text-gray-300">/</span>
                    <span class="hover:text-gray-700 cursor-pointer">Pacientes</span>
                    <span class="text-gray-300">/</span>
                    <span class="hover:text-gray-700 font-medium text-gray-700">Luna</span>
                    <span class="text-gray-300">/</span>
                    <span class="hover:text-gray-700 cursor-pointer">Tratamientos</span>
                    <span class="text-gray-300">/</span>
                    <span class="font-semibold text-brand-800 bg-brand-100 px-2 py-0.5 rounded-md">Rehabilitación
                        Postoperatoria</span>
                </nav>
                <div class="flex items-center gap-4">
                    <!-- Quick Status Indicator -->
                    <div
                        class="flex items-center gap-2 px-3 py-1 bg-amber-50 border border-amber-200/60 rounded-full text-[11px] font-medium text-amber-800">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        1 sesión requiere acción
                    </div>
                    <!-- New Patient Action Button -->
                    <button
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            </path>
                        </svg>
                        Nueva Consulta
                    </button>
                </div>
            </header>
            <!-- Scrollable Main Clinical Content -->
            <div class="flex-1 overflow-y-auto p-8 space-y-6">
                <!-- Luna's Context Card -->
                <section class="bg-white rounded-2xl border border-[#E8E5DD] p-6 shadow-xs relative">
                    <div class="flex items-start justify-between">
                        <div class="flex gap-5 items-center">
                            <div
                                class="w-16 h-16 rounded-2xl bg-[#FFF6E5] border border-amber-200/70 flex items-center justify-center text-3xl shadow-inner">
                                🐕
                            </div>
                            <div>
                                <div class="flex items-center gap-3">
                                    <h2 class="text-2xl font-bold text-gray-900">Luna</h2>
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-brand-100 text-brand-800 border border-brand-200">
                                        Tratamiento Activo
                                    </span>
                                    <span class="text-xs text-gray-400 font-mono">ID: #PA-8924</span>
                                </div>
                                <div class="flex items-center gap-4 mt-1.5 text-xs text-gray-500">
                                    <span class="font-medium text-gray-700">Golden Retriever</span>
                                    <span>•</span>
                                    <span>Hembra (Esterilizada)</span>
                                    <span>•</span>
                                    <span>4 años (28 kg)</span>
                                    <span>•</span>
                                    <span>Tutor: <strong class="text-gray-700 font-semibold">María González</strong>
                                        (+54 9 11 5821-9920)</span>
                                </div>
                            </div>
                        </div>
                        <!-- Patient Tabs -->
                        <div
                            class="flex items-center gap-2 bg-[#F7F6F0] p-1 rounded-xl text-xs font-medium border border-[#E8E5DD]">
                            <button class="px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900">Resumen</button>
                            <button class="px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900">Historia
                                clínica</button>
                            <button
                                class="px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900">Solicitudes</button>
                            <button
                                class="px-3 py-1.5 rounded-lg bg-white text-brand-800 font-bold shadow-xs">Tratamientos</button>
                        </div>
                    </div>
                </section>
                <!-- Treatment Overview & Progress Card -->
                <section class="bg-white rounded-2xl border border-[#E8E5DD] p-6 shadow-xs">
                    <div
                        class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-gray-100 gap-4">
                        <div>
                            <div
                                class="flex items-center gap-2 text-xs font-bold uppercase text-brand-700 tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                                Plan Fisiátrico
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mt-1">Rehabilitación Postoperatoria y Terapia
                                Motora</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Indicado para recuperación de ligamento cruzado
                                anterior derecho (TPLO).</p>
                        </div>
                        <!-- Progress stats badge -->
                        <div
                            class="flex items-center gap-4 bg-brand-50/70 border border-brand-200/60 px-4 py-3 rounded-xl">
                            <div class="text-right">
                                <p class="text-xs text-brand-800 font-semibold">4 de 6 sesiones completadas</p>
                                <p class="text-[11px] text-brand-600">67% del tratamiento concluido</p>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full border-4 border-brand-200 border-t-brand-500 flex items-center justify-center font-bold text-xs text-brand-800">
                                67%
                            </div>
                        </div>
                    </div>
                    <!-- Progress track bar -->
                    <div class="mt-4 w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-brand-500 h-2 rounded-full" style="width: 66.6%;"></div>
                    </div>
                    <!-- Session List Header -->
                    <div class="flex items-center justify-between mt-8 mb-4">
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-sm text-gray-900">Sesiones de Tratamiento</h4>
                            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">6
                                sesiones en total</span>
                        </div>
                        <button class="text-xs font-semibold text-brand-700 hover:text-brand-800 hover:underline">
                            + Agregar sesión extra
                        </button>
                    </div>
                    <!-- Session Table/List -->
                    <div class="space-y-3">
                        <!-- Sesión 1 (Completada) -->
                        <div
                            class="flex items-center justify-between p-3.5 rounded-xl border border-gray-100 bg-gray-50/60 hover:bg-white hover:border-[#E8E5DD] transition">
                            <div class="flex items-center gap-3.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                    ✓
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-gray-900">Sesión 1</span>
                                        <span class="text-[11px] text-gray-400">02 Sep 2026 • 10:30 hs</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Laserterapia + Movilización pasiva inicial</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200/60">Completada</span>
                                <button class="text-xs text-gray-400 hover:text-gray-700 font-medium">Ver
                                    notas</button>
                            </div>
                        </div>
                        <!-- Sesión 2 (Completada) -->
                        <div
                            class="flex items-center justify-between p-3.5 rounded-xl border border-gray-100 bg-gray-50/60 hover:bg-white hover:border-[#E8E5DD] transition">
                            <div class="flex items-center gap-3.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                    ✓
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-gray-900">Sesión 2</span>
                                        <span class="text-[11px] text-gray-400">10 Sep 2026 • 11:00 hs</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Electroestimulación suave (TENS) y ejercicios
                                        propioceptivos</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200/60">Completada</span>
                                <button class="text-xs text-gray-400 hover:text-gray-700 font-medium">Ver
                                    notas</button>
                            </div>
                        </div>
                        <!-- Sesión 3 (Completada) -->
                        <div
                            class="flex items-center justify-between p-3.5 rounded-xl border border-gray-100 bg-gray-50/60 hover:bg-white hover:border-[#E8E5DD] transition">
                            <div class="flex items-center gap-3.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                    ✓
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-gray-900">Sesión 3</span>
                                        <span class="text-[11px] text-gray-400">18 Sep 2026 • 16:00 hs</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Caminata en cinta subacuática (12 minutos a
                                        ritmo controlado)</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200/60">Completada</span>
                                <button class="text-xs text-gray-400 hover:text-gray-700 font-medium">Ver
                                    notas</button>
                            </div>
                        </div>
                        <!-- Sesión 4 (Completada) -->
                        <div
                            class="flex items-center justify-between p-3.5 rounded-xl border border-gray-100 bg-gray-50/60 hover:bg-white hover:border-[#E8E5DD] transition">
                            <div class="flex items-center gap-3.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                    ✓
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-gray-900">Sesión 4</span>
                                        <span class="text-[11px] text-gray-400">25 Sep 2026 • 15:30 hs</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Ejercicios de balance en plato de Freeman y
                                        fortalecimiento de cuádriceps</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200/60">Completada</span>
                                <button class="text-xs text-gray-400 hover:text-gray-700 font-medium">Ver
                                    notas</button>
                            </div>
                        </div>
                        <!-- Sesión 5 (PENDIENTE - OBJETO DE LA CONSULTA A SIMÓN) -->
                        <div
                            class="flex items-center justify-between p-4 rounded-xl border-2 border-brand-500/40 bg-brand-50/30 hover:bg-brand-50/60 transition shadow-xs">
                            <div class="flex items-center gap-3.5">
                                <div
                                    class="w-9 h-9 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs ring-2 ring-amber-300/40">
                                    5
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-gray-900">Sesión 5 (Hoy)</span>
                                        <span
                                            class="text-[11px] text-amber-800 font-semibold bg-amber-100/80 px-2 py-0.5 rounded-md">Pendiente
                                            de registro</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-0.5">02 Oct 2026 • Evaluación de rango articular
                                        y propiocepción avanzada</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <button
                                    class="px-3.5 py-1.5 bg-brand-500 hover:bg-brand-600 text-white font-semibold text-xs rounded-lg shadow-xs transition flex items-center gap-1.5"
                                    onclick="handleSessionCompleteDemo()">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewbox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"></path>
                                    </svg>
                                    Gestionar sesión
                                </button>
                            </div>
                        </div>
                        <!-- Sesión 6 (Programada) -->
                        <div
                            class="flex items-center justify-between p-3.5 rounded-xl border border-gray-100 bg-white hover:border-[#E8E5DD] transition opacity-80">
                            <div class="flex items-center gap-3.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-xs">
                                    6
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-gray-800">Sesión 6 (Cierre)</span>
                                        <span class="text-[11px] text-gray-400">09 Oct 2026 • 11:00 hs</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Evaluación final funcional y alta de
                                        fisioterapia motora</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Programada</span>
                                <button
                                    class="text-xs text-gray-400 hover:text-gray-700 font-medium">Reagendar</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        <!-- END: MainWorkspace -->
        <!-- BEGIN: SimonAssistantPanel -->
        <aside
            class="w-[400px] bg-white border-l border-[#E8E5DD] flex flex-col justify-between shrink-0 shadow-lg z-30 transition-all duration-300"
            id="simon-panel">
            <!-- Panel Header -->
            <div class="p-4 border-b border-[#E8E5DD] bg-white">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <img alt="Avatar de Simón el pastor blanco"
                                class="w-11 h-11 rounded-full object-cover ring-2 ring-brand-500/20 shadow-sm"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBApsRraUt-XIXBxHsz43QLSa9vMYFujivJNalG_AjEfC1pO3qHgqgCFGUrhhtlSToXNQJ95YNrxRr9a4zV4vGRHPyIKDQkn8ONteM8gIKMohhpavlEM-oqTCZB5_2RQhY0MWxWOLAoT7Rx0Qyj-obQN3WisHmJpLWqAKrAHnKv1wYUdiAVFoIkFz0xSqfjuW0taTdy2mdegFSiHWUb7z7G2SbUdaYnvpgfrqcKQSCD" />
                            <span
                                class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white"></span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-gray-900 text-sm leading-tight">Simón</h3>
                                <span
                                    class="text-[10px] font-bold bg-brand-100 text-brand-800 px-2 py-0.5 rounded-full border border-brand-200">
                                    Asistente VetZen
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-0.5">Soporte y guía operativa clínica</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <!-- Minimize / Close Toggle Button -->
                        <button class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition"
                            onclick="toggleSimonPanel()" title="Cerrar panel de Simón">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Context Pill Badge -->
                <div class="mt-3 flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                    <div
                        class="inline-flex items-center gap-1.5 bg-brand-100 text-brand-800 text-[11px] font-semibold px-2.5 py-1 rounded-full">
                        <svg class="w-3.5 h-3.5 text-brand-700" fill="none" stroke="currentColor"
                            viewbox="0 0 24 24">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>Estás en: Tratamientos &gt; Sesiones</span>
                    </div>
                    <span class="text-[10px] text-gray-400">Contexto activo</span>
                </div>
                <!-- State Switcher Pills Bar (Interactive State Showcase) -->
                <div class="mt-3 pt-2 border-t border-dashed border-gray-200">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1.5">Modo de
                        visualización:</span>
                    <div class="grid grid-cols-3 gap-1 text-[11px]">
                        <button
                            class="px-2 py-1 rounded font-medium bg-brand-100 text-brand-800 border border-brand-300"
                            id="btn-state-convo" onclick="switchSimonState('conversation')">
                            Respuesta
                        </button>
                        <button class="px-2 py-1 rounded font-medium bg-gray-100 text-gray-600 hover:bg-gray-200"
                            id="btn-state-load" onclick="switchSimonState('loading')">
                            Procesando
                        </button>
                        <button class="px-2 py-1 rounded font-medium bg-gray-100 text-gray-600 hover:bg-gray-200"
                            id="btn-state-empty" onclick="switchSimonState('nodata')">
                            Sin datos
                        </button>
                    </div>
                </div>
            </div>
            <!-- Main Conversation Body Area -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-brand-50/20" id="simon-chat-container">
                <!-- STATE 1: Standard Active Conversation (Respuesta + Fuentes) -->
                <div class="space-y-4" id="state-conversation">
                    <!-- User Prompt Bubble -->
                    <div class="flex justify-end">
                        <div
                            class="max-w-[85%] bg-brand-700 text-white rounded-2xl rounded-tr-xs p-3 text-xs leading-relaxed shadow-xs">
                            <p>¿Cómo completo esta sesión?</p>
                            <span class="text-[9px] text-brand-200 mt-1 block text-right font-medium">10:42 hs</span>
                        </div>
                    </div>
                    <!-- Simon Assistant Response Bubble -->
                    <div class="flex gap-2.5 items-start">
                        <img alt="Simón Avatar"
                            class="w-7 h-7 rounded-full object-cover shrink-0 mt-0.5 ring-1 ring-brand-300"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBApsRraUt-XIXBxHsz43QLSa9vMYFujivJNalG_AjEfC1pO3qHgqgCFGUrhhtlSToXNQJ95YNrxRr9a4zV4vGRHPyIKDQkn8ONteM8gIKMohhpavlEM-oqTCZB5_2RQhY0MWxWOLAoT7Rx0Qyj-obQN3WisHmJpLWqAKrAHnKv1wYUdiAVFoIkFz0xSqfjuW0taTdy2mdegFSiHWUb7z7G2SbUdaYnvpgfrqcKQSCD" />
                        <div
                            class="max-w-[90%] bg-white border border-[#E8E5DD] rounded-2xl rounded-tl-xs p-3.5 text-xs text-gray-800 shadow-xs space-y-2.5">
                            <p class="font-medium text-gray-900">
                                Para completar la sesión pendiente de Luna:
                            </p>
                            <ol
                                class="list-decimal list-inside space-y-1 text-gray-700 pl-0.5 leading-relaxed font-normal">
                                <li>Seleccioná la <span class="font-semibold text-brand-800">Sesión 5
                                        (Pendiente)</span>.</li>
                                <li>Hacé clic en el botón <strong class="font-semibold text-gray-900">"Gestionar
                                        sesión"</strong>.</li>
                                <li>Revisá los signos vitales y observaciones terapéuticas.</li>
                                <li>Presioná <span
                                        class="font-bold text-brand-700 bg-brand-50 px-1 py-0.5 rounded">Completar
                                        sesión</span> para cerrar el historial.</li>
                            </ol>
                            <!-- Fuentes / Documentation References -->
                            <div class="pt-2.5 mt-2 border-t border-gray-100 text-[11px] text-gray-500">
                                <span
                                    class="font-bold text-gray-700 block mb-1 text-[10px] uppercase tracking-wide">Fuentes
                                    consultadas:</span>
                                <ul class="space-y-1">
                                    <li>
                                        <a class="inline-flex items-center gap-1.5 text-brand-700 hover:text-brand-900 font-medium hover:underline"
                                            href="#">
                                            <svg class="w-3 h-3 text-brand-500" fill="none" stroke="currentColor"
                                                viewbox="0 0 24 24">
                                                <path
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                </path>
                                            </svg>
                                            • Gestionar sesiones de un tratamiento
                                        </a>
                                    </li>
                                    <li>
                                        <a class="inline-flex items-center gap-1.5 text-brand-700 hover:text-brand-900 font-medium hover:underline"
                                            href="#">
                                            <svg class="w-3 h-3 text-brand-500" fill="none" stroke="currentColor"
                                                viewbox="0 0 24 24">
                                                <path
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                </path>
                                            </svg>
                                            • Qué es una sesión del tratamiento
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- STATE 2: Simon Processing / Thinking (Hidden by default) -->
                <div class="hidden space-y-4" id="state-loading">
                    <div class="flex justify-end">
                        <div
                            class="max-w-[85%] bg-brand-700 text-white rounded-2xl rounded-tr-xs p-3 text-xs shadow-xs">
                            <p>¿Qué ocurre si el tutor cancela la próxima sesión?</p>
                        </div>
                    </div>
                    <div class="flex gap-2.5 items-start">
                        <img alt="Simón Avatar"
                            class="w-7 h-7 rounded-full object-cover shrink-0 ring-1 ring-brand-300"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBApsRraUt-XIXBxHsz43QLSa9vMYFujivJNalG_AjEfC1pO3qHgqgCFGUrhhtlSToXNQJ95YNrxRr9a4zV4vGRHPyIKDQkn8ONteM8gIKMohhpavlEM-oqTCZB5_2RQhY0MWxWOLAoT7Rx0Qyj-obQN3WisHmJpLWqAKrAHnKv1wYUdiAVFoIkFz0xSqfjuW0taTdy2mdegFSiHWUb7z7G2SbUdaYnvpgfrqcKQSCD" />
                        <div
                            class="bg-white border border-[#E8E5DD] rounded-2xl rounded-tl-xs p-3.5 text-xs text-gray-700 shadow-xs flex items-center gap-3">
                            <div class="flex items-center gap-1.5 py-1">
                                <span class="w-2 h-2 rounded-full bg-brand-500 typing-dot"></span>
                                <span class="w-2 h-2 rounded-full bg-brand-500 typing-dot"></span>
                                <span class="w-2 h-2 rounded-full bg-brand-500 typing-dot"></span>
                            </div>
                            <span class="text-xs text-gray-500 italic">Simón está buscando en la guía de
                                VetZen...</span>
                        </div>
                    </div>
                </div>
                <!-- STATE 3: No information found state (Hidden by default) -->
                <div class="hidden space-y-4" id="state-nodata">
                    <div class="flex justify-end">
                        <div
                            class="max-w-[85%] bg-brand-700 text-white rounded-2xl rounded-tr-xs p-3 text-xs shadow-xs">
                            <p>¿A qué hora cierra la farmacia de la avenida principal?</p>
                        </div>
                    </div>
                    <div class="flex gap-2.5 items-start">
                        <img alt="Simón Avatar"
                            class="w-7 h-7 rounded-full object-cover shrink-0 ring-1 ring-brand-300"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBApsRraUt-XIXBxHsz43QLSa9vMYFujivJNalG_AjEfC1pO3qHgqgCFGUrhhtlSToXNQJ95YNrxRr9a4zV4vGRHPyIKDQkn8ONteM8gIKMohhpavlEM-oqTCZB5_2RQhY0MWxWOLAoT7Rx0Qyj-obQN3WisHmJpLWqAKrAHnKv1wYUdiAVFoIkFz0xSqfjuW0taTdy2mdegFSiHWUb7z7G2SbUdaYnvpgfrqcKQSCD" />
                        <div
                            class="max-w-[90%] bg-amber-50/70 border border-amber-200/80 rounded-2xl rounded-tl-xs p-3.5 text-xs text-amber-900 shadow-xs space-y-2">
                            <div class="flex items-center gap-1.5 font-bold text-amber-800">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                <span>Sin suficiente información</span>
                            </div>
                            <p class="leading-relaxed text-amber-900/90">
                                No encontré información sobre eso en la documentación de VetZen. ¿Querés consultarme
                                sobre sesiones, historias clínicas o pacientes?
                            </p>
                            <div class="pt-1">
                                <button class="text-brand-700 font-bold hover:underline text-[11px]"
                                    onclick="switchSimonState('conversation')">
                                    ← Volver a consulta anterior
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Input Footer Area -->
            <div class="p-3.5 border-t border-[#E8E5DD] bg-white">
                <form class="relative flex items-center" onsubmit="handleSimonInput(event)">
                    <input
                        class="w-full text-xs bg-[#F7F6F0] border border-[#E8E5DD] rounded-xl pl-3.5 pr-10 py-2.5 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition"
                        id="simon-input" placeholder="Preguntale a Simón..." type="text" />
                    <button
                        class="absolute right-1.5 p-1.5 bg-brand-500 hover:bg-brand-600 text-white rounded-lg transition shadow-xs flex items-center justify-center"
                        title="Enviar mensaje" type="submit">
                        <!-- Send arrow icon -->
                        <svg class="w-3.5 h-3.5 transform rotate-90" fill="none" stroke="currentColor"
                            viewbox="0 0 24 24">
                            <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"></path>
                        </svg>
                    </button>
                </form>
                <!-- Clinical Disclaimer Note -->
                <p class="text-[10px] text-center text-gray-400 mt-2 leading-tight px-2">
                    Simón ayuda a usar VetZen. No brinda asistencia médica ni diagnósticos.
                </p>
            </div>
        </aside>
        <!-- END: SimonAssistantPanel -->
    </div>
    <!-- END: ApplicationLayout -->
    <!-- BEGIN: PersistentSimonFloatingTrigger (When panel is minimized) -->
    <button
        class="fixed bottom-6 right-6 hidden z-50 flex items-center gap-3 bg-white border border-[#E8E5DD] p-1.5 pr-4 rounded-full shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-200 group"
        id="simon-floating-trigger" onclick="toggleSimonPanel()" title="Abrir Simón">
        <div class="relative">
            <img alt="Simón Avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-brand-500"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBApsRraUt-XIXBxHsz43QLSa9vMYFujivJNalG_AjEfC1pO3qHgqgCFGUrhhtlSToXNQJ95YNrxRr9a4zV4vGRHPyIKDQkn8ONteM8gIKMohhpavlEM-oqTCZB5_2RQhY0MWxWOLAoT7Rx0Qyj-obQN3WisHmJpLWqAKrAHnKv1wYUdiAVFoIkFz0xSqfjuW0taTdy2mdegFSiHWUb7z7G2SbUdaYnvpgfrqcKQSCD" />
            <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-brand-500 rounded-full ring-2 ring-white"></span>
        </div>
        <div class="text-left">
            <span class="text-xs font-bold text-gray-900 block leading-none">Preguntale a Simón</span>
            <span class="text-[10px] text-brand-700 font-medium">Asistente VetZen</span>
        </div>
    </button>
    <!-- END: PersistentSimonFloatingTrigger -->
    <!-- BEGIN: ClientScriptHandlers -->
    <script data-purpose="simon-state-management">
        /**
         * Switch between visual demonstration states of Simón
         * @param {'conversation' | 'loading' | 'nodata'} stateName
         */
        function switchSimonState(stateName) {
            const stateConvo = document.getElementById('state-conversation');
            const stateLoad = document.getElementById('state-loading');
            const stateEmpty = document.getElementById('state-nodata');

            const btnConvo = document.getElementById('btn-state-convo');
            const btnLoad = document.getElementById('btn-state-load');
            const btnEmpty = document.getElementById('btn-state-empty');

            // Reset all views
            stateConvo.classList.add('hidden');
            stateLoad.classList.add('hidden');
            stateEmpty.classList.add('hidden');

            // Reset buttons styles
            const defaultBtn = "px-2 py-1 rounded font-medium bg-gray-100 text-gray-600 hover:bg-gray-200";
            const activeBtn =
                "px-2 py-1 rounded font-medium bg-brand-100 text-brand-800 border border-brand-300 font-semibold";

            btnConvo.className = defaultBtn;
            btnLoad.className = defaultBtn;
            btnEmpty.className = defaultBtn;

            if (stateName === 'conversation') {
                stateConvo.classList.remove('hidden');
                btnConvo.className = activeBtn;
            } else if (stateName === 'loading') {
                stateLoad.classList.remove('hidden');
                btnLoad.className = activeBtn;
            } else if (stateName === 'nodata') {
                stateEmpty.classList.remove('hidden');
                btnEmpty.className = activeBtn;
            }
        }

        /**
         * Minimize or restore Simon's right panel
         */
        function toggleSimonPanel() {
            const panel = document.getElementById('simon-panel');
            const floatingTrigger = document.getElementById('simon-floating-trigger');

            if (panel.classList.contains('hidden')) {
                // Open
                panel.classList.remove('hidden');
                floatingTrigger.classList.add('hidden');
            } else {
                // Close
                panel.classList.add('hidden');
                floatingTrigger.classList.remove('hidden');
            }
        }

        /**
         * Form submit mock handler
         */
        function handleSimonInput(e) {
            e.preventDefault();
            const input = document.getElementById('simon-input');
            if (!input.value.trim()) return;

            // Simulate a quick lookup state
            switchSimonState('loading');
            setTimeout(() => {
                switchSimonState('conversation');
                input.value = '';
            }, 1200);
        }

        /**
         * Interactive demo click on session 5
         */
        function handleSessionCompleteDemo() {
            // If panel is closed, open it
            const panel = document.getElementById('simon-panel');
            if (panel.classList.contains('hidden')) {
                toggleSimonPanel();
            }
            switchSimonState('conversation');
        }
    </script>
    <!-- END: ClientScriptHandlers -->
</body>

</html>
