{{-- Campos comunes de crear y editar. old() recupera lo escrito si la validación falla,
     y @error enseña el mensaje de error de cada campo --}}
@csrf

@php
    // Estado que sale marcado en el desplegable
    $estadoElegido = old('status', $project->status);
@endphp

<div class="space-y-6">
    <div>
        <label for="title" class="block text-sm font-medium text-stone-700">Título</label>
        <input id="title" name="title" type="text" value="{{ old('title', $project->title) }}" required autofocus class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">
        @error('title')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-stone-700">Descripción (opcional)</label>
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">{{ old('description', $project->description) }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="deadline" class="block text-sm font-medium text-stone-700">Fecha límite</label>
            <input id="deadline" name="deadline" type="date" value="{{ old('deadline', $project->deadline) }}" required class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">
            @error('deadline')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-stone-700">Estado</label>
            <select id="status" name="status" class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">
                <option value="pendiente" @if ($estadoElegido == 'pendiente') selected @endif>Pendiente</option>
                <option value="en curso" @if ($estadoElegido == 'en curso') selected @endif>En curso</option>
                <option value="terminado" @if ($estadoElegido == 'terminado') selected @endif>Terminado</option>
            </select>
            @error('status')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
