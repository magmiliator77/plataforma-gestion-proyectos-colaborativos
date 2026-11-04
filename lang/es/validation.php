<?php

// Mensajes de error de validación en español.
// :attribute se cambia por el nombre del campo (ver 'attributes' al final).
return [
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser un texto.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'lowercase' => 'El campo :attribute debe estar en minúsculas.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'unique' => 'Ya existe una cuenta con ese :attribute.',
    'exists' => 'El :attribute elegido no existe.',
    'in' => 'El valor elegido en :attribute no es válido.',
    'current_password' => 'La contraseña no es correcta.',
    'max' => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
    ],
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'numeric' => 'El campo :attribute debe ser como mínimo :min.',
    ],

    // Nombres de los campos tal y como los ve el usuario
    'attributes' => [
        'title' => 'título',
        'description' => 'descripción',
        'deadline' => 'fecha límite',
        'status' => 'estado',
        'user_id' => 'usuario',
        'role' => 'rol',
        'contribution_hours' => 'horas',
        'name' => 'nombre',
        'email' => 'correo',
        'password' => 'contraseña',
    ],
];
