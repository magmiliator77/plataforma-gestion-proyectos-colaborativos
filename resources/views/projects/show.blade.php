<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1>{{ $project->title }}</h1>

        <p>{{ $project->description }}</p>
        <p>Fecha límite: {{ $project->deadline }}</p>
        <p>Estado: {{ $project->status }}</p>
        <p>Propietario: {{ $project->owner->name }}</p>

        {{-- Guardo en una variable si el usuario conectado es el propietario, para no repetirlo --}}
        @php
            $esPropietario = $project->user_id == auth()->id();
        @endphp

        <h2>Participantes</h2>

        <table>
            <tr>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Horas</th>
                @if ($esPropietario)
                    <th>Acciones</th>
                @endif
            </tr>

            {{-- pivot da acceso a las columnas extra de la tabla project_user --}}
            @forelse ($project->participants as $participante)
                <tr>
                    <td>{{ $participante->name }}</td>

                    @if ($esPropietario)
                        {{-- El formulario de editar va en las celdas de rol y horas.
                             form="..." une cada input con su formulario aunque estén en celdas distintas --}}
                        <td>
                            <input type="text" name="role" value="{{ $participante->pivot->role }}" form="editar-{{ $participante->id }}">
                        </td>
                        <td>
                            <input type="number" name="contribution_hours" min="0" value="{{ $participante->pivot->contribution_hours }}" form="editar-{{ $participante->id }}">
                        </td>
                        <td>
                            <form id="editar-{{ $participante->id }}" method="POST" action="{{ route('participants.update', [$project, $participante]) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit">Guardar</button>
                            </form>

                            <form method="POST" action="{{ route('participants.destroy', [$project, $participante]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Quitar</button>
                            </form>
                        </td>
                    @else
                        <td>{{ $participante->pivot->role }}</td>
                        <td>{{ $participante->pivot->contribution_hours }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="4">Este proyecto no tiene participantes.</td>
                </tr>
            @endforelse
        </table>

        {{-- Solo el propietario puede añadir participantes, editar y borrar el proyecto --}}
        @if ($esPropietario)
            <h2>Añadir participante</h2>

            @if ($usuariosDisponibles->isEmpty())
                <p>No quedan usuarios para añadir.</p>
            @else
                <form method="POST" action="{{ route('participants.store', $project) }}">
                    @csrf

                    <div>
                        <label for="user_id">Usuario</label>
                        <select id="user_id" name="user_id">
                            @foreach ($usuariosDisponibles as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <p>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="role">Rol</label>
                        <input type="text" id="role" name="role" value="{{ old('role') }}" placeholder="Desarrollador, Diseñador, Tester...">
                        @error('role') <p>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="contribution_hours">Horas trabajadas</label>
                        <input type="number" id="contribution_hours" name="contribution_hours" min="0" value="{{ old('contribution_hours', 0) }}">
                        @error('contribution_hours') <p>{{ $message }}</p> @enderror
                    </div>

                    <button type="submit">Añadir</button>
                </form>
            @endif

            <h2>Opciones del proyecto</h2>

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
