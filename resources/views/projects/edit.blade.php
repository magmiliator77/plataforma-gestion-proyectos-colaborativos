<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1>Editar proyecto</h1>

        <form method="POST" action="{{ route('projects.update', $project) }}">
            {{-- Los formularios solo envían GET o POST; @method indica que es un PUT --}}
            @method('PUT')
            @include('projects._form')
        </form>
    </div>
</x-app-layout>
