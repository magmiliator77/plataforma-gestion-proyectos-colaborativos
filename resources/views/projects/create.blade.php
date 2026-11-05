<x-app-layout>
    <x-slot name="header">
        <h1 class="titulo text-4xl font-extrabold text-stone-900">Nuevo proyecto</h1>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="POST" action="{{ route('projects.store') }}" class="max-w-2xl rounded-xl border border-stone-200 bg-white p-6">
            @include('projects._form')

            <div class="mt-8 flex items-center gap-4">
                <x-primary-button>Crear proyecto</x-primary-button>
                <a href="{{ route('projects.index') }}" class="text-sm text-stone-600 hover:text-stone-900 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
