{{-- Campos comunes de crear y editar. old() recupera lo escrito si la validación falla.
     Uso los componentes que trae Breeze (x-input-label, x-text-input, x-input-error) --}}
@csrf

<div class="space-y-6">
    <div>
        <x-input-label for="title" value="Título" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $project->title)" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Descripción (opcional)" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">{{ old('description', $project->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="deadline" value="Fecha límite" />
            <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline', $project->deadline)" required />
            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="status" value="Estado" />
            <select id="status" name="status" class="mt-1 block w-full rounded-md border-stone-300 focus:border-stone-900 focus:ring-stone-900">
                @foreach (['pendiente', 'en curso', 'terminado'] as $estado)
                    <option value="{{ $estado }}" @selected(old('status', $project->status) == $estado)>{{ ucfirst($estado) }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>
</div>
