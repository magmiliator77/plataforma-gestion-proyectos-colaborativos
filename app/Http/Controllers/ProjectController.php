<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Lista todos los proyectos
    public function index()
    {
        $projects = Project::all();

        return view('projects.index', ['projects' => $projects]);
    }

    // Muestra el formulario para crear un proyecto
    public function create()
    {
        // Paso un proyecto vacío para usar el mismo formulario que en editar
        $project = new Project();

        return view('projects.create', ['project' => $project]);
    }

    // Guarda en la base de datos el proyecto nuevo
    public function store(Request $request)
    {
        // Si algún dato no es válido, Laravel vuelve al formulario con los errores
        $datos = $request->validate($this->reglas());
        $datos['status'] = $this->estadoValido($datos['status']);

        // El propietario es siempre el usuario que ha iniciado sesión
        $datos['user_id'] = Auth::user()->id;

        $project = Project::create($datos);

        return redirect()->route('projects.show', $project->id)->with('mensaje', 'Proyecto creado.');
    }

    // Muestra el detalle de un proyecto
    public function show(string $id)
    {
        // findOrFail busca el proyecto por su id y da un error 404 si no existe
        $project = Project::findOrFail($id);

        // Sumo las horas de todos los participantes recorriéndolos uno a uno
        $totalHoras = 0;
        foreach ($project->participants as $participante) {
            $totalHoras = $totalHoras + $participante->pivot->contribution_hours;
        }

        // Usuarios que se pueden añadir: ni el propietario ni los que ya participan
        $usuariosDisponibles = [];
        foreach (User::all() as $usuario) {
            $yaParticipa = false;
            foreach ($project->participants as $participante) {
                if ($participante->id == $usuario->id) {
                    $yaParticipa = true;
                }
            }

            if ($usuario->id != $project->user_id && $yaParticipa == false) {
                $usuariosDisponibles[] = $usuario;
            }
        }

        return view('projects.show', [
            'project' => $project,
            'totalHoras' => $totalHoras,
            'usuariosDisponibles' => $usuariosDisponibles,
        ]);
    }

    // Muestra el formulario para editar un proyecto
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);

        // Solo el propietario puede editarlo; si no lo es, vuelve al proyecto con un aviso
        if ($this->esPropietario($project) == false) {
            return redirect()->route('projects.show', $project->id)->with('mensaje', 'Solo el propietario puede editar el proyecto.');
        }

        return view('projects.edit', ['project' => $project]);
    }

    // Guarda los cambios de un proyecto
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        if ($this->esPropietario($project) == false) {
            return redirect()->route('projects.show', $project->id)->with('mensaje', 'Solo el propietario puede editar el proyecto.');
        }

        $datos = $request->validate($this->reglas());
        $datos['status'] = $this->estadoValido($datos['status']);
        $project->update($datos);

        return redirect()->route('projects.show', $project->id)->with('mensaje', 'Cambios guardados.');
    }

    // Borra un proyecto
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);

        if ($this->esPropietario($project) == false) {
            return redirect()->route('projects.show', $project->id)->with('mensaje', 'Solo el propietario puede borrar el proyecto.');
        }

        $project->delete();

        return redirect()->route('projects.index')->with('mensaje', 'Proyecto borrado.');
    }

    // Reglas de validación, iguales al crear y al editar
    private function reglas()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'max:1000',
            'deadline' => 'required',
            'status' => 'required',
        ];
    }

    // El estado solo puede ser uno de estos tres; si llega otro, lo dejo en 'pendiente'
    private function estadoValido($estado)
    {
        if ($estado == 'pendiente' || $estado == 'en curso' || $estado == 'terminado') {
            return $estado;
        }

        return 'pendiente';
    }

    // Devuelve true si el usuario que ha iniciado sesión es el propietario del proyecto
    private function esPropietario(Project $project)
    {
        if ($project->user_id == Auth::user()->id) {
            return true;
        }

        return false;
    }
}
