<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- Las mismas fuentes que la portada: Bricolage Grotesque para los títulos y Figtree para el texto --}}
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,800|figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Clase .titulo: la misma letra de los títulos de la portada --}}
        <style>
            .titulo {
                font-family: 'Bricolage Grotesque', sans-serif;
                letter-spacing: -0.02em;
                text-wrap: balance;
            }
        </style>
    </head>
    <body class="bg-stone-50 font-sans text-stone-900 antialiased">
        <div class="flex min-h-screen flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                {{-- Cabecera de cada página, sin sombra, como las secciones de la portada --}}
                <header>
                    <div class="max-w-5xl mx-auto px-4 pt-10 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{-- Mensaje que manda el controlador con ->with('mensaje', ...) después de guardar --}}
                @if (session('mensaje'))
                    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                        <p class="rounded-md border border-stone-200 border-l-4 border-l-[#e0261b] bg-white px-4 py-3 text-sm text-stone-800">
                            {{ session('mensaje') }}
                        </p>
                    </div>
                @endif

                {{ $slot }}
            </main>

            {{-- El mismo pie que la portada --}}
            <footer class="border-t border-stone-200">
                <div class="max-w-5xl mx-auto px-4 py-6 text-sm text-stone-500 sm:px-6 lg:px-8">
                    Práctica final de Desarrollo Web en Entorno Servidor, hecha con Laravel.
                </div>
            </footer>
        </div>
    </body>
</html>
