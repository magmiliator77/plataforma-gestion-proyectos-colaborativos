<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1>Nuevo proyecto</h1>

        <form method="POST" action="{{ route('projects.store') }}">
            @include('projects._form')
        </form>
    </div>
</x-app-layout>
