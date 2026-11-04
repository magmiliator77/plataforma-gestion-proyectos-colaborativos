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
        // with() trae el propietario y los participantes en pocas consultas,
        // en vez de hacer una consulta por cada proyecto de la lista
        $projects = Project::with('owner', 'participants')->orderBy('deadline')->get();

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

        // El propietario es siempre el usuario que ha iniciado sesión
        $datos['user_id'] = Auth::id();

        $project = Project::create($datos);

        return redirect()->route('projects.show', $project)->with('mensaje', 'Proyecto creado.');
    }

    // Muestra el detalle de un proyecto
    public function show(Project $project)
    {
        // Ids de los usuarios que ya están en el proyecto
        $idsParticipantes = $project->participants->pluck('id');

        // Usuarios que se pueden añadir: ni el propietario ni los que ya participan
        $usuariosDisponibles = User::where('id', '!=', $project->user_id)
            ->whereNotIn('id', $idsParticipantes)
            ->orderBy('name')
            ->get();

        return view('projects.show', [
            'project' => $project,
            'usuariosDisponibles' => $usuariosDisponibles,
        ]);
    }

    // Muestra el formulario para editar un proyecto
    public function edit(Project $project)
    {
        $this->comprobarPropietario($project);

        return view('projects.edit', ['project' => $project]);
    }

    // Guarda los cambios de un proyecto
    public function update(Request $request, Project $project)
    {
        $this->comprobarPropietario($project);

        $datos = $request->validate($this->reglas());
        $project->update($datos);

        return redirect()->route('projects.show', $project)->with('mensaje', 'Cambios guardados.');
    }

    // Borra un proyecto
    public function destroy(Project $project)
    {
        $this->comprobarPropietario($project);

        $project->delete();

        return redirect()->route('projects.index')->with('mensaje', 'Proyecto borrado.');
    }

    // Reglas de validación, iguales al crear y al editar
    private function reglas()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'deadline' => 'required|date',
            'status' => 'required|in:pendiente,en curso,terminado',
        ];
    }

    // Si el usuario no es el propietario, corta con un error 403 (prohibido)
    private function comprobarPropietario(Project $project)
    {
        if ($project->user_id != Auth::id()) {
            abort(403);
        }
    }
}
