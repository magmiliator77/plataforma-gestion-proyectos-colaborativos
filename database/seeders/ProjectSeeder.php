<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Creo los usuarios de prueba a mano.
        // La contraseña se guarda cifrada sola, porque el modelo User tiene 'password' => 'hashed'
        $ana = User::create([
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'password',
        ]);

        $luis = User::create([
            'name' => 'Luis',
            'email' => 'luis@example.com',
            'password' => 'password',
        ]);

        $eva = User::create([
            'name' => 'Eva',
            'email' => 'eva@example.com',
            'password' => 'password',
        ]);

        $web = Project::create([
            'title' => 'Tienda online',
            'description' => 'Web para vender productos',
            'deadline' => '2027-01-15',
            'status' => 'en curso',
            'user_id' => $ana->id,
        ]);

        $app = Project::create([
            'title' => 'App de reservas',
            'description' => 'Reservas para un restaurante',
            'deadline' => '2027-03-01',
            'status' => 'pendiente',
            'user_id' => $luis->id,
        ]);

        // attach() guarda una fila en la tabla pivote con sus datos extra
        $web->participants()->attach($luis->id, ['role' => 'Desarrollador', 'contribution_hours' => 12]);
        $web->participants()->attach($eva->id, ['role' => 'Diseñadora', 'contribution_hours' => 8]);
        $app->participants()->attach($eva->id, ['role' => 'Tester', 'contribution_hours' => 5]);
    }
}
