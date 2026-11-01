# Plataforma de gestión de proyectos colaborativos (Práctica final 3.9)

Aplicación en Laravel donde los usuarios crean proyectos y añaden participantes con un rol y sus horas de trabajo.

## Cómo levantarla

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Abrir http://localhost. Usuarios de prueba: `ana@example.com`, `luis@example.com` y `eva@example.com` (contraseña `password`).

## Base de datos

- `users`: usuarios (login con Breeze).
- `projects`: título, descripción, fecha límite, estado y propietario (`user_id`).
- `project_user`: tabla pivote con los participantes, su `role` y sus `contribution_hours`.
