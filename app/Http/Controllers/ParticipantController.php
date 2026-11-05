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
    public function store(Request $request, string $projectId)
    {
        $project = Project::findOrFail($projectId);

        if ($this->esPropietario($project) == false) {
            return redirect()->route('projects.show', $project->id)->with('mensaje', 'Solo el propietario puede cambiar el equipo.');
        }

        $datos = $request->validate([
            'user_id' => 'required',
            'role' => 'required|max:50',
            'contribution_hours' => 'required|numeric|min:0',
        ]);

        // find() devuelve el usuario o null si no existe; así compruebo que el usuario elegido existe
        $usuario = User::find($datos['user_id']);
        if ($usuario == null) {
            return redirect()->route('projects.show', $project->id)->with('mensaje', 'Ese usuario no existe.');
        }

        // Antes de añadirlo miro uno a uno si ya está en el proyecto, para no repetirlo
        foreach ($project->participants as $participante) {
            if ($participante->id == $usuario->id) {
                return redirect()->route('projects.show', $project->id)->with('mensaje', 'Ese usuario ya participa en el proyecto.');
            }
        }

        // attach() inserta una fila en la tabla pivote project_user.
        // El segundo parámetro son las columnas extra de esa fila.
        $project->participants()->attach($usuario->id, [
            'role' => $datos['role'],
            'contribution_hours' => $datos['contribution_hours'],
        ]);

        return redirect()->route('projects.show', $project->id)->with('mensaje', 'Participante añadido.');
    }

    // Cambia el rol y las horas de un participante
    public function update(Request $request, string $projectId, string $userId)
    {
        $project = Project::findOrFail($projectId);
        $usuario = User::findOrFail($userId);

        if ($this->esPropietario($project) == false) {
            return redirect()->route('projects.show', $project->id)->with('mensaje', 'Solo el propietario puede cambiar el equipo.');
        }

        $datos = $request->validate([
            'role' => 'required|max:50',
            'contribution_hours' => 'required|numeric|min:0',
        ]);

        // Para cambiar los datos de la tabla pivote quito la fila antigua con detach()
        // y la vuelvo a poner con attach() y los datos nuevos
        $project->participants()->detach($usuario->id);
        $project->participants()->attach($usuario->id, [
            'role' => $datos['role'],
            'contribution_hours' => $datos['contribution_hours'],
        ]);

        return redirect()->route('projects.show', $project->id)->with('mensaje', 'Participante actualizado.');
    }

    // Quita a un usuario del proyecto
    public function destroy(string $projectId, string $userId)
    {
        $project = Project::findOrFail($projectId);
        $usuario = User::findOrFail($userId);

        if ($this->esPropietario($project) == false) {
            return redirect()->route('projects.show', $project->id)->with('mensaje', 'Solo el propietario puede cambiar el equipo.');
        }

        // detach() es lo contrario de attach(): borra la fila de la tabla pivote,
        // pero el usuario sigue existiendo en la tabla users
        $project->participants()->detach($usuario->id);

        return redirect()->route('projects.show', $project->id)->with('mensaje', 'Participante quitado del proyecto.');
    }

    // Igual que en ProjectController: true si el usuario conectado es el propietario
    private function esPropietario(Project $project)
    {
        if ($project->user_id == Auth::user()->id) {
            return true;
        }

        return false;
    }
}
