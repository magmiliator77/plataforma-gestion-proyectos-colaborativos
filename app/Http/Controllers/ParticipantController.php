<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Este controlador gestiona la relación N:N entre proyectos y usuarios.
// Lo he separado de ProjectController para que cada controlador haga una sola cosa.
class ParticipantController extends Controller
{
    // Añade un usuario al proyecto con su rol y sus horas
    public function store(Request $request, Project $project)
    {
        $this->comprobarPropietario($project);

        // exists:users,id comprueba que el usuario elegido existe de verdad en la tabla users
        $datos = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|max:50',
            'contribution_hours' => 'required|integer|min:0',
        ]);

        // Antes de añadirlo miro si ya está en el proyecto, para no repetirlo
        $yaEsta = $project->participants()->where('user_id', $datos['user_id'])->exists();
        if ($yaEsta) {
            return back()->withErrors(['user_id' => 'Ese usuario ya participa en el proyecto.']);
        }

        // attach() inserta una fila en la tabla pivote project_user.
        // El segundo parámetro son las columnas extra de esa fila.
        $project->participants()->attach($datos['user_id'], [
            'role' => $datos['role'],
            'contribution_hours' => $datos['contribution_hours'],
        ]);

        return redirect()->route('projects.show', $project);
    }

    // Cambia el rol y las horas de un participante
    public function update(Request $request, Project $project, User $user)
    {
        $this->comprobarPropietario($project);

        $datos = $request->validate([
            'role' => 'required|max:50',
            'contribution_hours' => 'required|integer|min:0',
        ]);

        // updateExistingPivot() modifica la fila de la tabla pivote que ya existe
        $project->participants()->updateExistingPivot($user->id, [
            'role' => $datos['role'],
            'contribution_hours' => $datos['contribution_hours'],
        ]);

        return redirect()->route('projects.show', $project);
    }

    // Quita a un usuario del proyecto
    public function destroy(Project $project, User $user)
    {
        $this->comprobarPropietario($project);

        // detach() es lo contrario de attach(): borra la fila de la tabla pivote,
        // pero el usuario sigue existiendo en la tabla users
        $project->participants()->detach($user->id);

        return redirect()->route('projects.show', $project);
    }

    // Igual que en ProjectController: solo el propietario puede gestionar participantes
    private function comprobarPropietario(Project $project)
    {
        if ($project->user_id != Auth::id()) {
            abort(403);
        }
    }
}
