<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Proyectos en equipo</title>

        {{-- Dos fuentes: Bricolage Grotesque para los títulos y Figtree (la de Breeze) para el texto --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,800|figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-stone-50 font-sans text-stone-900 antialiased">

        {{-- Cabecera --}}
        <header class="max-w-6xl mx-auto flex items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <a href="/" class="titulo text-xl font-extrabold">Proyectos en equipo</a>

            {{-- @auth / @guest muestran cosas distintas si has iniciado sesión o no --}}
            <nav class="flex items-center gap-5 text-sm font-medium">
                @auth
                    <a href="{{ route('projects.index') }}" class="rounded-md bg-stone-900 px-4 py-2 text-white hover:bg-stone-700">Ir a mis proyectos</a>
                @else
                    <a href="{{ route('login') }}" class="text-stone-700 hover:text-stone-900 hover:underline">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-stone-900 px-4 py-2 text-white hover:bg-stone-700">Crear cuenta</a>
                @endauth
            </nav>
        </header>

        <main>
            {{-- Portada: el texto a la izquierda y, a la derecha, cómo se ve un proyecto de verdad --}}
            <section class="max-w-6xl mx-auto grid items-center gap-12 px-4 pt-10 pb-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:pt-16">
                <div>
                    <h1 class="titulo text-5xl font-extrabold leading-none sm:text-6xl">
                        Cada proyecto,<br>
                        con su equipo<br>
                        <span class="text-red-600">y sus horas.</span>
                    </h1>
                    <p class="mt-6 max-w-md text-lg leading-relaxed text-stone-700">
                        Crea un proyecto, ponle fecha límite y suma a tus compañeros con el rol que tiene cada uno.
                        Las horas que apunta cada persona se van sumando solas.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-5">
                        @guest
                            <a href="{{ route('register') }}" class="rounded-md bg-red-600 px-6 py-3 font-semibold text-white hover:bg-red-700">Empezar un proyecto</a>
                            <a href="{{ route('login') }}" class="font-medium text-stone-700 underline decoration-stone-300 underline-offset-4 hover:decoration-stone-700">Ya tengo cuenta</a>
                        @endguest
                        @auth
                            <a href="{{ route('projects.create') }}" class="rounded-md bg-red-600 px-6 py-3 font-semibold text-white hover:bg-red-700">Crear un proyecto</a>
                        @endauth
                    </div>
                </div>

                {{-- Vista previa de un proyecto, con los mismos datos que el seeder --}}
                <div class="relative">
                    <div class="flex justify-end pr-10" aria-hidden="true">
                        <div class="logo-grande">
                            @include('partials.logo-cubo')
                        </div>
                    </div>

                    <div class="relative mt-2 rounded-xl border border-stone-200 bg-white p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm text-stone-500">Propietaria: Ana</p>
                                <p class="titulo text-2xl font-semibold">Tienda online</p>
                            </div>
                            {{-- La subvista _estado usa la variable $estado --}}
                            @php
                                $estado = 'en curso';
                            @endphp
                            @include('projects._estado')
                        </div>

                        <table class="mt-6 w-full text-left text-sm">
                            <thead class="text-stone-500">
                                <tr>
                                    <th class="pb-2 font-medium">Persona</th>
                                    <th class="pb-2 font-medium">Rol</th>
                                    <th class="pb-2 text-right font-medium">Horas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                <tr>
                                    <td class="py-2 font-medium">Luis</td>
                                    <td class="py-2 text-stone-700">Desarrollador</td>
                                    <td class="py-2 text-right">12 h</td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-medium">Eva</td>
                                    <td class="py-2 text-stone-700">Diseñadora</td>
                                    <td class="py-2 text-right">8 h</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 flex items-center justify-between border-t border-stone-200 pt-4 text-sm">
                            <span class="text-stone-500">Entrega el 15/01/2027</span>
                            <span class="font-semibold">Total del equipo: 20 h</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Cómo funciona, en tres pasos --}}
            <section class="border-y border-stone-200 bg-white">
                <div class="max-w-6xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
                    <h2 class="titulo text-3xl font-extrabold">Así se trabaja</h2>

                    <ol class="mt-10 grid gap-10 md:grid-cols-3">
                        <li>
                            <span class="titulo text-5xl font-extrabold text-red-600">1</span>
                            <h3 class="mt-2 text-lg font-semibold">Creas el proyecto</h3>
                            <p class="mt-2 max-w-xs leading-relaxed text-stone-600">Le pones nombre, descripción, fecha límite y en qué estado está. Tú eres su propietario.</p>
                        </li>
                        <li>
                            <span class="titulo text-5xl font-extrabold text-red-600">2</span>
                            <h3 class="mt-2 text-lg font-semibold">Montas el equipo</h3>
                            <p class="mt-2 max-w-xs leading-relaxed text-stone-600">Añades a otros usuarios con su rol. La misma persona puede ser tester en uno y diseñadora en otro.</p>
                        </li>
                        <li>
                            <span class="titulo text-5xl font-extrabold text-red-600">3</span>
                            <h3 class="mt-2 text-lg font-semibold">Apuntas las horas</h3>
                            <p class="mt-2 max-w-xs leading-relaxed text-stone-600">Cada participante tiene sus horas y el proyecto enseña el total. Solo el propietario puede cambiarlo.</p>
                        </li>
                    </ol>
                </div>
            </section>

            {{-- Llamada final --}}
            <section class="max-w-6xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <p class="titulo max-w-xl text-3xl font-extrabold">¿Tienes un proyecto entre manos? Organízalo aquí.</p>
                    @guest
                        <a href="{{ route('register') }}" class="rounded-md bg-stone-900 px-6 py-3 font-semibold text-white hover:bg-stone-700">Crear cuenta</a>
                    @endguest
                    @auth
                        <a href="{{ route('projects.index') }}" class="rounded-md bg-stone-900 px-6 py-3 font-semibold text-white hover:bg-stone-700">Ir a mis proyectos</a>
                    @endauth
                </div>
            </section>
        </main>

        <footer class="border-t border-stone-200">
            <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-stone-500 sm:px-6 lg:px-8">
                Práctica final de Desarrollo Web en Entorno Servidor, hecha con Laravel.
            </div>
        </footer>
    </body>
</html>
