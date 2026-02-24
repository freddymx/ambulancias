<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Ambulancias') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-600 bg-slate-50 selection:bg-blue-500 selection:text-white">

    <!-- Navigation -->
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                            <!-- Logo Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <span class="font-bold text-xl text-slate-900 tracking-tight">Ambulancias</span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/admin/login" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">
                        Iniciar Sesión
                    </a>
                    <a href="/admin/register" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                        Registrarse
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden pt-16 pb-32 lg:pt-32 lg:pb-40">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                <span class="block">Gestión Integral de</span>
                <span class="block text-blue-600">Personal de Ambulancias</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-slate-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Optimiza la asignación de turnos, gestiona el equipo médico y asegura la cobertura en todo momento con nuestra plataforma especializada.
            </p>
            <div class="mt-10 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center gap-4">
                <div class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5">
                    <a href="/admin/register" class="flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10 shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5">
                        Comenzar Ahora
                    </a>
                    <a href="#features" class="flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10 transition-colors">
                        Saber Más
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Background Pattern -->
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -z-10 w-full h-full opacity-30 pointer-events-none">
            <svg class="absolute top-0 left-0 w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid-pattern" width="4" height="4" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1" class="text-slate-200" fill="currentColor" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern)" />
            </svg>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Características</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Todo lo que necesitas para gestionar tu equipo
                </p>
                <p class="mt-4 max-w-2xl text-xl text-slate-500 mx-auto">
                    Una solución completa diseñada específicamente para la coordinación de personal sanitario.
                </p>
            </div>

            <div class="mt-20">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-2xl bg-blue-50 text-blue-600 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900">Calendario Interactivo</h3>
                        <p class="mt-2 text-base text-slate-500">
                            Visualiza y selecciona tus días de guardia de forma intuitiva. Evita solapamientos y mantén tu agenda organizada.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-2xl bg-blue-50 text-blue-600 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900">Gestión de Personal</h3>
                        <p class="mt-2 text-base text-slate-500">
                            Administra perfiles de enfermeros, roles y permisos. Asegura que el personal adecuado esté en el lugar correcto.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-2xl bg-blue-50 text-blue-600 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900">Aprobación Segura</h3>
                        <p class="mt-2 text-base text-slate-500">
                            Sistema de aprobación por administradores. Control total sobre quién se une al equipo y en qué fechas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">¿Listo para empezar?</span>
                <span class="block text-blue-200">Únete al equipo hoy mismo.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="/admin/register" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 transition-colors">
                        Registrarse
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="/admin/login" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-700 hover:bg-blue-800 transition-colors">
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <div class="flex justify-center space-x-6 md:order-2">
                <!-- Social links could go here -->
            </div>
            <div class="mt-8 md:mt-0 md:order-1">
                <p class="text-center text-base text-slate-400">
                    &copy; {{ date('Y') }} Ambulancias. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>