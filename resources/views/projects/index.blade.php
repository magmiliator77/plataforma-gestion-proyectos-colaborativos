<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1>Proyectos</h1>

        <a href="{{ route('projects.create') }}">Nuevo proyecto</a>

        {{-- Recorro los proyectos; si no hay ninguno se muestra el @empty --}}
        @forelse ($projects as $project)
            <p>
                <a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a>
                - {{ $project->status }} - Fecha límite: {{ $project->deadline }}
            </p>
        @empty
            <p>Todavía no hay proyectos.</p>
        @endforelse
    </div>
</x-app-layout>
