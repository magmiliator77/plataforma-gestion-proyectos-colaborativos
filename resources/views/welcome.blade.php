<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-stone-50 font-sans text-stone-900 antialiased">
        <header class="max-w-5xl mx-auto flex items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
            <span class="text-lg font-bold">{{ config('app.name') }}</span>

            {{-- @auth / @guest muestran cosas distintas si has iniciado sesión o no --}}
            <nav class="flex items-center gap-4 text-sm font-medium">
                @auth
                    <a href="{{ route('projects.index') }}" class="text-teal-700 hover:underline">Ir a mis proyectos</a>
                @else
                    <a href="{{ route('login') }}" class="text-stone-700 hover:text-stone-900 hover:underline">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-teal-700 px-4 py-2 text-white hover:bg-teal-800">Crear cuenta</a>
                @endauth
            </nav>
        </header>

        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <section class="py-16 sm:py-24">
                <h1 class="max-w-2xl text-4xl font-bold leading-tight sm:text-5xl" style="text-wrap: balance">
                    Organiza los proyectos de tu equipo y quién hace qué
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-stone-700">
                    Crea un proyecto, ponle fecha límite y añade a tus compañeros con su rol.
                    Cada uno apunta sus horas y todos veis cómo va.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    @guest
                        <a href="{{ route('register') }}" class="rounded-md bg-teal-700 px-5 py-3 font-semibold text-white hover:bg-teal-800">Crear cuenta gratis</a>
                        <a href="{{ route('login') }}" class="font-medium text-stone-700 hover:underline">Ya tengo cuenta</a>
                    @endguest
                </div>
            </section>

            <section class="grid gap-10 border-t border-stone-200 py-12 sm:grid-cols-3">
                <div>
                    <h2 class="font-semibold">Un propietario por proyecto</h2>
                    <p class="mt-2 leading-relaxed text-stone-600">Quien crea el proyecto es el único que puede editarlo o borrarlo.</p>
                </div>
                <div>
                    <h2 class="font-semibold">Roles en el equipo</h2>
                    <p class="mt-2 leading-relaxed text-stone-600">Desarrollador, diseñadora, tester... Cada persona puede tener un rol distinto en cada proyecto.</p>
                </div>
                <div>
                    <h2 class="font-semibold">Horas de cada uno</h2>
                    <p class="mt-2 leading-relaxed text-stone-600">Apunta las horas que trabaja cada participante y mira el total del proyecto.</p>
                </div>
            </section>
        </main>

        <footer class="max-w-5xl mx-auto px-4 py-8 text-sm text-stone-500 sm:px-6 lg:px-8">
            Práctica final de DWES · Laravel
        </footer>
    </body>
</html>
