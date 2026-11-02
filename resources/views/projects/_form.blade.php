{{-- Campos comunes de crear y editar. old() recupera lo escrito si la validación falla --}}
@csrf

<div>
    <label for="title">Título</label>
    <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}">
    @error('title') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="description">Descripción</label>
    <textarea id="description" name="description">{{ old('description', $project->description) }}</textarea>
    @error('description') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="deadline">Fecha límite</label>
    <input type="date" id="deadline" name="deadline" value="{{ old('deadline', $project->deadline) }}">
    @error('deadline') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="status">Estado</label>
    <select id="status" name="status">
        @foreach (['pendiente', 'en curso', 'terminado'] as $estado)
            <option value="{{ $estado }}" @selected(old('status', $project->status) == $estado)>{{ $estado }}</option>
        @endforeach
    </select>
    @error('status') <p>{{ $message }}</p> @enderror
</div>

<button type="submit">Guardar</button>
