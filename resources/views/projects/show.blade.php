<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1>{{ $project->title }}</h1>

        <p>{{ $project->description }}</p>
        <p>Fecha límite: {{ $project->deadline }}</p>
        <p>Estado: {{ $project->status }}</p>
        <p>Propietario: {{ $project->owner->name }}</p>

        <h2>Participantes</h2>
        {{-- pivot da acceso a las columnas extra de la tabla project_user --}}
        @forelse ($project->participants as $participante)
            <p>{{ $participante->name }} - {{ $participante->pivot->role }} - {{ $participante->pivot->contribution_hours }} horas</p>
        @empty
            <p>Este proyecto no tiene participantes.</p>
        @endforelse

        {{-- Los botones de editar y borrar solo los ve el propietario --}}
        @if ($project->user_id == auth()->id())
            <a href="{{ route('projects.edit', $project) }}">Editar</a>

            <form method="POST" action="{{ route('projects.destroy', $project) }}">
                @csrf
                @method('DELETE')
                <button type="submit">Borrar</button>
            </form>
        @endif

        <a href="{{ route('projects.index') }}">Volver a la lista</a>
    </div>
</x-app-layout>
