<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-stone-900">Editar «{{ $project->title }}»</h1>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="POST" action="{{ route('projects.update', $project) }}" class="rounded-lg border border-stone-200 bg-white p-6">
            {{-- Los formularios solo envían GET o POST; @method indica que es un PUT --}}
            @method('PUT')
            @include('projects._form')

            <div class="mt-8 flex items-center gap-4">
                <x-primary-button>Guardar cambios</x-primary-button>
                <a href="{{ route('projects.show', $project) }}" class="text-sm text-stone-600 hover:text-stone-900 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
