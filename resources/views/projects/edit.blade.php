@extends('layouts.app')

@section('title', 'Editar proyecto')

@section('header')
    <h1 class="titulo text-4xl font-extrabold text-stone-900">Editar «{{ $project->title }}»</h1>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="POST" action="{{ route('projects.update', $project->id) }}" class="max-w-2xl rounded-xl border border-stone-200 bg-white p-6">
            {{-- Los formularios solo envían GET o POST; @method indica que es un PUT --}}
            @method('PUT')
            @include('projects._form')

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Guardar cambios</button>
                <a href="{{ route('projects.show', $project->id) }}" class="text-sm text-stone-600 hover:text-stone-900 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
