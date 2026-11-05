@extends('layouts.app')

@section('title', $project->title)

{{-- Guardo en variables si el usuario conectado es el propietario y el estado, para no repetirlo --}}
@php
    $esPropietario = $project->user_id == Auth::user()->id;
    $estado = $project->status;
@endphp

@section('header')
    <a href="{{ route('projects.index') }}" class="text-sm text-stone-600 hover:text-stone-900 hover:underline">← Volver a proyectos</a>
    <div class="mt-2 flex flex-wrap items-center gap-3">
        <h1 class="titulo text-4xl font-extrabold text-stone-900">{{ $project->title }}</h1>
        {{-- La subvista _estado usa la variable $estado --}}
        @include('projects._estado')
    </div>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        {{-- Errores de los formularios de participantes, todos juntos arriba (como en los apuntes) --}}
        @if ($errors->any())
            <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        {{-- Datos del proyecto --}}
        <section>
            <dl class="grid gap-4 sm:grid-cols-3">
                <div>
                    <dt class="text-sm text-stone-500">Propietario</dt>
                    <dd class="font-medium text-stone-900">{{ $project->owner->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-stone-500">Fecha límite</dt>
                    <dd class="font-medium text-stone-900">{{ $project->deadline }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-stone-500">Horas del equipo</dt>
                    {{-- $totalHoras lo calcula el controlador sumando las horas de cada participante --}}
                    <dd class="font-medium text-stone-900">{{ $totalHoras }} h</dd>
                </div>
            </dl>

            @if ($project->description)
                <p class="mt-6 max-w-prose leading-relaxed text-stone-700">{{ $project->description }}</p>
            @endif
        </section>

        {{-- Participantes --}}
        <section>
            <h2 class="titulo text-2xl font-extrabold text-stone-900">Equipo</h2>

            <div class="mt-3 overflow-x-auto rounded-xl border border-stone-200 bg-white">
                <table class="min-w-full divide-y divide-stone-200 text-left text-sm">
                    <thead class="bg-stone-50 text-stone-600">
                        <tr>
                            <th class="px-4 py-3 font-medium">Nombre</th>
                            <th class="px-4 py-3 font-medium">Rol</th>
                            <th class="px-4 py-3 font-medium">Horas</th>
                            @if ($esPropietario)
                                <th class="px-4 py-3 font-medium text-right">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        {{-- pivot da acceso a las columnas extra de la tabla project_user --}}
                        @forelse ($project->participants as $participante)
                            <tr>
                                <td class="px-4 py-3 font-medium text-stone-900">{{ $participante->name }}</td>

                                @if ($esPropietario)
                                    {{-- El formulario de editar va en las celdas de rol y horas.
                                         form="..." une cada input con su formulario aunque estén en celdas distintas --}}
                                    <td class="px-4 py-2">
                                        <label for="rol-{{ $participante->id }}" class="sr-only">Rol de {{ $participante->name }}</label>
                                        <input id="rol-{{ $participante->id }}" type="text" name="role" value="{{ $participante->pivot->role }}" form="editar-{{ $participante->id }}" class="w-40 rounded-md border-stone-300 text-sm focus:border-stone-900 focus:ring-stone-900">
                                    </td>
                                    <td class="px-4 py-2">
                                        <label for="horas-{{ $participante->id }}" class="sr-only">Horas de {{ $participante->name }}</label>
                                        <input id="horas-{{ $participante->id }}" type="number" name="contribution_hours" min="0" value="{{ $participante->pivot->contribution_hours }}" form="editar-{{ $participante->id }}" class="w-24 rounded-md border-stone-300 text-sm focus:border-stone-900 focus:ring-stone-900">
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex justify-end gap-2">
                                            <form id="editar-{{ $participante->id }}" method="POST" action="{{ route('participants.update', [$project->id, $participante->id]) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="rounded-md border border-stone-300 bg-white px-3 py-1.5 text-sm font-medium text-stone-700 hover:bg-stone-50">Guardar</button>
                                            </form>

                                            <form method="POST" action="{{ route('participants.destroy', [$project->id, $participante->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-50">Quitar</button>
                                            </form>
                                        </div>
                                    </td>
                                @else
                                    <td class="px-4 py-3 text-stone-700">{{ $participante->pivot->role }}</td>
                                    <td class="px-4 py-3 text-stone-700">{{ $participante->pivot->contribution_hours }} h</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-stone-600">
                                    Aún no hay nadie en el equipo.
                                    @if ($esPropietario)
                                        Añade al primero con el formulario de abajo.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Solo el propietario puede añadir participantes, editar y borrar el proyecto --}}
        @if ($esPropietario)
            <section>
                <h2 class="titulo text-2xl font-extrabold text-stone-900">Añadir participante</h2>

                @if (count($usuariosDisponibles) == 0)
                    <p class="mt-2 text-stone-600">Todos los usuarios registrados ya están en el equipo.</p>
                @else
                    <form method="POST" action="{{ route('participants.store', $project->id) }}" class="mt-3 rounded-xl border border-stone-200 bg-white p-6">
                        @csrf

                        <div class="grid gap-6 sm:grid-cols-3">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-stone-700">Usuario</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">
                                    @foreach ($usuariosDisponibles as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-stone-700">Rol</label>
                                <input id="role" name="role" type="text" value="{{ old('role') }}" placeholder="Ej.: Desarrollador" class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">
                            </div>

                            <div>
                                <label for="contribution_hours" class="block text-sm font-medium text-stone-700">Horas trabajadas</label>
                                <input id="contribution_hours" name="contribution_hours" type="number" min="0" value="{{ old('contribution_hours', 0) }}" class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Añadir al equipo</button>
                        </div>
                    </form>
                @endif
            </section>

            <section class="flex flex-wrap items-center gap-3 border-t border-stone-200 pt-6">
                <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex items-center rounded-md border border-stone-300 bg-white px-4 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-50">Editar proyecto</a>

                <form method="POST" action="{{ route('projects.destroy', $project->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Borrar proyecto</button>
                </form>
            </section>
        @endif
    </div>
@endsection
