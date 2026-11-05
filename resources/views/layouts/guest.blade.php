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
    <body class="font-sans text-stone-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-stone-50">
            {{-- Logo de Laravel animado con bloques (resources/views/partials/logo-cubo.blade.php) --}}
            <a href="/" class="flex flex-col items-center">
                @include('partials.logo-cubo')
                <span class="titulo mt-2 text-xl font-extrabold text-stone-900">{{ config('app.name') }}</span>
            </a>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white border border-stone-200 overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
