<?php

use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Las 7 rutas del CRUD de proyectos, solo con sesión iniciada
Route::resource('projects', ProjectController::class)->middleware('auth');

// Rutas para añadir, editar y quitar participantes de un proyecto
Route::middleware('auth')->group(function () {
    Route::post('/projects/{project}/participants', [ParticipantController::class, 'store'])->name('participants.store');
    Route::put('/projects/{project}/participants/{user}', [ParticipantController::class, 'update'])->name('participants.update');
    Route::delete('/projects/{project}/participants/{user}', [ParticipantController::class, 'destroy'])->name('participants.destroy');
});

require __DIR__.'/auth.php';
