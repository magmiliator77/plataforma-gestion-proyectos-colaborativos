<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h1 class="titulo text-4xl font-extrabold text-stone-900">Proyectos</h1>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center rounded-md bg-[#e0261b] px-4 py-2 text-sm font-semibold text-white hover:bg-[#c11f15] focus:outline-none focus:ring-2 focus:ring-[#e0261b] focus:ring-offset-2">
                Nuevo proyecto
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (count($projects) == 0)
            {{-- Estado vacío: explica qué hacer en vez de dejar la página en blanco --}}
            <div class="rounded-xl border border-dashed border-stone-300 bg-white px-6 py-12 text-center">
                <p class="titulo text-2xl font-extrabold text-stone-900">Todavía no hay proyectos</p>
                <p class="mt-1 text-stone-600">Crea el primero y añade a tu equipo desde su página.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-stone-200 bg-white">
                <table class="min-w-full divide-y divide-stone-200 text-left text-sm">
                    <thead class="bg-stone-50 text-stone-600">
                        <tr>
                            <th class="px-4 py-3 font-medium">Proyecto</th>
                            <th class="px-4 py-3 font-medium">Estado</th>
                            <th class="px-4 py-3 font-medium">Fecha límite</th>
                            <th class="px-4 py-3 font-medium">Propietario</th>
                            <th class="px-4 py-3 font-medium text-right">Equipo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($projects as $project)
                            <tr class="hover:bg-stone-50">
                                <td class="px-4 py-3">
                                    <a href="{{ route('projects.show', $project) }}" class="font-medium text-stone-900 hover:text-[#e0261b] hover:underline">
                                        {{ $project->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">@include('projects._estado', ['estado' => $project->status])</td>
                                {{-- strtotime() convierte el texto de la fecha en un número y date() le da formato español --}}
                                <td class="px-4 py-3 text-stone-700">{{ date('d/m/Y', strtotime($project->deadline)) }}</td>
                                <td class="px-4 py-3 text-stone-700">
                                    {{ $project->owner->name }}
                                    @if ($project->user_id == auth()->id())
                                        <span class="text-stone-500">(tú)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-stone-700">{{ count($project->participants) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
