<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Ambulancias') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .text-gradient {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-600 bg-slate-50 selection:bg-blue-500 selection:text-white overflow-x-hidden">

    <!-- Decorative Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-sky-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 border-b border-transparent" 
         x-data="{ scrolled: false }" 
         :class="{ 'glass-nav border-slate-200 shadow-sm': scrolled, 'bg-transparent': !scrolled }"
         @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                        <div class="relative flex items-center justify-center w-12 h-12 rounded-xl group-hover:scale-110 transition-transform duration-300">
                            <!-- Simple Logo Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-blue-600">
                                <path d="M10 10H6"></path>
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14"></path>
                                <path d="M8 8v4"></path>
                                <path d="M9 18h6"></path>
                                <circle cx="17" cy="18" r="2"></circle>
                                <circle cx="7" cy="18" r="2"></circle>
                            </svg>
                        </div>
                        <span class="font-bold text-xl text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">Ambulancias</span>
                    </a>
                </div>
                <div class="flex items-center gap-6">
                    <a href="/admin/login" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors relative group">
                        Iniciar Sesión
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="/admin/register" class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-full text-white bg-slate-900 hover:bg-blue-600 transform hover:-translate-y-0.5 transition-all duration-300 shadow-lg hover:shadow-blue-600/30">
                        Registrarse
                        <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)" class="space-y-8">
                
                <!-- Badge -->
                <div x-show="shown" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-sm font-medium mb-4">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Gestión Inteligente de Turnos
                </div>

                <!-- Main Heading -->
                <h1 x-show="shown" 
                    x-transition:enter="transition ease-out duration-700 delay-100"
                    x-transition:enter-start="opacity-0 translate-y-10"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="text-5xl tracking-tight font-extrabold text-slate-900 sm:text-6xl md:text-7xl lg:text-8xl">
                    <span class="block">Tu equipo,</span>
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 pb-2">siempre listo.</span>
                </h1>
                
                <!-- Description -->
                <p x-show="shown" 
                   x-transition:enter="transition ease-out duration-700 delay-200"
                   x-transition:enter-start="opacity-0 translate-y-10"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   class="mt-6 max-w-2xl mx-auto text-lg text-slate-600 sm:text-xl md:mt-10 leading-relaxed">
                    Optimiza la asignación de guardias, coordina personal médico y garantiza la cobertura total con nuestra plataforma de gestión avanzada.
                </p>

                <!-- CTA Buttons -->
                <div x-show="shown" 
                     x-transition:enter="transition ease-out duration-700 delay-300"
                     x-transition:enter-start="opacity-0 translate-y-10"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-10 flex justify-center gap-4 flex-col sm:flex-row">
                    <a href="/admin/register" class="group relative inline-flex items-center justify-center px-8 py-4 text-base font-medium text-white transition-all duration-200 bg-blue-600 rounded-full hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 overflow-hidden">
                        <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="relative flex items-center gap-2">
                            Comenzar Ahora
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </span>
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-slate-700 transition-all duration-200 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:border-slate-300 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200 shadow-sm hover:shadow-md">
                        Saber Más
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Abstract Illustration -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 opacity-40 w-full h-full pointer-events-none">
             <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                 <path d="M0 100 C 20 0 50 0 100 100 Z" fill="none" stroke="url(#gradient)" stroke-width="0.5" />
                 <defs>
                     <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                         <stop offset="0%" style="stop-color:#3B82F6;stop-opacity:0" />
                         <stop offset="50%" style="stop-color:#3B82F6;stop-opacity:0.5" />
                         <stop offset="100%" style="stop-color:#3B82F6;stop-opacity:0" />
                     </linearGradient>
                 </defs>
             </svg>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20" 
                 x-data="{ shown: false }" 
                 x-intersect="shown = true">
                <h2 x-show="shown" 
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-y-10"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="text-base font-semibold text-blue-600 tracking-wide uppercase">
                    Características
                </h2>
                <p x-show="shown" 
                   x-transition:enter="transition ease-out duration-700 delay-100"
                   x-transition:enter-start="opacity-0 translate-y-10"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   class="mt-2 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
                    Todo lo que necesitas.
                </p>
                <p x-show="shown" 
                   x-transition:enter="transition ease-out duration-700 delay-200"
                   x-transition:enter-start="opacity-0 translate-y-10"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   class="mt-4 text-xl text-slate-500">
                    Herramientas diseñadas para simplificar la gestión diaria de tu personal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div x-data="{ hover: false }" 
                     @mouseenter="hover = true" 
                     @mouseleave="hover = false"
                     class="relative p-8 bg-slate-50 rounded-3xl border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-blue-600/10 hover:-translate-y-1 group">
                    <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                         <svg class="w-24 h-24 text-blue-600 transform rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"/></svg>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-blue-600/30 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Calendario Interactivo</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Visualiza y gestiona turnos con una interfaz intuitiva. Arrastra, suelta y organiza tu mes en segundos.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div x-data="{ hover: false }" 
                     @mouseenter="hover = true" 
                     @mouseleave="hover = false"
                     class="relative p-8 bg-slate-50 rounded-3xl border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-blue-600/10 hover:-translate-y-1 group">
                     <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-blue-600 transform -rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                   </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-600/30 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Aprobación Rápida</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Sistema ágil para validar solicitudes. Los administradores pueden aceptar o rechazar turnos con un solo clic.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div x-data="{ hover: false }" 
                     @mouseenter="hover = true" 
                     @mouseleave="hover = false"
                     class="relative p-8 bg-slate-50 rounded-3xl border border-slate-100 transition-all duration-300 hover:shadow-xl hover:shadow-blue-600/10 hover:-translate-y-1 group">
                     <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-blue-600 transform rotate-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/></svg>
                   </div>
                    <div class="w-14 h-14 rounded-2xl bg-sky-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-sky-600/30 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Control de Límites</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Gestión automática de límites de días por personal. Evita solapamientos y asegura una distribución justa.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-3 mb-4 md:mb-0">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white">
                            <path d="M10 10H6"></path>
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14"></path>
                            <path d="M8 8v4"></path>
                            <path d="M9 18h6"></path>
                            <circle cx="17" cy="18" r="2"></circle>
                            <circle cx="7" cy="18" r="2"></circle>
                        </svg>
                    </div>
                    <span class="text-slate-300 font-semibold text-lg">Ambulancias</span>
                </div>
                <div class="text-slate-400 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Alpine.js Plugin for Intersection -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.directive('intersect', (el, { value, expression }, { Alpine, effect, cleanup }) => {
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            el.dispatchEvent(new CustomEvent('intersect', { bubbles: false }))
                            if (expression) {
                                Alpine.evaluate(el, expression)
                            }
                            observer.disconnect()
                        }
                    })
                }, { threshold: 0.2 })
                observer.observe(el)
                cleanup(() => observer.disconnect())
            })
        })
    </script>
</body>
</html>